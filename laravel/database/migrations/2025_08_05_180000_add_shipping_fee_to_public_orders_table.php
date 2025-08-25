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
            $table->decimal('shipping_fee', 15, 2)->default(0)->after('destination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_orders', function (Blueprint $table) {
            $table->dropColumn('shipping_fee');
        });
    }
};
