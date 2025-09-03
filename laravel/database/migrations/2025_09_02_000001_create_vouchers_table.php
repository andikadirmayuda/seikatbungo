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
            $table->enum('type', [
                'percent',          // Diskon Persentase
                'nominal',          // Diskon Nominal
                'cashback',         // Cashback
                'shipping',         // Potongan Ongkir
                'seasonal',         // Voucher Musiman/Event
                'first_purchase',   // Voucher Pembelian Pertama
                'loyalty',          // Voucher Member/Loyal Customer
            ]);
            $table->decimal('value', 12, 2);              // Nilai voucher
            $table->decimal('minimum_spend', 12, 2);      // Minimum pembelanjaan
            $table->decimal('maximum_discount', 12, 2)->nullable();  // Maksimum potongan (untuk percent)
            $table->integer('usage_limit')->nullable();    // Batas penggunaan
            $table->integer('usage_count')->default(0);    // Jumlah telah digunakan
            $table->boolean('is_active')->default(true);   // Status aktif
            $table->datetime('start_date');               // Tanggal mulai
            $table->datetime('end_date');                 // Tanggal berakhir

            // Fields untuk Seasonal Voucher
            $table->string('event_name')->nullable();      // Nama event (Valentine, Wisuda, dll)
            $table->string('event_type')->nullable();      // Tipe event

            // Fields untuk First Purchase
            $table->boolean('first_purchase_only')->default(false);

            // Fields untuk Loyalty/Member
            $table->integer('minimum_points')->nullable(); // Minimum poin member
            $table->string('member_level')->nullable();    // Level member yang bisa menggunakan

            // Fields untuk tracking
            $table->json('applied_to')->nullable();        // Riwayat penggunaan
            $table->json('restrictions')->nullable();      // Pembatasan produk/kategori
            $table->text('terms_and_conditions')->nullable(); // Syarat dan ketentuan

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('code');
            $table->index('type');
            $table->index('is_active');
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
};
