<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\User;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_view_category_data(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);
        $response = $this->actingAs($user)->get(route('category.index'));
        Category::factory()->count(15)->create();
        $response->assertStatus(200);
        $response->assertViewIs('category');
        $response->assertViewHas('categories');
    }

    public function test_store_creates_new_category(): void
    {
          $user = User::factory()->create([
            'role' => 'admin',
        ]);
        $categoryData =[
            'name' => "Category",
            'description' => "THis is dummy description",
            'status' => 1,
        ];

        $response = $this->actingAs($user)->post(route('category.store'), $categoryData);
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Category Added');
         // Assert category was created in database
        $this->assertDatabaseHas('categories', [
        'name' => 'Category',  
        'description' => 'THis is dummy description',
        'status' => 1,
    ]);

    }
}
