<?php

namespace App\Http\Controllers;

use App\Models\Bouquet;
use App\Models\BouquetSize;
use App\Models\BouquetComponent;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BouquetComponentController extends Controller
{
    // Mass edit/manage komponen untuk bouquet dan size tertentu
    // API: Ambil produk berdasarkan kategori
    public function getProductsByCategory($categoryId)
    {
        $products = \App\Models\Product::where('category_id', $categoryId)->orderBy('name')->get();
        return response()->json($products);
    }
    public function manage($bouquetId, $sizeId)
    {
        $bouquet = Bouquet::findOrFail($bouquetId);
        $size = BouquetSize::findOrFail($sizeId);
        $components = BouquetComponent::where('bouquet_id', $bouquetId)
            ->where('size_id', $sizeId)
            ->with('product')
            ->get()
            ->filter(function($component) {
                return $component->product !== null; // Filter out components with deleted products
            });
        $products = Product::orderBy('name')->get();
        return view('bouquet-components.manage', compact('bouquet', 'size', 'components', 'products'));
    }
    public function index()
    {
        // Ambil unique bouquets terlebih dahulu
        $bouquets = Bouquet::with(['category'])->get();
        $bouquetGroups = [];
        $categories = [];

        foreach ($bouquets as $bouquet) {
            $categoryName = $bouquet->category->name ?? 'Uncategorized';
            
            // Simpan kategori unik
            if (!in_array($categoryName, $categories)) {
                $categories[] = $categoryName;
            }

            // Ambil semua komponen dan size untuk bouquet ini, filter out komponen dengan produk yang sudah dihapus
            $components = BouquetComponent::where('bouquet_id', $bouquet->id)
                ->with(['size', 'product'])
                ->get()
                ->filter(function($component) {
                    return $component->product !== null; // Filter out components with deleted products
                })
                ->groupBy('size_id');

            $sizes = [];
            foreach ($components as $sizeId => $sizeComponents) {
                if ($sizeComponents->isNotEmpty()) {
                    $sizes[$sizeId] = [
                        'size' => $sizeComponents->first()->size,
                        'components' => $sizeComponents
                    ];
                }
            }

            // Simpan ke group
            $bouquetGroups[$bouquet->id] = [
                'bouquet' => $bouquet,
                'sizes' => $sizes
            ];
        }

        // Sort categories dan tambah 'All'
        sort($categories);
        array_unshift($categories, 'All');

        return view('bouquet-components.index', compact('bouquetGroups', 'categories'));
    }

    public function create()
    {
        $bouquets = Bouquet::orderBy('name')->get();
        $sizes = BouquetSize::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('bouquet-components.create', compact('bouquets', 'sizes', 'products', 'categories'));
    }

    public function store(Request $request)
    {
        // Jika komponen dikirim sebagai string JSON, ubah ke array
        if (is_string($request->components)) {
            $request->merge(['components' => json_decode($request->components, true)]);
        }

        $validated = $request->validate([
            'bouquet_id' => 'required|exists:bouquets,id',
            'size_id' => 'required|exists:bouquet_sizes,id',
            'components' => 'required|array|min:1',
            'components.*.product_id' => [
                'required',
                'exists:products,id',
            ],
            'components.*.quantity' => 'required|integer|min:1',
        ], [
            'components.*.product_id.required' => 'Produk harus dipilih.',
            'components.*.product_id.exists' => 'Produk tidak valid.',
            'components.*.quantity.required' => 'Jumlah harus diisi.',
        ]);

        // Cek duplikasi produk untuk kombinasi bouquet_id, size_id
        $usedProducts = [];
        foreach ($validated['components'] as $comp) {
            $key = $validated['bouquet_id'].'-'.$validated['size_id'].'-'.$comp['product_id'];
            if (in_array($key, $usedProducts)) {
                return back()->withErrors(['components' => 'Produk tidak boleh sama dalam satu ukuran!'])->withInput();
            }
            $usedProducts[] = $key;
            // Cek di database
            $exists = BouquetComponent::where('bouquet_id', $validated['bouquet_id'])
                ->where('size_id', $validated['size_id'])
                ->where('product_id', $comp['product_id'])
                ->exists();
            if ($exists) {
                return back()->withErrors(['components' => 'Produk sudah ada dalam komponen buket untuk ukuran yang dipilih!'])->withInput();
            }
        }

        foreach ($validated['components'] as $comp) {
            BouquetComponent::create([
                'bouquet_id' => $validated['bouquet_id'],
                'size_id' => $validated['size_id'],
                'product_id' => $comp['product_id'],
                'quantity' => $comp['quantity'],
            ]);
        }

        return redirect()->route('bouquet-components.index')
            ->with('success', 'Komponen buket berhasil ditambahkan.');
    }

    public function show(BouquetComponent $bouquetComponent)
    {
        $bouquetComponent->load(['bouquet', 'size', 'product']);
        return view('bouquet-components.show', compact('bouquetComponent'));
    }

    public function edit(BouquetComponent $bouquetComponent)
    {
        $bouquets = Bouquet::orderBy('name')->get();
        $sizes = BouquetSize::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        
        return view('bouquet-components.edit', compact('bouquetComponent', 'bouquets', 'sizes', 'products'));
    }

    public function update(Request $request, BouquetComponent $bouquetComponent)
    {
        $validated = $request->validate([
            'bouquet_id' => 'required|exists:bouquets,id',
            'size_id' => 'required|exists:bouquet_sizes,id',
            'product_id' => [
                'required',
                'exists:products,id',
                Rule::unique('bouquet_components')->where(function ($query) use ($request, $bouquetComponent) {
                    return $query->where('bouquet_id', $request->bouquet_id)
                                ->where('size_id', $request->size_id)
                                ->where('product_id', $request->product_id)
                                ->where('id', '!=', $bouquetComponent->id);
                }),
            ],
            'quantity' => 'required|integer|min:1',
        ], [
            'product_id.unique' => 'Produk ini sudah ada dalam komponen buket untuk ukuran yang dipilih.',
        ]);

        $bouquetComponent->update($validated);

        return redirect()->route('bouquet-components.index')
            ->with('success', 'Komponen buket berhasil diperbarui.');
    }

    public function destroy(BouquetComponent $bouquetComponent)
    {
        $bouquetComponent->delete();

        return redirect()->route('bouquet-components.index')
            ->with('success', 'Komponen buket berhasil dihapus.');
    }

    // Method tambahan untuk mendapatkan komponen berdasarkan bouquet dan size
    public function getComponentsByBouquetAndSize(Request $request)
    {
        $components = BouquetComponent::where('bouquet_id', $request->bouquet_id)
            ->where('size_id', $request->size_id)
            ->with(['product'])
            ->get();

        return response()->json($components);
    }
}
