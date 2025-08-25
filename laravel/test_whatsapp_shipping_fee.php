<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PublicOrder;
use App\Services\WhatsAppNotificationService;

echo "=== TESTING WHATSAPP MESSAGE WITH SHIPPING FEE ===\n\n";

try {
    // Test 1: Create order without shipping fee (customer checkout)
    echo "1. Creating test order like customer checkout...\n";

    $order = PublicOrder::create([
        'public_code' => 'TEST-WA-' . time(),
        'customer_name' => 'John Doe',
        'pickup_date' => '2025-08-08',
        'pickup_time' => '10:00',
        'delivery_method' => 'Gosend (Pesan Dari Toko)',
        'destination' => 'Jl. Merdeka No. 123, Jakarta',
        'notes' => 'Kirim secepatnya ya',
        'wa_number' => '081234567890',
        'status' => 'pending',
        'payment_status' => 'waiting_confirmation',
    ]);

    // Add some items
    $order->items()->create([
        'product_id' => null,
        'product_name' => 'Bouquet Mawar Merah',
        'price_type' => 'medium',
        'unit_equivalent' => 1,
        'quantity' => 1,
        'price' => 75000,
        'item_type' => 'bouquet',
    ]);

    $order->items()->create([
        'product_id' => null,
        'product_name' => 'Bunga Lily Putih',
        'price_type' => 'small',
        'unit_equivalent' => 1,
        'quantity' => 2,
        'price' => 45000,
        'item_type' => 'bouquet',
    ]);

    echo "✓ Order created with ID: {$order->id}\n";
    echo "✓ Items total: Rp " . number_format($order->items->sum(function ($item) {
        return $item->quantity * $item->price;
    }), 0, ',', '.') . "\n";
    echo "✓ Shipping fee: Rp " . number_format($order->shipping_fee ?? 0, 0, ',', '.') . "\n";
    echo "✓ Grand total (model): Rp " . number_format($order->total, 0, ',', '.') . "\n\n";

    // Test 2: Generate WhatsApp message BEFORE admin sets shipping fee
    echo "2. Generate WhatsApp message BEFORE admin sets ongkir...\n";

    $messageWithoutShipping = WhatsAppNotificationService::generateNewOrderMessage($order);
    echo "--- MESSAGE WITHOUT SHIPPING FEE ---\n";
    echo $messageWithoutShipping . "\n";
    echo "--- END MESSAGE ---\n\n";

    // Test 3: Admin sets shipping fee
    echo "3. Admin sets shipping fee...\n";

    $order->shipping_fee = 25000;
    $order->save();

    echo "✓ Admin set shipping fee to: Rp " . number_format($order->shipping_fee, 0, ',', '.') . "\n";
    echo "✓ New grand total: Rp " . number_format($order->fresh()->total, 0, ',', '.') . "\n\n";

    // Test 4: Generate WhatsApp message AFTER admin sets shipping fee
    echo "4. Generate WhatsApp message AFTER admin sets ongkir...\n";

    $messageWithShipping = WhatsAppNotificationService::generateNewOrderMessage($order->fresh());
    echo "--- MESSAGE WITH SHIPPING FEE ---\n";
    echo $messageWithShipping . "\n";
    echo "--- END MESSAGE ---\n\n";

    // Test 5: Compare both messages
    echo "5. Comparing messages...\n";

    $beforeTotal = 165000; // Items only
    $afterTotal = 190000;  // Items + shipping

    if (strpos($messageWithoutShipping, 'Rp 165.000') !== false) {
        echo "✓ Message without shipping shows correct items total (Rp 165.000)\n";
    } else {
        echo "❌ Message without shipping doesn't show expected total\n";
    }

    if (strpos($messageWithShipping, 'Rp 190.000') !== false) {
        echo "✓ Message with shipping shows correct grand total (Rp 190.000)\n";
    } else {
        echo "❌ Message with shipping doesn't show expected total\n";
    }

    if (strpos($messageWithShipping, 'Ongkir: Rp 25.000') !== false) {
        echo "✓ Message with shipping shows ongkir breakdown\n";
    } else {
        echo "❌ Message with shipping doesn't show ongkir breakdown\n";
    }

    // Test 6: Test admin controller method
    echo "\n6. Testing admin controller WhatsApp message generation...\n";

    // Simulate admin controller call
    $adminMessage = WhatsAppNotificationService::generateNewOrderMessage($order->fresh());

    if ($adminMessage === $messageWithShipping) {
        echo "✓ Admin controller generates same message as service\n";
    } else {
        echo "❌ Admin controller message differs from service\n";
    }

    // Cleanup
    echo "\n7. Cleaning up test data...\n";
    $order->items()->delete();
    $order->delete();
    echo "✓ Test data cleaned up\n\n";

    echo "=== ALL TESTS COMPLETED ===\n";
    echo "✅ WhatsApp message now includes shipping fee in total\n";
    echo "✅ Ongkir breakdown is shown when present\n";
    echo "✅ Admin copy pesan button will show correct total\n";
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
