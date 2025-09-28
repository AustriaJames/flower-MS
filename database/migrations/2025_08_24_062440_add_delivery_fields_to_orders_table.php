<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('delivery_type', ['delivery', 'pickup'])->after('total_amount');
            $table->date('delivery_date')->after('delivery_type');
            $table->time('delivery_time')->nullable()->after('delivery_date');
            $table->time('pickup_time')->nullable()->after('delivery_time');
            $table->enum('payment_method', ['cod', 'online'])->default('cod')->after('pickup_time');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending')->after('payment_method');
            $table->boolean('cancellation_requested')->default(false)->after('payment_status');
            $table->string('tracking_number')->unique()->nullable()->after('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_type',
                'delivery_date',
                'delivery_time',
                'pickup_time',
                'payment_method',
                'payment_status',
                'cancellation_requested',
                'tracking_number'
            ]);
        });
    }
};
