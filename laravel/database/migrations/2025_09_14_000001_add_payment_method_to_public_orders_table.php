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
        Schema::table('public_orders', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'transfer', 'debit', 'e-wallet'])
                ->nullable()
                ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_orders', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};
