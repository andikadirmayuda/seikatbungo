<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    public function show(Voucher $voucher)
    {
        return view('admin.vouchers.show', compact('voucher'));
    }
    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }
    public function index(Request $request)
    {
        $query = Voucher::query();

        // Filter berdasarkan status
        if ($request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('end_date', '<', now());
            }
        }

        // Filter berdasarkan tipe
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Pencarian
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $vouchers = $query->latest()->paginate(10);

        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:vouchers,code|regex:/^[A-Za-z0-9]+$/',
            'description' => 'required|string|max:255',
            'type' => 'required|in:percent,nominal,shipping,cashback,seasonal,first_purchase,loyalty',
            'value' => 'required|numeric|min:0',
            'minimum_spend' => 'required|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            // Validasi untuk field seasonal
            'event_name' => 'nullable|required_if:type,seasonal|string|max:255',
            'event_type' => 'nullable|required_if:type,seasonal|string|max:255',
            // Validasi untuk field loyalty
            'minimum_points' => 'nullable|required_if:type,loyalty|integer|min:0',
            'member_level' => 'nullable|required_if:type,loyalty|string|max:50',
            // Validasi untuk first purchase
            'first_purchase_only' => 'nullable|boolean',
        ]);

        // Default voucher aktif jika tidak dicentang
        $isActive = $request->has('is_active') ? true : false;

        // Validasi tambahan untuk voucher persentase
        if ($validated['type'] === 'percent' && $validated['value'] > 100) {
            return back()->withInput()->withErrors(['value' => 'Persentase diskon tidak boleh lebih dari 100%']);
        }

        DB::beginTransaction();
        try {
            // Persiapkan data dasar voucher
            $data = [
                'code' => strtoupper($validated['code']),
                'description' => $validated['description'],
                'type' => $validated['type'],
                'value' => $validated['value'],
                'minimum_spend' => $validated['minimum_spend'],
                'maximum_discount' => $validated['maximum_discount'],
                'usage_limit' => $validated['usage_limit'],
                'usage_count' => 0,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'is_active' => $isActive,
                'applied_to' => [],
                'restrictions' => [],
                'terms_and_conditions' => [],
                'event_name' => null,
                'event_type' => null,
                'minimum_points' => null,
                'member_level' => null,
                'first_purchase_only' => false
            ];

            // Update fields berdasarkan tipe voucher
            if ($validated['type'] === 'seasonal') {
                $data['event_name'] = $validated['event_name'];
                $data['event_type'] = $validated['event_type'];
            } elseif ($validated['type'] === 'loyalty') {
                $data['minimum_points'] = $validated['minimum_points'];
                $data['member_level'] = $validated['member_level'];
            } elseif ($validated['type'] === 'first_purchase') {
                $data['first_purchase_only'] = true;
            }

            $voucher = Voucher::create($data);

            DB::commit();
            return redirect()->route('admin.vouchers.index')
                ->with('success', 'Voucher berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat voucher. ' . $e->getMessage());
        }
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'type' => 'required|in:percent,nominal,shipping,cashback,seasonal,first_purchase,loyalty',
            'value' => 'required|numeric|min:0',
            'minimum_spend' => 'required|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        // Validasi tambahan untuk voucher persentase
        if ($validated['type'] === 'percent' && $validated['value'] > 100) {
            return back()->withInput()->withErrors(['value' => 'Persentase diskon tidak boleh lebih dari 100%']);
        }

        DB::beginTransaction();
        try {
            $voucher->update([
                'description' => $validated['description'],
                'type' => $validated['type'],
                'value' => $validated['value'],
                'minimum_spend' => $validated['minimum_spend'],
                'maximum_discount' => $validated['maximum_discount'],
                'usage_limit' => $validated['usage_limit'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'is_active' => $request->has('is_active'),
            ]);

            DB::commit();
            return redirect()->route('admin.vouchers.index')
                ->with('success', 'Voucher berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui voucher. ' . $e->getMessage());
        }
    }

    public function destroy(Voucher $voucher)
    {
        try {
            // Cek apakah voucher sudah digunakan
            if ($voucher->usage_count > 0) {
                return back()->with('error', 'Voucher tidak dapat dihapus karena sudah digunakan.');
            }

            $voucher->forceDelete();
            return redirect()->route('admin.vouchers.index')
                ->with('success', 'Voucher berhasil dihapus secara permanen.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus voucher. ' . $e->getMessage());
        }
    }

    public function validateVoucher(Request $request)
    {
        $code = $request->code;

        $voucher = Voucher::where('code', $code)
            ->where('end_date', '>=', now())
            ->where('start_date', '<=', now())
            ->where('is_active', true)
            ->first();

        if ($voucher) {
            // Konversi tipe ke format yang diharapkan frontend
            $frontendType = $voucher->type === 'nominal' ? 'fixed' : 'percentage';

            // Format tampilan diskon untuk display
            $displayDiscount = $voucher->getFormattedValue();

            return response()->json([
                'valid' => true,
                'code' => $voucher->code,
                'discount' => $displayDiscount,
                'description' => $voucher->description,
                'type' => $frontendType,
                'value' => floatval($voucher->value),
                'min_purchase' => floatval($voucher->minimum_spend),
                'validity' => 'Berlaku sampai ' . $voucher->end_date->format('d F Y')
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Kode voucher tidak ditemukan atau sudah kadaluarsa'
        ]);
    }

    /**
     * Bulk actions untuk voucher (activate/deactivate/delete)
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'vouchers' => 'required|array',
            'vouchers.*' => 'exists:vouchers,id'
        ]);

        DB::beginTransaction();
        try {
            $vouchers = Voucher::whereIn('id', $validated['vouchers']);

            switch ($validated['action']) {
                case 'activate':
                    $vouchers->update(['is_active' => true]);
                    $message = 'Voucher berhasil diaktifkan';
                    break;

                case 'deactivate':
                    $vouchers->update(['is_active' => false]);
                    $message = 'Voucher berhasil dinonaktifkan';
                    break;

                case 'delete':
                    // Cek jika ada voucher yang sudah digunakan
                    $usedVouchers = $vouchers->where('usage_count', '>', 0)->count();
                    if ($usedVouchers > 0) {
                        return back()->with('error', 'Beberapa voucher tidak dapat dihapus karena sudah digunakan');
                    }
                    $vouchers->delete();
                    $message = 'Voucher berhasil dihapus';
                    break;
            }

            DB::commit();
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    /**
     * Tampilkan statistik penggunaan voucher
     */
    public function statistics(Request $request)
    {
        $stats = [
            // Statistik Umum
            'total_vouchers' => Voucher::count(),
            'active_vouchers' => Voucher::where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->count(),
            'expired_vouchers' => Voucher::where('end_date', '<', now())->count(),

            // Statistik Penggunaan
            'usage_stats' => [
                'total_used' => DB::table('vouchers')->sum('usage_count'),
                'usage_today' => DB::table('public_orders')
                    ->whereNotNull('voucher_code')
                    ->whereDate('created_at', today())
                    ->count(),
                'total_discount_given' => DB::table('public_orders')
                    ->whereNotNull('voucher_code')
                    ->sum('voucher_amount'),
            ],

            // Distribusi berdasarkan tipe
            'type_distribution' => Voucher::select('type', DB::raw('count(*) as total'))
                ->groupBy('type')
                ->get(),

            // Statistik penggunaan per tipe
            'usage_by_type' => Voucher::select('type', DB::raw('SUM(usage_count) as total_used'))
                ->groupBy('type')
                ->get(),

            // Top Vouchers
            'most_used' => Voucher::orderBy('usage_count', 'desc')
                ->take(5)
                ->get(['code', 'description', 'type', 'usage_count']),

            // Recent Usage
            'recent_usage' => DB::table('public_orders')
                ->whereNotNull('voucher_code')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(['voucher_code', 'voucher_amount', 'created_at']),
        ];

        // Return JSON jika request adalah AJAX
        if ($request->ajax()) {
            return response()->json($stats);
        }

        // Return view untuk halaman statistik
        return view('admin.vouchers.statistics', compact('stats'));
    }
}
