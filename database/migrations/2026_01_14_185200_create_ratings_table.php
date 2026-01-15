<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->uuid('product_id');

            // 1..5
            $table->unsignedTinyInteger('stars');

            $table->text('comment')->nullable();

            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete();

            // 1 user = 1 review për 1 produkt
            $table->unique(['user_id', 'product_id']);

            // opsionale: për query më të shpejta
            $table->index(['product_id', 'stars']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
