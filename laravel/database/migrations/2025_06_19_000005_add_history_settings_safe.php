<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $settings = [
            [
                'key' => 'history_cleanup_period',
                'value' => 'monthly', // options: biweekly, monthly
            ],
            [
                'key' => 'history_retention_days',
                'value' => '30', // Default: simpan riwayat selama 30 hari
            ]
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']], // kriteria pencarian
                [
                    'value' => $setting['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down()
    {
        DB::table('settings')->whereIn('key', [
            'history_cleanup_period',
            'history_retention_days'
        ])->delete();
    }
};
