<?php

namespace Database\Factories;

use App\Models\TaskTimeLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
use App\Models\User;

/**
 * @extends Factory<TaskTimeLog>
 */
class TaskTimeLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TaskTimeLog::class;

    public function definition(): array
    {
        return [
            'task_id'     => Task::inRandomOrder()->value('id') ?? Task::factory(),
            'user_id'     => User::inRandomOrder()->value('id') ?? User::factory(),
            'hours'       => fake()->randomFloat(2, 0.5, 8),
            'logged_at'   => fake()->dateTimeBetween('-1 month', 'now'),
            'description' => fake()->sentence(6),
        ];
    }
}
