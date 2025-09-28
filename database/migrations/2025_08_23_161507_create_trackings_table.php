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
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number', 100)->unique();
            $table->string('carrier', 100); // e.g., 'FedEx', 'UPS', 'Local Delivery'
            $table->enum('status', ['pending', 'picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'failed'])->default('pending');
            $table->text('current_location')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('estimated_delivery')->nullable();
            $table->timestamp('actual_delivery')->nullable();
            $table->json('tracking_history')->nullable(); // Array of tracking events
            $table->timestamps();
            
            // Foreign keys
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            
            // Indexes
            $table->index('order_id');
            $table->index('tracking_number');
            $table->index('status');
            $table->index('carrier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackings');
    }
};
