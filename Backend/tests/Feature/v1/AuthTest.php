<?php

namespace Tests\Feature\v1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'frank@example.com',
            'password' => bcrypt('password123@P'),
        ]);
    }


    public function test_user_can_register_successfully()
    {
        $user = [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'password123@P',
            'password_confirmation' => 'password123@P',
        ];

        $response = $this->postJson('/api/v1/register', $user);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'access_token',
            'type',
            'expires_in',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'frank@example.com',
            'password' => 'password123@P',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'access_token',
            'type',
            'expires_in',
        ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'frank@example.com',
            'password' => 'the_incorrect_password',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'status' => 'failed',
            'message' => 'Invalid credentials!',
        ]);
    }

    public function test_user_can_access_profile_with_valid_token()
    {
        $token = auth()->login($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/v1/profile');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
        ]);
    }

    public function test_user_cannot_access_profile_without_token()
    {
        $response = $this->getJson('/api/v1/profile');

        $response->assertStatus(401);
    }

    public function test_user_can_logout_with_token()
    {
        $token = auth()->login($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/v1/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }
}
