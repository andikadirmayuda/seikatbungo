<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VoucherController extends Controller
{
    protected function formatCurrency($amount)
    {
        return number_format(floatval($amount), 0, ',', '.');
    }

    public function validate(Request $request)
    {
        try {
            $code = strtoupper($request->input('voucher_code'));
            // Gunakan total dari request jika ada, jika tidak gunakan dari session
            $total = $request->input('total_amount', session('cart_total', 0));

            Log::info('Validating voucher with total:', [
                'code' => $code,
                'total' => $total,
                'total_type' => gettype($total),
                'session_total' => session('cart_total'),
                'request_total' => $request->input('total_amount')
            ]);

            $voucher = Voucher::where('code', $code)
                ->where('is_active', true)
                ->first();

            if (!$voucher) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'valid' => false,
                        'message' => 'Kode voucher tidak ditemukan.'
                    ]);
                }
                return redirect()->back()->with('voucher_error', 'Kode voucher tidak ditemukan.');
            }

            if (!$voucher->isValid()) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'valid' => false,
                        'message' => 'Voucher tidak valid atau sudah kadaluarsa.'
                    ]);
                }
                return redirect()->back()->with('voucher_error', 'Voucher tidak valid atau sudah kadaluarsa.');
            }

            // Convert to proper numeric values for comparison
            $minSpend = floatval($voucher->minimum_spend);
            $currentTotal = floatval($total);

            Log::info('Checking minimum spend requirement:', [
                'minimum_spend_raw' => $voucher->minimum_spend,
                'minimum_spend_float' => $minSpend,
                'total_raw' => $total,
                'total_float' => $currentTotal,
                'comparison_result' => $minSpend > $currentTotal,
                'difference' => $minSpend - $currentTotal
            ]);

            if (!$voucher->checkMinimumSpend($total)) {

                $minSpendFormatted = number_format(floatval($voucher->minimum_spend), 0, ',', '.');
                $message = sprintf(
                    'Total pembelian belum mencapai minimum untuk menggunakan voucher ini. (Min. Rp %s)',
                    $this->formatCurrency($voucher->minimum_spend)
                );

                if ($request->wantsJson()) {
                    return response()->json([
                        'valid' => false,
                        'message' => $message
                    ]);
                }
                return redirect()->back()->with('voucher_error', $message);
            }

            // Calculate discount
            $discount = $voucher->type === 'percentage'
                ? ($total * $voucher->value / 100)
                : $voucher->value;

            // Store voucher in session
            session(['applied_voucher' => [
                'code' => $voucher->code,
                'description' => $voucher->description,
                'type' => $voucher->type,
                'value' => $voucher->value,
                'discount' => $discount,
                'minimum_spend' => $voucher->minimum_spend,
                'validity' => 'Berlaku sampai ' . $voucher->end_date->format('d F Y')
            ]]);

            if ($request->wantsJson()) {
                return response()->json([
                    'valid' => true,
                    'code' => $voucher->code,
                    'description' => $voucher->description,
                    'type' => $voucher->type,
                    'value' => $voucher->value,
                    'discount' => $discount,
                    'minimum_spend' => $voucher->minimum_spend,
                    'validity' => 'Berlaku sampai ' . $voucher->end_date->format('d F Y')
                ]);
            }

            return redirect()->back()->with('success', 'Voucher berhasil digunakan.');
        } catch (\Exception $e) {
            Log::error('Error validating voucher:', [
                'code' => $code ?? null,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'valid' => false,
                'message' => 'Terjadi kesalahan saat memvalidasi voucher.'
            ]);
        }
    }
}
