<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\PublicOrder;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class VoucherService
{
    /**
     * Validasi dan proses voucher
     */
    public function processVoucher($voucherCode, $totalAmount, $customer = null, $shippingFee = 0, $items = [])
    {
        $result = [
            'success' => false,
            'voucher' => null,
            'discount' => 0,
            'message' => '',
            'type' => '',
        ];

        try {
            $voucher = Voucher::where('code', strtoupper($voucherCode))
                ->where('is_active', true)
                ->first();

            if (!$voucher) {
                $result['message'] = 'Kode voucher tidak valid.';
                return $result;
            }

            if (!$voucher->isValid()) {
                $result['message'] = 'Voucher tidak valid atau sudah kadaluarsa.';
                return $result;
            }

            // Validasi customer-specific
            if ($customer) {
                // Cek First Purchase
                if ($voucher->first_purchase_only) {
                    $hasOrders = PublicOrder::where('customer_id', $customer->id)->exists();
                    if ($hasOrders) {
                        $result['message'] = 'Voucher hanya berlaku untuk pembelian pertama.';
                        return $result;
                    }
                }

                // Cek Member Level
                if ($voucher->member_level && $customer->level !== $voucher->member_level) {
                    $result['message'] = 'Voucher hanya berlaku untuk member level ' . $voucher->member_level;
                    return $result;
                }

                // Cek Minimum Points
                if ($voucher->minimum_points && $customer->points < $voucher->minimum_points) {
                    $result['message'] = "Diperlukan minimum {$voucher->minimum_points} poin untuk menggunakan voucher ini.";
                    return $result;
                }
            }

            // Validasi minimum spend
            if ($totalAmount < $voucher->minimum_spend) {
                $result['message'] = 'Total belanja belum memenuhi syarat minimum voucher.';
                return $result;
            }

            // Validasi restrictions jika ada
            if ($voucher->hasRestrictions() && !$this->validateRestrictions($voucher, $items)) {
                $result['message'] = 'Voucher tidak berlaku untuk item yang dibeli.';
                return $result;
            }

            // Hitung diskon
            $discount = $this->calculateDiscount($voucher, $totalAmount, $shippingFee);

            $result['success'] = true;
            $result['voucher'] = $voucher;
            $result['discount'] = $discount;
            $result['type'] = $voucher->type;
            $result['message'] = 'Voucher berhasil digunakan.';

            Log::info('Voucher processed successfully', [
                'code' => $voucherCode,
                'type' => $voucher->type,
                'total_amount' => $totalAmount,
                'discount' => $discount
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing voucher', [
                'code' => $voucherCode,
                'error' => $e->getMessage()
            ]);
            $result['message'] = 'Terjadi kesalahan saat memproses voucher.';
        }

        return $result;
    }

    /**
     * Hitung jumlah diskon berdasarkan tipe voucher
     */
    /**
     * Validasi restrictions voucher terhadap items yang dibeli
     */
    private function validateRestrictions($voucher, $items)
    {
        if (empty($items)) {
            return true;
        }

        $restrictions = $voucher->restrictions;

        // Jika tidak ada restrictions, return true
        if (empty($restrictions)) {
            return true;
        }

        // Cek setiap item terhadap restrictions
        foreach ($items as $item) {
            // Cek kategori
            if (
                isset($restrictions['categories']) &&
                !empty($item['category_id']) &&
                !in_array($item['category_id'], $restrictions['categories'])
            ) {
                return false;
            }

            // Cek produk
            if (
                isset($restrictions['products']) &&
                !in_array($item['product_id'], $restrictions['products'])
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Hitung jumlah diskon berdasarkan tipe voucher
     */
    private function calculateDiscount($voucher, $totalAmount, $shippingFee = 0)
    {
        switch ($voucher->type) {
            case Voucher::TYPE_NOMINAL:
                return min($voucher->value, $totalAmount);

            case Voucher::TYPE_PERCENT:
                $discount = $totalAmount * ($voucher->value / 100);
                return $voucher->maximum_discount
                    ? min($discount, $voucher->maximum_discount)
                    : $discount;

            case Voucher::TYPE_CASHBACK:
                // Cashback akan disimpan sebagai kredit customer
                return min($voucher->value, $totalAmount);

            case Voucher::TYPE_SHIPPING:
                // Potongan ongkir
                return min($voucher->value, $shippingFee);

            case Voucher::TYPE_SEASONAL:
            case Voucher::TYPE_FIRST_PURCHASE:
            case Voucher::TYPE_LOYALTY:
                // Gunakan logika yang sama dengan nominal/percent
                if ($voucher->type === 'percent') {
                    $discount = $totalAmount * ($voucher->value / 100);
                    return $voucher->maximum_discount
                        ? min($discount, $voucher->maximum_discount)
                        : $discount;
                }
                return min($voucher->value, $totalAmount);

            default:
                return 0;
        }
    }

    /**
     * Catat penggunaan voucher
     */
    public function recordVoucherUsage($voucher, $orderId)
    {
        try {
            $voucher->usage_count += 1;
            $voucher->save();

            Log::info('Voucher usage recorded', [
                'voucher_code' => $voucher->code,
                'order_id' => $orderId
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error recording voucher usage', [
                'voucher_code' => $voucher->code,
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
