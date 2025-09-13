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
            $table->text('greeting_card')->nullable()->after('custom_instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_order_items', function (Blueprint $table) {
            $table->dropColumn('greeting_card');
        });
    }
};
