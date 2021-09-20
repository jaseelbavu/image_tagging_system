<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * User registration test.
     *
     * @return void
     */
    public function test_registration()
    {
        $register = [
            'email' => 'mytestmail@gmail.com',
            'name' => 'Test Mail',
            'password' => '123456',
            'password_confirmation' => '123456',
        ];

        $response = $this->json('POST', route('register'), $register);
        $response->assertStatus(201);
    }

    /**
     * User login test.
     *
     * @return void
     */
    public function test_login()
    {
        User::create([
            'name' => 'Test Mail',
            'email'=>'mytestmail@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $response = $this->json('GET', route('login'), [
            'email' => 'mytestmail@gmail.com',
            'password' => '123456',
        ]);

        $response->assertStatus(200);
    }
}
