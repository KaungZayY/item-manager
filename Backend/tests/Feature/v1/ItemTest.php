<?php

namespace Tests\Feature\v1;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
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

    public function test_user_can_fetch_items_with_filters()
    {
        $token = auth()->login($this->user);

        $category = Category::factory()->create();
        Item::factory()->count(3)->create([
            'category_id' => $category->id,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/v1/items?category_id=' . $category->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ])
            ->assertJsonStructure([
                'status',
                'items',
            ]);

        $this->assertCount(3, $response->json('items.data'));
    }

    public function test_user_can_add_a_new_item()
    {
        $token = auth()->login($this->user);
        $category = Category::factory()->create();
        $item = [
            'title' => 'Test Item',
            'description' => 'This is a test description',
            'category_id' => $category->id,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/v1/items', $item);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'item' => [
                    'title' => 'Test Item',
                    'description' => 'This is a test description',
                    'category_id' => $category->id,
                ],
            ]);

        $this->assertDatabaseHas('items', [
            'title' => 'Test Item',
            'description' => 'This is a test description',
            'category_id' => $category->id,
        ]);
    }

    public function test_user_can_update_an_existing_item()
    {
        $token = auth()->login($this->user);
        $category = Category::factory()->create();
        $item = Item::factory()->create([
            'category_id' => $category->id,
        ]);
        $updatedData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'category_id' => $item->category_id,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson("/api/v1/items/{$item->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'item' => [
                    'title' => 'Updated Title',
                    'description' => 'Updated Description',
                ],
            ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description',
        ]);
    }

    public function test_user_can_delete_an_item()
    {
        $token = auth()->login($this->user);
        $category = Category::factory()->create();
        $item = Item::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/v1/items/{$item->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Item deleted successfully!',
            ]);

        $this->assertDatabaseMissing('items', [
            'id' => $item->id,
        ]);
    }

    public function test_fetching_items_returns_paginated_results()
    {
        $token = auth()->login($this->user);
        $category = Category::factory()->create();
        Item::factory()->count(10)->create([
            'category_id' => $category->id,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/v1/items');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'items' => [
                    'current_page',
                    'data',
                    'total',
                    'last_page',
                ],
            ]);

        $this->assertEquals(5, count($response->json('items.data')));
    }

    public function test_fetching_items_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/items');

        $response->assertStatus(401);
    }
}
