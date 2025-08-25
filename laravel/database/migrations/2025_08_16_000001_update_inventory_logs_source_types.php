<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            // Backup data lama
            $logs = DB::table('inventory_logs')->get();

            // Hapus kolom source yang lama
            $table->dropColumn('source');

            // Buat kolom source yang baru dengan enum yang diperbarui
            $table->enum('source', [
                'sale',                           // Penjualan langsung di toko
                'public_order',                   // Pesanan publik - umum
                'public_order_hold',              // Penahanan stok pesanan publik
                'public_order_product',           // Pesanan publik - produk normal
                'public_order_bouquet',           // Pesanan publik - bouquet
                'public_order_bouquet_hold',      // Penahanan stok komponen bouquet
                'public_order_custom',            // Pesanan publik - custom bouquet
                'public_order_custom_hold',       // Penahanan stok custom bouquet
                'purchase',                 // Pembelian stok
                'return',                  // Pengembalian barang
                'adjustment',              // Penyesuaian manual
                'correction'               // Koreksi kesalahan
            ])->after('qty');

            // Restore data lama
            foreach ($logs as $log) {
                if ($log->source === 'direct_sale') {
                    $log->source = 'sale';
                }
                DB::table('inventory_logs')
                    ->where('id', $log->id)
                    ->update(['source' => $log->source]);
            }
        });
    }

    public function down()
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->dropColumn('source');
            $table->enum('source', ['purchase', 'sale', 'return', 'adjustment'])
                ->after('qty');
        });
    }
};
