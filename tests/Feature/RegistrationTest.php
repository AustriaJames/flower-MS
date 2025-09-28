<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $userData = [
            'first_name' => 'John',
            'middle_name' => 'Michael',
            'last_name' => 'Doe',
            'phone' => '+63 912 345 6789',
            'email' => 'john.doe@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ];

        $response = $this->post('/register', $userData);

        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
        ]);

        $this->assertEquals(1, User::count()); // Only the newly created user in test database

        $user = User::where('email', 'john.doe@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('John', $user->first_name);
        $this->assertEquals('Doe', $user->last_name);

        $this->assertAuthenticated();
        $response->assertRedirect('/home');
    }

    public function test_registration_validation_works()
    {
        $response = $this->post('/register', [
            'first_name' => '',
            'middle_name' => '',
            'last_name' => '',
            'phone' => '',
            'email' => 'invalid-email',
            'password' => 'weak',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'phone',
            'email',
            'password',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'invalid-email',
        ]);
    }

    public function test_phone_number_validation_works()
    {
        $response = $this->post('/register', [
            'first_name' => 'Jane',
            'middle_name' => null,
            'last_name' => 'Smith',
            'phone' => '123', // Too short
            'email' => 'jane.smith@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['phone']);
    }

    public function test_password_complexity_validation_works()
    {
        $response = $this->post('/register', [
            'first_name' => 'Bob',
            'middle_name' => null,
            'last_name' => 'Johnson',
            'phone' => '+63 912 345 6789',
            'email' => 'bob.johnson@example.com',
            'password' => 'weakpassword', // Missing uppercase, number, special char
            'password_confirmation' => 'weakpassword',
        ]);

        $response->assertSessionHasErrors(['password']);
    }
}
