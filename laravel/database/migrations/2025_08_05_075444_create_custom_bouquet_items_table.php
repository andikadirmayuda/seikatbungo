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
        Schema::create('custom_bouquet_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_bouquet_id')->constrained('custom_bouquets')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->enum('price_type', [
                'per_tangkai',
                'ikat_5',
                'ikat_10',
                'ikat_20',
                'reseller',
                'normal',
                'promo',
                'custom_ikat',
                'custom_tangkai',
                'custom_khusus'
            ])->default('per_tangkai');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->index(['custom_bouquet_id', 'product_id']);
            $table->index('price_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_bouquet_items');
    }
};
