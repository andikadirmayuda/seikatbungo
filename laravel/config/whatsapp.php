<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk integrasi WhatsApp, termasuk nomor grup karyawan
    | dan template pesan untuk berbagai keperluan.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Target Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi target untuk pengiriman pesan WhatsApp
    |
    */

    // Target untuk notifikasi (bisa grup atau individual)
    'employee_target' => env('WA_GROUP_EMPLOYEES', ''),
    
    // Tipe target: 'group' untuk grup WhatsApp, 'individual' untuk nomor telepon
    'target_type' => env('WA_TARGET_TYPE', 'group'),
    
    // Untuk grup: gunakan invite code (bagian setelah chat.whatsapp.com/)
    // Untuk individual: gunakan nomor dengan format 62xxxxxxxxx
        // Grup WhatsApp karyawan (bisa berupa link grup atau nomor)
    'employee_group' => env('WA_GROUP_EMPLOYEES', 'https://chat.whatsapp.com/I225DAAEpU8E3zOtXKO3xT'),
    
    // Type: 'group_link' untuk link grup, 'phone' untuk nomor telepon
    'employee_group_type' => env('WA_GROUP_TYPE', 'group_link'),

    // Template pesan untuk berbagai jenis notifikasi
    'message_templates' => [
        'new_order' => "🌸 *PESANAN BARU MASUK* 🌸\n\n📋 *Detail Pesanan:*\n{order_details}\n\n🛒 *Item Pesanan:*\n{order_items}\n\n💰 *Total: {total}*\n\n{notes}{invoice_link}⚠️ *Mohon segera diproses!*\n📱 Cek detail lengkap di admin panel.",
        
        'status_update' => "🔔 *UPDATE STATUS PESANAN* 🔔\n\nPesanan *{order_code}* telah diupdate:\n📊 Status: *{old_status}* → *{new_status}*\n👤 Customer: {customer_name}\n\n{invoice_link}",
        
        'payment_received' => "💰 *PEMBAYARAN DITERIMA* 💰\n\nPesanan *{order_code}*:\n👤 Customer: {customer_name}\n💵 Jumlah: Rp {amount}\n📊 Status Bayar: *{payment_status}*\n\n{invoice_link}"
    ],

    // Format untuk konversi nomor WhatsApp Indonesia
    'phone_format' => [
        'country_code' => '62',
        'remove_prefix' => '0'
    ]
];
