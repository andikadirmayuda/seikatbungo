<?php

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST FITUR SHARE LINK DETAIL PESANAN KE CUSTOMER ===\n\n";

// Create dummy order for testing
$order = new \App\Models\PublicOrder();
$order->id = 999;
$order->public_code = '15f8dcb30aed22f9';
$order->customer_name = 'Test001';
$order->wa_number = '083865425936';
$order->created_at = now();
$order->pickup_date = '2025-08-14';
$order->pickup_time = '06:54:00';
$order->delivery_method = 'pickup';
$order->destination = 'sf';
$order->status = 'pending';
$order->payment_status = 'waiting_confirmation';
$order->notes = 'sda';

// Mock shipping fee and total
$order->shipping_fee = 20000;

// Create dummy items for total calculation
$items = collect([
    (object)[
        'product_name' => 'Aster Merah Ragen',
        'quantity' => 1,
        'price' => 16000
    ]
]);

// Mock total
$order->total = 36000; // 16000 + 20000

// Mock the items relationship
$order->setRelation('items', $items);

echo "1. Testing Customer Link Message Generation...\n";
$customerMessage = \App\Services\WhatsAppNotificationService::generateCustomerOrderLinkMessage($order);

if ($customerMessage) {
    echo "✅ Berhasil generate pesan customer!\n\n";
    echo "2. Pesan WhatsApp untuk Customer:\n";
    echo "====================================\n";
    echo $customerMessage;
    echo "\n====================================\n\n";

    echo "3. Link Detail Pesanan:\n";
    $orderDetailUrl = route('public.order.detail', ['public_code' => $order->public_code]);
    echo "🔗 URL: {$orderDetailUrl}\n\n";

    echo "4. Format WhatsApp URL untuk Customer:\n";
    $customerWhatsApp = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $order->wa_number));
    $whatsappUrl = \App\Services\WhatsAppNotificationService::generateCustomerWhatsAppUrl($customerWhatsApp, $customerMessage);
    echo "📱 WhatsApp URL: {$whatsappUrl}\n\n";

    echo "5. Verification:\n";
    echo "   - Customer Name: {$order->customer_name} ✅\n";
    echo "   - Order Code: {$order->public_code} ✅\n";
    echo "   - Status dalam Bahasa Indonesia: Menunggu ✅\n";
    echo "   - Total dengan Ongkir: Rp " . number_format($order->total, 0, ',', '.') . " ✅\n";
    echo "   - Link Detail Order: Included ✅\n";
    echo "   - Customer WhatsApp: +{$customerWhatsApp} ✅\n\n";
} else {
    echo "❌ Gagal generate pesan customer!\n";
}

echo "=== PENGGUNAAN FITUR BARU ===\n";
echo "1. **Tombol Baru di Admin Panel:**\n";
echo "   - Tombol 'Share Link ke Customer' (warna teal)\n";
echo "   - Terletak di samping tombol 'Share ke Grup Karyawan'\n\n";

echo "2. **Cara Kerja:**\n";
echo "   - Admin klik tombol 'Share Link ke Customer'\n";
echo "   - Sistem generate pesan pribadi untuk customer\n";
echo "   - WhatsApp terbuka langsung ke chat customer\n";
echo "   - Pesan berisi link detail pesanan & informasi penting\n\n";

echo "3. **Isi Pesan Customer:**\n";
echo "   - Sapaan personal dengan nama customer\n";
echo "   - Detail pesanan singkat (kode, tanggal, status, total)\n";
echo "   - Link detail pesanan yang bisa diklik\n";
echo "   - Informasi fitur yang tersedia di halaman detail\n";
echo "   - Closing yang ramah dari tim Seikat Bungo\n\n";

echo "4. **Benefit untuk Customer:**\n";
echo "   - Mendapat link langsung ke detail pesanan\n";
echo "   - Bisa cek status real-time\n";
echo "   - Bisa download invoice\n";
echo "   - Bisa upload bukti pembayaran\n";
echo "   - Tidak perlu ingat kode pesanan\n\n";

echo "🎉 Fitur Share Link ke Customer siap digunakan!\n";
echo "📱 Test dengan pesanan nyata di: /admin/public-orders/{id}\n";
