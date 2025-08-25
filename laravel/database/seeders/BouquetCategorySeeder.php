<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BouquetCategory;

class BouquetCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'code' => 'WEDDING',
                'name' => 'Wedding',
                // 'description' => 'Bouquet untuk pernikahan'
            ],
            [
                'code' => 'BIRTH',
                'name' => 'Birthday',
                // 'description' => 'Bouquet untuk ulang tahun'
            ],
            [
                'code' => 'GRAD',
                'name' => 'Graduation',
                // 'description' => 'Bouquet untuk wisuda'
            ],
            [
                'code' => 'ANNIV',
                'name' => 'Anniversary',
                // 'description' => 'Bouquet untuk anniversary'
            ],
            [
                'code' => 'CONG',
                'name' => 'Congratulation',
                // 'description' => 'Bouquet untuk ucapan selamat'
            ],
            [
                'code' => 'SYMPA',
                'name' => 'Sympathy',
                // 'description' => 'Bouquet untuk belasungkawa'
            ],
            [
                'code' => 'VDAY',
                'name' => 'Valentine',
                // 'description' => 'Bouquet untuk Valentine'
            ],
            [
                'code' => 'MOTHER',
                'name' => 'Mother\'s Day',
                // 'description' => 'Bouquet untuk Hari Ibu'
            ]
        ];

        foreach ($categories as $category) {
            BouquetCategory::firstOrCreate(
                ['code' => $category['code']],
                $category
            );
        }
    }
}
