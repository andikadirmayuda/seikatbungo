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
        Schema::table('custom_bouquets', function (Blueprint $table) {
            // Update the status ENUM to include 'finalized'
            $table->enum('status', ['draft', 'in_cart', 'finalized', 'ordered'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_bouquets', function (Blueprint $table) {
            // Revert back to original ENUM values
            $table->enum('status', ['draft', 'in_cart', 'ordered'])->default('draft')->change();
        });
    }
};
