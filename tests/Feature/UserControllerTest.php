<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_cannot_access_user_index(): void
    {
        // UserPolicy::viewAny() is admin-only.
        $manager = User::factory()->create(['role' => 'manager']);

        $response = $this->actingAs($manager)->get(route('user.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_view_user_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('user.index'));

        $response->assertStatus(200);
        $response->assertViewIs('user');
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $payload = [
            'name' => 'New Team Member',
            'email' => 'newmember@example.com',
            'password' => 'password123',
            'role' => 'member',
        ];

        $response = $this->actingAs($admin)->post(route('user.submit'), $payload);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'User created successfully.');
        $this->assertDatabaseHas('users', ['email' => 'newmember@example.com', 'role' => 'member']);
    }

    public function test_create_user_fails_when_email_already_taken(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->actingAs($admin)->post(route('user.submit'), [
            'name' => 'Duplicate Email',
            'email' => 'taken@example.com',
            'password' => 'password123',
            'role' => 'member',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_admin_cannot_edit_another_admin(): void
    {
        // UserPolicy::update() blocks editing any account whose role is admin.
        $admin = User::factory()->create(['role' => 'admin']);
        $otherAdmin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->put(route('user.update', $otherAdmin), [
            'name' => 'Renamed',
            'email' => $otherAdmin->email,
            'role' => 'admin',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_cannot_edit_themselves_from_the_user_list(): void
    {
        // UserPolicy::update() blocks self-edit here — that's what /profile is for.
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->put(route('user.update', $admin), [
            'name' => 'Self Rename',
            'email' => $admin->email,
            'role' => 'admin',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_a_member(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'member', 'name' => 'Old Name']);

        $response = $this->actingAs($admin)->put(route('user.update', $member), [
            'name' => 'Updated Name',
            'email' => $member->email,
            'role' => 'manager',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'User updated successfully.');
        $this->assertDatabaseHas('users', ['id' => $member->id, 'name' => 'Updated Name', 'role' => 'manager']);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->delete(route('user.destroy', $admin));

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_a_member(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($admin)->delete(route('user.destroy', $member));

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'User deleted successfully.');
        $this->assertDatabaseMissing('users', ['id' => $member->id]);
    }

    public function test_authenticated_user_can_view_their_own_profile(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($member)->get(route('user.profile'));

        $response->assertStatus(200);
        $response->assertViewIs('profile');
    }
}