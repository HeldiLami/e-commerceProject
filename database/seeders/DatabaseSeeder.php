<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Rating;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $regularUsers = User::factory()->count(10)->create();

        for ($i = 1; $i <= 30; $i++) {
            Product::factory()->create();
        }

        User::factory()->count(5)->admin()->create();

        $allProducts = Product::all();

        foreach ($allProducts as $product) {
            $potentialRaters = $regularUsers;

            $numberOfRaters = min($potentialRaters->count(), rand(2, 5));
            
            $raters = $potentialRaters->random($numberOfRaters);

            foreach ($raters as $rater) {
                Rating::factory()->create([
                    'user_id' => $rater->id,
                    'product_id' => $product->id,
                ]);
            }
        }
    }
}