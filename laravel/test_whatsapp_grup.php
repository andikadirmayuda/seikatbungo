<?php

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST KONFIGURASI WHATSAPP GRUP ===\n\n";

// Test konfigurasi
$groupTarget = config('whatsapp.employee_group');
$groupType = config('whatsapp.employee_group_type');

echo "1. Konfigurasi Saat Ini:\n";
echo "   - Target: {$groupTarget}\n";
echo "   - Type: {$groupType}\n\n";

// Test WhatsApp Service
$targetInfo = \App\Services\WhatsAppNotificationService::getTargetInfo();

echo "2. Info Target WhatsApp:\n";
echo "   - Tipe: {$targetInfo['type']}\n";
echo "   - Nama: {$targetInfo['name']}\n";
echo "   - Target: {$targetInfo['target']}\n";
echo "   - Catatan: {$targetInfo['note']}\n\n";

// Test generate URL
$testMessage = "🌸 *TEST PESAN* 🌸\n\nIni adalah pesan test untuk memastikan konfigurasi WhatsApp grup berfungsi dengan baik.\n\n⚠️ Jika Anda menerima pesan ini, berarti konfigurasi sudah benar!";

$whatsappUrl = \App\Services\WhatsAppNotificationService::generateEmployeeGroupWhatsAppUrl($testMessage);

echo "3. Test Generate URL:\n";
if ($whatsappUrl) {
    echo "   ✅ Berhasil generate URL\n";
    echo "   🔗 URL: {$whatsappUrl}\n";

    if ($targetInfo['type'] === 'group') {
        echo "\n   📋 Cara penggunaan:\n";
        echo "   1. Buka URL di atas\n";
        echo "   2. Copy pesan test ini:\n";
        echo "   ====================================\n";
        echo $testMessage;
        echo "\n   ====================================\n";
        echo "   3. Paste di grup WhatsApp\n";
    } else {
        echo "\n   📱 Cara penggunaan:\n";
        echo "   1. Klik URL di atas\n";
        echo "   2. WhatsApp akan terbuka dengan pesan siap kirim\n";
    }
} else {
    echo "   ❌ Gagal generate URL\n";
    echo "   🔧 Periksa konfigurasi WA_GROUP_EMPLOYEES di file .env\n";
}

echo "\n=== PANDUAN TROUBLESHOOTING ===\n";
echo "1. Pastikan link grup valid dan aktif\n";
echo "2. Pastikan Anda sudah join grup tersebut\n";
echo "3. Test dengan mengirim pesan manual dulu\n";
echo "4. Jika masih bermasalah, coba gunakan nomor admin grup\n\n";

echo "🔧 Untuk mengubah konfigurasi, edit file .env:\n";
echo "   WA_GROUP_EMPLOYEES=https://chat.whatsapp.com/KODE_GRUP_ANDA\n";
echo "   WA_GROUP_TYPE=group_link\n\n";
