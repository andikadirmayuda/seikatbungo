<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum kolom 'type' pada tabel product_prices agar support 'ikat_3'
        DB::statement("ALTER TABLE product_prices MODIFY COLUMN type ENUM('per_tangkai','ikat_3','ikat_5','ikat_10','ikat_20','reseller','normal','promo','custom_ikat','custom_tangkai','custom_khusus')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan enum tanpa 'ikat_3'
        DB::statement("ALTER TABLE product_prices MODIFY COLUMN type ENUM('per_tangkai','ikat_5','ikat_10','ikat_20','reseller','normal','promo','custom_ikat','custom_tangkai','custom_khusus')");
    }
};
