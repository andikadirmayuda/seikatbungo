<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_histories', function (Blueprint $table) {
            $table->dateTime('pickup_date')->nullable()->change();
            $table->dateTime('original_created_at')->nullable()->change();
            $table->dateTime('original_updated_at')->nullable()->change();
            $table->dateTime('archived_at')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('order_histories', function (Blueprint $table) {
            $table->dateTime('pickup_date')->nullable(false)->change();
            $table->dateTime('original_created_at')->nullable(false)->change();
            $table->dateTime('original_updated_at')->nullable(false)->change();
            $table->dateTime('archived_at')->nullable(false)->change();
        });
    }
};
