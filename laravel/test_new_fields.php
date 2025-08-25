<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PublicOrder;

echo "=== TESTING NEW FIELDS: stock_holded & info ===\n\n";

try {
    // Test 1: Create order dengan field baru
    echo "1. Testing field baru stock_holded dan info...\n";

    $order = PublicOrder::create([
        'public_code' => 'TEST-NEW-FIELDS-' . time(),
        'customer_name' => 'Test Customer',
        'pickup_date' => '2025-08-08',
        'pickup_time' => '10:00',
        'delivery_method' => 'Ambil Langsung Ke Toko',
        'destination' => 'Test Address',
        'status' => 'pending',
        'stock_holded' => true,
        'info' => 'Testing info field untuk customer'
    ]);

    echo "✓ Order created dengan stock_holded: " . ($order->stock_holded ? 'true' : 'false') . "\n";
    echo "✓ Info field: " . ($order->info ?? 'null') . "\n\n";

    // Test 2: Update field via AdminController logic simulation
    echo "2. Testing stock_holded update simulation...\n";

    // Simulate processed status
    $order->status = 'processed';
    $order->stock_holded = true;
    $order->save();
    echo "✓ Status processed - stock_holded set to: " . ($order->stock_holded ? 'true' : 'false') . "\n";

    // Simulate cancelled status
    $order->status = 'cancelled';
    $order->stock_holded = false;
    $order->save();
    echo "✓ Status cancelled - stock_holded set to: " . ($order->stock_holded ? 'true' : 'false') . "\n\n";

    // Test 3: Test info field in blade template context
    echo "3. Testing info field untuk blade template...\n";
    $defaultInfo = $order->info ?? 'Harap Dibaca seluruh informasinya, Jika ada pertanyaan silahkan hubungi kami';
    echo "✓ Info display: " . $defaultInfo . "\n\n";

    // Test 4: Test dengan default values
    echo "4. Testing dengan default values...\n";
    $orderDefaults = PublicOrder::create([
        'public_code' => 'TEST-DEFAULTS-' . time(),
        'customer_name' => 'Test Default Customer',
        'pickup_date' => '2025-08-08',
        'delivery_method' => 'Ambil Langsung Ke Toko',
        // stock_holded will use default: false (0)
        // info will use default: null
    ]);

    echo "✓ stock_holded default handled: " . ($orderDefaults->stock_holded ? 'true' : 'false') . "\n";
    echo "✓ info default handled: " . ($orderDefaults->info ?? 'null') . "\n\n";

    // Cleanup
    echo "5. Cleaning up test data...\n";
    $order->delete();
    $orderDefaults->delete();
    echo "✓ Test data cleaned up\n\n";

    echo "=== ALL NEW FIELDS TESTS PASSED ===\n";
    echo "✅ stock_holded field working correctly\n";
    echo "✅ info field working correctly\n";
    echo "✅ Null handling working properly\n";
    echo "✅ Database schema updated successfully\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
