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
        // 1. Krijojmë përdoruesit e thjeshtë (Pronarët e produkteve)
        $regularUsers = User::factory()->count(10)->create();

        // 2. Secili përdorues krijon produkte (2 deri në 5 produkte secili)
        foreach ($regularUsers as $user) {
            Product::factory()
                ->count(rand(2, 5))
                ->create(['user_id' => $user->id]);
        }

        // 3. Krijojmë Adminët
        User::factory()->count(5)->admin()->create();

        // 4. Marrim të gjithë përdoruesit dhe produktet për Ratings
        $allUsers = User::all();
        $allProducts = Product::all();

        // 5. Krijojmë Ratings (Lidhja midis përdoruesve dhe produkteve)
        foreach ($allProducts as $product) {
            // Marrim të gjithë përdoruesit përveç pronarit të produktit
            $potentialRaters = $allUsers->where('id', '!=', $product->user_id);

            // Zgjedhim një numër të arsyeshëm vlerësuesish (psh 2 deri në 5 për produkt)
            // Përdorim min() që mos të kërkojmë më shumë përdorues sesa kemi në DB
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