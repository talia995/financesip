<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'category_id' => Category::factory(),
            'type' => fake()->randomElement(['income', 'expense']),
            'amount' => fake()->numberBetween(10000, 500000),
            'transaction_date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}

