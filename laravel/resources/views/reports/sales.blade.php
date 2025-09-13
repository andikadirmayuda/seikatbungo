{{-- Sales Report View --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-pink-100 rounded-xl mr-3">
                    <i class="bi bi-shop text-pink-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Penjualan</h1>
                    <p class="text-sm text-gray-500 mt-1">Analisis data penjualan langsung di toko</p>
                </div>
            </div>
            <form method="GET"
                class="flex flex-wrap items-end gap-3 bg-white p-3 rounded-xl shadow-sm border border-gray-100">
                <div class="flex flex-wrap gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            <i class="bi bi-calendar3 mr-1 text-pink-500"></i>
                            Dari Tanggal
                        </label>
                        <input type="date" name="start_date" value="{{ $start }}"
                            class="px-3 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            <i class="bi bi-calendar3 mr-1 text-pink-500"></i>
                            Sampai Tanggal
                        </label>
                        <input type="date" name="end_date" value="{{ $end }}"
                            class="px-3 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            <i class="bi bi-funnel mr-1 text-pink-500"></i>
                            Status
                        </label>
                        <select name="status"
                            class="px-3 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all text-sm">
                            <option value="">Semua Status</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="h-9 px-4 bg-pink-500 hover:bg-pink-600 text-white text-sm font-semibold rounded-lg transition-all duration-200 flex items-center">
                        <i class="bi bi-search mr-1.5"></i>
                        Filter
                    </button>
                    <a href="{{ route('reports.sales') }}"
                        class="h-9 px-4 bg-white border border-gray-200 hover:border-pink-500 hover:bg-pink-50 text-gray-700 hover:text-pink-600 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center">
                        <i class="bi bi-arrow-counterclockwise mr-1.5"></i>
                        Reset
                    </a>
                    <a href="{{ route('reports.sales.pdf', request()->all()) }}"
                        class="h-9 px-4 bg-white border border-gray-200 hover:border-red-500 hover:bg-red-50 text-gray-700 hover:text-red-600 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center">
                        <i class="bi bi-file-pdf mr-1.5"></i>
                        Export PDF
                    </a>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-8 gradient-bg min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cards Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Total Penjualan Card -->
                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i class="bi bi-shop text-8xl text-green-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                                <i class="bi bi-shop text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Total Penjualan</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalSales }}</p>
                        <div class="flex items-center mt-2">
                            <i class="bi bi-receipt text-xs text-green-500 mr-1"></i>
                            <p class="text-xs text-gray-500">Transaksi penjualan</p>
                        </div>
                    </div>
                </div>

                <!-- Total Pendapatan Card -->
                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i class="bi bi-cash-stack text-8xl text-blue-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                <i class="bi bi-cash-stack text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Total Pendapatan</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($totalRevenue, 0, ',', '.') }}
                        </p>
                        <div class="flex items-center mt-2">
                            <i class="bi bi-graph-up text-xs text-blue-500 mr-1"></i>
                            <p class="text-xs text-gray-500">Pendapatan kotor</p>
                        </div>
                    </div>
                </div>

                <!-- Rata-rata Transaksi Card -->
                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i class="bi bi-calculator text-8xl text-purple-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                <i class="bi bi-calculator text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Rata-rata Transaksi</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">
                            Rp{{ number_format($averageTransaction, 0, ',', '.') }}</p>
                        <div class="flex items-center mt-2">
                            <i class="bi bi-currency-dollar text-xs text-purple-500 mr-1"></i>
                            <p class="text-xs text-gray-500">Nilai rata-rata per transaksi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Penjualan -->
            <div class="section-card">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-pink-50 rounded-lg flex items-center justify-center mr-3">
                                <i class="bi bi-table text-lg text-pink-500"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Data Penjualan</h3>
                                <p class="text-gray-500 text-sm">Riwayat transaksi penjualan langsung di toko</p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="bi bi-calendar3-range mr-2"></i>
                            Periode: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($end)->format('d M Y') }}
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-left">
                                    <div
                                        class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-receipt text-pink-400"></i>
                                        <span>No. Transaksi</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <div
                                        class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-calendar3 text-pink-400"></i>
                                        <span>Tanggal</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <div
                                        class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-credit-card text-pink-400"></i>
                                        <span>Metode Bayar</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <div
                                        class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-cart3 text-pink-400"></i>
                                        <span>Item</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <div
                                        class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-cash text-pink-400"></i>
                                        <span>Total</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <div
                                        class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-check-circle text-pink-400"></i>
                                        <span>Status</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($sales as $sale)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex items-center justify-center w-8 h-8 bg-pink-100 rounded-lg mr-3">
                                                <i class="bi bi-receipt text-pink-600 text-sm"></i>
                                            </div>
                                            <span
                                                class="text-sm font-medium text-gray-900">#{{ $sale->order_number }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $sale->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $sale->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                    @if($sale->payment_method === 'cash')
                                                                        bg-green-100 text-green-800
                                                                    @else
                                                                        bg-blue-100 text-blue-800
                                                                    @endif">
                                            <i
                                                class="bi {{ $sale->payment_method === 'cash' ? 'bi-cash' : 'bi-credit-card' }} mr-1"></i>
                                            {{ ucfirst($sale->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            @foreach($sale->items as $item)
                                                {{ $item->product->name }} ({{ $item->quantity }}x)
                                                @if(!empty($item->price_type))
                                                    {{ $item->price_type }}
                                                @endif<br>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            Rp{{ number_format($sale->total, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                        @if($sale->status === 'completed')
                                                                            bg-green-100 text-green-800
                                                                        @else
                                                                            bg-red-100 text-green-800
                                                                        @endif
                                                                    ">
                                            <i
                                                class="bi {{ $sale->status === 'completed' ? 'bi-check-circle' : 'bi-check-circle' }} mr-1"></i>
                                            {{ $sale->status === 'completed' ? 'Selesai' : 'selesai' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="bi bi-inbox text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-gray-500 text-sm">Tidak ada data penjualan ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination dihapus agar semua data tampil tanpa pagination --}}
            </div>
        </div>
    </div>
</x-app-layout>