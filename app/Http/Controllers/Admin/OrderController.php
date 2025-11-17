<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\PhpMailerService;

class OrderController extends Controller
{
    protected PhpMailerService $mailer;

    public function __construct(PhpMailerService $mailer)
    {
        $this->mailer = $mailer;
    }

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

        // Shipping logic ONLY if order is for delivery
        if ($validated['status'] === 'shipped' 
            && !$order->tracking 
            && $order->delivery_method === 'delivery') 
        {
            $order->tracking()->create([
                'tracking_number' => 'TRK-' . strtoupper(uniqid()),
                'carrier' => 'Standard Delivery',
                'status' => 'in_transit',
                'current_location' => 'Processing Center',
                'description' => 'Order has been shipped',
                'estimated_delivery' => $validated['estimated_delivery'],
            ]);
        }

        // Delivered logic ONLY for delivery orders
        if ($validated['status'] === 'delivered' 
            && $order->delivery_method === 'delivery') 
        {
            $order->update(['delivered_at' => now()]);
        }

        $this->sendOrderStatusEmail($order);

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

        // Shipping logic ONLY if delivery order
        if ($request->status === 'shipped' 
            && !$order->tracking 
            && $order->delivery_method === 'delivery') 
        {
            $order->tracking()->create([
                'tracking_number' => 'TRK-' . strtoupper(uniqid()),
                'carrier' => 'Standard Delivery',
                'status' => 'in_transit',
                'current_location' => 'Processing Center',
                'description' => 'Order has been shipped',
                'estimated_delivery' => now()->addDays(3),
            ]);
        }

        // Delivered logic ONLY if delivery order
        if ($request->status === 'delivered' 
            && $order->delivery_method === 'delivery') 
        {
            $order->update(['delivered_at' => now()]);
        }

        $this->sendOrderStatusEmail($order, $oldStatus);

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

        $csv = "Order Number,Customer,Status,Total,Order Date\n";

        foreach ($orders as $order) {
            $csv .= "{$order->order_number},{$order->user->name},{$order->status},{$order->total_amount},{$order->order_date}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="orders.csv"');
    }

    public function approveCancellation(Order $order)
    {
        if ($order->status !== 'confirmed' || !$order->cancellation_requested) {
            return redirect()->back()->with('error', 'Cannot approve cancellation for this order.');
        }

        $order->update([
            'status' => 'cancelled',
            'cancellation_requested' => false
        ]);

        $this->sendOrderStatusEmail($order, 'confirmed');

        return redirect()->back()->with('success', 'Cancellation request approved. Order has been cancelled.');
    }

    public function createTracking(Request $request, Order $order)
    {
        $validated = $request->validate([
            'carrier' => 'required|string|max:255',
            'estimated_delivery' => 'required|date|after:today',
            'description' => 'nullable|string|max:500',
        ]);

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

    public function rejectCancellation(Order $order)
    {
        if ($order->status !== 'confirmed' || !$order->cancellation_requested) {
            return redirect()->back()->with('error', 'Cannot reject cancellation for this order.');
        }

        $order->update(['cancellation_requested' => false]);

        $this->sendOrderStatusEmail($order, 'confirmed');

        return redirect()->back()->with('success', 'Cancellation request rejected. Order remains confirmed.');
    }

    private function sendOrderStatusEmail(Order $order, ?string $oldStatus = null): void
    {
        if (!$order->user || !$order->user->email) {
            return;
        }

        $user = $order->user;
        $email = $user->email;
        $toName = $user->name ?? trim($user->first_name . ' ' . $user->last_name);

        $subject = 'Order Status Updated - ' . config('app.name') . ' (Order ' . $order->order_number . ')';
        $htmlBody = view('emails.orders.status', [
            'order' => $order,
            'user' => $user,
            'oldStatus' => $oldStatus,
        ])->render();

        $this->mailer->send($email, $toName ?: $email, $subject, $htmlBody);
    }
}
