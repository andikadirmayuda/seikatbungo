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
        Schema::create('public_orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->date('pickup_date');
            $table->time('pickup_time')->nullable();
            $table->string('delivery_method');
            $table->string('destination')->nullable();
            $table->string('status')->default('pending');
            $table->string('wa_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_orders');
    }
};
