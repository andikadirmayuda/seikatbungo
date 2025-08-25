<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Role;
use App\Models\User;
use App\Models\PublicOrder;
use App\Models\InventoryLog;
use App\Models\BouquetCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Enable query logging
        DB::enableQueryLog();

        $user = Auth::user();

        // Statistik utama
        // Hitung pelanggan online berdasarkan unique wa_number dari PublicOrder, bukan dari tabel Customer
        $totalCustomers = PublicOrder::select('wa_number')
            ->whereNotNull('customer_name')
            ->whereNotNull('wa_number')
            ->where('wa_number', '!=', '')
            ->where('wa_number', '!=', '-')
            ->distinct()
            ->count();

        $totalProducts = Product::count();
        $totalOrders = PublicOrder::whereIn('status', ['pending', 'confirmed', 'processing', 'ready', 'completed'])->count();
        $totalSales = Sale::count(); // Sales menggunakan SoftDeletes, count() otomatis exclude yang deleted

        // Total pendapatan dari Sales dan PublicOrder 
        $salesRevenue = Sale::sum('total'); // Ambil semua sales yang tidak di-soft delete

        // Hitung pendapatan dari PublicOrder melalui items (quantity * price)
        $ordersRevenue = DB::table('public_orders')
            ->join('public_order_items', 'public_orders.id', '=', 'public_order_items.public_order_id')
            ->whereIn('public_orders.status', ['confirmed', 'processing', 'ready', 'completed'])
            ->sum(DB::raw('public_order_items.quantity * public_order_items.price'));

        $totalRevenue = $salesRevenue + $ordersRevenue;

        // Ambil semua produk dengan stok > 0
        $readyProducts = Product::with(['category'])
            ->where('current_stock', '>', 0)
            ->orderBy('name')
            ->get();

        // Pesanan terbaru
        $recentOrders = PublicOrder::latest()->take(5)->get();

        // Data grafik penjualan (7 hari terakhir) - PERBAIKAN
        $sales = Sale::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as total')
            ->where('created_at', '>=', now()->subDays(6))
            // Tidak ada filter status karena Sale menggunakan SoftDeletes
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Buat array 7 hari terakhir untuk memastikan semua tanggal tampil
        $last7DaysSales = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dateFormatted = now()->subDays($i)->format('d M');
            $saleData = $sales->where('date', $date)->first();
            $count = $saleData->count ?? 0;
            $total = $saleData->total ?? 0;

            $last7DaysSales->push([
                'date' => $dateFormatted,
                'count' => $count,
                'total' => $total
            ]);
        }

        $salesChartData = [
            'labels' => $last7DaysSales->pluck('date')->toArray(),
            'datasets' => [[
                'label' => 'Transaksi Penjualan',
                'data' => $last7DaysSales->pluck('count')->toArray(),
                'backgroundColor' => '#3B82F6',
                'borderColor' => '#3B82F6',
                'fill' => false,
            ]],
        ];

        // Data grafik pesanan (7 hari terakhir) - PERBAIKAN QUERY
        // Gunakan DB query builder langsung untuk menghindari konflik dengan model accessor
        $ordersQuery = DB::table('public_orders')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as order_count')
            ->where('created_at', '>=', now()->subDays(6))
            ->whereIn('status', ['pending', 'confirmed', 'processing', 'ready', 'completed'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Data untuk Performa Produk (berdasarkan kategori)
        // Hitung penjualan dari sales
        $directSales = DB::table('categories as c')
            ->select([
                'c.id as category_id',
                'c.name as category_name',
                DB::raw('COALESCE(SUM(si.quantity), 0) as total_sold')
            ])
            ->join('products as p', 'p.category_id', '=', 'c.id')
            ->join('sale_items as si', 'si.product_id', '=', 'p.id')
            ->join('sales as s', function ($join) {
                $join->on('s.id', '=', 'si.sale_id')
                    ->whereNull('s.deleted_at');
            })
            ->groupBy('c.id', 'c.name');

        // Hitung penjualan dari public orders
        $onlineOrders = DB::table('categories as c')
            ->select([
                'c.id as category_id',
                'c.name as category_name',
                DB::raw('COALESCE(SUM(poi.quantity), 0) as total_sold')
            ])
            ->join('products as p', 'p.category_id', '=', 'c.id')
            ->join('public_order_items as poi', 'poi.product_id', '=', 'p.id')
            ->join('public_orders as po', function ($join) {
                $join->on('po.id', '=', 'poi.public_order_id')
                    ->whereIn('po.status', ['completed', 'delivered']);
            })
            ->groupBy('c.id', 'c.name');

        // Dapatkan penjualan langsung
        $directSalesResult = $directSales->get();

        // Dapatkan penjualan online
        $onlineOrdersResult = $onlineOrders->get();

        // Gabungkan hasil penjualan langsung dan online
        $salesByCategory = collect();

        // Masukkan data penjualan langsung
        foreach ($directSalesResult as $sale) {
            $salesByCategory->put($sale->category_id, [
                'category_name' => $sale->category_name,
                'total_sold' => $sale->total_sold
            ]);
        }

        // Gabungkan dengan penjualan online
        foreach ($onlineOrdersResult as $order) {
            if ($salesByCategory->has($order->category_id)) {
                // Update existing category
                $current = $salesByCategory->get($order->category_id);
                $salesByCategory->put($order->category_id, [
                    'category_name' => $current['category_name'],
                    'total_sold' => $current['total_sold'] + $order->total_sold
                ]);
            } else {
                // Add new category
                $salesByCategory->put($order->category_id, [
                    'category_name' => $order->category_name,
                    'total_sold' => $order->total_sold
                ]);
            }
        }

        // Filter yang memiliki penjualan dan urutkan
        $salesByCategory = $salesByCategory
            ->filter(function ($value) {
                return $value['total_sold'] > 0;
            })
            ->sortByDesc('total_sold')
            ->values();

        // Debug info
        info('Direct Sales:', $directSalesResult->toArray());
        info('Online Orders:', $onlineOrdersResult->toArray());
        info('Combined Sales:', $salesByCategory->toArray());

        // Debug: tampilkan query yang dijalankan
        Log::info('Query Categories Sales:', [
            'query' => DB::getQueryLog()[count(DB::getQueryLog()) - 1] ?? 'No query logged'
        ]);

        $productsByCategory = collect($salesByCategory);

        // Debug data penjualan per kategori
        Log::info('Product Sales by Category:', $productsByCategory->toArray());

        $productChartData = [
            'labels' => $productsByCategory->pluck('category_name')->toArray(),
            'data' => $productsByCategory->pluck('total_sold')->toArray()
        ];

        // Hitung revenue terpisah dari items
        $revenueQuery = DB::table('public_orders')
            ->join('public_order_items', 'public_orders.id', '=', 'public_order_items.public_order_id')
            ->selectRaw('DATE(public_orders.created_at) as date, SUM(public_order_items.quantity * public_order_items.price) as revenue')
            ->where('public_orders.created_at', '>=', now()->subDays(6))
            ->whereIn('public_orders.status', ['pending', 'confirmed', 'processing', 'ready', 'completed'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Buat array 7 hari terakhir untuk memastikan semua tanggal tampil
        $last7DaysOrders = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dateFormatted = now()->subDays($i)->format('d M');

            // Ambil data count dari ordersQuery (gunakan order_count, bukan total)
            $orderData = $ordersQuery->where('date', $date)->first();
            $count = $orderData->order_count ?? 0;

            // Ambil data revenue dari revenueQuery  
            $revenueData = $revenueQuery->where('date', $date)->first();
            $revenue = $revenueData->revenue ?? 0;

            $last7DaysOrders->push([
                'date' => $dateFormatted,
                'count' => $count,
                'revenue' => $revenue
            ]);
        }

        $ordersChartData = [
            'labels' => $last7DaysOrders->pluck('date')->toArray(),
            'datasets' => [[
                'label' => 'Pesanan Online',
                'data' => $last7DaysOrders->pluck('count')->toArray(),
                'backgroundColor' => '#8B5CF6',
            ]],
        ];

        // Data grafik pendapatan (7 hari terakhir) - TAMBAHAN BARU
        // Gabungkan pendapatan dari Sales dan PublicOrder
        $revenueChartData = [
            'labels' => $last7DaysOrders->pluck('date')->toArray(),
            'datasets' => [[
                'label' => 'Pendapatan Harian',
                'data' => $last7DaysOrders->map(function ($orderDay) use ($last7DaysSales) {
                    $salesRevenue = $last7DaysSales->where('date', $orderDay['date'])->first()['total'] ?? 0;
                    return $salesRevenue + $orderDay['revenue'];
                })->toArray(),
                'backgroundColor' => '#10B981',
                'borderColor' => '#10B981',
                'fill' => false,
            ]],
        ];

        // Produk ready stock (stok > 0)
        $readyProducts = Product::with(['category', 'prices'])
            ->where('current_stock', '>', 0)
            ->orderByDesc('current_stock')
            ->get();

        // Data untuk Performa Produk (berdasarkan kategori)
        $productPerformance = DB::table('categories')
            ->select(
                'categories.name as category_name',
                DB::raw('COALESCE(SUM(DISTINCT sale_items.quantity), 0) + COALESCE(SUM(DISTINCT order_items.quantity), 0) as total_sold')
            )
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin(DB::raw('(
                SELECT sale_items.product_id, sale_items.quantity
                FROM sale_items
                JOIN sales ON sales.id = sale_items.sale_id
                WHERE sales.deleted_at IS NULL
            ) as sale_items'), 'products.id', '=', 'sale_items.product_id')
            ->leftJoin(DB::raw('(
                SELECT public_order_items.product_id, public_order_items.quantity
                FROM public_order_items
                JOIN public_orders ON public_orders.id = public_order_items.public_order_id
                WHERE public_orders.status IN ("completed", "delivered")
            ) as order_items'), 'products.id', '=', 'order_items.product_id')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc(DB::raw('total_sold'))
            ->take(5)
            ->get();

        $productChartData = [
            'labels' => $productPerformance->pluck('category_name')->toArray(),
            'data' => $productPerformance->pluck('total_sold')->toArray(),
            'tooltip' => $productPerformance->map(function ($item) {
                return "{$item->category_name}: {$item->total_sold} terjual";
            })->toArray()
        ];

        // Data untuk Performa Bouquet - Menggabungkan data dari public_orders dan sales
        $bouquetCategorySales = DB::table('bouquet_categories')
            ->select(
                'bouquet_categories.name as category_name',
                DB::raw('COALESCE(SUM(po.quantity), 0) + COALESCE(SUM(s.quantity), 0) as total_sold')
            )
            ->leftJoin('products', 'products.category_id', '=', 'bouquet_categories.id')
            ->leftJoin(DB::raw('(
                SELECT 
                    public_order_items.product_id,
                    SUM(public_order_items.quantity) as quantity
                FROM public_order_items
                JOIN public_orders ON public_orders.id = public_order_items.public_order_id
                WHERE public_orders.status IN ("completed", "delivered")
                GROUP BY public_order_items.product_id
            ) as po'), 'products.id', '=', 'po.product_id')
            ->leftJoin(DB::raw('(
                SELECT 
                    sale_items.product_id,
                    SUM(sale_items.quantity) as quantity
                FROM sale_items
                JOIN sales ON sales.id = sale_items.sale_id
                WHERE sales.deleted_at IS NULL
                GROUP BY sale_items.product_id
            ) as s'), 'products.id', '=', 's.product_id')
            ->groupBy('bouquet_categories.id', 'bouquet_categories.name')
            ->having(DB::raw('total_sold'), '>', 0)
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Jika tidak ada data penjualan bouquet, fallback ke semua kategori yang tersedia
        if ($bouquetCategorySales->isEmpty()) {
            // Jika tidak ada penjualan, tampilkan kategori dengan stok terbanyak
            $bouquetCategorySales = DB::table('bouquet_categories')
                ->select(
                    'bouquet_categories.name as category_name',
                    DB::raw('COALESCE(SUM(products.current_stock), 0) as total_stock')
                )
                ->leftJoin('products', function ($join) {
                    $join->on('products.category_id', '=', 'bouquet_categories.id')
                        ->where('products.current_stock', '>', 0);
                })
                ->groupBy('bouquet_categories.id', 'bouquet_categories.name')
                ->orderByDesc('total_stock')
                ->take(5)
                ->get();
        }

        $bouquetChartData = [
            'labels' => $bouquetCategorySales->pluck('category_name')->toArray(),
            'data' => $bouquetCategorySales->pluck(isset($bouquetCategorySales->first()->total_sold) ? 'total_sold' : 'total_stock')->toArray(),
            'tooltip' => $bouquetCategorySales->map(function ($item) {
                $metric = isset($item->total_sold) ? 'terjual' : 'stok';
                $value = isset($item->total_sold) ? $item->total_sold : $item->total_stock;
                return "{$item->category_name}: {$value} {$metric}";
            })->toArray()
        ];

        // Debug: Uncomment untuk debugging
        // dd([
        //     'bouquet_category_sales' => $bouquetCategorySales,
        //     'bouquet_chart_data' => $bouquetChartData,
        //     'all_categories' => BouquetCategory::with('bouquets')->get(),
        //     'bouquet_order_items_sample' => BouquetOrderItem::with(['bouquet.category'])->take(5)->get()
        // ]);

        // Hitung penjualan untuk setiap produk
        foreach ($readyProducts as $product) {
            // Hitung total penjualan dari sale_items
            $soldInSales = DB::table('sale_items')
                ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
                ->where('sale_items.product_id', $product->id)
                ->whereNull('sales.deleted_at')
                ->sum('sale_items.quantity');

            // Hitung total penjualan dari public_orders
            $soldInOrders = DB::table('public_order_items')
                ->join('public_orders', 'public_orders.id', '=', 'public_order_items.public_order_id')
                ->where('public_order_items.product_id', $product->id)
                ->whereIn('public_orders.status', ['completed', 'delivered'])
                ->sum('public_order_items.quantity');

            $totalSold = $soldInSales + $soldInOrders;
            $product->total_sold = $totalSold;
        }

        $data = compact(
            'user',
            'totalCustomers',
            'totalProducts',
            'totalOrders',
            'totalSales',
            'totalRevenue',
            'recentOrders',
            'salesChartData',
            'ordersChartData',
            'revenueChartData',
            'readyProducts',
            'productChartData',
            'bouquetChartData'
        );

        // Selalu arahkan ke dashboard utama
        return view('dashboard', $data);
    }
}
