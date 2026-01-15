<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Krijon një përdorues të ri ose lidhet me një ekzistues
            'user_id' => User::factory(),
            
            // Krijon një produkt të ri (me UUID) ose lidhet me një ekzistues
            'product_id' => Product::factory(),
            
            // Gjeneron vlerat: 1.0, 1.5, 2.0, ..., 5.0
            'stars' => fake()->randomElement([
                1.0, 1.5, 2.0, 2.5, 3.0, 3.5, 4.0, 4.5, 5.0
            ]),
            
            'comment' => fake()->optional(0.7)->sentence(), // 70% shans të ketë koment
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}