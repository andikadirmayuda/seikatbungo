<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['current_stock', 'is_active'], 'idx_stock_tracking');
        });

        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->index(['reference_id', 'source'], 'idx_inventory_reference');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_stock_tracking');
        });

        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->dropIndex('idx_inventory_reference');
        });
    }
};
