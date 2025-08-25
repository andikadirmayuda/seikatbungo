<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPricesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('type', [
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
            ]);
            $table->decimal('price', 12, 2);
            $table->integer('unit_equivalent');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->unique(['product_id', 'type']);
            $table->index('type');
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
