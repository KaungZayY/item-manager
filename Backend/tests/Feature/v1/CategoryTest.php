<?php

namespace Tests\Feature\v1;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'frank@example.com',
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_authenicated_user_can_fetch_categories(): void
    {
        $token = auth()->login($this->user);

        Category::factory(5)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/v1/categories');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'categories',
        ]);
        $this->assertCount(5, $response->json('categories'));
    }

    public function test_fetching_categories_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(401);
    }
}
