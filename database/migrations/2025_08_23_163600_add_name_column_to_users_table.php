<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id')->nullable();
        });

        // Populate the name column with concatenated first, middle, and last names
        // Only update if there are existing users
        if (DB::table('users')->count() > 0) {
            DB::statement("UPDATE users SET name = CONCAT_WS(' ', first_name, middle_name, last_name)");

            // Make name column required after populating
            Schema::table('users', function (Blueprint $table) {
                $table->string('name')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
