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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'rating')) {
                $table->decimal('rating', 3, 2)->default(0)->after('sort_order');
            }
            if (!Schema::hasColumn('products', 'review_count')) {
                $table->integer('review_count')->default(0)->after('rating');
            }
            if (!Schema::hasColumn('products', 'is_flower_of_week')) {
                $table->boolean('is_flower_of_week')->default(false)->after('is_featured');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['rating', 'review_count', 'is_flower_of_week']);
        });
    }
};
