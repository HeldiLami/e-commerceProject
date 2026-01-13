<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin users with products
        $adminUsers = User::factory()
            ->count(5)
            ->admin()
            ->create();

        // Create products for each admin user
        foreach ($adminUsers as $adminUser) {
            Product::factory()
                ->count(rand(3, 8))
                ->create([
                    'user_id' => $adminUser->id,
                ]);
        }

        // Create regular users without products
        User::factory()
            ->count(10)
            ->create();
    }
}
