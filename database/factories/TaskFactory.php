<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Models\User;
/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Task::class;
    public function definition(): array
    {
        
        return [
            'project_id'       => Project::inRandomOrder()->value('id') ?? Project::factory(),
            'title'            => fake()->sentence(4),
            'description'      => fake()->paragraph(),
            'member_id'        => User::inRandomOrder()->value('id'),
            'priority'         => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'status'           => fake()->randomElement(['todo', 'in_progress', 'review', 'completed']),
            'due_date'         => fake()->dateTimeBetween('now', '+2 months'),
            'estimated_hours'  => fake()->randomFloat(2, 1, 40),
        ];
    }
}
