<?php

namespace App\Http\Controllers;

use App\Models\Bouquet;
use App\Models\BouquetCategory;
use App\Models\BouquetSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BouquetComponent; // Added this import

class PublicBouquetController extends Controller
{
    public function index(Request $request)
    {
        $query = Bouquet::with(['category', 'components.product', 'sizes', 'prices.size']);

        // Filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan rentang harga
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('prices', function ($priceQuery) use ($request) {
                if ($request->filled('min_price')) {
                    $priceQuery->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $priceQuery->where('price', '<=', $request->max_price);
                }
            });
        }

        // Filter berdasarkan pencarian nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $bouquets = $query->orderBy('name')->get();

        // Ambil kategori bouquet untuk filter
        $bouquetCategories = BouquetCategory::orderBy('name')->get();

        // Ambil ukuran bouquet untuk informasi
        $bouquetSizes = BouquetSize::orderBy('name')->get();

        // Ambil rentang harga untuk filter
        $minPrice = DB::table('bouquet_prices')->min('price') ?? 0;
        $maxPrice = DB::table('bouquet_prices')->max('price') ?? 1000000;

        $lastUpdated = Bouquet::max('updated_at') ?? now();

        return view('public.bouquets', [
            'bouquets' => $bouquets,
            'bouquetCategories' => $bouquetCategories,
            'bouquetSizes' => $bouquetSizes,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'lastUpdated' => $lastUpdated,
            'activeTab' => 'bouquets'
        ]);
    }

    public function getBouquetData()
    {
        // Method untuk mendapatkan data bouquet yang bisa dipanggil dari controller lain
        $bouquets = Bouquet::with(['category', 'components.product', 'sizes', 'prices.size'])
            ->orderBy('name')
            ->get();

        $bouquetCategories = BouquetCategory::orderBy('name')->get();
        $bouquetSizes = BouquetSize::orderBy('name')->get();

        return [
            'bouquets' => $bouquets,
            'bouquetCategories' => $bouquetCategories,
            'bouquetSizes' => $bouquetSizes,
        ];
    }

    public function detail($id)
    {
        $bouquet = Bouquet::with(['category', 'components.product', 'sizes', 'prices.size'])
            ->findOrFail($id);

        return view('public.bouquet.detail', [
            'bouquet' => $bouquet
        ]);
    }

    public function detailJson($id)
    {
        $bouquet = Bouquet::with([
            'category',
            'components.product',
            'components.size',
            'sizes',
            'prices.size'
        ])->findOrFail($id);

        // Bersihkan komponen yang tidak valid (produk sudah dihapus)
        $bouquet->cleanupInvalidComponents();

        // Ambil ulang data bouquet dengan komponen yang valid saja
        $bouquet = Bouquet::with([
            'category',
            'validComponents.product',
            'validComponents.size',
            'sizes',
            'prices.size'
        ])->findOrFail($id);

        // Kelompokkan komponen berdasarkan ukuran - hanya ukuran yang memiliki komponen
        $componentsBySize = $bouquet->validComponents->groupBy('size_id');

        // Format data untuk response
        $bouquetData = $bouquet->toArray();

        // Override components dengan validComponents
        $bouquetData['components'] = $bouquet->validComponents->toArray();
        $bouquetData['components_by_size'] = [];

        foreach ($componentsBySize as $sizeId => $components) {
            // Pastikan sizeId dikonversi ke string untuk konsistensi dengan JavaScript
            $bouquetData['components_by_size'][(string)$sizeId] = $components->toArray();
        }

        return response()->json($bouquetData);
    }

    public function getComponentsBySize($bouquetId, $sizeId)
    {
        // Add simple server-side caching
        $cacheKey = "bouquet_components_{$bouquetId}_{$sizeId}";
        
        // Try to get from cache first
        if (cache()->has($cacheKey)) {
            return response()->json(cache()->get($cacheKey));
        }

        // Optimized query - langsung ambil komponen yang valid untuk size tertentu
        $components = BouquetComponent::where('bouquet_id', $bouquetId)
            ->where('size_id', $sizeId)
            ->whereHas('product') // Hanya komponen dengan produk yang masih ada
            ->with(['product' => function($query) {
                $query->select('id', 'name', 'category_id', 'base_unit', 'current_stock', 'price');
            }, 'product.category' => function($query) {
                $query->select('id', 'name');
            }])
            ->select('id', 'bouquet_id', 'size_id', 'product_id', 'quantity')
            ->get();

        // Format data untuk response
        $formattedComponents = $components->map(function ($component) {
            return [
                'id' => $component->id,
                'product_id' => $component->product_id,
                'product_name' => $component->product->name,
                'product_category' => $component->product->category->name ?? 'Tanpa Kategori',
                'quantity' => $component->quantity,
                'unit' => $component->product->base_unit ?? 'pcs',
                'current_stock' => $component->product->current_stock,
                'price' => $component->product->price,
                'total_price' => $component->quantity * $component->product->price
            ];
        });

        $response = [
            'success' => true,
            'components' => $formattedComponents,
            'total_components' => $formattedComponents->count()
        ];

        // Cache the response for 5 minutes
        cache()->put($cacheKey, $response, 300);

        return response()->json($response);
    }
}
