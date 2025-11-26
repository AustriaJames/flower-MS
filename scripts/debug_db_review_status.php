<?php
// debug_db_review_status.php
// Run: php scripts/debug_db_review_status.php

use Illuminate\Database\Capsule\Manager as DB;

require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel DB connection
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class);

// Query all reviews, show id, user_id, product_id, order_id, order_item_id, status, created_at
$reviews = DB::table('reviews')->orderBy('created_at', 'desc')->get();

printf("%-5s %-8s %-10s %-10s %-13s %-10s %-20s\n", 'ID', 'User', 'Product', 'Order', 'OrderItem', 'Status', 'Created At');
foreach ($reviews as $r) {
    printf("%-5d %-8d %-10d %-10s %-13s %-10s %-20s\n",
        $r->id, $r->user_id, $r->product_id, $r->order_id, $r->order_item_id ?? 'NULL', $r->status, $r->created_at);
}
