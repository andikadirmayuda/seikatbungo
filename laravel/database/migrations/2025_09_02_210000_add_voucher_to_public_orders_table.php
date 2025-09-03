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
            $table->string('voucher_code')->nullable()->after('wa_number');
            $table->decimal('voucher_amount', 12, 2)->default(0)->after('voucher_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_orders', function (Blueprint $table) {
            $table->dropColumn(['voucher_code', 'voucher_amount']);
        });
    }
};
