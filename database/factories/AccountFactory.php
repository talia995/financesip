<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $initialBalance = fake()->numberBetween(100000, 5000000);

        return [
            'name' => fake()->randomElement(['BCA Utama', 'Mandiri Tabungan', 'GoPay', 'OVO', 'Dompet Tunai']),
            'type' => fake()->randomElement(['bank', 'e-wallet', 'cash']),
            'account_number' => fake()->optional()->bankAccountNumber(),
            'initial_balance' => $initialBalance,
            'current_balance' => $initialBalance,
            'is_active' => true,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}

