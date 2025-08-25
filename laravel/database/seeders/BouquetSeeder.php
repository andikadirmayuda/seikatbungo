<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bouquet;
use App\Models\BouquetCategory;

class BouquetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create Wedding category
        $weddingCategory = BouquetCategory::firstOrCreate(
            ['name' => 'Wedding'],
            // ['description' => 'Wedding bouquet collection']
        );

        // Create the Cascade bouquet
        $bouquet = Bouquet::create([
            'name' => 'Cascade',
            'category_id' => $weddingCategory->id,
            // 'description' => 'Elegant cascade wedding bouquet with premium flowers',
        ]);

        // Get all sizes
        $sizes = \App\Models\BouquetSize::all();

        // Create price variations for the bouquet
        $prices = [
            'Small' => 400000.00,
            'Medium' => 600000.00,
            'Large' => 800000.00,
        ];
        $bouquet = Bouquet::create([
            'name' => 'kfd',
            'category_id' => $weddingCategory->id,
            // 'description' => 'Elegant cascade wedding bouquet with premium flowers',
        ]);

        // Get all sizes
        $sizes = \App\Models\BouquetSize::all();

        // Create price variations for the bouquet
        $prices = [
            'Small' => 400000.00,
            'Medium' => 600000.00,
            'Large' => 800000.00,
        ];
        $bouquet = Bouquet::create([
            'name' => 'fd',
            'category_id' => $weddingCategory->id,
            // 'description' => 'Elegant cascade wedding bouquet with premium flowers',
        ]);

        // Get all sizes
        $sizes = \App\Models\BouquetSize::all();

        // Create price variations for the bouquet
        $prices = [
            'Small' => 400000.00,
            'Medium' => 600000.00,
            'Large' => 800000.00,
        ];
        $bouquet = Bouquet::create([
            'name' => 'utrt',
            'category_id' => $weddingCategory->id,
            // 'description' => 'Elegant cascade wedding bouquet with premium flowers',
        ]);

        // Get all sizes
        $sizes = \App\Models\BouquetSize::all();

        // Create price variations for the bouquet
        $prices = [
            'Small' => 400000.00,
            'Medium' => 600000.00,
            'Large' => 800000.00,
        ];
        $bouquet = Bouquet::create([
            'name' => 'kdf',
            'category_id' => $weddingCategory->id,
            // 'description' => 'Elegant cascade wedding bouquet with premium flowers',
        ]);

        // Get all sizes
        $sizes = \App\Models\BouquetSize::all();

        // Create price variations for the bouquet
        $prices = [
            'Small' => 400000.00,
            'Medium' => 600000.00,
            'Large' => 800000.00,
        ];
        $bouquet = Bouquet::create([
            'name' => 'sfas',
            'category_id' => $weddingCategory->id,
            // 'description' => 'Elegant cascade wedding bouquet with premium flowers',
        ]);

        // Get all sizes
        $sizes = \App\Models\BouquetSize::all();

        // Create price variations for the bouquet
        $prices = [
            'Small' => 400000.00,
            'Medium' => 600000.00,
            'Large' => 800000.00,
        ];
        $bouquet = Bouquet::create([
            'name' => 'ssd',
            'category_id' => $weddingCategory->id,
            // 'description' => 'Elegant cascade wedding bouquet with premium flowers',
        ]);

        // Get all sizes
        $sizes = \App\Models\BouquetSize::all();

        // Create price variations for the bouquet
        $prices = [
            'Small' => 400000.00,
            'Medium' => 600000.00,
            'Large' => 800000.00,
        ];
        $bouquet = Bouquet::create([
            'name' => 'sfssf',
            'category_id' => $weddingCategory->id,
            // 'description' => 'Elegant cascade wedding bouquet with premium flowers',
        ]);

        // Get all sizes
        $sizes = \App\Models\BouquetSize::all();

        // Create price variations for the bouquet
        $prices = [
            'Small' => 400000.00,
            'Medium' => 600000.00,
            'Large' => 800000.00,
        ];
        $bouquet = Bouquet::create([
            'name' => 'rsffssf',
            'category_id' => $weddingCategory->id,
            // 'description' => 'Elegant cascade wedding bouquet with premium flowers',
        ]);

        // Get all sizes
        $sizes = \App\Models\BouquetSize::all();

        // Create price variations for the bouquet
        $prices = [
            'Small' => 400000.00,
            'Medium' => 600000.00,
            'Large' => 800000.00,
        ];
        $bouquet = Bouquet::create([
            'name' => 'abc',
            'category_id' => $weddingCategory->id,
            // 'description' => 'Elegant cascade wedding bouquet with premium flowers',
        ]);

        // Get all sizes
        $sizes = \App\Models\BouquetSize::all();

        // Create price variations for the bouquet
        $prices = [
            'Small' => 400000.00,
            'Medium' => 600000.00,
            'Large' => 800000.00,
        ];

        foreach ($sizes as $size) {
            if (isset($prices[$size->name])) {
                $bouquet->prices()->create([
                    'size_id' => $size->id,
                    'price' => $prices[$size->name],
                ]);
            }
        }
    }
}
