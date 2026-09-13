<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Department;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * Rewritten to match the projects migration: there is no category_id or
     * assigned_user_id column, and the date field is 'deadline', not
     * 'end_date'. 'slug' is required (unique, not nullable).
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-3 months', 'now');
        $deadline = fake()->dateTimeBetween($start, '+3 months');
        $name = fake()->unique()->catchPhrase();

        return [
            'department_id' => Department::inRandomOrder()->value('id'),
            'client_id' => Client::inRandomOrder()->value('id'),
            'created_by' => User::inRandomOrder()->value('id'),
            'project_manager_id' => User::inRandomOrder()->value('id'),

            'name' => $name,
            'code' => strtoupper(fake()->unique()->bothify('PRJ-####')),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->paragraph(),

            'start_date' => $start,
            'deadline' => $deadline,

            'budget' => fake()->randomFloat(2, 1000, 50000),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => fake()->randomElement(['planning', 'in_progress', 'review', 'completed']),
            'health_status' => fake()->randomElement(['on_track', 'at_risk', 'off_track']),
            'progress' => fake()->numberBetween(0, 100),
            'is_billable' => fake()->boolean(80),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
