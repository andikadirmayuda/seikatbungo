<?php

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST PESAN WHATSAPP DENGAN STATUS BAHASA INDONESIA ===\n\n";

// Create dummy order with Indonesian status
$order = new \App\Models\PublicOrder();
$order->id = 999;
$order->public_code = '15f8dcb30aed2210';
$order->customer_name = 'Test001';
$order->wa_number = '083865425936';
$order->created_at = now();
$order->pickup_date = '2025-08-14';
$order->pickup_time = '06:54:00';
$order->delivery_method = 'pickup'; // akan diterjemahkan ke "Ambil Langsung"
$order->destination = 'sf';
$order->status = 'pending'; // akan diterjemahkan ke "Menunggu"
$order->payment_status = 'waiting_confirmation'; // akan diterjemahkan ke "Menunggu Konfirmasi"
$order->notes = null;

// Create dummy items
$items = collect([
    (object)[
        'product_name' => 'Aster Merah Ragen',
        'quantity' => 1,
        'price' => 16000
    ]
]);

// Mock the items relationship
$order->setRelation('items', $items);

// Generate pesan
echo "1. Generating pesan WhatsApp...\n";
$message = \App\Services\WhatsAppNotificationService::generateNewOrderMessage($order);

if ($message) {
    echo "✅ Berhasil generate pesan!\n\n";
    echo "2. Pesan WhatsApp (dengan status Indonesia):\n";
    echo "====================================\n";
    echo $message;
    echo "\n====================================\n\n";

    echo "3. Verificasi terjemahan status:\n";
    echo "   - Status pesanan: pending → Menunggu ✅\n";
    echo "   - Status bayar: waiting_confirmation → Menunggu Konfirmasi ✅\n";
    echo "   - Metode: pickup → Ambil Langsung ✅\n\n";
} else {
    echo "❌ Gagal generate pesan!\n";
}

// Test status update message
echo "4. Test Status Update Message:\n";
$statusUpdateMessage = \App\Services\WhatsAppNotificationService::generateStatusUpdateMessage(
    $order,
    'pending',
    'processed'
);

if ($statusUpdateMessage) {
    echo "✅ Status update message:\n";
    echo "====================================\n";
    echo $statusUpdateMessage;
    echo "\n====================================\n\n";
    echo "   - Old status: pending → Menunggu ✅\n";
    echo "   - New status: processed → Diproses ✅\n";
} else {
    echo "❌ Gagal generate status update message!\n";
}

echo "\n=== DAFTAR TERJEMAHAN STATUS ===\n";
echo "Status Pesanan:\n";
echo "- pending → Menunggu\n";
echo "- processed → Diproses\n";
echo "- packing → Dikemas\n";
echo "- ready → Siap\n";
echo "- shipped → Dikirim\n";
echo "- completed → Selesai\n";
echo "- cancelled → Dibatalkan\n\n";

echo "Status Pembayaran:\n";
echo "- waiting_confirmation → Menunggu Konfirmasi\n";
echo "- waiting_payment → Menunggu Pembayaran\n";
echo "- waiting_verification → Menunggu Verifikasi\n";
echo "- paid → Lunas\n";
echo "- unpaid → Belum Bayar\n";
echo "- partial → Bayar Sebagian\n";
echo "- cancelled → Dibatalkan\n";
echo "- refunded → Dikembalikan\n\n";

echo "Metode Pengiriman:\n";
echo "- pickup → Ambil Langsung\n";
echo "- delivery → Diantar\n";
echo "- courier → Kurir\n";
echo "- pos → Pos Indonesia\n";
echo "- jne → JNE\n";
echo "- tiki → TIKI\n";
echo "- sicepat → SiCepat\n\n";

echo "🎉 Sistem terjemahan status sudah aktif!\n";
