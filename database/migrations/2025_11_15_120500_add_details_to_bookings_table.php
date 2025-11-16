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
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'guest_count')) {
                $table->integer('guest_count')->nullable()->after('event_time');
            }

            if (!Schema::hasColumn('bookings', 'venue_address')) {
                $table->string('venue_address', 500)->nullable()->after('guest_count');
            }

            if (!Schema::hasColumn('bookings', 'contact_person')) {
                $table->string('contact_person', 100)->nullable()->after('venue_address');
            }

            if (!Schema::hasColumn('bookings', 'contact_phone')) {
                $table->string('contact_phone', 20)->nullable()->after('contact_person');
            }

            if (!Schema::hasColumn('bookings', 'special_requirements')) {
                $table->text('special_requirements')->nullable()->after('contact_phone');
            }

            if (!Schema::hasColumn('bookings', 'budget_range')) {
                $table->string('budget_range', 50)->nullable()->after('special_requirements');
            }

            if (!Schema::hasColumn('bookings', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('budget_range');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columns = [
                'guest_count',
                'venue_address',
                'contact_person',
                'contact_phone',
                'special_requirements',
                'budget_range',
                'category_id',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
