<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase; // the default in laravel 8 not this one
use App\Models\User;
use Tests\TestCase; // we need to change like this 

class LoginTest extends TestCase
{

    // Environment every test will run this at first
    // protected function setUp(): void
    // {
    //     parent::setUp();
    //     $user = User::first();

    //     $this->actingAs($user);
    // }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_valid_user()
    {

        // Create a user with valid login credentials
        $user = User::create([
            'name' => 'kokurate',
            'email' => 'valid@example.com',
            'password' => bcrypt('password123'),
            'level' => 'admin'
        ]);

        // Make a POST request to the login route with valid credentials
        $response = $this->post(route('authenticate'), [
            'email' => 'valid@example.com',
            'password' => 'password123',
        ]);

        // Assert that the user is redirected to the intended page upon successful login
        $response->assertRedirect(route('admin.index'));

        // You can also assert that the user is authenticated
        $this->assertAuthenticatedAs($user);

        $user->delete();
    }

    public function test_invalid_user()
    {
        
         // Attempt login with invalid credentials
         $response = $this->post(route('authenticate'), [
            'email' => 'invalid@example.com',
            'password' => 'invalidpassword',
        ]);

        // Assert that the user is not authenticated and stays on the login page
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

}
