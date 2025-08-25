<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class PublicCartController extends Controller
{
    public function index()
    {
        return view('public.cart');
    }

    // Tambahkan method sesuai kebutuhan, contoh:
    public function addToCart(Request $request)
    {
        // Logika untuk menambah produk ke keranjang
        // return response atau redirect sesuai kebutuhan
        return response()->json(['message' => 'Produk berhasil ditambahkan ke keranjang.']);
    }

    public function getCart()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $items = [];
        foreach ($cart as $cartKey => $item) {
            $total += $item['price'] * $item['qty'];

            // Format image URL dengan benar
            $imageUrl = null;
            if (isset($item['image']) && $item['image']) {
                if (isset($item['type']) && $item['type'] === 'custom_bouquet') {
                    // For custom bouquet, image is already stored with path
                    $imageUrl = asset('storage/' . $item['image']);
                } else {
                    $imageUrl = asset('storage/' . $item['image']);
                }
            }

            // Format nama produk dengan price_type
            $productName = $item['name'];
            if (isset($item['price_type']) && $item['price_type']) {
                $priceTypeLabel = $this->getPriceTypeLabel($item['price_type']);
                if ($item['price_type'] !== 'Custom') { // Don't add Custom again for custom bouquet
                    $productName .= ' (' . $priceTypeLabel . ')';
                }
            }

            $formattedItem = [
                'id' => $cartKey, // Gunakan cartKey sebagai ID unik
                'product_id' => $item['id'], // ID produk asli
                'name' => $productName,
                'price' => $item['price'],
                'quantity' => $item['qty'],
                'price_type' => $item['price_type'] ?? null,
                'type' => $item['type'] ?? 'product', // Tambahkan type identifier  
                'image' => $imageUrl
            ];

            // Add custom bouquet specific data
            if (isset($item['type']) && $item['type'] === 'custom_bouquet') {
                $formattedItem['components_summary'] = $item['components_summary'] ?? null;
                $formattedItem['custom_bouquet_id'] = $item['custom_bouquet_id'] ?? null;
                $formattedItem['ribbon_color'] = $item['ribbon_color'] ?? null;
            }

            // Add greeting card if exists
            if (isset($item['greeting_card']) && !empty($item['greeting_card'])) {
                $formattedItem['greeting_card'] = $item['greeting_card'];
            }

            $items[] = $formattedItem;
        }
        return response()->json([
            'items' => $items,
            'total' => $total,
            'success' => true
        ]);
    }

    private function getPriceTypeLabel($priceType)
    {
        $labels = [
            'tangkai' => 'Per Tangkai',
            'ikat5' => 'Ikat 5',
            'reseller' => 'Reseller',
            'promo' => 'Promo',
            'custom_ikat' => 'Custom Ikat',
            'custom_tangkai' => 'Custom Tangkai',
            'custom_khusus' => 'Custom Khusus'
        ];

        return $labels[$priceType] ?? ucfirst($priceType);
    }
    public function add(Request $request)
    {


        $productId = $request->input('product_id');
        $qty = $request->input('quantity', 1);
        $priceType = $request->input('price_type');

        $productModel = Product::find($productId);
        if (!$productModel || !($productModel instanceof Product)) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.'
            ], 404);
        }

        // Ambil harga berdasarkan price_type jika ada, jika tidak fallback ke default
        $priceQuery = $productModel->prices();
        if ($priceType) {
            $priceModel = $priceQuery->where('type', $priceType)->first();
        } else {
            $priceModel = $priceQuery->where('is_default', true)->first();
        }
        $price = ($priceModel && isset($priceModel->price)) ? $priceModel->price : 0;
        $selectedPriceType = $priceModel ? $priceModel->type : ($priceType ?? null);

        $product = [
            'id' => $productModel->id ?? null,
            'name' => $productModel->name ?? '',
            'price' => $price,
            'qty' => $qty,
            'price_type' => $selectedPriceType,
            'type' => 'product', // Identifikasi sebagai produk biasa
            'image' => $productModel->image ?? null,
        ];

        $cart = session()->get('cart', []);

        // Gunakan kombinasi product_id dan price_type sebagai key unik
        $cartKey = $product['id'] . '_' . ($selectedPriceType ?? 'default');

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $product['qty'];
        } else {
            $cart[$cartKey] = $product;
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang.',
            'cart' => $cart
        ]);
    }

    public function updateQuantity(Request $request, $cartKey)
    {
        $cart = session()->get('cart', []);
        $quantityChange = $request->input('quantity_change', 0);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $quantityChange;

            // Hapus item jika quantity <= 0
            if ($cart[$cartKey]['qty'] <= 0) {
                unset($cart[$cartKey]);
            }

            session(['cart' => $cart]);

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui.',
                'cart' => $cart
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan di keranjang.'
        ], 404);
    }

    public function remove(Request $request, $cartKey)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session(['cart' => $cart]);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang.',
                'cart' => $cart
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan di keranjang.'
        ], 404);
    }

    public function clear(Request $request)
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan.'
        ]);
    }

    public function addBouquet(Request $request)
    {
        $bouquetId = $request->input('bouquet_id');
        $sizeId = $request->input('size_id');
        $qty = $request->input('quantity', 1);
        $greetingCard = $request->input('greeting_card', ''); // New: greeting card input

        // Log incoming request for debugging
        Log::info('AddBouquet Request:', [
            'bouquet_id' => $bouquetId,
            'size_id' => $sizeId,
            'quantity' => $qty,
            'greeting_card' => $greetingCard
        ]);

        // Ambil data bouquet
        $bouquet = \App\Models\Bouquet::with(['prices.size', 'sizes'])->find($bouquetId);
        if (!$bouquet) {
            return response()->json([
                'success' => false,
                'message' => 'Bouquet tidak ditemukan.'
            ], 404);
        }

        // Ambil harga berdasarkan size
        $bouquetPrice = $bouquet->prices()->where('size_id', $sizeId)->first();
        if (!$bouquetPrice) {
            Log::error('Bouquet price not found:', [
                'bouquet_id' => $bouquetId,
                'size_id' => $sizeId,
                'available_prices' => $bouquet->prices()->get()->toArray()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Ukuran bouquet tidak tersedia.'
            ], 404);
        }

        $size = $bouquetPrice->size;
        $price = $bouquetPrice->price;

        $product = [
            'id' => $bouquet->id,
            'name' => $bouquet->name,
            'price' => $price,
            'qty' => $qty,
            'price_type' => $size->name,
            'size_id' => $size->id,
            'image' => $bouquet->image,
            'type' => 'bouquet',
            'greeting_card' => mb_convert_encoding(trim($greetingCard), 'UTF-8', 'UTF-8') // Store greeting card message with proper encoding
        ];

        $cart = session()->get('cart', []);

        // Gunakan kombinasi bouquet_id dan size_id sebagai key unik
        // Jika ada greeting card berbeda, buat entry terpisah
        $cartKey = 'bouquet_' . $bouquet->id . '_' . $size->id;

        // Jika sudah ada item yang sama dan greeting card berbeda, buat key baru
        if (isset($cart[$cartKey])) {
            $existingGreeting = $cart[$cartKey]['greeting_card'] ?? '';
            if ($existingGreeting !== mb_convert_encoding(trim($greetingCard), 'UTF-8', 'UTF-8')) {
                // Create unique key dengan timestamp untuk diferensiasi
                $cartKey .= '_' . time();
            } else {
                // Same item, same greeting - just add quantity
                $cart[$cartKey]['qty'] += $product['qty'];
                session(['cart' => $cart]);

                Log::info('Updated existing cart item:', ['cart_key' => $cartKey, 'new_qty' => $cart[$cartKey]['qty']]);

                return response()->json([
                    'success' => true,
                    'message' => 'Bouquet berhasil ditambahkan ke keranjang.',
                    'cart' => $cart
                ]);
            }
        }

        $cart[$cartKey] = $product;
        session(['cart' => $cart]);

        Log::info('Added new cart item:', ['cart_key' => $cartKey, 'product' => $product]);

        return response()->json([
            'success' => true,
            'message' => 'Bouquet berhasil ditambahkan ke keranjang.',
            'cart' => $cart
        ]);
    }

    public function addCustomBouquet(Request $request)
    {
        $validated = $request->validate([
            'custom_bouquet_id' => 'required|integer|exists:custom_bouquets,id',
            'quantity' => 'integer|min:1',
        ]);

        $customBouquetId = $validated['custom_bouquet_id'];
        $qty = $validated['quantity'] ?? 1;

        // Ambil data custom bouquet beserta items-nya dan warna pita
        $customBouquet = \App\Models\CustomBouquet::with(['items.product'])->find($customBouquetId);

        // Log untuk debugging
        Log::info('Custom Bouquet Data:', [
            'id' => $customBouquet->id,
            'ribbon_color' => $customBouquet->ribbon_color,
            'status' => $customBouquet->status
        ]);
        if (!$customBouquet) {
            return response()->json([
                'success' => false,
                'message' => 'Custom bouquet tidak ditemukan.'
            ], 404);
        }

        // Cek apakah custom bouquet sudah finalized
        if ($customBouquet->status !== 'finalized') {
            return response()->json([
                'success' => false,
                'message' => 'Custom bouquet belum selesai dibuat.'
            ], 400);
        }

        // Hitung total harga
        $totalPrice = $customBouquet->calculateTotalPrice();

        // Ambil komponen-komponen bouquet dan validasi price_type
        $components = $customBouquet->items->map(function ($item) {
            // Validate each component has the correct price type
            $product = Product::with(['prices'])->find($item->product_id);
            if (!$product) {
                throw new \Exception("Product not found: {$item->product_id}");
            }

            // Validate price type exists for this product
            $priceExists = $product->prices()->where('type', $item->price_type)->exists();
            if (!$priceExists) {
                throw new \Exception("Harga {$item->price_type} tidak ditemukan untuk {$product->name}");
            }

            return [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price_type' => $item->price_type,
                'product_name' => $product->name
            ];
        })->toArray();

        // Gunakan nama sederhana tanpa komponen untuk menghindari duplikasi
        $componentsArray = $customBouquet->getComponentsArray();
        $cartName = "Custom Bouquet";

        $ribbon_color = $customBouquet->ribbon_color;
        if (empty($ribbon_color)) {
            Log::warning('Custom Bouquet has no ribbon color selected', [
                'custom_bouquet_id' => $customBouquet->id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Silakan pilih warna pita terlebih dahulu.'
            ], 400);
        }

        $product = [
            'id' => 'custom_bouquet_' . $customBouquet->id,
            'name' => $cartName,
            'price' => $totalPrice,
            'qty' => $qty,
            'price_type' => 'Custom',
            'type' => 'custom_bouquet',
            'custom_bouquet_id' => $customBouquet->id,
            'image' => $customBouquet->reference_image ?? null,
            'components_summary' => $componentsArray,
            'components' => $components, // Tambahkan komponen untuk stok management
            'ribbon_color' => $ribbon_color
        ];

        $cart = session()->get('cart', []);
        $cartKey = 'custom_bouquet_' . $customBouquet->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $product['qty'];
        } else {
            $cart[$cartKey] = $product;
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => 'Custom bouquet berhasil ditambahkan ke keranjang.',
            'cart' => $cart
        ]);
    }
}
