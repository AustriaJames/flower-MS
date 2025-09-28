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
        Schema::table('reviews', function (Blueprint $table) {
            // Add status column to replace is_approved
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('is_approved');

            // Add admin response and notes columns
            $table->text('admin_response')->nullable()->after('status');
            $table->text('admin_notes')->nullable()->after('admin_response');

            // Add review tracking columns
            $table->timestamp('reviewed_at')->nullable()->after('admin_notes');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('reviewed_at');
            $table->timestamp('replied_at')->nullable()->after('reviewed_by');
            $table->foreignId('replied_by')->nullable()->constrained('users')->onDelete('set null')->after('replied_at');

            // Add indexes for new columns
            $table->index(['status']);
            $table->index(['reviewed_by']);
            $table->index(['replied_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Remove added columns
            $table->dropColumn([
                'status',
                'admin_response',
                'admin_notes',
                'reviewed_at',
                'reviewed_by',
                'replied_at',
                'replied_by'
            ]);

            // Remove added indexes
            $table->dropIndex(['status']);
            $table->dropIndex(['reviewed_by']);
            $table->dropIndex(['replied_by']);
        });
    }
};
