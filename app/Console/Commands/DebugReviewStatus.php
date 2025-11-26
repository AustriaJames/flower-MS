<?php
// app/Console/Commands/DebugReviewStatus.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Review;

class DebugReviewStatus extends Command
{
    protected $signature = 'debug:review-status';
    protected $description = 'List all reviews with status and related fields';

    public function handle()
    {
        $reviews = Review::orderBy('created_at', 'desc')->get();
        $this->line("ID  User  Product  Order  OrderItem  Status     Created At");
        foreach ($reviews as $r) {
            $this->line(sprintf(
                '%-3d %-5d %-8d %-6s %-10s %-10s %s',
                $r->id,
                $r->user_id,
                $r->product_id,
                $r->order_id,
                $r->order_item_id ?? 'NULL',
                $r->status,
                $r->created_at
            ));
        }
        return 0;
    }
}
