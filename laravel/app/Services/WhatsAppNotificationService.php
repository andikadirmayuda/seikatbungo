<?php

namespace App\Services;

use App\Models\PublicOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    /**
     * Translate status ke bahasa Indonesia
     */
    private static function translateStatus($status)
    {
        $translations = [
            'pending' => 'Menunggu',
            'processed' => 'Diproses',
            'packing' => 'Dikemas',
            'ready' => 'Siap',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        return $translations[$status] ?? ucfirst($status);
    }

    /**
     * Translate payment status ke bahasa Indonesia
     */
    private static function translatePaymentStatus($paymentStatus)
    {
        $translations = [
            'waiting_confirmation' => 'Menunggu Konfirmasi',
            'waiting_payment' => 'Menunggu Pembayaran',
            'waiting_verification' => 'Menunggu Verifikasi',
            'paid' => 'Lunas',
            'unpaid' => 'Belum Bayar',
            'partial' => 'Bayar Sebagian',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dikembalikan'
        ];

        return $translations[$paymentStatus] ?? ucfirst(str_replace('_', ' ', $paymentStatus));
    }

    /**
     * Translate delivery method ke bahasa Indonesia
     */
    private static function translateDeliveryMethod($method)
    {
        $translations = [
            'pickup' => 'Ambil Langsung',
            'delivery' => 'Diantar',
            'courier' => 'Kurir',
            'pos' => 'Pos Indonesia',
            'jne' => 'JNE',
            'tiki' => 'TIKI',
            'sicepat' => 'SiCepat'
        ];

        return $translations[strtolower($method)] ?? $method;
    }

    /**
     * Generate pesan untuk share link detail pesanan ke customer
     */
    public static function generateCustomerOrderLinkMessage(PublicOrder $order)
    {
        try {
            // Format tanggal Indonesia
            $createdAt = $order->created_at ? $order->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') : 'N/A';
            $pickupDate = $order->pickup_date ? Carbon::parse($order->pickup_date)->format('d/m/Y') : 'N/A';

            // Link detail pesanan
            $orderDetailUrl = route('public.order.detail', ['public_code' => $order->public_code]);

            // Generate total harga dengan breakdown
            $itemsTotal = 0;
            if ($order->items && $order->items->count() > 0) {
                foreach ($order->items as $item) {
                    $itemsTotal += ($item->quantity ?? 0) * ($item->price ?? 0);
                }
            }

            $shippingFee = $order->shipping_fee ?? 0;
            $voucherAmount = $order->voucher_amount ?? 0;
            $grandTotal = $itemsTotal + $shippingFee - $voucherAmount;

            // Format harga
            $formattedItemsTotal = "Rp " . number_format($itemsTotal, 0, ',', '.');
            $formattedShippingFee = "Rp " . number_format($shippingFee, 0, ',', '.');
            $formattedVoucher = "Rp " . number_format($voucherAmount, 0, ',', '.');
            $formattedGrandTotal = "Rp " . number_format($grandTotal, 0, ',', '.');

            // Build pesan untuk customer
            $message = "*Halo {$order->customer_name}!*\n\n";
            $message .= "Terima kasih telah memesan di *Seikat Bungo*\n\n";
            $message .= "*Detail Pesanan Anda:*\n";
            $message .= "• Kode Pesanan: *{$order->public_code}*\n";
            $message .= "• Tanggal Pesan: {$createdAt}\n";
            $message .= "• Tanggal Ambil: {$pickupDate}\n";
            $message .= "• Metode: " . self::translateDeliveryMethod($order->delivery_method ?? 'N/A') . "\n";
            if (!empty($order->destination)) {
                $message .= "• Tujuan: {$order->destination}\n";
            }
            $message .= "• Status: *" . self::translateStatus($order->status ?? 'pending') . "*\n\n";

            $message .= "*Rincian Harga:*\n";
            $message .= "• Subtotal Produk: {$formattedItemsTotal}\n";
            if ($shippingFee > 0) {
                $message .= "• Ongkir: {$formattedShippingFee}\n";
            }
            if ($voucherAmount > 0) {
                $message .= "• Voucher: -{$formattedVoucher}\n";
            }
            $message .= "• *Total Keseluruhan: {$formattedGrandTotal}*\n\n";

            $message .= "*Lihat Detail Lengkap:*\n";
            $message .= "{$orderDetailUrl}\n\n";

            $message .= "*Fitur yang tersedia:*\n";
            $message .= "• Lihat status pesanan real-time\n";
            $message .= "• Download invoice\n";
            $message .= "• Lihat detail produk & harga\n";
            $message .= "• Upload bukti pembayaran\n\n";

            $message .= "Terima kasih atas kepercayaan Anda!\n\n";
            $message .= "*Seikat Bungo*";

            return $message;
        } catch (\Exception $e) {
            Log::error('Error generating WhatsApp message for customer order link', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }
    /**
     * Generate pesan WhatsApp untuk pesanan baru
     */
    public static function generateNewOrderMessage(PublicOrder $order)
    {
        try {
            // Load items if not already loaded
            if (!$order->relationLoaded('items')) {
                $order->load('items');
            }

            // Format tanggal Indonesia
            $createdAt = $order->created_at ? $order->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') : 'N/A';
            $pickupDate = $order->pickup_date ? Carbon::parse($order->pickup_date)->format('d/m/Y') : 'N/A';

            // Detail pesanan
            $orderDetails = "• Kode: " . ($order->public_code ?? 'N/A') . "\n";
            $orderDetails .= "• Nama: " . ($order->customer_name ?? 'N/A') . "\n";
            $orderDetails .= "• WhatsApp: " . ($order->wa_number ?? 'N/A') . "\n";
            $orderDetails .= "• Tanggal Pesan: {$createdAt}\n";
            $orderDetails .= "• Tanggal Ambil: {$pickupDate} " . ($order->pickup_time ?? '') . "\n";
            $orderDetails .= "• Metode: " . self::translateDeliveryMethod($order->delivery_method ?? 'N/A') . " " . ($order->delivery_method == 'gosend' ? '(Dipesan Pribadi)' : '') . "\n";
            if (!empty($order->destination)) {
                $orderDetails .= "• Tujuan: {$order->destination}\n";
            }
            $orderDetails .= "• Status Pesanan: " . self::translateStatus($order->status ?? 'pending') . "\n";
            $orderDetails .= "• Status Pembayaran: " . self::translatePaymentStatus($order->payment_status ?? 'waiting_confirmation');

            // Item pesanan
            $orderItems = "";
            $itemsTotal = 0;

            if ($order->items && $order->items->count() > 0) {
                foreach ($order->items as $item) {
                    $price = $item->price ?? 0;
                    $quantity = $item->quantity ?? 0;
                    $productName = $item->product_name ?? 'Produk';
                    $unit = $item->unit ?? 'tangkai'; // Default unit adalah tangkai

                    // Tambah detail komponen jika custom bouquet
                    if (strpos(strtolower($productName), 'custom bouquet') !== false) {
                        // Ekstrak komponen dari product_name jika ada
                        if (preg_match('/\(Komponen:(.*?)\)/', $productName, $matches)) {
                            $components = $matches[1];
                            $productName = "Custom Bouquet x{$quantity} = Rp " . number_format($price * $quantity, 0, ',', '.') . "\n";
                            $productName .= "📝 Komposisi:" . $components . "\n";
                        }
                    } else {
                        // Format normal untuk produk non-custom bouquet
                        $unit = ($quantity > 1 || strtolower($unit) === 'ikat') ? 'ikat' : 'tangkai';
                        $subtotal = $quantity * $price;
                        $orderItems .= "• {$productName}, {$quantity} {$unit}, Rp" . number_format($price, 0, ',', '.') . "\n";
                        $itemsTotal += $subtotal;
                        continue;
                    }

                    // Tambah greeting card jika ada
                    if (isset($item->greeting_card) && !empty($item->greeting_card)) {
                        $productName .= "💌 Kartu Ucapan: {$item->greeting_card}\n";
                    }

                    // Tambah gambar referensi jika ada
                    if (isset($item->reference_image) && !empty($item->reference_image)) {
                        $productName .= "🖼️ Referensi: {$item->reference_image}\n";
                    }

                    $subtotal = $quantity * $price;
                    $itemsTotal += $subtotal;
                    $orderItems .= "• {$productName}";
                }
            } else {
                $orderItems = "• Tidak ada item\n";
            }


            // Shipping fee, voucher, dan subtotal
            $shippingFee = $order->shipping_fee ?? 0;
            $voucherAmount = $order->voucher_amount ?? 0;
            $shippingInfo = "";
            $formattedItemsTotal = "Rp " . number_format($itemsTotal, 0, ',', '.');
            $shippingInfo .= "• Subtotal Produk: {$formattedItemsTotal}\n";
            if ($shippingFee > 0) {
                $shippingInfo .= "• Ongkir: Rp " . number_format($shippingFee, 0, ',', '.') . "\n";
            }
            if ($voucherAmount > 0) {
                $shippingInfo .= "• Voucher: -Rp " . number_format($voucherAmount, 0, ',', '.') . "\n";
            }

            // Total akhir
            $grandTotal = $itemsTotal + $shippingFee - $voucherAmount;
            $formattedTotal = "Rp " . number_format($grandTotal, 0, ',', '.');

            // Catatan (jika ada)
            $notes = "";
            if ($order->notes) {
                $notes = "📝 *Catatan:*\n{$order->notes}\n\n";
            }

            // Link invoice (jika ada)
            $invoiceLink = "";
            if ($order->public_code) {
                $invoiceUrl = route('public.order.invoice', ['public_code' => $order->public_code]);
                $invoiceLink = "🔗 *Link Invoice:*\n{$invoiceUrl}\n\n";
            }

            // Build message dari template
            $template = config('whatsapp.message_templates.new_order');
            $message = str_replace([
                '{order_details}',
                '{order_items}',
                '{total}',
                '{notes}',
                '{invoice_link}'
            ], [
                $orderDetails,
                $orderItems . $shippingInfo, // Add shipping & voucher info to items section
                $formattedTotal,
                $notes,
                $invoiceLink
            ], $template);

            return $message;
        } catch (\Exception $e) {
            Log::error('Error generating WhatsApp message for new order', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Generate pesan WhatsApp untuk update status
     */
    public static function generateStatusUpdateMessage(PublicOrder $order, $oldStatus, $newStatus)
    {
        try {
            $invoiceLink = "";
            if ($order->public_code) {
                $invoiceUrl = route('public.order.invoice', ['public_code' => $order->public_code]);
                $invoiceLink = "🔗 *Link Invoice:*\n{$invoiceUrl}\n\n";
            }

            $template = config('whatsapp.message_templates.status_update');
            $message = str_replace([
                '{order_code}',
                '{old_status}',
                '{new_status}',
                '{customer_name}',
                '{invoice_link}'
            ], [
                $order->public_code ?? 'N/A',
                self::translateStatus($oldStatus ?? 'unknown'),
                self::translateStatus($newStatus ?? 'unknown'),
                $order->customer_name ?? 'N/A',
                $invoiceLink
            ], $template);

            return $message;
        } catch (\Exception $e) {
            Log::error('Error generating WhatsApp message for status update', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Generate URL WhatsApp untuk grup karyawan atau individual
     */
    public static function generateEmployeeGroupWhatsAppUrl($message)
    {
        return self::generateWhatsAppUrl($message);
    }

    /**
     * Generate URL WhatsApp umum (mendukung grup link dan nomor)
     */
    public static function generateWhatsAppUrl($message)
    {
        $employeeGroup = config('whatsapp.employee_group');
        $groupType = config('whatsapp.employee_group_type', 'group_link');

        // Validasi input
        if (empty($message)) {
            return null;
        }

        if (empty($employeeGroup)) {
            Log::warning('WhatsApp employee group not configured');
            return null;
        }

        if ($groupType === 'group_link' && filter_var($employeeGroup, FILTER_VALIDATE_URL)) {
            // Untuk link grup WhatsApp, kita tidak bisa langsung mengirim pesan
            // User harus join grup dulu, lalu paste pesan manual
            return $employeeGroup;
        } else {
            // Fallback ke nomor telepon jika bukan link grup
            $encodedMessage = urlencode($message);
            // Remove protocol dari URL jika ada
            $cleanNumber = preg_replace('/^https?:\/\//', '', $employeeGroup);
            $cleanNumber = preg_replace('/[^0-9]/', '', $cleanNumber);

            if (empty($cleanNumber)) {
                Log::warning('Invalid WhatsApp number format', ['employee_group' => $employeeGroup]);
                return null;
            }

            return "https://wa.me/{$cleanNumber}?text={$encodedMessage}";
        }
    }

    /**
     * Get info target WhatsApp (grup atau individual)
     */
    public static function getTargetInfo()
    {
        $employeeGroup = config('whatsapp.employee_group');
        $groupType = config('whatsapp.employee_group_type', 'group_link');

        if (empty($employeeGroup)) {
            return [
                'type' => 'none',
                'name' => 'Tidak Dikonfigurasi',
                'target' => null,
                'note' => 'WhatsApp employee group belum dikonfigurasi.'
            ];
        }

        if ($groupType === 'group_link' && filter_var($employeeGroup, FILTER_VALIDATE_URL)) {
            return [
                'type' => 'group',
                'name' => 'Grup Karyawan Seikat Bungo',
                'target' => $employeeGroup,
                'note' => 'Pesan akan disalin ke clipboard. Buka grup dan paste manual.'
            ];
        } else {
            return [
                'type' => 'individual',
                'name' => 'Nomor Karyawan',
                'target' => $employeeGroup,
                'note' => 'Pesan akan dikirim langsung ke WhatsApp.'
            ];
        }
    }

    /**
     * Generate URL WhatsApp untuk customer
     */
    public static function generateCustomerWhatsAppUrl($phoneNumber, $message)
    {
        // Validasi input
        if (empty($phoneNumber) || empty($message)) {
            return null;
        }

        // Format nomor WhatsApp (hapus 0 di depan, tambah 62)
        $formattedNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $phoneNumber));

        if (empty($formattedNumber)) {
            return null;
        }

        $encodedMessage = urlencode($message);

        return "https://wa.me/{$formattedNumber}?text={$encodedMessage}";
    }
}
