<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();

            // orders.id është bigint
            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            // products.id është uuid
            $table->uuid('product_id');

            $table->unsignedInteger('quantity')->default(1);

            // ruaj çmimin në momentin e blerjes (shumë e rëndësishme)
            $table->unsignedInteger('unit_price_cents');

            $table->timestamps();

            // FK te products
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete();

            // mos lejo të njëjtin produkt 2 herë në të njëjtin order
            $table->unique(['order_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};