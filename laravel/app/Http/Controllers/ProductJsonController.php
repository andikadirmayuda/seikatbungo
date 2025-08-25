<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class ProductJsonController extends Controller
{
    // Export semua produk ke file JSON dan download
    public function export()
    {
        $products = Product::all();
        $json = $products->toJson(JSON_PRETTY_PRINT);
        $filename = 'products_export_' . date('Ymd_His') . '.json';
        return response($json)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    // Import produk dari file JSON yang diupload
    public function import(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json',
        ]);
        $json = file_get_contents($request->file('json_file')->getRealPath());
        $data = json_decode($json, true);
        $imported = 0;
        if (is_array($data)) {
            foreach ($data as $item) {
                // Sesuaikan field sesuai kebutuhan
                Product::updateOrCreate(
                    ['id' => $item['id'] ?? null],
                    [
                        'category_id' => $item['category_id'] ?? null,
                        'name' => $item['name'] ?? null,
                        'description' => $item['description'] ?? null,
                        'image' => $item['image'] ?? null,
                        'base_unit' => $item['base_unit'] ?? null,
                        'current_stock' => $item['current_stock'] ?? 0,
                        'min_stock' => $item['min_stock'] ?? 0,
                        'is_active' => $item['is_active'] ?? 1,
                        'price_per_tangkai' => $item['price_per_tangkai'] ?? 0,
                        'price_ikat_5' => $item['price_ikat_5'] ?? 0,
                        'price_ikat_10' => $item['price_ikat_10'] ?? 0,
                        'price_ikat_20' => $item['price_ikat_20'] ?? 0,
                        'price_reseller' => $item['price_reseller'] ?? 0,
                        'price_normal' => $item['price_normal'] ?? 0,
                        'price_promo' => $item['price_promo'] ?? 0,
                        'default_price_type' => $item['default_price_type'] ?? null,
                    ]
                );
                $imported++;
            }
        }
        return back()->with('success', "Berhasil import $imported produk dari file JSON.");
    }
}
