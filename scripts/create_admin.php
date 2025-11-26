<?php
// scripts/create_admin.php
// Run: php scripts/create_admin.php

use Illuminate\Support\Facades\Hash;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Set your new admin credentials here
$newEmail = 'admin@example.com';
$newPassword = 'admin1234';
$newName = 'Admin User';

// Hash the password
$hashedPassword = Hash::make($newPassword);

// Insert admin user
\DB::table('users')->insert([
    'name' => $newName,
    'email' => $newEmail,
    'password' => $hashedPassword,
    'is_admin' => 1, // Make sure your users table has this column
    'created_at' => now(),
    'updated_at' => now(),
]);

echo "New admin user created: $newEmail\n";