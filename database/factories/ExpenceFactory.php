<?php

namespace Database\Factories;

use App\Models\Expence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expence>
 */
class ExpenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => null,
            'created_by' => null,
            'approved_by' => null,
            'category' => fake()->randomElement(['travel', 'software', 'hardware', 'meals']),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
            'amount' => fake()->randomFloat(2, 1, 10000),
            'currency' => 'USD',
            'expense_date' => fake()->date(),
            'vendor' => fake()->optional()->company(),
            'payment_method' => fake()->randomElement(['credit_card', 'cash', 'bank_transfer']),
            'receipt_path' => null,
            'status' => 'pending',
            'approved_at' => null,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
