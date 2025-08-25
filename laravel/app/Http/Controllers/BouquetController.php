<?php

namespace App\Http\Controllers;

use App\Models\Bouquet;
use App\Models\BouquetCategory;
use App\Models\BouquetSize;
use App\Models\BouquetPrice;
use App\Models\BouquetComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BouquetController extends Controller
{
    public function index()
    {
        $bouquets = Bouquet::with(['category', 'sizes', 'components.product'])
            ->orderBy('category_id')
            ->get();

        $categories = \App\Models\BouquetCategory::orderBy('name')->get();

        return view('bouquets.index', compact('bouquets', 'categories'));
    }

    public function create()
    {
        $categories = BouquetCategory::orderBy('name')->get();
        $sizes = BouquetSize::orderBy('name')->get();
        return view('bouquets.create', compact('categories', 'sizes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:bouquet_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'prices' => 'required|array',
            'prices.*' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('bouquets', 'public');
                $validated['image'] = $imagePath;
            }

            // Create bouquet
            $bouquet = Bouquet::create([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'],
                'image' => $validated['image'] ?? null,
            ]);

            // Create prices for each size
            foreach ($validated['prices'] as $sizeId => $price) {
                if ($price > 0) {
                    BouquetPrice::create([
                        'bouquet_id' => $bouquet->id,
                        'size_id' => $sizeId,
                        'price' => $price
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('bouquets.index')
                ->with('success', 'Template bouquet berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat membuat template bouquet.');
        }
    }

    public function show(Bouquet $bouquet)
    {
        $bouquet->load(['category', 'sizes', 'components.product']);

        // Group components by size
        $componentsBySize = $bouquet->components
            ->groupBy('size_id')
            ->map(function ($components) {
                return [
                    'size' => $components->first()->size,
                    'components' => $components,
                    'total_cost' => $components->sum(function ($component) {
                        return $component->quantity * $component->product->price;
                    }),
                    'price' => $components->first()->size->prices
                        ->where('bouquet_id', $components->first()->bouquet_id)
                        ->first()?->price ?? 0
                ];
            });

        return view('bouquets.show', compact('bouquet', 'componentsBySize'));
    }

    public function edit(Bouquet $bouquet)
    {
        $categories = BouquetCategory::orderBy('name')->get();
        $sizes = BouquetSize::orderBy('name')->get();
        $bouquet->load(['prices']);

        return view('bouquets.edit', compact('bouquet', 'categories', 'sizes'));
    }

    public function update(Request $request, Bouquet $bouquet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:bouquet_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sizes' => 'required|array',
            'sizes.*' => 'exists:bouquet_sizes,id',
            'prices' => 'required|array',
            'prices.*' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('bouquets', 'public');
                $validated['image'] = $imagePath;
            }

            // Update bouquet
            $bouquet->update([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'],
                'image' => $validated['image'] ?? $bouquet->image,
            ]);

            // Update prices
            $bouquet->prices()->delete(); // Remove old prices
            foreach ($validated['sizes'] as $index => $sizeId) {
                BouquetPrice::create([
                    'bouquet_id' => $bouquet->id,
                    'size_id' => $sizeId,
                    'price' => $validated['prices'][$index]
                ]);
            }

            DB::commit();
            return redirect()->route('bouquets.index')
                ->with('success', 'Template bouquet berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui template bouquet.');
        }
    }

    public function destroy(Bouquet $bouquet)
    {
        try {
            DB::beginTransaction();

            // 1. Hapus semua harga (price) terkait
            $bouquet->prices()->delete();

            // 2. Hapus semua komponen terkait
            $bouquet->components()->delete();

            // 3. Hapus file gambar jika ada
            if ($bouquet->image && Storage::disk('public')->exists($bouquet->image)) {
                Storage::disk('public')->delete($bouquet->image);
            }

            // 4. Hapus bouquet
            $bouquet->delete();

            DB::commit();

            return redirect()->route('bouquets.index')
                ->with('success', 'Buket berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menghapus bouquet: ' . $e->getMessage());

            return back()
                ->with('error', 'Terjadi kesalahan saat menghapus buket. ' . $e->getMessage());
        }
    }

    // API method untuk mendapatkan detail bouquet
    public function getDetails(Bouquet $bouquet)
    {
        $bouquet->load(['sizes.components.product', 'prices']);
        return response()->json($bouquet);
    }

    // Method untuk cek ketersediaan komponen
    public function checkAvailability(Bouquet $bouquet, BouquetSize $size)
    {
        $components = $bouquet->getComponentsForSize($size->id);
        $availability = true;
        $missingComponents = [];

        foreach ($components as $component) {
            if ($component->product->stock < $component->quantity) {
                $availability = false;
                $missingComponents[] = [
                    'product' => $component->product->name,
                    'required' => $component->quantity,
                    'available' => $component->product->stock
                ];
            }
        }

        return response()->json([
            'available' => $availability,
            'missing_components' => $missingComponents
        ]);
    }
}
