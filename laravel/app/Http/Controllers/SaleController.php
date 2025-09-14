<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::active()->latest()->paginate(20);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $categories = DB::table('categories')->get();
        $products = Product::with(['prices'])->get();
        return view('sales.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        // Konversi items dari JSON ke array jika perlu
        if ($request->has('items') && is_string($request->items)) {
            $request->merge(['items' => json_decode($request->items, true) ?: []]);
        }
        $request->validate([
            'items' => 'required|array',
            'payment_method' => 'required|in:cash,debit,transfer',
        ]);

        // Cek stok produk sebelum transaksi
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product || !$product->hasEnoughStock($item['quantity'])) {
                return back()->withErrors(['error' => 'Stok produk ' . ($product ? $product->name : 'tidak ditemukan') . ' tidak mencukupi!'])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $today = Carbon::now()->format('dmY');

            // Cari nomor urut terakhir termasuk yang sudah di-soft delete
            $latestSale = Sale::withTrashed()
                ->where('order_number', 'like', 'SALE-' . $today . '%')
                ->orderByRaw('CAST(SUBSTRING(order_number, -3) AS UNSIGNED) DESC')
                ->first();

            $counter = 1;
            if ($latestSale) {
                // Ambil 3 digit terakhir dan tambah 1
                $lastNumber = (int) substr($latestSale->order_number, -3);
                $counter = $lastNumber + 1;
            }

            // Generate nomor order baru
            $order_number = 'SALE-' . $today . str_pad($counter, 3, '0', STR_PAD_LEFT);

            // Double check untuk memastikan benar-benar unik
            while (Sale::withTrashed()->where('order_number', $order_number)->exists()) {
                $counter++;
                $order_number = 'SALE-' . $today . str_pad($counter, 3, '0', STR_PAD_LEFT);
            }

            $subtotal = collect($request->items)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });
            $total = $subtotal; // Bisa ditambah diskon/pajak jika perlu

            // Generate kode unik public_code
            $public_code = bin2hex(random_bytes(8));

            $cash_given = null;
            $change = null;
            if ($request->payment_method === 'cash') {
                $cash_given = $request->input('cash_given') ?? 0;
                $change = $cash_given - $total;
            }
            $sale = Sale::create([
                'order_number' => $order_number,
                'order_time' => now(),
                'total' => $total,
                'subtotal' => $subtotal,
                'payment_method' => $request->payment_method,
                'public_code' => $public_code,
                'cash_given' => $cash_given,
                'change' => $change,
                'wa_number' => $request->wa_number,
            ]);

            foreach ($request->items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'price_type' => $item['price_type'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
                // Update stok
                $product = Product::find($item['product_id']);

                // Hitung quantity berdasarkan unit_equivalent
                $qtyToReduce = $item['quantity'];
                $price = $product->prices->where('type', $item['price_type'])->first();
                if ($price && $price->unit_equivalent > 0) {
                    $qtyToReduce = $item['quantity'] * $price->unit_equivalent;
                }

                // Kurangi stok produk
                $product->decrement('current_stock', $qtyToReduce);

                // Catat di log inventaris
                \App\Models\InventoryLog::create([
                    'product_id' => $item['product_id'],
                    'qty' => -abs($qtyToReduce),
                    'source' => \App\Models\InventoryLog::SOURCE_SALE,
                    'reference_id' => "sale:{$sale->id}",
                    'notes' => "Penjualan langsung #{$sale->order_number} - {$item['price_type']}"
                ]);
            }

            DB::commit();
            return redirect()->route('sales.show', $sale->id)->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $sale = Sale::with('items.product')->findOrFail($id);

        // Log any missing product references for maintenance
        $missingProducts = $sale->items->filter(function ($item) {
            return $item->product === null;
        });

        if ($missingProducts->count() > 0) {
            Log::warning("Sale {$sale->id} has {$missingProducts->count()} items with deleted products", [
                'sale_id' => $sale->id,
                'missing_product_ids' => $missingProducts->pluck('product_id')->toArray()
            ]);
        }

        if (request('print') == 1) {
            return view('sales.receipt', compact('sale'));
        }
        return view('sales.show', compact('sale'));
    }

    public function downloadPdf($id)
    {
        $sale = \App\Models\Sale::with('items.product')->findOrFail($id);

        // Log any missing product references for maintenance
        $missingProducts = $sale->items->filter(function ($item) {
            return $item->product === null;
        });

        if ($missingProducts->count() > 0) {
            Log::warning("PDF generation for sale {$sale->id} has {$missingProducts->count()} items with deleted products", [
                'sale_id' => $sale->id,
                'missing_product_ids' => $missingProducts->pluck('product_id')->toArray()
            ]);
        }

        $pdf = Pdf::loadView('sales.receipt', compact('sale'));
        $filename = 'Struk-Penjualan-' . $sale->order_number . '.pdf';
        return $pdf->download($filename);
    }

    public function destroy(Request $request, Sale $sale)
    {
        // Ambil sale dengan withTrashed agar forceDelete bisa berjalan pada data yang sudah di-soft delete
        $sale = \App\Models\Sale::withTrashed()->find($sale->id);

        // Validasi: hanya transaksi hari ini yang bisa dihapus
        $today = now()->format('Y-m-d');
        $saleDate = \Carbon\Carbon::parse($sale->order_time)->format('Y-m-d');

        if ($saleDate !== $today) {
            return back()->withErrors(['error' => 'Hanya transaksi hari ini yang dapat dihapus!']);
        }

        // Validasi alasan wajib
        $request->validate([
            'deletion_reason' => 'required|string|min:5|max:255',
        ], [
            'deletion_reason.required' => 'Alasan penghapusan wajib diisi',
            'deletion_reason.min' => 'Alasan penghapusan minimal 5 karakter',
            'deletion_reason.max' => 'Alasan penghapusan maksimal 255 karakter',
        ]);

        DB::beginTransaction();
        try {
            // Hapus sale dan seluruh relasinya secara permanen (cascade)
            $sale->forceDelete();

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Transaksi berhasil dihapus permanen.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menghapus transaksi: ' . $e->getMessage()]);
        }
    }

    /**
     * Bulk delete selected sales
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:sales,id'
        ]);

        DB::beginTransaction();
        try {
            $sales = Sale::withTrashed()->whereIn('id', $request->ids)->get();
            $count = 0;
            foreach ($sales as $sale) {
                $sale->forceDelete();
                $count++;
            }
            DB::commit();
            return redirect()->route('sales.index')
                ->with('success', $count . ' transaksi berhasil dihapus permanen.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Bulk delete sales failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal menghapus transaksi: ' . $e->getMessage()]);
        }
    }
}
