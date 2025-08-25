<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bouquet;
use App\Models\BouquetComponent;
use App\Models\BouquetSize;
use App\Models\Product;

class BouquetComponentSeeder extends Seeder
{
    public function run()
    {
        // Ambil data yang diperlukan
        $bouquets = Bouquet::all();
        $sizes = BouquetSize::all();
        $products = Product::limit(10)->get(); // Ambil 10 produk pertama sebagai bunga

        if ($bouquets->isEmpty() || $sizes->isEmpty() || $products->isEmpty()) {
            $this->command->info('Tidak ada data bouquet, size, atau product. Pastikan seeder lain sudah dijalankan.');
            return;
        }

        // Untuk setiap bouquet, buat komponen untuk setiap ukuran
        foreach ($bouquets as $bouquet) {
            foreach ($sizes as $size) {
                // Buat 2-4 komponen berbeda untuk setiap ukuran
                $componentCount = rand(2, 4);
                $usedProducts = collect();
                
                for ($i = 0; $i < $componentCount; $i++) {
                    // Pilih produk yang belum digunakan untuk size ini
                    $availableProducts = $products->diff($usedProducts);
                    if ($availableProducts->isEmpty()) {
                        break;
                    }
                    
                    $product = $availableProducts->random();
                    $usedProducts->push($product);
                    
                    // Quantity berdasarkan ukuran
                    $quantity = match($size->name) {
                        'Small' => rand(3, 5),
                        'Medium' => rand(5, 8),
                        'Large' => rand(8, 12),
                        default => rand(3, 6)
                    };

                    BouquetComponent::create([
                        'bouquet_id' => $bouquet->id,
                        'size_id' => $size->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                    ]);
                }
            }
        }

        $this->command->info('Bouquet components seeded successfully!');
    }
}
