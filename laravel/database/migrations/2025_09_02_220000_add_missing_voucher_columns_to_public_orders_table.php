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
            // Tambah kolom-kolom voucher yang kurang setelah voucher_amount
            $table->string('voucher_type')->nullable()->after('voucher_amount');
            $table->decimal('voucher_value', 12, 2)->nullable()->after('voucher_type');
            $table->decimal('voucher_minimum', 12, 2)->nullable()->after('voucher_value');
            $table->string('voucher_description')->nullable()->after('voucher_minimum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_orders', function (Blueprint $table) {
            $table->dropColumn([
                'voucher_type',
                'voucher_value',
                'voucher_minimum',
                'voucher_description'
            ]);
        });
    }
};
