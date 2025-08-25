<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    public function index(Request $request)
    {
        // Ambil log aktivitas stok terbaru (inventory log) beserta relasi produk
        $logs = \App\Models\InventoryLog::with('product.category')
            ->latest('id')
            ->paginate(20);

        // Filter produk
        $query = Product::with('category');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                  ->orWhere('name', 'like', "%$search%");
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }
        $products = $query->get();

        // Kirim data kategori beserta produk dan stok ke view untuk filter frontend
        $categoriesWithProducts = \App\Models\Category::with(['products' => function($q) {
            $q->select('id','category_id','name','current_stock','base_unit');
        }])->get();

        return view('inventory.index', [
            'logs' => $logs,
            'products' => $products,
            'categoriesWithProducts' => $categoriesWithProducts,
            'filter_search' => $request->input('search'),
            'filter_category' => $request->input('category_id'),
        ]);
    }

    public function history(Product $product)
    {
        $logs = $this->inventoryService->getProductHistory($product);
        
        return view('inventory.history', compact('product', 'logs'));
    }



    /**
     * Tampilkan form penyesuaian stok.
     * Jika $product null, tampilkan form global (dropdown produk).
     */
    public function adjustForm(Product $product = null)
    {
        if ($product) {
            return view('inventory.adjust', compact('product'));
        }
        $products = Product::orderBy('name')->get();
        return view('inventory.adjust', [
            'product' => null,
            'products' => $products,
        ]);
    }

    public function adjust(Request $request, Product $product = null)
    {
        // Jika form global, ambil product_id dari request
        if (!$product && $request->has('product_id')) {
            $product = Product::find($request->product_id);
            if (!$product) {
                return back()->withErrors(['product_id' => 'Produk tidak ditemukan.'])->withInput();
            }
        }
        if (!$product) {
            return back()->withErrors(['product_id' => 'Produk wajib dipilih.'])->withInput();
        }

        $request->validate([
            'quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        $this->inventoryService->processStockAdjustment(
            product: $product,
            newQuantity: $request->quantity,
            notes: $request->notes
        );

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Stock adjusted successfully');
    }
}
