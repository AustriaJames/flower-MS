<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['category', 'products'])
            ->orderBy('event_date', 'desc')
            ->get();

        return view('customer.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $categories = Category::where('is_occasion', true)->get();
        $products = Product::where('is_active', true)->where('in_stock', true)->get();

        return view('customer.bookings.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_type' => 'required|string|max:100',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required|date_format:H:i',
            'guest_count' => 'required|integer|min:1|max:1000',
            'venue_address' => 'required|string|max:500',
            'contact_person' => 'required|string|max:100',
            'contact_phone' => 'required|string|max:20',
            'special_requirements' => 'nullable|string|max:1000',
            'budget_range' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'products' => 'array',
            'products.*.product_id' => 'exists:products,id',
            'products.*.quantity' => 'integer|min:1'
        ]);

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'event_type' => $request->event_type,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'guest_count' => $request->guest_count,
            'venue_address' => $request->venue_address,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'special_requirements' => $request->special_requirements,
            'budget_range' => $request->budget_range,
            'category_id' => $request->category_id,
            'status' => 'pending'
        ]);

        // Attach products if any
        if ($request->products) {
            foreach ($request->products as $productData) {
                $booking->products()->attach($productData['product_id'], [
                    'quantity' => $productData['quantity']
                ]);
            }
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking created successfully! We will contact you soon to confirm details.');
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['category', 'products']);

        return view('customer.bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot modify confirmed or completed bookings.');
        }

        $request->validate([
            'event_date' => 'required|date|after:today',
            'event_time' => 'required|date_format:H:i',
            'guest_count' => 'required|integer|min:1|max:1000',
            'venue_address' => 'required|string|max:500',
            'contact_person' => 'required|string|max:100',
            'contact_phone' => 'required|string|max:20',
            'special_requirements' => 'nullable|string|max:1000'
        ]);

        $booking->update($request->only([
            'event_date', 'event_time', 'guest_count', 'venue_address',
            'contact_person', 'contact_phone', 'special_requirements'
        ]));

        return redirect()->back()->with('success', 'Booking updated successfully!');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot cancel confirmed or completed bookings.');
        }

        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully!');
    }
}
