<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel orders
            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            // Foreign key ke tabel product_variants
            $table->foreignId('variant_id')
                ->constrained('product_variants')
                ->cascadeOnDelete();

            // Snapshot data untuk histori harga dan nama
            $table->string('product_name');
            $table->string('variant_name');

            $table->integer('price');
            $table->integer('qty');
            $table->integer('subtotal');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
