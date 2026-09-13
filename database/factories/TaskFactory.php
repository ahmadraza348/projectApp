<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * The tasks table's assignment column is 'assigned_to' (and 'created_by').
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::inRandomOrder()->value('id') ?? Project::factory(),
            'parent_task_id' => null,
            'created_by' => User::inRandomOrder()->value('id'),
            'assigned_to' => User::inRandomOrder()->value('id'),

            'title' => fake()->sentence(4),
            'task_code' => strtoupper(fake()->unique()->bothify('TSK-####')),
            'description' => fake()->paragraph(),
            'type' => fake()->randomElement(['bug', 'feature', 'task', 'improvement']),

            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => fake()->randomElement(['todo', 'in_progress', 'review', 'completed']),

            'start_date' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'due_date' => fake()->dateTimeBetween('now', '+2 months'),

            'estimated_hours' => fake()->randomFloat(2, 1, 40),
            'is_billable' => fake()->boolean(70),
        ];
    }
}
