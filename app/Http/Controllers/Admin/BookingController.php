<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\PhpMailerService;

class BookingController extends Controller
{
    /**
     * @var PhpMailerService
     */
    protected PhpMailerService $mailer;

    public function __construct(PhpMailerService $mailer)
    {
        $this->mailer = $mailer;
    }
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
                    'title' => ($booking->customer_name ?? ($booking->user ? $booking->user->first_name . ' ' . $booking->user->last_name : 'Guest')) . ' - ' . ucfirst($booking->event_type),
                    'start' => $booking->event_date,
                    'end' => $booking->event_date,
                    'className' => 'booking-' . $booking->status,
                    'extendedProps' => [
                        'status' => $booking->status,
                        'customer' => $booking->customer_name ?? ($booking->user ? $booking->user->first_name . ' ' . $booking->user->last_name : 'Guest'),
                        'event_type' => $booking->event_type,
                        'requirements' => $booking->special_requirements ?? $booking->requirements
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
            'event_type' => 'required|string|in:wedding,birthday,anniversary,graduation,funeral,corporate,other',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required|string|max:10',
            'guest_count' => 'nullable|integer|min:1|max:1000',
            'venue_address' => 'nullable|string|max:500',
            'contact_person' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:20',
            'special_requirements' => 'nullable|string|max:1000',
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

        // Send cancellation notification via PHPMailer
        if ($booking->customer_email) {
            $this->sendCancellationEmail($booking);
        }

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
                'ID', 'Customer Name', 'Customer Email', 'Event Type', 'Event Date',
                'Event Time', 'Venue Address', 'Contact Person', 'Contact Phone', 
                'Guest Count', 'Status', 'Budget Range', 'Created At'
            ]);

            // Add data
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->customer_name ?? ($booking->user ? $booking->user->first_name . ' ' . $booking->user->last_name : 'Guest'),
                    $booking->customer_email ?? ($booking->user ? $booking->user->email : ''),
                    $booking->event_type,
                    $booking->event_date->format('Y-m-d'),
                    $booking->event_time,
                    $booking->venue_address ?? $booking->venue,
                    $booking->contact_person,
                    $booking->contact_phone,
                    $booking->guest_count,
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
        if (!$booking->customer_email) {
            return; // Skip if no customer email
        }

        switch ($booking->status) {
            case 'confirmed':
                $this->sendConfirmationEmail($booking);
                break;
            case 'cancelled':
                $this->sendCancellationEmail($booking);
                break;
        }
    }

    /**
     * Send reschedule notification
     */
    private function sendRescheduleNotification($booking)
    {
        if ($booking->customer_email) {
            $this->sendRescheduledEmail($booking);
        }
    }

    /**
     * Send booking confirmation email via PHPMailer.
     */
    private function sendConfirmationEmail(Booking $booking): void
    {
        $subject = 'Booking Confirmed - ' . config('app.name');
        $htmlBody = view('emails.bookings.confirmation', [
            'booking' => $booking,
        ])->render();

        $this->mailer->send(
            $booking->customer_email,
            $booking->customer_name ?? ($booking->user->first_name . ' ' . $booking->user->last_name ?? ''),
            $subject,
            $htmlBody
        );
    }

    /**
     * Send booking cancellation email via PHPMailer.
     */
    private function sendCancellationEmail(Booking $booking): void
    {
        $subject = 'Booking Cancelled - ' . config('app.name');
        $htmlBody = view('emails.bookings.cancelled', [
            'booking' => $booking,
        ])->render();

        $this->mailer->send(
            $booking->customer_email,
            $booking->customer_name ?? ($booking->user->first_name . ' ' . $booking->user->last_name ?? ''),
            $subject,
            $htmlBody
        );
    }

    /**
     * Send booking rescheduled email via PHPMailer.
     */
    private function sendRescheduledEmail(Booking $booking): void
    {
        $subject = 'Booking Rescheduled - ' . config('app.name');
        $htmlBody = view('emails.bookings.rescheduled', [
            'booking' => $booking,
        ])->render();

        $this->mailer->send(
            $booking->customer_email,
            $booking->customer_name ?? ($booking->user->first_name . ' ' . $booking->user->last_name ?? ''),
            $subject,
            $htmlBody
        );
    }
}
