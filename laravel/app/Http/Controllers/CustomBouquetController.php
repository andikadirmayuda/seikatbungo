<?php

namespace App\Http\Controllers;

use App\Models\CustomBouquet;
use App\Models\CustomBouquetItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPrice;
use App\Models\Ribbon;
use App\Enums\RibbonColor;
use App\Enums\CustomPriceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CustomBouquetController extends Controller
{
    /**
     * Calculate total price for custom bouquet items
     */
    private function calculateTotalPrice($items)
    {
        $total = 0;
        foreach ($items as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                $price = $product->prices()
                    ->where('type', $item['price_type'])
                    ->first();
                if ($price) {
                    $total += $price->price * $item['quantity'];
                }
            }
        }
        return $total;
    }
    /**
     * Update ribbon color for custom bouquet
     */
    public function updateRibbon(Request $request, $id)
    {
        try {
            Log::info('Ribbon update request:', $request->all());

            $validated = $request->validate([
                'ribbon_color' => ['required', 'string', 'in:' . implode(',', RibbonColor::values())]
            ]);

            $customBouquet = CustomBouquet::findOrFail($id);

            Log::info('Updating ribbon color for bouquet:', [
                'bouquet_id' => $customBouquet->id,
                'old_color' => $customBouquet->ribbon_color,
                'new_color' => $validated['ribbon_color']
            ]);

            $customBouquet->ribbon_color = $validated['ribbon_color'];
            $customBouquet->save();

            return response()->json([
                'success' => true,
                'message' => 'Warna pita berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui warna pita'
            ], 500);
        }
    }
    /**
     * Show the custom bouquet builder
     */
    public function create()
    {
        // Get products that have valid custom prices
        $products = Product::with(['category', 'prices'])
            ->where('is_active', true)
            ->where('current_stock', '>', 0)
            ->whereHas('prices', function ($query) {
                $query->whereIn('type', CustomPriceType::values());
            })
            ->get()
            ->filter(function ($product) {
                return $product->hasValidCustomPrices();
            })
            ->values()
            ->sortBy('name');

        // Get all categories for filtering
        $categories = Category::orderBy('name')->get();

        // Create a new draft custom bouquet for this session
        $customBouquet = CustomBouquet::create([
            'name' => 'Custom Bouquet Draft',
            'status' => 'draft',
            'total_price' => 0
        ]);

        return view('custom-bouquet.create', compact('products', 'categories', 'customBouquet'));
    }

    /**
     * Get product details for AJAX requests
     */
    public function getProductDetails(Product $product)
    {
        $product->load(['prices', 'category']);

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->getAttribute('id'),
                'name' => $product->getAttribute('name'),
                'description' => $product->getAttribute('description'),
                'current_stock' => $product->getAttribute('current_stock'),
                'base_unit' => $product->getAttribute('base_unit'),
                'category' => $product->category ? $product->category->getAttribute('name') : 'Uncategorized',
                'prices' => $product->prices->map(function ($price) {
                    return [
                        'type' => $price->getAttribute('type'),
                        'price' => $price->getAttribute('price'),
                        'unit_equivalent' => $price->getAttribute('unit_equivalent'),
                        'is_default' => $price->getAttribute('is_default'),
                        'display_name' => $this->getPriceTypeDisplayName($price->getAttribute('type'))
                    ];
                })
            ]
        ]);
    }

    /**
     * Add item to custom bouquet
     */
    /**
     * Add custom bouquet to cart
     */
    public function addToCart(Request $request, CustomBouquet $customBouquet)
    {
        $validated = $request->validate([
            'ribbon_color' => 'required|string|in:' . implode(',', RibbonColor::values()),
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_type' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            // Update ribbon color
            $customBouquet->update([
                'ribbon_color' => $validated['ribbon_color'],
                'status' => 'in_cart'
            ]);

            // Add to cart session
            $cart = session()->get('cart', []);

            // Process items with complete product information
            $processedItems = collect($validated['items'])->map(function ($item) {
                $product = Product::find($item['id']);
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price_type' => $item['price_type'],
                    'unit' => $product->base_unit,
                    'product_type' => 'custom_item'
                ];
            })->toArray();

            $cart['custom_bouquet_' . $customBouquet->id] = [
                'type' => 'custom_bouquet',
                'id' => $customBouquet->id,
                'name' => 'Custom Bouquet',
                'items' => $processedItems,
                'qty' => 1,
                'price' => $this->calculateTotalPrice($validated['items']),
                'price_type' => 'custom',
                'ribbon_color' => $validated['ribbon_color'],
                'reference_image' => $customBouquet->reference_image,
                'created_at' => now()->timestamp
            ];
            session()->put('cart', $cart);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Custom bouquet added to cart successfully',
                'cartCount' => count($cart)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add custom bouquet to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'custom_bouquet_id' => 'required|exists:custom_bouquets,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price_type' => 'required|string|in:custom_ikat,custom_tangkai,custom_khusus'
        ]);

        DB::beginTransaction();
        try {
            $customBouquet = CustomBouquet::find($validated['custom_bouquet_id']);
            $product = Product::find($validated['product_id']);

            // Cari harga berdasarkan price_type yang dipilih
            $productPrice = ProductPrice::where('product_id', $validated['product_id'])
                ->where('type', $validated['price_type'])
                ->first();

            // Jika price_type yang dipilih tidak tersedia
            if (!$productPrice) {
                // Cari harga custom yang tersedia untuk produk ini
                $availablePrices = ProductPrice::where('product_id', $validated['product_id'])
                    ->whereIn('type', CustomPriceType::values())
                    ->get();

                if ($availablePrices->isEmpty()) {
                    Log::warning('No custom prices found for product', [
                        'product_id' => $validated['product_id'],
                        'product_name' => $product->name
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => "Produk {$product->name} tidak memiliki konfigurasi harga untuk Custom Bouquet"
                    ]);
                }

                // Beritahu user price_type apa saja yang tersedia
                $availableTypes = $availablePrices->pluck('type')->toArray();
                return response()->json([
                    'success' => false,
                    'message' => "Harga {$validated['price_type']} tidak tersedia untuk {$product->name}. Silakan pilih dari: " . implode(', ', $availableTypes)
                ]);
            }

            Log::info('Found price for product', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price_type' => $productPrice->type,
                'price' => $productPrice->price
            ]);

            if (!$productPrice) {
                return response()->json([
                    'success' => false,
                    'message' => "Konfigurasi harga tidak valid untuk {$product->name}"
                ]);
            }

            // Gunakan price_type dari harga yang ditemukan
            $priceType = $productPrice->type;

            // Calculate required stock based on price type
            $requiredStock = $this->calculateRequiredStock($validated['quantity'], $productPrice);

            // Check stock availability
            if ($product->getAttribute('current_stock') < $requiredStock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Available: ' . $product->getAttribute('current_stock') . ' ' . $product->getAttribute('base_unit')
                ]);
            }

            // Check if item already exists in custom bouquet
            $existingItem = CustomBouquetItem::where('custom_bouquet_id', $validated['custom_bouquet_id'])
                ->where('product_id', $validated['product_id'])
                ->where('price_type', $priceType)
                ->first();

            if ($existingItem) {
                // Update existing item
                $existingItem->quantity += $validated['quantity'];
                $existingItem->subtotal = $existingItem->calculateSubtotal();
                $existingItem->save();
                $item = $existingItem;
            } else {
                // Create new item
                $item = CustomBouquetItem::create([
                    'custom_bouquet_id' => $validated['custom_bouquet_id'],
                    'product_id' => $validated['product_id'],
                    'price_type' => $priceType, // Gunakan price_type dari harga yang ditemukan
                    'quantity' => $validated['quantity'],
                    'unit_price' => $productPrice->getAttribute('price'),
                ]);
            }

            // Update custom bouquet total price
            $customBouquet->total_price = $customBouquet->calculateTotalPrice();
            $customBouquet->save();

            DB::commit();

            Log::info('Custom bouquet item added:', [
                'custom_bouquet_id' => $validated['custom_bouquet_id'],
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price_type' => $priceType,
                'subtotal' => $item->subtotal
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil Di Tambahkan.',
                'item' => [
                    'id' => $item->getAttribute('id'),
                    'product_name' => $product->getAttribute('name'),
                    'quantity' => $item->getAttribute('quantity'),
                    'price_type' => $item->getAttribute('price_type'),
                    'price_type_display' => $item->price_type_display,
                    'unit_price' => $item->getAttribute('unit_price'),
                    'subtotal' => $item->getAttribute('subtotal'),
                ],
                'total_price' => $customBouquet->getAttribute('total_price')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding item to custom bouquet: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error adding item to custom bouquet: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove item from custom bouquet
     */
    public function removeItem(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:custom_bouquet_items,id',
        ]);

        DB::beginTransaction();
        try {
            $item = CustomBouquetItem::find($validated['item_id']);
            $customBouquet = $item->customBouquet;

            $item->delete();

            // Update custom bouquet total price
            $customBouquet->total_price = $customBouquet->calculateTotalPrice();
            $customBouquet->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil dihapus.',
                'total_price' => $customBouquet->getAttribute('total_price')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error removing item from custom bouquet: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error removing item from custom bouquet.'
            ]);
        }
    }

    /**
     * Update item quantity in custom bouquet
     */
    public function updateItem(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:custom_bouquet_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $item = CustomBouquetItem::with(['product', 'customBouquet'])->find($validated['item_id']);

            // Get the price info for stock calculation
            $productPrice = ProductPrice::where('product_id', $item->product_id)
                ->where('type', $item->price_type)
                ->first();

            // Calculate required stock
            $requiredStock = $this->calculateRequiredStock($validated['quantity'], $productPrice);

            // Check stock availability
            if ($item->product->getAttribute('current_stock') < $requiredStock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Available: ' . $item->product->getAttribute('current_stock') . ' ' . $item->product->getAttribute('base_unit')
                ]);
            }

            $item->quantity = $validated['quantity'];
            $item->save(); // This will trigger the subtotal calculation

            // Update custom bouquet total price
            $item->customBouquet->total_price = $item->customBouquet->calculateTotalPrice();
            $item->customBouquet->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item quantity updated successfully.',
                'item' => [
                    'id' => $item->getAttribute('id'),
                    'quantity' => $item->getAttribute('quantity'),
                    'subtotal' => $item->getAttribute('subtotal'),
                ],
                'total_price' => $item->customBouquet->getAttribute('total_price')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating item quantity: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating item quantity.'
            ]);
        }
    }

    /**
     * Upload reference image for custom bouquet
     */
    public function uploadReference(Request $request)
    {
        $validated = $request->validate([
            'custom_bouquet_id' => 'required|exists:custom_bouquets,id',
            'reference_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $customBouquet = CustomBouquet::find($validated['custom_bouquet_id']);

            // Delete old reference image if exists
            if ($customBouquet->getAttribute('reference_image') && Storage::disk('public')->exists($customBouquet->getAttribute('reference_image'))) {
                Storage::disk('public')->delete($customBouquet->getAttribute('reference_image'));
            }

            // Store new image
            $imagePath = $request->file('reference_image')->store('custom-bouquets', 'public');

            $customBouquet->reference_image = $imagePath;
            $customBouquet->save();

            return response()->json([
                'success' => true,
                'message' => 'Reference image uploaded successfully.',
                'image_path' => $imagePath,
                'image_url' => Storage::url($imagePath)
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading reference image: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error uploading reference image.'
            ]);
        }
    }

    /**
     * Get custom bouquet details
     */
    public function getDetails(CustomBouquet $customBouquet)
    {
        $customBouquet->load(['items.product']);

        return response()->json([
            'success' => true,
            'custom_bouquet' => [
                'id' => $customBouquet->getAttribute('id'),
                'name' => $customBouquet->getAttribute('name'),
                'total_price' => $customBouquet->getAttribute('total_price'),
                'reference_image' => $customBouquet->getAttribute('reference_image'),
                'reference_image_url' => $customBouquet->getAttribute('reference_image') ? Storage::url($customBouquet->getAttribute('reference_image')) : null,
                'status' => $customBouquet->getAttribute('status'),
                'items' => $customBouquet->items->map(function ($item) {
                    return [
                        'id' => $item->getAttribute('id'),
                        'product_id' => $item->getAttribute('product_id'),
                        'product_name' => $item->product->getAttribute('name'),
                        'quantity' => $item->getAttribute('quantity'),
                        'price_type' => $item->getAttribute('price_type'),
                        'price_type_display' => $item->price_type_display,
                        'unit_price' => $item->getAttribute('unit_price'),
                        'subtotal' => $item->getAttribute('subtotal'),
                    ];
                }),
                'components_summary' => $customBouquet->getComponentsSummary()
            ]
        ]);
    }

    /**
     * Helper method to get price type display name
     */
    private function getPriceTypeDisplayName($type)
    {
        $labels = [
            'per_tangkai' => 'Per Tangkai',
            'ikat_5' => 'Ikat 5',
            'ikat_10' => 'Ikat 10',
            'ikat_20' => 'Ikat 20',
            'reseller' => 'Reseller',
            'normal' => 'Normal',
            'promo' => 'Promo',
            'custom_ikat' => 'Custom Ikat',
            'custom_tangkai' => 'Custom Tangkai',
            'custom_khusus' => 'Custom Khusus'
        ];
        return $labels[$type] ?? ucfirst(str_replace('_', ' ', $type));
        // return $names[$type] ?? $type;
    }

    /**
     * Helper method to calculate required stock based on price type
     */
    private function calculateRequiredStock($quantity, $productPrice)
    {
        return $quantity * $productPrice->getAttribute('unit_equivalent');
    }

    public function finalize($id)
    {
        try {
            Log::info('Attempting to finalize custom bouquet with ID: ' . $id);

            // Find the custom bouquet
            $customBouquet = CustomBouquet::find($id);
            if (!$customBouquet) {
                Log::error('Custom bouquet not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Custom bouquet tidak ditemukan.'
                ], 404);
            }

            // Validate that ribbon color is selected
            if (!$customBouquet->ribbon_color) {
                Log::warning('Ribbon color not selected for bouquet ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan pilih warna pita terlebih dahulu'
                ], 400);
            }

            Log::info('Found custom bouquet ID: ' . $customBouquet->getAttribute('id'));

            // Check if custom bouquet has items
            $itemsCount = $customBouquet->items()->count();
            Log::info('Custom bouquet has ' . $itemsCount . ' items');

            if ($itemsCount === 0) {
                Log::warning('Custom bouquet has no items');
                return response()->json([
                    'success' => false,
                    'message' => 'Custom bouquet tidak memiliki item. Tambahkan beberapa bunga terlebih dahulu.'
                ], 400);
            }

            // Check if ribbon color is selected
            if (!$customBouquet->ribbon_color) {
                Log::warning('Custom bouquet has no ribbon color selected');
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan pilih warna pita terlebih dahulu.'
                ], 400);
            }

            // Update status to finalized
            Log::info('Updating custom bouquet status to finalized');
            $customBouquet->update(['status' => 'finalized']);

            Log::info('Custom bouquet finalized successfully');
            return response()->json([
                'success' => true,
                'message' => 'Custom bouquet berhasil diselesaikan.',
                'custom_bouquet' => $customBouquet->load(['items.product'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error finalizing custom bouquet: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan custom bouquet. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all items from custom bouquet
     */
    public function clear(Request $request)
    {
        try {
            $customBouquetId = $request->input('custom_bouquet_id');

            if (!$customBouquetId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Custom bouquet ID tidak ditemukan.'
                ], 400);
            }

            $customBouquet = CustomBouquet::find($customBouquetId);

            if (!$customBouquet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Custom bouquet tidak ditemukan.'
                ], 404);
            }

            DB::beginTransaction();

            // Delete all items from this custom bouquet
            CustomBouquetItem::where('custom_bouquet_id', $customBouquetId)->delete();

            // Reset total price
            $customBouquet->update([
                'total_price' => 0,
                'reference_image' => null
            ]);

            // Remove reference image file if exists
            if ($customBouquet->getAttribute('reference_image') && Storage::disk('public')->exists($customBouquet->getAttribute('reference_image'))) {
                Storage::disk('public')->delete($customBouquet->getAttribute('reference_image'));
            }

            DB::commit();

            Log::info("Custom bouquet {$customBouquetId} cleared successfully");

            return response()->json([
                'success' => true,
                'message' => 'Builder berhasil dikosongkan.',
                'total_price' => 0
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error clearing custom bouquet: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengosongkan builder. Silakan coba lagi.'
            ], 500);
        }
    }
}
