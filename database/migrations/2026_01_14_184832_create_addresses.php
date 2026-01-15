<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('full_name');
            $table->string('phone')->nullable();

            $table->string('country')->default('Albania');
            $table->string('city');
            $table->string('street');
            $table->string('zip')->nullable();

            $table->boolean('is_default')->default(false);

            $table->timestamps();

            // opsionale: për kërkime më të shpejta
            $table->index(['user_id', 'is_default']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};