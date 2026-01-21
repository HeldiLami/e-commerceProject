<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->unsignedInteger('amount_cents');
            $table->string('provider')->default('card');
            $table->string('status')->default('pending');
            $table->string('transaction_ref')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->unique('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};