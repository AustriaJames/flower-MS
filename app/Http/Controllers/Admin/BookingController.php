<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;
use App\Mail\BookingRescheduled;
use App\Mail\BookingCancelled;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'category']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('date_from')) {
            $query->where('event_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('event_date', '<=', $request->date_to);
        }

        $bookings = $query->latest()->get();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Display calendar view of all bookings
     */
    public function calendar()
    {
        $bookings = Booking::with(['user', 'category'])
            ->where('status', '!=', 'cancelled')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'title' => $booking->customer_name . ' - ' . ucfirst($booking->event_type),
                    'start' => $booking->event_date,
                    'end' => $booking->event_date,
                    'className' => 'booking-' . $booking->status,
                    'extendedProps' => [
                        'status' => $booking->status,
                        'customer' => $booking->customer_name,
                        'event_type' => $booking->event_type,
                        'requirements' => $booking->requirements
                    ]
                ];
            });

        return view('admin.bookings.calendar', compact('bookings'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'category']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $eventTypes = ['wedding', 'birthday', 'anniversary', 'graduation', 'funeral', 'corporate', 'other'];
        $statuses = ['pending', 'confirmed', 'rescheduled', 'cancelled', 'completed'];

        return view('admin.bookings.edit', compact('booking', 'eventTypes', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'event_type' => 'required|string|in:wedding,birthday,anniversary,graduation,funeral,corporate,other',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required|string|max:10',
            'venue' => 'nullable|string|max:500',
            'requirements' => 'nullable|string|max:1000',
            'budget_range' => 'nullable|string|max:100',
            'status' => 'required|string|in:pending,confirmed,rescheduled,cancelled,completed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $booking->status;
        $oldDate = $booking->event_date;

        $booking->update($request->all());

        // Send notifications based on status changes
        if ($oldStatus !== $request->status) {
            $this->sendStatusNotification($booking, $oldStatus);
        }

        if ($oldDate !== $request->event_date) {
            $this->sendRescheduleNotification($booking);
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|string|in:pending,confirmed,rescheduled,cancelled,completed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $booking->status;

        $booking->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        // Send notification for status change
        $this->sendStatusNotification($booking, $oldStatus);

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }

    /**
     * Reschedule booking
     */
    public function reschedule(Request $request, Booking $booking)
    {
        $request->validate([
            'new_date' => 'required|date|after:today',
            'new_time' => 'required|string|max:10',
            'reason' => 'nullable|string|max:500',
        ]);

        $oldDate = $booking->event_date;
        $oldTime = $booking->event_time;

        $booking->update([
            'event_date' => $request->new_date,
            'event_time' => $request->new_time,
            'status' => 'rescheduled',
            'admin_notes' => $request->reason ? "Rescheduled: {$request->reason}" : 'Rescheduled by admin',
        ]);

        // Send reschedule notification
        $this->sendRescheduleNotification($booking);

        return redirect()->back()->with('success', 'Booking rescheduled successfully.');
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, Booking $booking)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $booking->update([
            'status' => 'cancelled',
            'admin_notes' => $request->reason ? "Cancelled: {$request->reason}" : 'Cancelled by admin',
        ]);

        // Send cancellation notification
        Mail::to($booking->customer_email)->send(new BookingCancelled($booking));

        return redirect()->back()->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }

    /**
     * Export bookings
     */
    public function export(Request $request)
    {
        $query = Booking::with(['user', 'category']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('event_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('event_date', '<=', $request->date_to);
        }

        $bookings = $query->get();

        $filename = 'bookings_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'ID', 'Customer Name', 'Email', 'Phone', 'Event Type', 'Event Date',
                'Event Time', 'Venue', 'Status', 'Budget Range', 'Created At'
            ]);

            // Add data
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->customer_name,
                    $booking->customer_email,
                    $booking->customer_phone,
                    $booking->event_type,
                    $booking->event_date->format('Y-m-d'),
                    $booking->event_time,
                    $booking->venue,
                    $booking->status,
                    $booking->budget_range,
                    $booking->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Send status change notification
     */
    private function sendStatusNotification($booking, $oldStatus)
    {
        switch ($booking->status) {
            case 'confirmed':
                Mail::to($booking->customer_email)->send(new BookingConfirmation($booking));
                break;
            case 'cancelled':
                Mail::to($booking->customer_email)->send(new BookingCancelled($booking));
                break;
        }
    }

    /**
     * Send reschedule notification
     */
    private function sendRescheduleNotification($booking)
    {
        Mail::to($booking->customer_email)->send(new BookingRescheduled($booking));
    }
}
