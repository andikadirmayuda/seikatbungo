<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\Log;

class OrderInventoryService
{
    /**
     * Handle direct sale inventory reduction
     */
    public function handleDirectSale(array $items, string $saleId)
    {
        foreach ($items as $item) {
            $product = Product::findOrFail($item['product_id']);

            if (!$product->hasEnoughStock($item['quantity'])) {
                throw new \Exception("Stok tidak mencukupi untuk {$product->name}");
            }

            $product->reduceStock(
                quantity: $item['quantity'],
                source: 'direct_sale',
                referenceId: "sale:{$saleId}",
                notes: "Penjualan langsung di toko #" . $saleId
            );
        }
    }

    /**
     * Handle public order product inventory reduction
     */
    public function handlePublicOrderProduct(array $items, string $orderId)
    {
        foreach ($items as $item) {
            $product = Product::findOrFail($item['product_id']);

            if (!$product->hasEnoughStock($item['quantity'])) {
                throw new \Exception("Stok tidak mencukupi untuk {$product->name}");
            }

            $product->reduceStock(
                quantity: $item['quantity'],
                source: 'public_order_product',
                referenceId: "public_order_product:{$orderId}",
                notes: "Pesanan publik produk: {$product->name}"
            );
        }
    }

    /**
     * Handle bouquet order inventory reduction
     */
    public function handlePublicOrderBouquet(array $components, int $quantity, string $orderId, string $bouquetName)
    {
        foreach ($components as $component) {
            $product = Product::findOrFail($component['product_id']);
            $requiredAmount = $component['quantity'] * $quantity;

            if (!$product->hasEnoughStock($requiredAmount)) {
                throw new \Exception("Stok tidak mencukupi untuk komponen {$product->name} pada bouquet {$bouquetName}");
            }

            $product->reduceStock(
                quantity: $requiredAmount,
                source: 'public_order_bouquet',
                referenceId: "public_order_bouquet:{$orderId}",
                notes: "Komponen bouquet: {$bouquetName}"
            );
        }
    }

    /**
     * Handle custom bouquet order inventory reduction
     */
    public function handlePublicOrderCustom(array $items, string $orderId, string $priceType)
    {
        foreach ($items as $item) {
            $product = Product::findOrFail($item['product_id']);

            // Get custom price type multiplier if needed
            $multiplier = $this->getCustomPriceMultiplier($priceType);
            $finalQuantity = $item['quantity'] * $multiplier;

            if (!$product->hasEnoughStock($finalQuantity)) {
                throw new \Exception("Stok tidak mencukupi untuk {$product->name} pada custom bouquet");
            }

            $product->reduceStock(
                quantity: $finalQuantity,
                source: 'public_order_custom',
                referenceId: "public_order_custom:{$orderId}",
                notes: "Custom bouquet - Tipe: {$priceType}"
            );
        }
    }

    /**
     * Get multiplier for custom price types
     */
    private function getCustomPriceMultiplier(string $priceType): int
    {
        return match ($priceType) {
            'custom_ikat' => 1,
            'custom_tangkai' => 1,
            'custom_khusus' => 1,
            default => 1,
        };
    }
}
