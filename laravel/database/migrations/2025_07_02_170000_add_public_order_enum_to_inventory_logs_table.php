<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum kolom source pada tabel inventory_logs, tambahkan 'public_order'
        DB::statement("ALTER TABLE inventory_logs MODIFY source ENUM('purchase', 'sale', 'return', 'adjustment', 'public_order')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan enum ke semula (tanpa 'public_order')
        DB::statement("ALTER TABLE inventory_logs MODIFY source ENUM('purchase', 'sale', 'return', 'adjustment')");
    }
};
