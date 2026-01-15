<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(), 
            'user_id' => User::factory(),
            'name' => fake()->words(3, true),
            'image' => 'products/' . fake()->image(null, 640, 480, 'technics', false),
            'type' => fake()->randomElement(['product', 'clothing', 'appliance']),
            'price_cents' => fake()->numberBetween(500, 10000),
            'keywords' => [fake()->word(), fake()->word(), fake()->word()],
            'quantity' => fake()->numberBetween(0, 50),
        ];
    }
}