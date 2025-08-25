<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->foreignId('customer_id')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('status');
            $table->decimal('total', 10, 2);
            $table->decimal('down_payment', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->string('delivery_method')->nullable();
            $table->text('delivery_address')->nullable();
            $table->datetime('pickup_date')->nullable();
            $table->text('items_json'); // Store order items as JSON
            $table->datetime('original_created_at');
            $table->datetime('original_updated_at');
            $table->datetime('archived_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_histories');
    }
};
