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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->enum('event_type', ['wedding', 'birthday', 'anniversary', 'graduation', 'funeral', 'corporate', 'other']);
            $table->date('event_date');
            $table->string('event_time');
            $table->integer('guest_count')->nullable();
            $table->string('venue_address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('special_requirements')->nullable();
            $table->string('venue')->nullable(); // Keep for backward compatibility
            $table->text('requirements')->nullable(); // Keep for backward compatibility
            $table->string('budget_range')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'rescheduled', 'cancelled', 'completed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('rescheduled_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['event_date', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
