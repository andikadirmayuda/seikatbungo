<?php

namespace App\Http\Controllers;

use App\Models\PublicOrder;
use App\Models\PublicOrderItem;
use App\Models\Product;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublicOrderController extends Controller
{
    /**
     * Proses pembayaran DP atau pelunasan untuk PublicOrder
     */
    public function pay(Request $request, $public_code)
    {
        $order = PublicOrder::where('public_code', $public_code)->firstOrFail();
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = $validated['amount'];
        $order->amount_paid = ($order->amount_paid ?? 0) + $amount;

        // Asumsi ada field total_price di order, jika tidak, silakan sesuaikan
        $total = $order->total_price ?? 0;
        if ($order->amount_paid >= $total && $total > 0) {
            $order->payment_status = 'paid';
        } else {
            $order->payment_status = 'dp';
        }
        $order->save();

        // (Opsional) Update status order jika sudah lunas
        if ($order->payment_status === 'paid') {
            $order->status = 'confirmed';
            $order->save();
        }

        return response()->json([
            'success' => true,
            'payment_status' => $order->payment_status,
            'amount_paid' => $order->amount_paid,
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'pickup_date' => 'required|date',
            'pickup_time' => 'nullable',
            'delivery_method' => 'required|string',
            'destination' => 'nullable|string',
            'wa_number' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.product_name' => 'required|string',
            'items.*.price_type' => 'required|string',
            'items.*.unit_equivalent' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.type' => 'nullable|string', // For identifying custom bouquets
            'items.*.custom_bouquet_id' => 'nullable|integer|exists:custom_bouquets,id',
            'voucher_code' => 'nullable|string|exists:vouchers,code',
        ]);

        $voucher = null;
        $voucherAmount = 0;
        if (!empty($validated['voucher_code'])) {
            $voucher = \App\Models\Voucher::where('code', $validated['voucher_code'])->first();
            if ($voucher && $voucher->isValid()) {
                // Hitung subtotal produk dari database
                $itemsTotal = 0;
                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $userPrice = $product->prices()->where('type', $item['price_type'])->first();
                    $hargaGrosir = $product->prices()->where('type', 'harga_grosir')->first();

                    $qty = $item['quantity'];
                    $minGrosirQty = $userPrice->min_grosir_qty ?? 0;

                    if ($hargaGrosir && $minGrosirQty > 0 && $qty >= $minGrosirQty) {
                        $price = $hargaGrosir->price;
                    } else {
                        $price = $userPrice ? $userPrice->price : 0;
                    }

                    $itemsTotal += $qty * $price;
                }
                $shippingFee = 0; // Jika ingin support potongan ongkir, ambil dari request jika ada
                $voucherAmount = $voucher->calculateDiscount($itemsTotal, $shippingFee);
            }
        }

        DB::beginTransaction();
        try {
            // Generate kode unik invoice publik
            $publicCode = bin2hex(random_bytes(8));
            $order = PublicOrder::create([
                'public_code' => $publicCode,
                'customer_name' => $validated['customer_name'],
                'pickup_date' => $validated['pickup_date'],
                'pickup_time' => $validated['pickup_time'] ?? null,
                'delivery_method' => $validated['delivery_method'],
                'destination' => $validated['destination'] ?? null,
                'wa_number' => $validated['wa_number'] ?? null,
                'status' => 'pending',
                'payment_status' => 'waiting_confirmation', // default status pembayaran
                'voucher_code' => $voucher ? $voucher->code : null,
                'voucher_amount' => $voucherAmount,
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                // Ambil harga grosir dan harga user
                $hargaGrosir = $product->prices()->where('type', 'harga_grosir')->first();
                $userPrice = $product->prices()->where('type', $item['price_type'])->first();

                $qty = $item['quantity'];
                $minGrosirQty = $userPrice->min_grosir_qty ?? 0;

                if ($hargaGrosir && $minGrosirQty > 0 && $qty >= $minGrosirQty) {
                    $selectedPrice = $hargaGrosir;
                } else {
                    $selectedPrice = $userPrice;
                }
                $price = $selectedPrice ? $selectedPrice->price : 0;
                $unitEquivalent = $item['unit_equivalent'] ?? ($selectedPrice ? $selectedPrice->unit_equivalent : 1);

                // Hitung total pengurangan stok (quantity x unit_equivalent)
                $totalQty = $qty * $unitEquivalent;
                if (!$product->hasEnoughStock($totalQty)) {
                    throw new \Exception('Stok produk ' . $product->name . ' tidak cukup!');
                }
                $product->decrementStock($totalQty);

                // Catat log inventaris
                $product->inventoryLogs()->create([
                    'qty' => -$totalQty,
                    'source' => 'sale',
                    'reference_id' => 'public_order:' . $order->id,
                    'notes' => 'Pesanan publik',
                ]);

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price_type' => $selectedPrice ? $selectedPrice->type : $item['price_type'],
                    'unit_equivalent' => $unitEquivalent,
                    'quantity' => $qty,
                    'price' => $price,
                ]);
            }
            DB::commit();

            // Trigger push notification untuk pesanan baru
            try {
                PushNotificationService::sendNewOrderNotification($order);
            } catch (\Exception $e) {
                Log::warning('Failed to send push notification for new order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }

            // Kirim link invoice publik ke frontend
            $invoiceUrl = url('/invoice/' . $order->public_code);
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'invoice_url' => $invoiceUrl,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Tampilkan form edit pesanan publik
     */
    public function edit($public_code)
    {
        if (!config('public_order.enable_public_order_edit')) {
            abort(403, 'Fitur edit pesanan publik sedang dinonaktifkan.');
        }
        $order = PublicOrder::where('public_code', $public_code)->with('items')->firstOrFail();
        // Batasi edit hanya jika status masih pending
        if ($order->status !== 'pending') {
            abort(403, 'Pesanan tidak dapat diedit.');
        }
        // Ambil semua produk beserta harga dan satuan
        $products = Product::with(['prices' => function ($q) {
            $q->orderBy('type');
        }])->where('is_active', 1)->get();
        return view('public.edit_order', compact('order', 'products'));
    }

    /**
     * Update pesanan publik
     */
    public function update(Request $request, $public_code)
    {
        if (!config('public_order.enable_public_order_edit')) {
            abort(403, 'Fitur edit pesanan publik sedang dinonaktifkan.');
        }

        $order = PublicOrder::where('public_code', $public_code)->with('items')->firstOrFail();
        if ($order->status !== 'pending') {
            abort(403, 'Pesanan tidak dapat diedit karena status sudah berubah.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'pickup_date' => 'required|date',
            'pickup_time' => 'nullable|string',
            'delivery_method' => 'required|string',
            'destination' => 'nullable|string|max:500',
            'wa_number' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|integer|exists:public_order_items,id',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.price_type' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.unit_equivalent' => 'required|integer|min:1',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Update order data
            $order->update([
                'customer_name' => $validated['customer_name'],
                'pickup_date' => $validated['pickup_date'],
                'pickup_time' => $validated['pickup_time'],
                'delivery_method' => $validated['delivery_method'],
                'destination' => $validated['destination'],
                'wa_number' => $validated['wa_number'],
                'notes' => $validated['notes'] ?? $order->notes,
            ]);

            // Track existing item IDs
            $existingItemIds = $order->items->pluck('id')->toArray();
            $updatedItemIds = [];

            // Update or create items
            foreach ($validated['items'] as $itemData) {
                // Get product name
                $product = Product::find($itemData['product_id']);
                if (!$product) {
                    throw new \Exception('Produk tidak ditemukan.');
                }

                $itemAttributes = [
                    'public_order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'product_name' => $product->name,
                    'price_type' => $itemData['price_type'],
                    'price' => (int)str_replace(['.', ','], '', $itemData['price']),
                    'unit_equivalent' => $itemData['unit_equivalent'],
                    'quantity' => $itemData['quantity'],
                    'item_type' => 'product', // default for regular products
                ];

                if (!empty($itemData['id']) && in_array($itemData['id'], $existingItemIds)) {
                    // Update existing item
                    $item = PublicOrderItem::find($itemData['id']);
                    $item->update($itemAttributes);
                    $updatedItemIds[] = $itemData['id'];
                } else {
                    // Create new item
                    $newItem = PublicOrderItem::create($itemAttributes);
                    $updatedItemIds[] = $newItem->id;
                }
            }

            // Delete items that were not updated (removed items)
            $itemsToDelete = array_diff($existingItemIds, $updatedItemIds);
            if (!empty($itemsToDelete)) {
                PublicOrderItem::whereIn('id', $itemsToDelete)->delete();
            }

            DB::commit();

            return redirect()->route('public.order.invoice', ['public_code' => $order->public_code])
                ->with('success', 'Pesanan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating public order: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui pesanan: ' . $e->getMessage()]);
        }
    }

    /**
     * Tampilkan invoice publik berdasarkan public_code
     */
    public function publicInvoice($public_code)
    {
        $order = PublicOrder::where('public_code', $public_code)->with('items')->firstOrFail();
        return view('public.invoice', compact('order'));
    }

    /**
     * Tampilkan detail pemesanan publik (tracking)
     */
    public function publicOrderDetail($public_code)
    {
        $order = PublicOrder::where('public_code', $public_code)
            ->with(['items', 'payments'])
            ->firstOrFail();
        return view('public.order_detail', compact('order'));
    }

    /**
     * Form & hasil tracking pesanan publik berdasarkan nomor WhatsApp
     */
    public function trackOrderForm(Request $request)
    {
        $orders = collect();
        $wa_number = $request->get('wa_number');
        if ($wa_number) {
            $orders = PublicOrder::where('wa_number', $wa_number)->with('items')->orderByDesc('created_at')->get();
        }
        return view('public.track_order', compact('orders', 'wa_number'));
    }
}
