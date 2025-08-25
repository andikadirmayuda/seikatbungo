<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {        // Setting untuk periode pembersihan
        DB::table('settings')->insert([
            'key' => 'history_cleanup_period',
            'value' => 'monthly', // options: biweekly, monthly
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Setting untuk berapa lama data disimpan
        DB::table('settings')->insert([
            'key' => 'history_retention_days',
            'value' => '30', // Default: simpan riwayat selama 30 hari
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        DB::table('settings')->where('key', 'history_retention_days')->delete();
    }
};
