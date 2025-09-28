<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'orderItems.product'])->latest()->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'orderItems.product',
            'shippingAddress',
            'billingAddress',
            'tracking'
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['user', 'orderItems.product', 'shippingAddress', 'billingAddress']);
        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];

        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string',
            'estimated_delivery' => 'nullable|date|after:today',
        ]);

        $order->update($validated);

        // Update tracking if status is shipped
        if ($validated['status'] === 'shipped' && !$order->tracking) {
            $order->tracking()->create([
                'tracking_number' => 'TRK-' . strtoupper(uniqid()),
                'carrier' => 'Standard Delivery',
                'status' => 'in_transit',
                'current_location' => 'Processing Center',
                'description' => 'Order has been shipped',
                'estimated_delivery' => $validated['estimated_delivery'],
            ]);
        }

        // Update delivered_at if status is delivered
        if ($validated['status'] === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully!');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Handle status-specific actions
        if ($request->status === 'shipped' && !$order->tracking) {
            $order->tracking()->create([
                'tracking_number' => 'TRK-' . strtoupper(uniqid()),
                'carrier' => 'Standard Delivery',
                'status' => 'in_transit',
                'current_location' => 'Processing Center',
                'description' => 'Order has been shipped',
                'estimated_delivery' => now()->addDays(3),
            ]);
        }

        if ($request->status === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        return redirect()->back()->with('success', "Order status updated from {$oldStatus} to {$request->status}!");
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully!');
    }

    public function export(Request $request)
    {
        $orders = Order::with(['user', 'orderItems.product'])->latest()->get();

        // Generate CSV content
        $csv = "Order Number,Customer,Status,Total,Order Date\n";

        foreach ($orders as $order) {
            $csv .= "{$order->order_number},{$order->user->name},{$order->status},{$order->total_amount},{$order->order_date}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="orders.csv"');
    }

    /**
     * Approve cancellation request for confirmed orders.
     */
    public function approveCancellation(Order $order)
    {
        if ($order->status !== 'confirmed' || !$order->cancellation_requested) {
            return redirect()->back()->with('error', 'Cannot approve cancellation for this order.');
        }

        $order->update([
            'status' => 'cancelled',
            'cancellation_requested' => false
        ]);

        return redirect()->back()->with('success', 'Cancellation request approved. Order has been cancelled.');
    }

    /**
     * Create tracking information for an order.
     */
    public function createTracking(Request $request, Order $order)
    {
        $validated = $request->validate([
            'carrier' => 'required|string|max:255',
            'estimated_delivery' => 'required|date|after:today',
            'description' => 'nullable|string|max:500',
        ]);

        // Create tracking information
        $order->tracking()->create([
            'tracking_number' => 'TRK-' . strtoupper(uniqid()),
            'carrier' => $validated['carrier'],
            'status' => 'processing',
            'current_location' => 'Processing Center',
            'description' => $validated['description'] ?? 'Order has been processed and is ready for shipping',
            'estimated_delivery' => $validated['estimated_delivery'],
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Tracking information created successfully!');
    }

    /**
     * Reject cancellation request for confirmed orders.
     */
    public function rejectCancellation(Order $order)
    {
        if ($order->status !== 'confirmed' || !$order->cancellation_requested) {
            return redirect()->back()->with('error', 'Cannot reject cancellation for this order.');
        }

        $order->update(['cancellation_requested' => false]);

        return redirect()->back()->with('success', 'Cancellation request rejected. Order remains confirmed.');
    }
}
