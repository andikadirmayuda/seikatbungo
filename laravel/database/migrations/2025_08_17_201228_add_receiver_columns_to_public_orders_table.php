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
            $table->string('receiver_name')->nullable()->after('wa_number');
            $table->string('receiver_wa')->nullable()->after('receiver_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_orders', function (Blueprint $table) {
            $table->dropColumn(['receiver_name', 'receiver_wa']);
        });
    }
};
