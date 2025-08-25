<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;

class FillSalePublicCodeSeeder extends Seeder
{
    public function run(): void
    {
        Sale::whereNull('public_code')->orWhere('public_code', '')->get()->each(function($sale) {
            $sale->public_code = bin2hex(random_bytes(8));
            $sale->save();
        });
    }
}
