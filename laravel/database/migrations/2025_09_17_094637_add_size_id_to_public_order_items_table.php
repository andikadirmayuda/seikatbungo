<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('public_order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('size_id')->nullable()->after('bouquet_id');
        });
    }

    public function down()
    {
        Schema::table('public_order_items', function (Blueprint $table) {
            $table->dropColumn('size_id');
        });
    }
};
