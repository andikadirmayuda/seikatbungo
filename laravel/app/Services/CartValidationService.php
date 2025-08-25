<?php

namespace App\Services;

use App\Models\CustomBouquet;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\Log;

class CartValidationService
{
    public function validateCustomBouquet($item)
    {
        // Skip jika bukan custom bouquet
        if (!isset($item['type']) || $item['type'] !== 'custom_bouquet') {
            return true;
        }

        // Validasi custom_bouquet_id
        if (!isset($item['custom_bouquet_id'])) {
            Log::error('Custom bouquet missing ID', ['item' => $item]);
            return false;
        }

        // Cari custom bouquet
        $customBouquet = CustomBouquet::find($item['custom_bouquet_id']);
        if (!$customBouquet) {
            Log::error('Custom bouquet not found', ['id' => $item['custom_bouquet_id']]);
            return false;
        }

        // Validasi harga untuk setiap item dalam custom bouquet
        foreach ($customBouquet->items as $bouquetItem) {
            // Cek apakah masih ada harga yang valid untuk setiap item
            $price = ProductPrice::where('product_id', $bouquetItem->product_id)
                ->where('type', $bouquetItem->price_type)
                ->first();

            if (!$price) {
                Log::error('Invalid price configuration for custom bouquet item', [
                    'product_id' => $bouquetItem->product_id,
                    'price_type' => $bouquetItem->price_type
                ]);
                return false;
            }
        }

        // Bouquet sudah valid jika sudah sampai di sini
        return true;
    }
}
