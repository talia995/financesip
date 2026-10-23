<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Makanan & Minuman', 'Transportasi', 'Belanja Harian', 'Gaji Bulanan', 'Bonus Proyek']),
            'type' => fake()->randomElement(['income', 'expense']),
            'icon' => fake()->randomElement(['🍔', '🚗', '🛍️', '💼', '🎁']),
            'color' => fake()->randomElement(['emerald', 'rose', 'blue', 'amber', 'purple']),
        ];
    }
}

