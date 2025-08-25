<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductRequest;

use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $products = Product::with('category')
            ->search($request->search)
            ->filterByCategory($request->category)
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $priceTypes = ProductRequest::getPriceTypes();
        $defaultUnitEquivalents = collect($priceTypes)->mapWithKeys(function ($type) {
            return [$type => ProductRequest::getDefaultUnitEquivalent($type)];
        });

        return view('products.form', compact('categories', 'priceTypes', 'defaultUnitEquivalents'));
    }

    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('prices');
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }
            $product = Product::create($data);
            // Process and save prices
            $this->processPrices($product, $request->input('prices', []));

            DB::commit();
            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'prices']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $priceTypes = ProductRequest::getPriceTypes();
        $defaultUnitEquivalents = collect($priceTypes)->mapWithKeys(function ($type) {
            return [$type => ProductRequest::getDefaultUnitEquivalent($type)];
        });

        // Prepare existing prices
        $existingPrices = $product->prices->keyBy('type');

        return view('products.form', compact('product', 'categories', 'priceTypes', 'defaultUnitEquivalents', 'existingPrices'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('prices');
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('products', 'public');
            }
            $product->update($data);
            // Process and save prices
            $this->processPrices($product, $request->input('prices', []));

            DB::commit();
            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete(); // Using soft delete
            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process and save product prices
     *
     * @param Product $product
     * @param array $prices
     * @return void
     */
    private function processPrices(Product $product, array $prices)
    {
        // Delete existing prices
        $product->prices()->delete();

        // Filter only prices that have values
        $validPrices = collect($prices)->filter(function ($price) {
            return !empty($price['price']);
        })->values();

        if ($validPrices->isEmpty()) {
            return;
        }

        // Find the price marked as default
        $defaultPrice = $validPrices->first(function ($price) {
            return isset($price['is_default']) && $price['is_default'];
        });

        // If no default price is set, use the first price
        if (!$defaultPrice) {
            $validPrices->first()['is_default'] = true;
        }

        // Create all prices
        foreach ($validPrices as $priceData) {
            $product->prices()->create([
                'type' => $priceData['type'],
                'price' => $priceData['price'],
                'unit_equivalent' => $priceData['unit_equivalent'] ?? ProductRequest::getDefaultUnitEquivalent($priceData['type']),
                'is_default' => isset($priceData['is_default']) && $priceData['is_default'],
            ]);
        }
    }

    /**
     * API: Get current stock for a product (for AJAX stock info)
     */
    public function apiStock(Product $product)
    {
        return response()->json([
            'current_stock' => $product->current_stock,
            'base_unit' => $product->base_unit,
        ]);
    }
}
