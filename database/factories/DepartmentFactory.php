<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            // 'user_id' (department head) is required and constrained on the
            // migration — it cannot be left out or null.
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(),
            'name' => ucfirst(fake()->unique()->words(2, true)),
            'description' => fake()->sentence(),
            'status' => true,
        ];
    }
}
