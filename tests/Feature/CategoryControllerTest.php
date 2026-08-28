<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('category.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_member_cannot_access_category_index(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($member)->get(route('category.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_view_category_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Category::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('category.index'));

        $response->assertStatus(200);
        $response->assertViewIs('category');
        $response->assertViewHas('categories');
    }

    public function test_store_creates_new_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $categoryData = [
            'name' => 'Category',
            'description' => 'This is dummy description',
            'status' => 1,
        ];

        $response = $this->actingAs($admin)->post(route('category.store'), $categoryData);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Category Added');
        $this->assertDatabaseHas('categories', [
            'name' => 'Category',
            'description' => 'This is dummy description',
            'status' => 1,
        ]);
    }

    public function test_store_fails_when_name_is_missing(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('category.store'), [
            'description' => 'Missing the name field',
            'status' => 1,
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('categories', 0);
    }

    public function test_update_modifies_existing_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($admin)->put(route('category.update', $category), [
            'name' => 'New Name',
            'description' => 'Updated description',
            'status' => 0,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Category Updated');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'New Name',
            'status' => 0,
        ]);
    }

    public function test_destroy_deletes_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->delete(route('category.destroy', $category));

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Category Deleted');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}