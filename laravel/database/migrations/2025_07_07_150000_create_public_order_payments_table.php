<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('public_order_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('public_order_id');
            $table->decimal('amount', 15, 2);
            $table->string('note')->nullable();
            $table->string('proof')->nullable();
            $table->timestamps();

            $table->foreign('public_order_id')->references('id')->on('public_orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('public_order_payments');
    }
};
