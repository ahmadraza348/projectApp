<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskTimeLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskTimeLog>
 */
class TaskTimeLogFactory extends Factory
{
    protected $model = TaskTimeLog::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'     => User::inRandomOrder()->value('id') ?? User::factory(),
            'task_id'     => Task::inRandomOrder()->value('id') ?? Task::factory(),
            'hours'       => fake()->randomFloat(2, 0.25, 8),
            'logged_at'   => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'description' => fake()->sentence(6),
        ];
    }
}
