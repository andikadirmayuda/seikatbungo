<?php

namespace App\Services;

use App\Models\Product;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductPrice;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Get inventory movement history for a product
     */
    public function getProductHistory(Product $product)
    {
        return $product->inventoryLogs()
            ->with('product')
            ->latest()
            ->paginate(20); // 20 items per page
    }

    /**
     * Get products that need restocking
     */
    public function getProductsNeedingRestock(): Collection
    {
        return Product::needsRestock()->get();
    }

    /**
     * Process a stock adjustment
     */
    public function processStockAdjustment(Product $product, int $newQuantity, string $notes = null): InventoryLog
    {
        return $product->adjustStock(
            newQuantity: $newQuantity,
            referenceId: 'ADJ-' . time(),
            notes: $notes
        );
    }

    /**
     * Process stock addition (e.g., from purchase)
     */
    public function processStockAddition(Product $product, int $quantity, string $source, string $referenceId, ?string $notes = null): InventoryLog
    {
        return $product->addStock(
            quantity: $quantity,
            source: $source,
            referenceId: $referenceId,
            notes: $notes
        );
    }

    /**
     * Process stock reduction (e.g., from sale)
     */
    public function processStockReduction(Product $product, int $quantity, string $source, string $referenceId, ?string $notes = null): InventoryLog
    {
        if ($product->current_stock < $quantity) {
            throw new \Exception("Insufficient stock for product {$product->name}");
        }

        return $product->reduceStock(
            quantity: $quantity,
            source: $source,
            referenceId: $referenceId,
            notes: $notes
        );
    }

    public function holdStock(Order $order)
    {
        foreach ($order->items as $item) {
            // Dapatkan product price untuk mendapatkan unit_equivalent
            $productPrice = ProductPrice::where([
                'product_id' => $item->product_id,
                'type' => $item->price_type
            ])->first();

            if (!$productPrice) {
                throw new \Exception("Harga produk tidak ditemukan untuk {$item->product->name}");
            }

            // Hitung total unit yang akan dikurangi (qty * unit_equivalent)
            $totalUnits = (int)$item->qty * (int)$productPrice->unit_equivalent;

            // Periksa stok tersedia
            if ($item->product->current_stock < $totalUnits) {
                throw new \Exception("Stok tidak cukup untuk produk {$item->product->name}. Stok tersedia: {$item->product->current_stock}, Dibutuhkan: {$totalUnits}");
            }

            // Kurangi stok produk sesuai total unit
            $item->product->decrement('current_stock', $totalUnits);

            // Catat di inventory log
            InventoryLog::create([
                'product_id' => $item->product_id,
                'qty' => -$totalUnits, // Negative karena stok berkurang
                'source' => 'sale',
                'reference_id' => $order->id,
                'notes' => "Stok ditahan untuk pesanan #{$order->order_number}"
            ]);

            // Catat di stock hold
            DB::table('stock_holds')->insert([
                'product_id' => $item->product_id,
                'order_id' => $order->id,
                'quantity' => $totalUnits, // Simpan dalam unit dasar (tangkai)
                'status' => 'held',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function releaseStock(Order $order)
    {
        foreach ($order->stockHolds as $hold) {
            if ($hold->status === 'held') {
                // Kembalikan stok sesuai quantity yang ditahan
                $hold->product->increment('current_stock', (int)$hold->quantity);

                // Catat di inventory log
                InventoryLog::create([
                    'product_id' => $hold->product_id,
                    'qty' => (int)$hold->quantity, // Positive karena stok kembali
                    'source' => 'return',
                    'reference_id' => $order->id,
                    'notes' => "Stok dikembalikan dari pembatalan pesanan #{$order->order_number}"
                ]);

                // Update status hold
                $hold->update([
                    'status' => 'released'
                ]);
            }
        }
    }

    public function completeOrder(Order $order)
    {
        foreach ($order->stockHolds as $hold) {
            if ($hold->status === 'held') {
                // Tanda released karena stok sudah dikurangi final oleh proses penjualan
                $hold->update(['status' => 'released']);

                // Catat di inventory log sebagai penjualan final
                InventoryLog::create([
                    'product_id' => $hold->product_id,
                    'qty' => -$hold->quantity, // Negative karena stok berkurang permanent
                    'source' => 'sale',
                    'reference_id' => $order->id,
                    'notes' => "Pesanan #{$order->order_number} selesai"
                ]);
            }
        }
    }
}
