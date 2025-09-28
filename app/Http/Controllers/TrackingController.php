<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tracking;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Show the tracking form page.
     */
    public function index()
    {
        return view('customer.tracking.index');
    }

    /**
     * Track an order by tracking number.
     */
    public function track(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255'
        ]);

        $tracking_number = $request->tracking_number;

        // First try to find order by tracking number
        $order = Order::where('tracking_number', $tracking_number)->first();

        if (!$order) {
            // If not found, try to find by tracking record
            $tracking_record = Tracking::where('tracking_number', $tracking_number)->first();
            if ($tracking_record) {
                $order = $tracking_record->order;
            }
        }

        if (!$order) {
            return redirect()->route('tracking.index')
                ->with('error', 'Tracking number not found. Please check your tracking number and try again.');
        }

        $tracking = Tracking::where('order_id', $order->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.tracking.show', compact('order', 'tracking'));
    }

    /**
     * Track an order by tracking number (direct URL access).
     */
    public function trackByNumber($tracking_number)
    {
        // First try to find order by tracking number
        $order = Order::where('tracking_number', $tracking_number)->first();

        if (!$order) {
            // If not found, try to find by tracking record
            $tracking_record = Tracking::where('tracking_number', $tracking_number)->first();
            if ($tracking_record) {
                $order = $tracking_record->order;
            }
        }

        if (!$order) {
            abort(404, 'Tracking number not found');
        }

        $tracking = Tracking::where('order_id', $order->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.tracking.show', compact('order', 'tracking'));
    }
}
