<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BouquetSize;

class BouquetSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            'Small',
            'Medium',
            'Large'
        ];

        foreach ($sizes as $size) {
            BouquetSize::firstOrCreate(['name' => $size]);
        }
    }
}
