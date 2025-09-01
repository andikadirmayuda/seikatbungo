<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Bouquet;
use App\Models\PublicOrder;
use App\Models\OrderItem;
use App\Models\InventoryLog;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\PushNotificationService;
use App\Services\CartValidationService;

class PublicCheckoutController extends Controller
{
    protected $cartValidationService;

    public function __construct(CartValidationService $cartValidationService)
    {
        $this->cartValidationService = $cartValidationService;
    }

    /**
     * Show checkout form
     */
    public function show(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong.');
        }

        // Format cart data untuk view
        $cartData = [];
        $totalAmount = 0;

        foreach ($cart as $cartKey => $item) {
            // Validasi item cart menggunakan service
            if (!$this->cartValidationService->validateCustomBouquet($item)) {
                // Jika validasi gagal, redirect dengan pesan error
                return redirect()->back()->with('error', 'Terjadi kesalahan pada Custom Bouquet dalam keranjang Anda. Silakan coba lagi.');
            }

            $cartData[] = [
                'id' => $item['id'] ?? null,
                'product_id' => $item['id'] ?? null,
                'product_name' => $item['name'],
                'quantity' => $item['qty'],
                'price' => $item['price'],
                'price_type' => $item['price_type'] ?? 'default',
                'type' => $item['type'] ?? 'product',
                'greeting_card' => $item['greeting_card'] ?? null,
                'components_summary' => $item['components_summary'] ?? null,
                'image' => $item['image'] ?? null,
                'custom_bouquet_id' => $item['custom_bouquet_id'] ?? null,
                'ribbon_color' => $item['ribbon_color'] ?? null,
                'items' => $item['items'] ?? null,
                'subtotal' => $item['price'] * $item['qty']
            ];
            $totalAmount += $item['price'] * $item['qty'];
        }

        return view('public.checkout', compact('cartData', 'totalAmount'));
    }

    /**
     * Process checkout
     */
    public function process(Request $request)
    {
        try {
            DB::beginTransaction();

            $cart = session('cart', []);
            if (empty($cart)) {
                throw new \Exception('Keranjang kosong.');
            }

            // Log input data untuk debugging
            Log::info('Checkout process input:', [
                'all_input' => $request->all(),
                'delivery_method' => $request->input('delivery_method'),
                'cart_contents' => $cart
            ]);

            // Validasi input
            $validated = $request->validate([
                'customer_name' => 'required|string|max:100',
                'wa_number' => 'required|string|max:20',
                'receiver_name' => 'nullable|string|max:100',
                'receiver_wa' => 'nullable|string|max:20',
                'pickup_date' => 'required|date|after_or_equal:today',
                'pickup_time' => 'required',
                'delivery_method' => 'required|string|in:Ambil Langsung Ke Toko,Gosend (Dipesan Pribadi),Gocar (Dipesan Pribadi),Gosend (Pesan Dari Toko),Gocar (Pesan Dari Toko),Travel (Di Pesan Sendiri - Khusus Luar Kota)',
                'destination' => 'required_if:delivery_method,Gosend (Dipesan Pribadi),Gocar (Dipesan Pribadi),Gosend (Pesan Dari Toko),Gocar (Pesan Dari Toko),Travel (Di Pesan Sendiri - Khusus Luar Kota)|nullable|string',
                'notes' => 'nullable|string|max:1000',
                'custom_instructions' => 'nullable|string|max:500',
            ], [
                'customer_name.required' => 'Nama pemesan wajib diisi.',
                'wa_number.required' => 'Nomor WhatsApp pemesan wajib diisi.',
                'pickup_date.required' => 'Tanggal ambil/kirim wajib diisi.',
                'pickup_date.after_or_equal' => 'Tanggal ambil/kirim minimal hari ini atau setelahnya.',
                'pickup_time.required' => 'Waktu ambil/pengiriman wajib diisi.',
                'delivery_method.required' => 'Metode pengiriman wajib dipilih.',
                'delivery_method.in' => 'Metode pengiriman tidak valid.',
                'destination.required_if' => 'Tujuan pengiriman wajib diisi jika metode pengiriman bukan "Ambil Langsung Ke Toko".',
            ]);

            // Generate kode unik pesanan
            $publicCode = $this->generateUniqueOrderCode();

            // Hitung total amount
            $totalAmount = collect($cart)->sum(function ($item) {
                return $item['price'] * $item['qty'];
            });

            // Buat order baru
            $order = PublicOrder::create([
                'public_code' => $publicCode,
                'customer_name' => $validated['customer_name'],
                'wa_number' => $validated['wa_number'],
                'receiver_name' => $validated['receiver_name'] ?? null,
                'receiver_wa' => $validated['receiver_wa'] ?? null,
                'pickup_date' => $validated['pickup_date'],
                'pickup_time' => $validated['pickup_time'],
                'delivery_method' => $validated['delivery_method'],
                'destination' => $validated['destination'],
                'notes' => $validated['notes'] ?? null,
                'custom_instructions' => $validated['custom_instructions'] ?? null,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'waiting_confirmation'
            ]);

            // Proses setiap item di cart
            foreach ($cart as $item) {
                // Cek stok sebelum proses
                $this->checkAndReduceStock($item, $order->id);

                // Hitung stok yang berkurang
                $stockReduction = $this->calculateStockReduction($item);

                // Simpan item pesanan dengan skema yang konsisten untuk bouquet/custom
                $cartType = $item["type"] ?? 'product';
                $normalizedType = $cartType; // gunakan nilai asli; enum menerima 'product', 'bouquet', 'custom_bouquet'

                $attributes = [
                    'product_name' => $item['name'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'price_type' => $item['price_type'] ?? 'default',
                    'subtotal' => $item['price'] * $item['qty'],
                    'item_type' => $normalizedType,
                    'greeting_card' => $item['greeting_card'] ?? null,
                    'ribbon_color' => $item['ribbon_color'] ?? null,
                    'components_summary' => $item['components_summary'] ?? null,
                    'custom_bouquet_id' => $item['custom_bouquet_id'] ?? null,
                    'reference_image' => $item['reference_image'] ?? null,
                    'custom_instructions' => $item['custom_instructions'] ?? null,
                    'details' => [
                        'items' => isset($item['items']) ? $item['items'] : [],
                        'ribbon_color' => $item['ribbon_color'] ?? null,
                        'reference_image' => $item['reference_image'] ?? null,
                        'type' => $cartType,
                        'components' => isset($item['components']) ? $item['components'] : [],
                        'custom_instructions' => $item['custom_instructions'] ?? null,
                        'size_id' => $item['size_id'] ?? null
                    ]
                ];

                // Metadata kolom tidak tersedia di DB; gunakan details.components dan fallback di proses status

                if ($normalizedType === 'product') {
                    $attributes['product_id'] = $item['id'];
                } elseif ($normalizedType === 'bouquet') {
                    $attributes['bouquet_id'] = $item['id'];
                    $attributes['product_id'] = null;
                } else { // custom
                    $attributes['product_id'] = null;
                }

                // Isi unit_equivalent dengan hasil getUnitEquivalent agar tidak null
                $attributes['unit_equivalent'] = $this->getUnitEquivalent($item);
                $orderItem = $order->items()->create($attributes);
            }

            // Kirim notifikasi
            try {
                PushNotificationService::sendNewOrderNotification([
                    'customer_name' => $order->customer_name,
                    'public_code' => $publicCode,
                    'total' => $totalAmount,
                    'pickup_date' => $validated['pickup_date']
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to send notification', [
                    'error' => $e->getMessage(),
                    'order_id' => $order->id
                ]);
            }

            DB::commit();

            // Clear cart setelah transaksi sukses
            session()->forget('cart');
            session(['last_public_order_code' => $publicCode]);

            return redirect()
                ->to("/order/{$publicCode}")
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Generate unique order code
     */
    private function generateUniqueOrderCode()
    {
        do {
            $code = strtoupper(bin2hex(random_bytes(4)));
        } while (PublicOrder::where('public_code', $code)->exists());

        return $code;
    }

    /**
     * Get unit equivalent for an item
     */
    private function getUnitEquivalent($item)
    {
        // Jika item sudah memiliki unit_equivalent, gunakan itu
        if (isset($item['unit_equivalent'])) {
            return $item['unit_equivalent'];
        }

        // Jika tidak ada id produk, return 1
        if (!isset($item['id'])) {
            return 1;
        }

        // Cek price type spesifik
        if (isset($item['price_type']) && $item['price_type'] !== 'default') {
            $price = ProductPrice::where('product_id', $item['id'])
                ->where('type', $item['price_type'])
                ->first();

            if ($price && $price->unit_equivalent > 0) {
                return $price->unit_equivalent;
            }
        }

        // Default fallback ke 1
        return 1;
    }

    /**
     * Hitung pengurangan stok berdasarkan unit equivalent
     */
    private function calculateStockReduction($item)
    {
        $qty = $item['qty'];

        // Hanya hitung unit equivalent untuk produk reguler
        if (($item['type'] ?? 'product') === 'product') {
            // Ambil produk dan price type yang sesuai
            $product = Product::with('prices')->findOrFail($item['id']);
            $price = $product->prices->where('type', $item['price_type'])->first();

            // Jika ada unit_equivalent, gunakan untuk menghitung pengurangan stok
            if ($price && $price->unit_equivalent > 0) {
                return $qty * $price->unit_equivalent;
            }
        }

        // Fallback ke quantity biasa jika tidak ada unit_equivalent atau bukan produk reguler
        return $qty;
    }
    /**
     * Check and reduce stock for an item
     */
    private function checkAndReduceStock($item, $orderId)
    {
        switch ($item['type'] ?? 'product') {
            case 'bouquet':
                $this->handleBouquetStock($item, $orderId);
                break;

            case 'custom_bouquet':
                $this->handleCustomBouquetStock($item, $orderId);
                break;

            default:
                $this->handleRegularProductStock($item, $orderId);
                break;
        }
    }

    /**
     * Handle regular product stock
     */
    /**
     * Handle regular product stock (hanya hold)
     */
    private function handleRegularProductStock($item, $orderId)
    {
        if (!isset($item['id'])) {
            throw new \Exception("ID produk tidak ditemukan untuk " . ($item['name'] ?? 'Unknown Product'));
        }

        $product = Product::findOrFail($item['id']);

        // Hitung pengurangan stok berdasarkan satuan dan price type
        $priceType = $item['price_type'] ?? 'default';
        $price = $product->prices()->where('type', $priceType)->first();

        // Hitung quantity dengan unit equivalent
        $requiredQty = $item['qty'] * ($price ? $price->unit_equivalent : 1);

        if ($product->current_stock < $requiredQty) {
            throw new \Exception("Stok tidak mencukupi untuk {$product->name}");
        }

        // Buat stock hold untuk pesanan baru
        $product->stockHolds()->create([
            'order_id' => $orderId,
            'quantity' => $requiredQty,
            'status' => 'held',
            'price_type' => $priceType
        ]);

        // Log inventory sebagai hold
        InventoryLog::create([
            'product_id' => $product->id,
            'qty' => 0, // Tidak mengurangi stok, hanya hold
            'source' => 'public_order_hold',
            'reference_id' => $orderId,
            'notes' => "Penahanan stok - Pesanan publik: {$product->name} ({$item['qty']} {$priceType})",
            'current_stock' => $product->current_stock
        ]);
    }

    /**
     * Handle bouquet stock (hanya hold)
     */
    private function handleBouquetStock($item, $orderId)
    {
        if (!isset($item['id'])) {
            throw new \Exception("ID bouquet tidak ditemukan untuk " . ($item['name'] ?? 'Unknown Bouquet'));
        }

        $bouquet = Bouquet::findOrFail($item['id']);

        // Filter komponen berdasarkan size yang dipilih
        $selectedSizeId = $item['size_id'] ?? null;
        $componentsQuery = $bouquet->components()->with('product');
        if ($selectedSizeId) {
            $componentsQuery->where('size_id', (int)$selectedSizeId);
        }
        $components = $componentsQuery->get();

        // Cek stok untuk komponen sesuai size
        foreach ($components as $component) {
            if (!$component->product) continue;

            // Hitung jumlah kebutuhan per komponen sesuai quantity di bouquet dan qty pesanan
            $requiredQty = (int)($component->quantity ?? 1) * (int)($item['qty'] ?? 1);

            if ($component->product->current_stock < $requiredQty) {
                throw new \Exception("Stok tidak mencukupi untuk komponen {$component->product->name} pada bouquet {$bouquet->name}");
            }

            // Buat stock hold untuk setiap komponen
            $component->product->stockHolds()->create([
                'order_id' => $orderId,
                'quantity' => $requiredQty,
                'status' => 'held',
                'price_type' => 'bouquet_component'
            ]);

            // Log inventory hold untuk setiap komponen
            InventoryLog::create([
                'product_id' => $component->product_id,
                'qty' => 0, // Tidak mengurangi stok, hanya hold
                'source' => 'public_order_bouquet_hold',
                'reference_id' => $orderId,
                'notes' => "Penahanan stok - Komponen bouquet: {$bouquet->name}",
                'current_stock' => $component->product->current_stock
            ]);
        }
    }

    /**
     * Handle custom bouquet stock (hanya hold)
     */
    private function handleCustomBouquetStock($item, $orderId)
    {
        if (empty($item['components'])) {
            throw new \Exception('Custom bouquet tidak memiliki komponen');
        }

        // Cek stok dan buat hold untuk setiap komponen
        foreach ($item['components'] as $component) {
            $product = Product::findOrFail($component['product_id']);
            $price = null;
            $priceType = null;

            // Cek price type dari komponen
            if (isset($component['price_type'])) {
                $price = $product->prices()->where('type', $component['price_type'])->first();
                if ($price) {
                    $priceType = $component['price_type'];
                }
            }

            // Jika tidak ada price type atau harga tidak ditemukan, cari harga custom yang tersedia
            if (!$price) {
                $price = $product->prices()
                    ->whereIn('type', ['custom_ikat', 'custom_tangkai', 'custom_khusus'])
                    ->first();

                if (!$price) {
                    throw new \Exception("Tidak ada konfigurasi harga custom yang valid untuk {$product->name}");
                }
                $priceType = $price->type;
            }

            // Hitung quantity dengan unit equivalent
            $requiredQty = $component['quantity'] * $item['qty'] * $price->unit_equivalent;

            if ($product->current_stock < $requiredQty) {
                throw new \Exception("Stok tidak mencukupi untuk {$product->name} pada custom bouquet");
            }

            // Buat stock hold
            $product->stockHolds()->create([
                'order_id' => $orderId,
                'quantity' => $requiredQty,
                'status' => 'held',
                'price_type' => $priceType
            ]);

            // Log inventory sebagai hold
            InventoryLog::create([
                'product_id' => $product->id,
                'qty' => 0, // Tidak mengurangi stok, hanya hold
                'source' => 'public_order_custom_hold',
                'reference_id' => $orderId,
                'notes' => "Penahanan stok - Custom Bouquet ({$priceType})",
                'current_stock' => $product->current_stock
            ]);
        }
    }
}
