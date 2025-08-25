<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Bouquet;
use App\Models\BouquetSize;
use App\Models\BouquetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PublicFlowerController extends Controller
{
    public function index()
    {
        // Tampilkan semua produk bunga, termasuk yang stoknya 0.
        // Urutkan: yang ada stok dulu, lalu habis, kemudian berdasarkan nama.
        $flowers = Product::with(['category', 'prices'])
            ->orderByRaw('(current_stock > 0) desc')
            ->orderBy('name')
            ->get();

        $lastUpdated = Product::max('updated_at') ?? now();

        $activeTab = request()->query('tab', 'flowers');

        // Jika tab adalah bouquets, ambil data bouquet juga (untuk backward compatibility)
        $bouquetData = [];
        if ($activeTab === 'bouquets') {
            $bouquetController = new PublicBouquetController();
            $bouquetData = $bouquetController->getBouquetData();
            // Update last updated untuk include bouquet data
            $lastUpdated = max($lastUpdated, Bouquet::max('updated_at') ?? now());
        }

        return view('public.flowers', array_merge([
            'flowers' => $flowers,
            'lastUpdated' => $lastUpdated,
            'activeTab' => $activeTab
        ], $bouquetData));
    }

    public function getFlowerData()
    {
        // Method untuk mendapatkan data flowers yang bisa dipanggil dari controller lain
        // Sertakan juga produk dengan stok 0 agar UI bisa menandai "Habis".
        $flowers = Product::with(['category', 'prices'])
            ->orderByRaw('(current_stock > 0) desc')
            ->orderBy('name')
            ->get();

        return [
            'flowers' => $flowers,
        ];
    }
}
