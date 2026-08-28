<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_categories(): void
    {
        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(401);
    }

    public function test_member_cannot_access_categories(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        Passport::actingAs($member);

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(403);
    }

    public function test_admin_can_list_categories(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Category::factory()->count(3)->create();
        Passport::actingAs($admin);

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Passport::actingAs($admin);

        $payload = [
            'name' => 'Marketing',
            'description' => 'Marketing related projects',
            'status' => 1,
        ];

        $response = $this->postJson('/api/v1/categories', $payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'data' => ['name' => 'Marketing'],
        ]);
        $this->assertDatabaseHas('categories', ['name' => 'Marketing']);
    }

    public function test_create_category_fails_validation_when_name_missing(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Passport::actingAs($admin);

        $response = $this->postJson('/api/v1/categories', [
            'description' => 'No name given',
            'status' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_admin_can_update_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create(['name' => 'Old Name']);
        Passport::actingAs($admin);

        $response = $this->putJson("/api/v1/categories/{$category->id}", [
            'name' => 'Renamed',
            'description' => $category->description,
            'status' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'Renamed');
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Renamed']);
    }

    public function test_admin_can_delete_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        Passport::actingAs($admin);

        $response = $this->deleteJson("/api/v1/categories/{$category->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_show_returns_404_for_missing_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Passport::actingAs($admin);

        $response = $this->getJson('/api/v1/categories/999');

        $response->assertStatus(404);
    }
}