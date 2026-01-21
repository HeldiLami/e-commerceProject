<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignUuid('product_id') 
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('stars', 2, 1);

            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);

            $table->index(['product_id', 'stars']);
        });

        DB::statement('ALTER TABLE ratings ADD CONSTRAINT chk_rating_stars_half_step CHECK (MOD(stars * 10, 5) = 0 AND stars <= 5)');
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};