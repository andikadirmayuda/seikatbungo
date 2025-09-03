<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description');
            $table->enum('type', ['fixed', 'percentage']);
            $table->decimal('value', 10, 2);
            $table->decimal('minimum_purchase', 10, 2)->default(0);
            $table->integer('max_uses')->default(0);
            $table->integer('used_count')->default(0);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Add voucher fields to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->string('voucher_code')->nullable()->after('total_amount');
            $table->string('voucher_description')->nullable();
            $table->decimal('voucher_discount', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vouchers');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['voucher_code', 'voucher_description', 'voucher_discount']);
        });
    }
};
