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
            $table->enum('item_type', ['product', 'bouquet', 'custom_bouquet'])->default('product')->after('product_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_order_items', function (Blueprint $table) {
            $table->dropColumn('item_type');
        });
    }
};
