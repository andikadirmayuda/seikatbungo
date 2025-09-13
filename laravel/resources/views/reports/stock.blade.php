<x-app-layout>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #fdf2f8 0%, #ffffff 50%, #f0fdf4 100%);
        }

        .section-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(244, 63, 94, 0.1);
            transition: all 0.3s ease;
        }

        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(244, 63, 94, 0.1);
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .form-enter {
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-pink-100 rounded-xl mr-3">
                    <i class="bi bi-boxes text-pink-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Stok Produk</h1>
                    <p class="text-sm text-gray-500 mt-1">Monitoring dan analisis pergerakan stok produk</p>
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
                        <input type="date" name="start_date" value="{{ $start ?? '' }}"
                            class="px-3 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            <i class="bi bi-calendar3 mr-1 text-pink-500"></i>
                            Sampai Tanggal
                        </label>
                        <input type="date" name="end_date" value="{{ $end ?? '' }}"
                            class="px-3 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all text-sm">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="h-9 px-4 bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center">
                        <i class="bi bi-funnel mr-1.5"></i>
                        Filter
                    </button>
                    <a href="{{ url()->current() }}"
                        class="h-9 px-4 bg-white border border-gray-200 hover:border-pink-500 hover:bg-pink-50 text-gray-700 hover:text-pink-600 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center">
                        <i class="bi bi-arrow-counterclockwise mr-1.5"></i>
                        Reset
                    </a>
                    <a href="{{ route('reports.stock.pdf', request()->all()) }}"
                        class="h-9 px-4 bg-white border border-gray-200 hover:border-red-500 hover:bg-red-50 text-gray-700 hover:text-red-600 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center">
                        <i class="bi bi-file-pdf mr-1.5"></i>
                        Export PDF
                    </a>
                </div>
            </form>
            </a>
        </div>
        </form>
        </div>
    </x-slot>

    <div class="py-8 gradient-bg min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cards Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Produk Card -->
                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i class="bi bi-box text-8xl text-purple-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                <i class="bi bi-box text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Total Produk</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">{{ $products->count() }}</p>
                        <div class="flex items-center mt-2">
                            <i class="bi bi-check-circle text-xs text-purple-500 mr-1"></i>
                            <p class="text-xs text-gray-500">Produk aktif</p>
                        </div>
                    </div>
                </div>

                <!-- Total Stok Masuk Card -->
                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i
                            class="bi bi-arrow-down-circle text-8xl text-green-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                                <i class="bi bi-arrow-down-circle text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Total Stok Masuk</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $products->sum(fn($p) => $rekap[$p->id]['masuk'] ?? 0) }}
                        </p>
                        <div class="flex items-center mt-2">
                            <i class="bi bi-plus-circle text-xs text-green-500 mr-1"></i>
                            <p class="text-xs text-gray-500">Barang masuk</p>
                        </div>
                    </div>
                </div>

                <!-- Total Stok Keluar Card -->
                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i
                            class="bi bi-arrow-up-circle text-8xl text-red-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg">
                                <i class="bi bi-arrow-up-circle text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Total Stok Keluar</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $products->sum(fn($p) => $rekap[$p->id]['keluar'] ?? 0) }}
                        </p>
                        <div class="flex items-center mt-2">
                            <i class="bi bi-dash-circle text-xs text-red-500 mr-1"></i>
                            <p class="text-xs text-gray-500">Barang keluar</p>
                        </div>
                    </div>
                </div>

                <!-- Total Penyesuaian Card -->
                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i class="bi bi-gear text-8xl text-blue-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                <i class="bi bi-gear text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Total Penyesuaian</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $products->sum(fn($p) => $rekap[$p->id]['penyesuaian'] ?? 0) }}
                        </p>
                        <div class="flex items-center mt-2">
                            <i class="bi bi-arrow-repeat text-xs text-blue-500 mr-1"></i>
                            <p class="text-xs text-gray-500">Adjustment stok</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabel Rekap Stok -->
            <div class="section-card p-6 mb-8">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-8 h-8 bg-pink-100 rounded-lg mr-3">
                        <i class="bi bi-table text-pink-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Rekap Stok Produk</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Produk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stok Masuk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stok Keluar</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Penyesuaian</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stok Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-lg mr-3">
                                                <i class="bi bi-box text-purple-600 text-sm"></i>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $product->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="bi bi-arrow-down-circle text-green-500 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900">{{ $rekap[$product->id]['masuk'] ?? 0 }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="bi bi-arrow-up-circle text-red-500 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900">{{ $rekap[$product->id]['keluar'] ?? 0 }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="bi bi-gear text-blue-500 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900">{{ $rekap[$product->id]['penyesuaian'] ?? 0 }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                                @if(($rekap[$product->id]['stok_akhir'] ?? $product->current_stock) > 10)
                                                                    bg-green-100 text-green-800
                                                                @elseif(($rekap[$product->id]['stok_akhir'] ?? $product->current_stock) > 5)
                                                                    bg-yellow-100 text-yellow-800
                                                                @else
                                                                    bg-red-100 text-red-800
                                                                @endif
                                                            ">
                                            {{ $rekap[$product->id]['stok_akhir'] ?? $product->current_stock }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="bi bi-inbox text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-gray-500 text-sm">Tidak ada data produk ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Log Perubahan Stok -->
            <div class="section-card p-6">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-8 h-8 bg-pink-100 rounded-lg mr-3">
                        <i class="bi bi-clock-history text-pink-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Log Perubahan Stok (Terbaru)</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Produk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Perubahan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg mr-3">
                                                <i class="bi bi-calendar3 text-blue-600 text-sm"></i>
                                            </div>
                                            <div class="text-sm text-gray-900">{{ $log->created_at->format('d-m-Y H:i') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-lg mr-3">
                                                <i class="bi bi-box text-purple-600 text-sm"></i>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">{{ $log->product->name ?? '-' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                                @if($log->qty > 0)
                                                                    bg-green-100 text-green-800
                                                                @elseif($log->qty < 0)
                                                                    bg-red-100 text-red-800
                                                                @else
                                                                    bg-gray-100 text-gray-800
                                                                @endif
                                                            ">
                                            @if($log->qty > 0)
                                                <i class="bi bi-plus-circle mr-1"></i>
                                            @elseif($log->qty < 0)
                                                <i class="bi bi-dash-circle mr-1"></i>
                                            @else
                                                <i class="bi bi-circle mr-1"></i>
                                            @endif
                                            {{ $log->qty }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $log->description }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="bi bi-clock-history text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-gray-500 text-sm">Tidak ada log perubahan stok ditemukan</p>
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
</x-app-layout>