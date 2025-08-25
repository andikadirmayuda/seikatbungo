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
            $table->foreignId('custom_bouquet_id')->nullable()->constrained('custom_bouquets')->onDelete('set null');
            $table->string('reference_image')->nullable();
            $table->text('custom_instructions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_order_items', function (Blueprint $table) {
            $table->dropForeign(['custom_bouquet_id']);
            $table->dropColumn(['custom_bouquet_id', 'reference_image', 'custom_instructions']);
        });
    }
};
