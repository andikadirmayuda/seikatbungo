<x-app-layout>
    {{-- <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="bi bi-speedometer2 mr-2"></i>
                Dashboard
            </h1>
            <div class="flex gap-3">
                <a href="{{ route('products.create') }}"
                    class="bg-gray-800 hover:bg-gray-900 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200">
                    <i class="bi bi-plus-circle mr-1"></i>
                    Tambah Produk
                </a>
                <a href="{{ route('sales.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200">
                    <i class="bi bi-cash-coin mr-1"></i>
                    Transaksi Baru
                </a>
            </div>
        </div>
    </x-slot> --}}

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #fdf2f8 0%, #ffffff 50%, #f3f4f6 100%);
        }

        .stats-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.8) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1.25rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -5px rgba(0, 0, 0, 0.15);
        }

        .chart-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.8) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1.25rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }

        .notification-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.8) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1.25rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }

        .form-enter {
            animation: slideInUp 0.4s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modern-table {
            border-radius: 1.25rem;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.8) 100%);
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }

        .modern-table thead {
            background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
        }

        .modern-table thead th {
            color: #be185d;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem;
            font-size: 0.75rem;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
        }

        .modern-table tbody tr:hover {
            background-color: rgba(236, 72, 153, 0.05);
        }

        .modern-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid rgba(241, 245, 249, 0.8);
        }
    </style>

    <div class="py-6 gradient-bg min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            {{-- <div class="text-center mb-8 form-enter">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    DASHBOARD SEIKAT BUNGO
                </h2>
                <p class="text-4xl text-gray-600">❤️</p>
            </div> --}}
            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-8 form-enter">
                <!-- Total Customers Card -->
                <div class="stats-card p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg mr-4">
                            <i class="bi bi-people text-2xl text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Pelanggan</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalCustomers ?? 0 }}</p>
                            <p class="text-xs text-blue-600 mt-1">Aktif</p>
                        </div>
                    </div>
                </div>

                <!-- Total Products Card -->
                <div class="stats-card p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg mr-4">
                            <i class="bi bi-box-seam text-2xl text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Produk</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalProducts ?? 0 }}</p>
                            <p class="text-xs text-green-600 mt-1">Ready</p>
                        </div>
                    </div>
                </div>

                <!-- Total Orders Card -->
                <div class="stats-card p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg mr-4">
                            <i class="bi bi-cart text-2xl text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pesanan Online</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalOrders ?? 0 }}</p>
                            <p class="text-xs text-purple-600 mt-1">Growing</p>
                        </div>
                    </div>
                </div>

                <!-- Total Sale Card -->
                <div class="stats-card p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-indigo-100 rounded-lg mr-4">
                            <i class="bi bi-receipt text-2xl text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Penjualan</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalSales ?? 0 }}</p>
                            <p class="text-xs text-indigo-600 mt-1">Transactions</p>
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->hasRole(['owner']))
            <!-- Total Revenue Card -->
            <div class="stats-card p-6 mb-6 form-enter">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg mr-4">
                        <i class="bi bi-cash-stack text-2xl text-orange-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
                        <p class="text-m font-bold text-gray-800">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
                        <p class="text-xs text-orange-600 mt-1">Revenue</p>
                    </div>
                </div>
            </div>
            @endif

            @if(auth()->user()->hasRole(['owner']))
            <!-- Dashboard Performance Section -->
                <!-- Performance Charts -->
                <!-- Baris 1: Performa Penjualan & Pesanan -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 form-enter">
                    <!-- Sales Performance Chart -->
                    <div class="chart-card p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="bi bi-graph-up-arrow mr-2 text-blue-600"></i>
                                Performa Penjualan
                            </h3>
                            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">7 Hari</span>
                        </div>
                        <div class="relative">
                            <canvas id="salesChart" class="w-full h-48"></canvas>
                        </div>
                    </div>

                    <!-- Orders Performance Chart -->
                    <div class="chart-card p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="bi bi-bar-chart mr-2 text-purple-600"></i>
                                Performa Pesanan
                            </h3>
                            <span class="text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded">Trending</span>
                        </div>
                        <div class="relative">
                            <canvas id="ordersChart" class="w-full h-48"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Baris 2: Performa Produk & Bouquet -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 form-enter">
                    <!-- Product Performance Chart (Doughnut) -->
                    <div class="chart-card p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="bi bi-pie-chart mr-2 text-rose-600"></i>
                                Performa Kategori Produk
                            </h3>
                            <span class="text-xs text-rose-600 bg-rose-50 px-2 py-1 rounded">Penjualan Aktual</span>
                        </div>
                        {{-- <div class="text-sm text-gray-500 mb-3">Total unit terjual dari transaksi yang sudah selesai</div> --}}
                        <div class="relative">
                            <canvas id="productChart" class="w-full h-48"></canvas>
                        </div>
                    </div>

                    <!-- Bouquet Performance Chart (Bar) -->
                    <div class="chart-card p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="bi bi-flower2 mr-2 text-pink-600"></i>
                                Performa Bouquet
                            </h3>
                            <span class="text-xs text-pink-600 bg-pink-50 px-2 py-1 rounded">Best Sellers</span>
                        </div>
                        <div class="relative">
                            <canvas id="bouquetChart" class="w-full h-48"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Baris 3: Performa Pendapatan (Full Width) -->
                <div class="grid grid-cols-1 gap-6 mb-8 form-enter">
                    <!-- Revenue Performance Chart -->
                    <div class="chart-card p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="bi bi-currency-dollar mr-2 text-green-600"></i>
                                Performa Pendapatan
                            </h3>
                            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">Monthly</span>
                        </div>
                        <div class="relative">
                            <canvas id="revenueChart" class="w-full h-64"></canvas>
                        </div>

                    </div>
                </div>
            @endif

            <!-- Business Intelligence Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 form-enter">
                <!-- Notifications & Alerts -->
                <div class="lg:col-span-2 notification-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="bi bi-bell mr-2 text-gray-600"></i>
                            Notifikasi & Peringatan
                        </h3>
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                            {{ count($lowStockProducts ?? []) + count($recentOrders ?? []) }} items
                        </span>
                    </div>
                    
                    <div class="space-y-3 max-h-72 overflow-y-auto">
                        @forelse($lowStockProducts ?? [] as $product)
                            @if(is_object($product))
                                <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="p-2 bg-red-100 rounded-lg mr-3">
                                        <i class="bi bi-exclamation-triangle text-red-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-red-800">Stok Menipis</p>
                                        <p class="text-xs text-red-600">
                                            {{ data_get($product, 'name', '-') }} tersisa {{ data_get($product, 'current_stock', 0) }} unit
                                        </p>
                                    </div>
                                    <a href="{{ route('inventory.adjust.form', $product) }}" 
                                    class="text-xs bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
                                        Sesuaikan
                                    </a>
                                </div>
                            @endif
                        @empty
                            <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="p-2 bg-green-100 rounded-lg mr-3">
                                    <i class="bi bi-check-circle text-green-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-green-800">All Good!</p>
                                    <p class="text-xs text-green-600">Tidak ada produk dengan stok menipis</p>
                                </div>
                            </div>
                        @endforelse

                        @forelse($recentOrders ?? [] as $order)
                            @if(is_object($order))
                                <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                        <i class="bi bi-cart-plus text-blue-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-blue-800">Pesanan Baru</p>
                                        <p class="text-xs text-blue-600">
                                            #{{ data_get($order, 'id', '-') }} oleh {{ data_get($order, 'customer_name', '-') }}
                                        </p>
                                    </div>
                                    <a href="{{ route('admin.public-orders.show', $order->id) }}" 
                                       class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                        Lihat
                                    </a>
                                </div>
                            @endif
                        @empty
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="notification-card p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-lightning-charge mr-2 text-gray-600"></i>
                        Aksi Cepat
                    </h3>
                    
                    <div class="space-y-3">
                        @if(auth()->user()->hasRole(['owner', 'admin']))
                            <!-- Aksi untuk Owner & Admin -->
                            <a href="{{ route('products.create') }}" 
                               class="w-full bg-gray-800 hover:bg-gray-900 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Tambah Produk
                            </a>

                            <a href="{{ route('inventory.index') }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-box-seam mr-2"></i>
                                Kelola Inventaris
                            </a>

                            <a href="{{ route('admin.public-orders.index') }}" 
                               class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-globe2 mr-2"></i>
                                Pesanan Online
                            </a>

                            <a href="{{ route('sales.index') }}"
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-cash-coin mr-2"></i>
                                Penjualan
                            </a>

                            <a href="{{ route('reports.sales') }}" 
                               class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-bar-chart mr-2"></i>
                                Laporan
                            </a>
                        @elseif(auth()->user()->hasRole('kasir'))
                            <!-- Aksi untuk Kasir -->
                            <a href="{{ route('products.index') }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-eye mr-2"></i>
                                Lihat Produk
                            </a>
                            
                            <a href="{{ route('inventory.index') }}" 
                               class="w-full bg-teal-600 hover:bg-teal-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-box-seam mr-2"></i>
                                Lihat Inventaris
                            </a>
                            
                            <a href="{{ route('admin.public-orders.index') }}" 
                               class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-globe2 mr-2"></i>
                                Pesanan Online
                            </a>

                            <a href="{{ route('sales.index') }}" 
                               class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-cash-coin mr-2"></i>
                                Penjualan
                            </a>

                            <a href="{{ route('reports.sales') }}" 
                               class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-bar-chart mr-2"></i>
                                Laporan
                            </a>
                        @elseif(auth()->user()->hasRole('karyawan'))
                            <!-- Aksi untuk Karyawan -->
                            <a href="{{ route('products.index') }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-eye mr-2"></i>
                                Lihat Produk
                            </a>
                            
                            <a href="{{ route('inventory.index') }}" 
                               class="w-full bg-teal-600 hover:bg-teal-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-box-seam mr-2"></i>
                                Kelola Inventaris
                            </a>
                            
                            <a href="{{ route('admin.public-orders.index') }}" 
                               class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-globe2 mr-2"></i>
                                Pesanan Online
                            </a>

                            <a href="{{ route('reports.stock') }}" 
                               class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-bar-chart mr-2"></i>
                                Laporan Stok
                            </a>
                        @elseif(auth()->user()->hasRole('customers service'))
                            <!-- Aksi untuk Customer Service -->
                            <a href="{{ route('online-customers.index') }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-people-fill mr-2"></i>
                                Kelola Pelanggan
                            </a>
                            
                            <a href="{{ route('admin.public-orders.index') }}" 
                               class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-globe2 mr-2"></i>
                                Pesanan Online
                            </a>

                            <a href="{{ route('reports.customers') }}" 
                               class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-bar-chart mr-2"></i>
                                Laporan Pelanggan
                            </a>
                        @else
                            <!-- Default Aksi -->
                            <a href="{{ route('products.index') }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-eye mr-2"></i>
                                Lihat Produk
                            </a>
                            
                            <a href="{{ route('admin.public-orders.index') }}" 
                               class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                                <i class="bi bi-globe2 mr-2"></i>
                                Pesanan Online
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Performance & Ready Stock -->
            <div class="notification-card p-6 form-enter">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="bi bi-flower2 mr-2 text-gray-600"></i>
                        Produk Ready Stock & Performance
                    </h3>
                    <div class="flex items-center space-x-4">
                        <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded">
                            {{ count($readyProducts ?? []) }} Produk Tersedia
                        </span>
                        <a href="{{ url('/product-seikatbungo') }}" target="_blank" 
                           class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded hover:bg-blue-200 transition-colors">
                            <i class="bi bi-globe mr-1"></i>
                            Link Publik
                        </a>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <i class="bi bi-info-circle text-blue-500 mr-2 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-blue-800 mb-1">Informasi Link Publik</p>
                            <p class="text-xs text-blue-600 mb-2">
                                Link ini dapat dibagikan kepada pelanggan untuk melihat produk ready stock tanpa perlu login:
                            </p>
                            <a href="{{ url('/product-seikatbungo') }}" target="_blank" 
                               class="text-xs text-blue-700 underline hover:text-blue-900 break-all">
                                {{ url('/product-seikatbungo') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="overflow-x-auto">
                        <table class="modern-table w-full">
                            <thead>
                                <tr>
                                    <th class="text-left">Nama Produk</th>
                                    <th class="text-left">Kategori</th>
                                    <th class="text-center">Stok Tersedia</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Penjualan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                        <tbody>
                            @forelse(($readyProducts ?? []) as $product)
                                @if(is_object($product))
                                    <tr>
                                        <td>
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="bi bi-flower1 text-gray-600"></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-800">{{ data_get($product, 'name', '-') }}</p>
                                                    <p class="text-xs text-gray-500">{{ data_get($product, 'code', '-') }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">
                                                {{ data_get($product, 'category.name', '-') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="flex flex-col items-center">
                                                <span class="text-lg font-bold text-gray-800">{{ data_get($product, 'current_stock', 0) }}</span>
                                                <span class="text-xs text-gray-500">{{ data_get($product, 'base_unit', 'pcs') }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @php
        $stock = data_get($product, 'current_stock', 0);
        $minStock = data_get($product, 'min_stock', 5);
                                            @endphp
                                            @if($stock > $minStock * 2)
                                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 rounded text-xs">
                                                    <i class="bi bi-check-circle mr-1"></i>
                                                    Baik
                                                </span>
                                            @elseif($stock > $minStock)
                                                <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">
                                                    <i class="bi bi-exclamation-circle mr-1"></i>
                                                    Sedang
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 rounded text-xs">
                                                    <i class="bi bi-x-circle mr-1"></i>
                                                    Menipis
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="flex flex-col items-center">
                                                @php
        // Dapatkan status performa dari atribut produk
        $soldCount = $product->total_sold ?? 0; // Pastikan ada field total_sold di tabel products
        
        // Kategorisasi performance berdasarkan penjualan
        if ($soldCount >= 20) {
            $performance = 'Laris';
            $percentage = 85;
            $color = 'green';
            $icon = 'bi-fire';
        } elseif ($soldCount >= 10) {
            $performance = 'Normal';
            $percentage = 60;
            $color = 'blue';
            $icon = 'bi-graph-up';
        } else {
            $performance = 'Kurang';
            $percentage = 25;
            $color = 'gray';
            $icon = 'bi-graph-down';
        }
                                                @endphp
                                                <span class="text-xs text-{{ $color }}-600 font-medium">
                                                    <i class="bi {{ $icon }} {{ $performance === 'Laris' ? 'text-orange-500' : '' }}"></i> 
                                                    {{ $performance }}
                                                </span>
                                                <div class="w-12 h-1 bg-gray-200 rounded-full mt-1">
                                                    <div class="h-full bg-{{ $color }}-500 rounded-full" style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <span class="text-xs text-gray-500 mt-1">
                                                    {{ number_format($soldCount) }} terjual
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('products.show', $product) }}" 
                                                   class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors text-xs">
                                                    <i class="bi bi-eye mr-1"></i>
                                                    Detail
                                                </a>
                                                <a href="{{ route('inventory.history', $product) }}" 
                                                   class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-xs">
                                                    <i class="bi bi-clock-history mr-1"></i>
                                                    History
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8">
                                        <div class="flex flex-col items-center">
                                            <i class="bi bi-inbox text-4xl text-gray-300 mb-2"></i>
                                            <p class="text-gray-500">Tidak ada produk ready stock saat ini</p>
                                            <a href="{{ route('inventory.index') }}" 
                                               class="text-blue-600 hover:text-blue-700 text-sm mt-2">
                                                Kelola Inventaris →
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Clean chart configuration
        Chart.defaults.font.family = 'system-ui, -apple-system, sans-serif';
        Chart.defaults.color = '#6B7280';

        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const productCtx = document.getElementById('productChart').getContext('2d');
        const bouquetCtx = document.getElementById('bouquetChart').getContext('2d');

        // Sales Performance Chart (Line Chart)
        new Chart(salesCtx, {
            type: 'line',
            data: @json($salesChartData ?? ['labels' => [], 'datasets' => []]),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(55, 65, 81, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 6,
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return 'Tanggal: ' + context[0].label;
                            },
                            label: function(context) {
                                return 'Penjualan: ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                },
                elements: {
                    line: { 
                        borderWidth: 2, 
                        borderColor: '#3B82F6',
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(59, 130, 246, 0.1)'
                    },
                    point: { 
                        radius: 4, 
                        backgroundColor: '#3B82F6',
                        borderColor: '#fff',
                        borderWidth: 2,
                        hoverRadius: 6
                    }
                },
                scales: {
                    x: { 
                        ticks: { color: '#6B7280', font: { size: 11 } },
                        grid: { display: false }
                    },
                    y: { 
                        ticks: { 
                            color: '#6B7280',
                            font: { size: 11 },
                            callback: function(value) {
                                return new Intl.NumberFormat('id-ID').format(value);
                            }
                        },
                        grid: { color: '#F3F4F6' }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Orders Performance Chart (Bar Chart)
        new Chart(ordersCtx, {
            type: 'bar',
            data: @json($ordersChartData ?? ['labels' => [], 'datasets' => []]),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(55, 65, 81, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 6,
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return 'Tanggal: ' + context[0].label;
                            },
                            label: function(context) {
                                return 'Pesanan: ' + context.parsed.y + ' order';
                            }
                        }
                    }
                },
                elements: {
                    bar: {
                        backgroundColor: '#8B5CF6',
                        borderRadius: 4,
                        borderSkipped: false,
                    }
                },
                scales: {
                    x: { 
                        ticks: { color: '#6B7280', font: { size: 11 } },
                        grid: { display: false }
                    },
                    y: { 
                        ticks: { 
                            color: '#6B7280',
                            font: { size: 11 },
                            stepSize: 1
                        },
                        grid: { color: '#F3F4F6' }
                    }
                }
            }
        });

        // Revenue Performance Chart (Area Chart) - DATA VALID DARI CONTROLLER
        const revenueData = {
            labels: {!! json_encode($revenueChartData['labels']) !!},
            datasets: [{
                label: 'Pendapatan Harian',
                data: {!! json_encode($revenueChartData['datasets'][0]['data']) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderColor: '#10B981',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10B981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        };

        new Chart(revenueCtx, {
            type: 'line',
            data: revenueData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(55, 65, 81, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 6,
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return 'Bulan: ' + context[0].label;
                            },
                            label: function(context) {
                                return 'Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    x: { 
                        ticks: { color: '#6B7280', font: { size: 11 } },
                        grid: { display: false }
                    },
                    y: { 
                        beginAtZero: true,
                        ticks: { 
                            color: '#6B7280',
                            font: { size: 11 },
                            callback: function(value) {
                                if (value === 0) return 'Rp 0';
                                return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(value / 1000) + 'rb';
                            }
                        },
                        grid: { color: '#F3F4F6' }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Product Performance Chart (Doughnut Chart) - DATA REAL DARI CONTROLLER
        const productData = {
            labels: {!! json_encode($productChartData['labels']) !!},
            datasets: [{
                data: {!! json_encode($productChartData['data']) !!},
                backgroundColor: [
                    '#FF6B6B', // Merah muda
                    '#4ECDC4', // Teal
                    '#45B7D1', // Biru
                    '#96CEB4', // Hijau mint
                    '#FFEAA7', // Kuning
                    '#DDA0DD', // Plum
                    '#F0E68C', // Khaki
                    '#FFB6C1'  // Light Pink
                ],
                borderWidth: 2,
                borderColor: '#fff',
                hoverBorderWidth: 3,
                hoverBorderColor: '#fff'
            }]
        };

        new Chart(productCtx, {
            type: 'doughnut',
            data: productData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            font: { size: 11 },
                            color: '#6B7280'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(55, 65, 81, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return [
                                    `Kategori: ${label}`,
                                    `Terjual: ${new Intl.NumberFormat('id-ID').format(value)} unit`,
                                    `(${percentage}% dari total penjualan)`
                                ];
                            }
                        }
                    }
                },
                cutout: '60%',
                elements: {
                    arc: {
                        borderWidth: 2
                    }
                }
            }
        });

        // Bouquet Performance Chart (Bar Chart) - DATA REAL DARI CONTROLLER
        const bouquetDataFromController = {!! json_encode($bouquetChartData) !!};
        
        // Fallback data jika tidak ada data bouquet
        const bouquetData = {
            labels: bouquetDataFromController.labels.length > 0 ? bouquetDataFromController.labels : ['Tidak ada data bouquet', 'Silakan tambah produk', 'dengan kata "bouquet"', 'atau "buket"', 'di nama produk'],
            datasets: [{
                label: 'Jumlah Terjual',
                data: bouquetDataFromController.data.length > 0 ? bouquetDataFromController.data : [0, 0, 0, 0, 0],
                backgroundColor: [
                    '#FF6B9D', // Pink
                    '#C44569', // Dark Pink
                    '#F8B500', // Orange
                    '#F39C12', // Yellow Orange
                    '#8E44AD', // Purple
                    '#E74C3C', // Red
                    '#3498DB', // Blue
                    '#2ECC71'  // Green
                ],
                borderColor: [
                    '#E91E63',
                    '#AD1457',
                    '#FF8F00',
                    '#F57C00',
                    '#7B1FA2',
                    '#C0392B',
                    '#2980B9',
                    '#27AE60'
                ],
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false,
            }]
        };

        new Chart(bouquetCtx, {
            type: 'bar',
            data: bouquetData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(55, 65, 81, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 6,
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return 'Produk: ' + context[0].label;
                            },
                            label: function(context) {
                                if (bouquetDataFromController.data.length > 0) {
                                    return 'Terjual: ' + context.parsed.y + ' unit';
                                } else {
                                    return 'Tidak ada data';
                                }
                            }
                        }
                    }
                },
                scales: {
                    x: { 
                        ticks: { 
                            color: '#6B7280', 
                            font: { size: 10 },
                            maxRotation: 45
                        },
                        grid: { display: false }
                    },
                    y: { 
                        ticks: { 
                            color: '#6B7280',
                            font: { size: 11 },
                            stepSize: 1,
                            callback: function(value) {
                                return Math.floor(value); // Hanya tampilkan bilangan bulat
                            }
                        },
                        grid: { color: '#F3F4F6' }
                    }
                }
            }
        });

        // Simple loading animation
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stats-card, .chart-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
            });
        });
    </script>
</x-app-layout>