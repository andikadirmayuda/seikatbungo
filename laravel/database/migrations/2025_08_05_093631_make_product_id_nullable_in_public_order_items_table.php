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
        Schema::table('public_order_items', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['product_id']);

            // Make product_id nullable and add back the foreign key
            $table->foreignId('product_id')->nullable()->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_order_items', function (Blueprint $table) {
            // Drop the nullable foreign key
            $table->dropForeign(['product_id']);

            // Make product_id required again
            $table->foreignId('product_id')->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
        });
    }
};
