<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Always create a default admin user
        \App\Models\User::updateOrCreate(
            [ 'email' => 'admin@bonasflowershop.com' ],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'phone' => '+63 912 345 6789',
                'password' => bcrypt('admin123'),
                'is_admin' => true,
            ]
        );

        // Seed categories and products
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
