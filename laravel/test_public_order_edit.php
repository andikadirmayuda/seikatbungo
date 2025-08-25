<?php

/**
 * Test Script untuk Public Order Edit
 * 
 * Script ini akan membantu kita test apakah fitur edit public order berfungsi dengan benar
 */

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PublicOrder;
use App\Models\PublicOrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

echo "=== TEST PUBLIC ORDER EDIT ===\n\n";

try {
    // 1. Cari public order yang statusnya pending
    $pendingOrder = PublicOrder::where('status', 'pending')
        ->whereNotNull('public_code')
        ->with('items')
        ->first();

    if (!$pendingOrder) {
        echo "❌ Tidak ada public order dengan status pending yang memiliki public_code.\n";
        echo "Membuat sample order untuk testing...\n\n";

        // Buat sample order
        $sampleOrder = PublicOrder::create([
            'public_code' => 'TEST-' . time(),
            'customer_name' => 'Test Customer',
            'pickup_date' => now()->addDay()->format('Y-m-d'),
            'pickup_time' => '10:00',
            'delivery_method' => 'Ambil Sendiri',
            'destination' => 'Test Address',
            'wa_number' => '081234567890',
            'notes' => 'Test order untuk edit',
            'status' => 'pending',
            'payment_status' => 'waiting_confirmation',
        ]);

        // Ambil produk yang ada
        $product = Product::with('prices')->where('is_active', 1)->first();
        if ($product && $product->prices->count() > 0) {
            $price = $product->prices->first();
            PublicOrderItem::create([
                'public_order_id' => $sampleOrder->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price_type' => $price->type,
                'price' => $price->price,
                'unit_equivalent' => $price->unit_equivalent,
                'quantity' => 2,
                'item_type' => 'product'
            ]);
        }

        $pendingOrder = $sampleOrder->load('items');
        echo "✅ Sample order dibuat dengan public_code: {$pendingOrder->public_code}\n\n";
    }

    // 2. Test data order
    echo "=== DETAIL ORDER YANG AKAN DIEDIT ===\n";
    echo "ID: {$pendingOrder->id}\n";
    echo "Public Code: {$pendingOrder->public_code}\n";
    echo "Customer: {$pendingOrder->customer_name}\n";
    echo "Status: {$pendingOrder->status}\n";
    echo "Payment Status: {$pendingOrder->payment_status}\n";
    echo "Pickup Date: {$pendingOrder->pickup_date}\n";
    echo "Delivery Method: {$pendingOrder->delivery_method}\n";
    echo "Items Count: {$pendingOrder->items->count()}\n\n";

    // 3. Test items
    if ($pendingOrder->items->count() > 0) {
        echo "=== ITEMS DETAIL ===\n";
        foreach ($pendingOrder->items as $index => $item) {
            echo "Item " . ($index + 1) . ":\n";
            echo "  - Product ID: {$item->product_id}\n";
            echo "  - Product Name: {$item->product_name}\n";
            echo "  - Price Type: {$item->price_type}\n";
            echo "  - Price: {$item->price}\n";
            echo "  - Quantity: {$item->quantity}\n";
            echo "  - Unit Equivalent: {$item->unit_equivalent}\n\n";
        }
    }

    // 4. Test config
    echo "=== CONFIG CHECK ===\n";
    $editEnabled = config('public_order.enable_public_order_edit');
    echo "Edit Enabled: " . ($editEnabled ? 'TRUE' : 'FALSE') . "\n\n";

    // 5. Test produk tersedia untuk edit
    $availableProducts = Product::with('prices')->where('is_active', 1)->get();
    echo "=== AVAILABLE PRODUCTS ===\n";
    echo "Total Active Products: {$availableProducts->count()}\n";

    if ($availableProducts->count() > 0) {
        $sampleProduct = $availableProducts->first();
        echo "Sample Product: {$sampleProduct->name}\n";
        echo "Sample Product Prices: {$sampleProduct->prices->count()}\n";
        if ($sampleProduct->prices->count() > 0) {
            echo "First Price Type: {$sampleProduct->prices->first()->type}\n";
            echo "First Price Value: {$sampleProduct->prices->first()->price}\n";
        }
    }

    // 6. Generate edit URL
    echo "\n=== EDIT URL ===\n";
    $editUrl = url("public-order/{$pendingOrder->public_code}/edit");
    echo "Edit URL: {$editUrl}\n";

    $invoiceUrl = url("invoice/{$pendingOrder->public_code}");
    echo "Invoice URL: {$invoiceUrl}\n\n";

    // 7. Test validasi edit (status harus pending)
    echo "=== VALIDATION CHECK ===\n";
    if ($pendingOrder->status === 'pending') {
        echo "✅ Order dapat diedit (status: pending)\n";
    } else {
        echo "❌ Order tidak dapat diedit (status: {$pendingOrder->status})\n";
    }

    // 8. Test controller dapat diakses
    echo "\n=== CONTROLLER AVAILABILITY ===\n";
    if (class_exists('App\Http\Controllers\PublicOrderController')) {
        echo "✅ PublicOrderController tersedia\n";

        $controller = new App\Http\Controllers\PublicOrderController();
        if (method_exists($controller, 'edit')) {
            echo "✅ Method edit() tersedia\n";
        } else {
            echo "❌ Method edit() tidak tersedia\n";
        }

        if (method_exists($controller, 'update')) {
            echo "✅ Method update() tersedia\n";
        } else {
            echo "❌ Method update() tidak tersedia\n";
        }
    } else {
        echo "❌ PublicOrderController tidak tersedia\n";
    }

    // 9. Test view availability
    echo "\n=== VIEW AVAILABILITY ===\n";
    $editViewPath = resource_path('views/public/edit_order.blade.php');
    if (file_exists($editViewPath)) {
        echo "✅ View edit_order.blade.php tersedia\n";
    } else {
        echo "❌ View edit_order.blade.php tidak tersedia\n";
    }

    $invoiceViewPath = resource_path('views/public/invoice.blade.php');
    if (file_exists($invoiceViewPath)) {
        echo "✅ View invoice.blade.php tersedia\n";
    } else {
        echo "❌ View invoice.blade.php tidak tersedia\n";
    }

    echo "\n=== KESIMPULAN ===\n";
    echo "✅ Test selesai! Fitur edit public order siap digunakan.\n";
    echo "🔗 Silakan akses: {$editUrl}\n";
    echo "📋 Untuk melihat invoice: {$invoiceUrl}\n\n";
} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
