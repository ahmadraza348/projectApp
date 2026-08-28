<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan; 
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:client', [
            '--personal' => true,
            '--name' => 'Test Personal Access Client',
            '--no-interaction' => true,
        ]);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'ahmad@example.com',
            // UserFactory hashes the literal string 'password' for every user.
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'ahmad@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => ['user', 'token', 'token_type'],
        ]);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create(['email' => 'ahmad@example.com']);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'ahmad@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['success' => false]);
    }

    public function test_login_fails_validation_when_email_missing(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_guest_cannot_access_me_endpoint(): void
    {
        $response = $this->getJson('/api/v1/me');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_view_profile_via_me(): void
    {
        $user = User::factory()->create(['name' => 'Ahmad Raza']);
        Passport::actingAs($user);

        $response = $this->getJson('/api/v1/me');

        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'Ahmad Raza');
    }
}
