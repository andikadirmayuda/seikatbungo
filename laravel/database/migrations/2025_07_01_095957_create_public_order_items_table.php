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
        Schema::create('public_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('public_order_id')->constrained('public_orders')->onDelete('cascade');
            // Relasi ke produk, wajib terisi
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->unsignedBigInteger('bouquet_id')->nullable()->comment('ID bouquet jika item ini adalah bouquet');
            // Nama produk disimpan untuk histori, tapi tetap ambil data utama dari tabel products
            $table->string('product_name');
            $table->integer('quantity');
            // Harga diambil dari tabel product_prices (is_default = true) saat order dibuat
            $table->decimal('price', 12, 2);
            $table->timestamps();
            // Index untuk optimasi
            $table->index('product_id');
            $table->index('public_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_order_items');
    }
};
