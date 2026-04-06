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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained();
            $table->foreignId('address_id')->constrained();
            $table->foreignId('voucher_id')->nullable()->constrained();

            $table->integer('total_price')->default(0);
            $table->integer('discount')->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->integer('final_price')->default(0);
            $table->string('payment_method')->nullable(); // ← tambah di sini
            $table->enum('status',['pending','paid','shipped','done','cancelled']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
