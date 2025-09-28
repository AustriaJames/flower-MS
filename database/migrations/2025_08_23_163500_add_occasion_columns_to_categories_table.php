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
        Schema::table('categories', function (Blueprint $table) {
            // Add parent category support for hierarchical categories
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('set null')->after('id');

            // Add occasion-specific columns
            $table->boolean('is_occasion')->default(false)->after('is_active');
            $table->date('occasion_date')->nullable()->after('is_occasion');

            // Add indexes for new columns
            $table->index(['parent_id']);
            $table->index(['is_occasion']);
            $table->index(['occasion_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Remove added columns
            $table->dropForeign(['parent_id']);
            $table->dropColumn([
                'parent_id',
                'is_occasion',
                'occasion_date'
            ]);

            // Remove added indexes
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['is_occasion']);
            $table->dropIndex(['occasion_date']);
        });
    }
};
