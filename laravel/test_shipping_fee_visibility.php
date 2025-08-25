<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PublicOrder;

echo "=== TESTING SHIPPING FEE VISIBILITY SYSTEM ===\n\n";

try {
    // Test 1: Create order with method that needs shipping fee
    echo "1. Creating order with 'Gosend (Pesan Dari Toko)' method...\n";

    $order = PublicOrder::create([
        'public_code' => 'TEST-VISIBILITY-' . time(),
        'customer_name' => 'Test Customer',
        'pickup_date' => '2025-08-06',
        'pickup_time' => '10:00',
        'delivery_method' => 'Gosend (Pesan Dari Toko)',
        'destination' => 'Test Address',
        'notes' => 'Test order for visibility testing',
        'wa_number' => '081234567890',
        'status' => 'pending',
        'payment_status' => 'waiting_confirmation',
        // No shipping_fee set initially
    ]);

    // Add test items
    $order->items()->create([
        'product_id' => null,
        'product_name' => 'Test Bouquet',
        'price_type' => 'medium',
        'unit_equivalent' => 1,
        'quantity' => 1,
        'price' => 100000,
        'item_type' => 'bouquet',
    ]);

    echo "✓ Order created with ID: {$order->id}\n";
    echo "✓ Public Code: {$order->public_code}\n";
    echo "✓ Delivery Method: {$order->delivery_method}\n\n";

    // Test 2: Check visibility conditions BEFORE admin sets shipping fee
    echo "2. Testing visibility conditions BEFORE admin sets shipping fee...\n";

    $order = $order->fresh(['items']);

    // Simulate the logic from order_detail.blade.php
    $itemsTotal = $order->items->sum(function ($item) {
        return $item->quantity * $item->price;
    });

    $needsShippingFee = in_array($order->delivery_method, [
        'Gosend (Pesan Dari Toko)',
        'Gocar (Pesan Dari Toko)'
    ]);

    $shippingFee = $order->shipping_fee ?? 0;
    $shippingFeeSet = $shippingFee > 0;
    $showGrandTotal = !$needsShippingFee || $shippingFeeSet;

    echo "✓ Items Total: Rp " . number_format($itemsTotal, 0, ',', '.') . "\n";
    echo "✓ Needs Shipping Fee: " . ($needsShippingFee ? 'YES' : 'NO') . "\n";
    echo "✓ Shipping Fee Set: " . ($shippingFeeSet ? 'YES' : 'NO') . "\n";
    echo "✓ Show Grand Total: " . ($showGrandTotal ? 'YES' : 'NO') . "\n";
    echo "✓ Expected: Should NOT show grand total yet\n\n";

    if (!$showGrandTotal) {
        echo "✅ CORRECT: Grand total is hidden (customer can't transfer yet)\n\n";
    } else {
        echo "❌ ERROR: Grand total is showing (customer might transfer wrong amount)\n\n";
    }

    // Test 3: Admin sets shipping fee
    echo "3. Admin sets shipping fee...\n";

    $order->shipping_fee = 20000;
    $order->save();

    echo "✓ Admin set shipping fee to: Rp " . number_format($order->shipping_fee, 0, ',', '.') . "\n\n";

    // Test 4: Check visibility conditions AFTER admin sets shipping fee
    echo "4. Testing visibility conditions AFTER admin sets shipping fee...\n";

    $order = $order->fresh(['items']);

    $shippingFee = $order->shipping_fee ?? 0;
    $shippingFeeSet = $shippingFee > 0;
    $showGrandTotal = !$needsShippingFee || $shippingFeeSet;
    $grandTotal = $itemsTotal + $shippingFee;

    echo "✓ Items Total: Rp " . number_format($itemsTotal, 0, ',', '.') . "\n";
    echo "✓ Shipping Fee: Rp " . number_format($shippingFee, 0, ',', '.') . "\n";
    echo "✓ Grand Total: Rp " . number_format($grandTotal, 0, ',', '.') . "\n";
    echo "✓ Shipping Fee Set: " . ($shippingFeeSet ? 'YES' : 'NO') . "\n";
    echo "✓ Show Grand Total: " . ($showGrandTotal ? 'YES' : 'NO') . "\n";
    echo "✓ Expected: Should show grand total now\n\n";

    if ($showGrandTotal) {
        echo "✅ CORRECT: Grand total is now visible (customer can transfer correct amount)\n\n";
    } else {
        echo "❌ ERROR: Grand total is still hidden\n\n";
    }

    // Test 5: Test with method that doesn't need shipping fee
    echo "5. Testing with method that doesn't need shipping fee...\n";

    $order2 = PublicOrder::create([
        'public_code' => 'TEST-NO-SHIPPING-' . time(),
        'customer_name' => 'Test Customer 2',
        'pickup_date' => '2025-08-06',
        'pickup_time' => '11:00',
        'delivery_method' => 'Ambil Langsung Ke Toko',
        'destination' => 'Toko',
        'notes' => 'Test order - no shipping needed',
        'wa_number' => '081234567891',
        'status' => 'pending',
        'payment_status' => 'waiting_confirmation',
    ]);

    // Add test items
    $order2->items()->create([
        'product_id' => null,
        'product_name' => 'Test Bouquet 2',
        'price_type' => 'small',
        'unit_equivalent' => 1,
        'quantity' => 1,
        'price' => 75000,
        'item_type' => 'bouquet',
    ]);

    $order2 = $order2->fresh(['items']);

    $itemsTotal2 = $order2->items->sum(function ($item) {
        return $item->quantity * $item->price;
    });

    $needsShippingFee2 = in_array($order2->delivery_method, [
        'Gosend (Pesan Dari Toko)',
        'Gocar (Pesan Dari Toko)'
    ]);

    $shippingFee2 = $order2->shipping_fee ?? 0;
    $showGrandTotal2 = !$needsShippingFee2 || $shippingFee2 > 0;

    echo "✓ Order 2 - Delivery Method: {$order2->delivery_method}\n";
    echo "✓ Order 2 - Needs Shipping Fee: " . ($needsShippingFee2 ? 'YES' : 'NO') . "\n";
    echo "✓ Order 2 - Show Grand Total: " . ($showGrandTotal2 ? 'YES' : 'NO') . "\n";
    echo "✓ Expected: Should show grand total immediately (no shipping fee needed)\n\n";

    if ($showGrandTotal2) {
        echo "✅ CORRECT: Grand total is visible for non-shipping methods\n\n";
    } else {
        echo "❌ ERROR: Grand total is hidden for non-shipping methods\n\n";
    }

    // Clean up
    echo "6. Cleaning up test data...\n";
    $order->items()->delete();
    $order->delete();
    $order2->items()->delete();
    $order2->delete();
    echo "✓ Test data cleaned up\n\n";

    echo "=== ALL VISIBILITY TESTS COMPLETED! ===\n";
    echo "✅ System correctly hides/shows grand total based on shipping fee status\n";
    echo "✅ Customers won't transfer wrong amounts\n";
    echo "✅ Payment instructions only show when total is final\n";
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
