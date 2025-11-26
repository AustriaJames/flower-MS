<?php
// scripts/debug_order_status.php

use App\Models\Order;

$orderNumber = 'ORD-IRUHSMMD'; // Change if needed
$order = Order::where('order_number', $orderNumber)->first();

if (!$order) {
    echo "Order not found.\n";
    exit(1);
}

echo "Order Number: {$order->order_number}\n";
echo "Status: {$order->status}\n";
echo "Delivery Type: {$order->delivery_type}\n";
echo "Created At: {$order->created_at}\n";
echo "Updated At: {$order->updated_at}\n";
