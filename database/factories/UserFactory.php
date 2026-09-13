<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * Matches the users migration columns, including the plain 'role'
     * string column (default 'member').
     */
    public function definition(): array
    {
        return [
            'department_id' => null,
            'role' => 'member',
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'password' => static::$password ??= Hash::make('password'),
            'avatar' => null,
            'job_title' => fake()->optional()->jobTitle(),
            'employee_code' => fake()->unique()->numerify('EMP-####'),
            'status' => true,
            'last_login_at' => null,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => ['email_verified_at' => null]);
    }
}
