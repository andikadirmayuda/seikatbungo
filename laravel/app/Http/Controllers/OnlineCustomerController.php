<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PublicOrder;
use App\Models\Customer;
use App\Models\ResellerCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class OnlineCustomerController extends Controller
{
    /**
     * Display a listing of online customers.
     * Note: Halaman ini menampilkan SEMUA data pelanggan tanpa filter tanggal,
     * berbeda dengan ReportController yang menggunakan filter periode tanggal.
     * Total belanja dihitung dari items (quantity x price) untuk konsistensi.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        // Ambil data pelanggan (baik dari pesanan online maupun yang ditambah manual)
        $customersQuery = Customer::query();
        if ($search) {
            $customersQuery->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        $customers = $customersQuery->get();

        // Ambil data pesanan online
        $orderData = PublicOrder::with(['items'])
            ->select(
                'wa_number',
                'customer_name',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(amount_paid) as total_spent'),
                DB::raw('MAX(created_at) as last_order_date'),
                DB::raw('MIN(created_at) as first_order_date')
            )
            ->whereNotNull('wa_number')
            ->where('wa_number', '!=', '')
            ->where('wa_number', '!=', '-');

        if ($search) {
            $orderData->where(function ($q) use ($search) {
                $q->where('customer_name', 'LIKE', "%{$search}%")
                    ->orWhere('wa_number', 'LIKE', "%{$search}%");
            });
        }

        $orderData = $orderData->groupBy('wa_number', 'customer_name')
            ->get()
            ->groupBy('wa_number');

        // Gabungkan data
        $groupedCustomers = collect();

        // Proses customer yang memiliki pesanan online
        foreach ($orderData as $waNumber => $orders) {
            $customer = $customers->firstWhere('phone', $waNumber) ?? Customer::firstOrCreate(
                ['phone' => $waNumber],
                ['name' => $orders->first()->customer_name]
            );

            $firstOrder = $orders->first();
            $groupedCustomers->push((object)[
                'customer_name' => $firstOrder->customer_name,
                'all_names' => $orders->pluck('customer_name')->unique()->values()->toArray(),
                'names_count' => $orders->pluck('customer_name')->unique()->count(),
                'wa_number' => $waNumber,
                'total_orders' => $firstOrder->total_orders,
                'total_spent' => $firstOrder->total_spent,
                'last_order_date' => $firstOrder->last_order_date,
                'first_order_date' => $firstOrder->first_order_date,
                'is_reseller' => $customer->is_reseller,
                'promo_discount' => $customer->promo_discount,
                'customer' => $customer
            ]);
        }

        // Tambahkan customer yang belum memiliki pesanan online
        foreach ($customers as $customer) {
            if (!$orderData->has($customer->phone)) {
                $groupedCustomers->push((object)[
                    'customer_name' => $customer->name,
                    'all_names' => [$customer->name],
                    'names_count' => 1,
                    'wa_number' => $customer->phone,
                    'total_orders' => 0,
                    'total_spent' => 0,
                    'last_order_date' => null,
                    'first_order_date' => null,
                    'is_reseller' => $customer->is_reseller,
                    'promo_discount' => $customer->promo_discount,
                    'customer' => $customer
                ]);
            }
        }

        // Urutkan: pelanggan dengan pesanan terbaru di atas, diikuti pelanggan tanpa pesanan
        $groupedCustomers = $groupedCustomers->sortByDesc(function ($customer) {
            return $customer->last_order_date ?? '0000-00-00';
        });

        // Tampilkan semua data tanpa pagination
        $onlineCustomers = $groupedCustomers->values();

        return view('online-customers.index', compact('onlineCustomers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Received store request', $request->all());
        $request->validate([
            'wa_number' => 'required|string|unique:customers,phone',
            'name' => 'required|string|max:255',
            'is_reseller' => 'boolean',
            'notes' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::create([
                'phone' => $request->wa_number,
                'name' => $request->name,
                'is_reseller' => $request->boolean('is_reseller'),
                'notes' => $request->notes
            ]);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pelanggan baru berhasil ditambahkan',
                    'redirect' => route('online-customers.index')
                ]);
            }

            return redirect()->route('online-customers.index')
                ->with('success', 'Pelanggan baru berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($wa_number)
    {
        // Cek dulu di tabel customers
        $customer = Customer::where('phone', $wa_number)->first();

        if (!$customer) {
            abort(404, 'Pelanggan tidak ditemukan');
        }

        // Ambil detail pesanan jika ada
        $customerData = PublicOrder::select(
            'customer_name',
            'wa_number',
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(amount_paid) as total_spent'),
            DB::raw('MAX(created_at) as last_order_date'),
            DB::raw('MIN(created_at) as first_order_date')
        )
            ->where('wa_number', $wa_number)
            ->groupBy('customer_name', 'wa_number')
            ->first();

        // Jika tidak ada data pesanan (pelanggan input manual), buat object dengan data default
        if (!$customerData) {
            $customerData = (object)[
                'customer_name' => $customer->name,
                'wa_number' => $customer->phone,
                'total_orders' => 0,
                'total_spent' => 0,
                'last_order_date' => $customer->created_at,
                'first_order_date' => $customer->created_at
            ];
        }

        $customerData->customer = $customer;

        // Ambil kode reseller aktif dan riwayat (inisialisasi dengan collection kosong jika tidak ada)
        $activeResellerCodes = collect();
        $resellerCodeHistory = collect();

        if ($customer && $customer->is_reseller) {
            $activeResellerCodes = ResellerCode::forCustomer($wa_number)->active()->get();
            $resellerCodeHistory = ResellerCode::forCustomer($wa_number)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        }

        // Ambil riwayat pesanan
        $orders = PublicOrder::where('wa_number', $wa_number)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('online-customers.show', compact('customerData', 'orders', 'activeResellerCodes', 'resellerCodeHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($wa_number)
    {
        // Cek dulu di tabel customers
        $customer = Customer::where('phone', $wa_number)->first();

        if (!$customer) {
            abort(404, 'Pelanggan tidak ditemukan');
        }

        // Ambil data pesanan jika ada
        $customerData = PublicOrder::select(
            'customer_name',
            'wa_number'
        )
            ->where('wa_number', $wa_number)
            ->first();

        // Jika tidak ada data pesanan (pelanggan input manual), buat object dengan data default
        if (!$customerData) {
            $customerData = (object)[
                'customer_name' => $customer->name,
                'wa_number' => $customer->phone
            ];
        }

        $customerData->customer = $customer;

        // Ambil kode reseller aktif dan riwayat
        $activeResellerCodes = collect();
        $resellerCodeHistory = collect();

        if ($customer && $customer->is_reseller) {
            $activeResellerCodes = ResellerCode::forCustomer($wa_number)->active()->get();
            $resellerCodeHistory = ResellerCode::forCustomer($wa_number)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        }

        return view('online-customers.edit', compact('customerData', 'activeResellerCodes', 'resellerCodeHistory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $wa_number)
    {
        $request->validate([
            'is_reseller' => 'boolean',
            'promo_discount' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            // Cari customer existing atau buat baru jika belum ada
            $customer = Customer::where('phone', $wa_number)->first();

            if (!$customer) {
                // Jika belum ada, buat baru
                $customer = Customer::create([
                    'phone' => $wa_number,
                    'name' => PublicOrder::where('wa_number', $wa_number)->value('customer_name'),
                    'is_reseller' => $request->boolean('is_reseller'),
                    'promo_discount' => $request->promo_discount,
                    'notes' => $request->notes,
                ]);
            } else {
                // Jika sudah ada, update data
                $customer->update([
                    'is_reseller' => $request->boolean('is_reseller'),
                    'promo_discount' => $request->promo_discount,
                    'notes' => $request->notes,
                ]);
            }

            DB::commit();
            return redirect()->route('online-customers.show', $wa_number)
                ->with('success', 'Data pelanggan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    /**
     * Set customer as reseller
     */
    public function setAsReseller(Request $request, $wa_number)
    {
        // Tidak perlu validation discount_percentage lagi

        DB::beginTransaction();
        try {
            $customer = Customer::where('phone', $wa_number)->first();

            if (!$customer) {
                // Jika belum ada, buat baru
                $customer = Customer::create([
                    'phone' => $wa_number,
                    'name' => PublicOrder::where('wa_number', $wa_number)->value('customer_name'),
                    'is_reseller' => true,
                ]);
            } else {
                // Jika sudah ada, update
                $customer->update([
                    'is_reseller' => true,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pelanggan berhasil ditetapkan sebagai reseller');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Set promo discount for customer
     */
    public function setPromoDiscount(Request $request, $wa_number)
    {
        $request->validate([
            'promo_discount' => 'required|numeric|min:0|max:100'
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::where('phone', $wa_number)->first();

            if (!$customer) {
                // Jika belum ada, buat baru
                $customer = Customer::create([
                    'phone' => $wa_number,
                    'name' => PublicOrder::where('wa_number', $wa_number)->value('customer_name'),
                    'promo_discount' => $request->promo_discount,
                ]);
            } else {
                // Jika sudah ada, update
                $customer->update([
                    'promo_discount' => $request->promo_discount,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Discount promo berhasil ditetapkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Generate kode reseller baru untuk customer
     */
    public function generateResellerCode(Request $request, $wa_number)
    {
        $request->validate([
            'code' => 'nullable|string|max:20',
            'expiry_hours' => 'required|integer|min:1|max:168', // Maksimal 1 minggu
            'notes' => 'nullable|string|max:500'
        ]);

        // Cek apakah customer terdaftar sebagai reseller
        $customer = Customer::where('phone', $wa_number)->first();
        if (!$customer || !$customer->is_reseller) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer belum terdaftar sebagai reseller'
                ]);
            }
            return redirect()->back()->with('error', 'Customer belum terdaftar sebagai reseller');
        }

        // Pastikan expiry_hours adalah integer
        $expiryHours = (int) $request->expiry_hours;

        // Generate kode baru dengan kode custom jika diberikan
        $code = $request->code ? strtoupper($request->code) : ResellerCode::generateUniqueCode();

        // Jika ada kode custom, cek uniqueness
        if ($request->code && ResellerCode::where('code', $code)->exists()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode sudah digunakan, silakan pilih kode lain'
                ]);
            }
            return redirect()->back()->with('error', 'Kode sudah digunakan, silakan pilih kode lain');
        }

        $resellerCode = ResellerCode::create([
            'wa_number' => $wa_number,
            'code' => $code,
            'expires_at' => Carbon::now()->addHours($expiryHours),
            'notes' => $request->notes
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'code' => $resellerCode->code,
                'expires_at' => $resellerCode->expires_at->format('d M Y H:i'),
                'message' => "Kode reseller berhasil dibuat: {$resellerCode->code}"
            ]);
        }

        return redirect()->back()->with('success', "Kode reseller berhasil dibuat: {$resellerCode->code}");
    }

    /**
     * Revoke/batalkan kode reseller
     */
    public function revokeResellerCode($wa_number, $codeId)
    {
        $resellerCode = ResellerCode::where('id', $codeId)
            ->where('wa_number', $wa_number)
            ->where('is_used', false)
            ->first();

        if (!$resellerCode) {
            return redirect()->back()->with('error', 'Kode reseller tidak ditemukan atau sudah digunakan');
        }

        $resellerCode->update([
            'expires_at' => Carbon::now(), // Set expired sekarang
            'notes' => ($resellerCode->notes ? $resellerCode->notes . ' | ' : '') . 'Revoked by admin at ' . Carbon::now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Kode reseller berhasil dibatalkan');
    }

    /**
     * API untuk validasi kode reseller dari frontend public
     */
    public function validateResellerCode(Request $request)
    {
        try {
            Log::info('Validating reseller code', [
                'code' => $request->code,
                'wa_number' => $request->wa_number
            ]);

            $request->validate([
                'code' => 'required|string',
                'wa_number' => 'required|string'
            ]);

            $validation = ResellerCode::validateCode($request->code, $request->wa_number);

            Log::info('Reseller code validation result', $validation);

            if ($validation['valid']) {
                // Cek apakah customer adalah reseller
                $customer = Customer::where('phone', $request->wa_number)->first();
                $isReseller = $customer && $customer->is_reseller;

                if (!$isReseller) {
                    return response()->json([
                        'valid' => false,
                        'message' => 'Customer bukan reseller'
                    ], 400);
                }

                return response()->json([
                    'valid' => true,
                    'message' => $validation['message'],
                    'code_id' => $validation['code']->id
                ]);
            }

            return response()->json([
                'valid' => false,
                'message' => $validation['message']
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error validating reseller code', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'valid' => false,
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * Mark kode sebagai used (dipanggil saat checkout sukses)
     */
    public function markResellerCodeUsed(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'wa_number' => 'required|string',
            'order_id' => 'nullable|integer'
        ]);

        $resellerCode = ResellerCode::where('code', $request->code)
            ->where('wa_number', $request->wa_number)
            ->where('is_used', false)
            ->first();

        if ($resellerCode && $resellerCode->isValid()) {
            $resellerCode->markAsUsed($request->order_id);

            return response()->json([
                'success' => true,
                'message' => 'Kode reseller berhasil digunakan'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kode reseller tidak valid'
        ], 400);
    }
}
