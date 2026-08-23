<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\User;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-3 months', 'now');
        $end = fake()->dateTimeBetween($start, '+3 months');

        return [
            'name'              => fake()->unique()->catchPhrase(),
            'description'       => fake()->paragraph(),
            'category_id'       => Category::inRandomOrder()->value('id') ?? Category::factory(),
            'assigned_user_id'  => User::inRandomOrder()->value('id'),
            'status'            => fake()->randomElement(['planning', 'in_progress', 'review', 'complete']),
            'start_date'        => $start,
            'end_date'          => $end,
            'budget'            => fake()->randomFloat(2, 1000, 50000),
        ];
    }
}
