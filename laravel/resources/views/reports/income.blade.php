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

    {{-- <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-pink-100 rounded-xl mr-3">
                    <i class="bi bi-graph-up-arrow text-pink-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Pendapatan</h1>
                    <p class="text-sm text-gray-500 mt-1">Analisis pendapatan dari penjualan dan pemesanan online</p>
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
                    <a href="{{ route('reports.income.pdf', request()->all()) }}"
                        class="h-9 px-4 bg-white border border-gray-200 hover:border-red-500 hover:bg-red-50 text-gray-700 hover:text-red-600 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center">
                        <i class="bi bi-file-pdf mr-1.5"></i>
                        Export PDF
                    </a>
                </div>
            </form>
        </div>
    </x-slot> --}}

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-pink-100 rounded-xl mr-3">
                    <i class="bi bi-bag-heart text-pink-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Pendapatan</h1>
                    {{-- <p class="text-sm text-gray-500 mt-1">Ringkasan data pemesanan dari pelanggan online</p> --}}
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
                    <a href="{{ route('reports.income.pdf', request()->all()) }}"
                        class="h-9 px-4 bg-white border border-gray-200 hover:border-red-500 hover:bg-red-50 text-gray-700 hover:text-red-600 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center">
                        <i class="bi bi-file-pdf mr-1.5"></i>
                        Export PDF
                    </a>
                </div>
            </form>
    </x-slot>

    <div class="py-8 gradient-bg min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cards Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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
                        <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($totalPenjualan, 0, ',', '.') }}
                        </p>
                        <div class="flex items-center mt-2">
                            <i class="bi bi-arrow-up-right text-xs text-green-500 mr-1"></i>
                            <p class="text-xs text-gray-500">Penjualan langsung</p>
                        </div>
                    </div>
                </div>

                <!-- Total Pemesanan Card -->
                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i class="bi bi-laptop text-8xl text-blue-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                <i class="bi bi-laptop text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Total Pemesanan</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($totalPemesanan, 0, ',', '.') }}
                        </p>
                        <div class="flex items-center mt-2">
                            <i class="bi bi-globe text-xs text-blue-500 mr-1"></i>
                            <p class="text-xs text-gray-500">Pemesanan online</p>
                        </div>
                    </div>
                </div>

                <!-- Total Pendapatan Card -->
                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i class="bi bi-cash-stack text-8xl text-purple-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                <i class="bi bi-cash-stack text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Total Pendapatan</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
                        </p>
                        <div class="flex items-center mt-2">
                            <i class="bi bi-check-circle text-xs text-purple-500 mr-1"></i>
                            <p class="text-xs text-gray-500">Gabungan semua</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabel Pendapatan Harian -->
            <div class="section-card p-6 mb-8">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-8 h-8 bg-pink-100 rounded-lg mr-3">
                        <i class="bi bi-calendar-day text-pink-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Pendapatan Harian</h2>
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
                                    Penjualan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pemesanan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($harian as $tgl => $row)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg mr-3">
                                                <i class="bi bi-calendar-event text-blue-600 text-sm"></i>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">{{ $tgl }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="bi bi-shop text-green-500 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900">Rp{{ number_format($row['penjualan'], 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="bi bi-laptop text-blue-500 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900">Rp{{ number_format($row['pemesanan'], 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            <i class="bi bi-cash-stack mr-1"></i>
                                            Rp{{ number_format($row['penjualan'] + $row['pemesanan'], 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="bi bi-calendar-x text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-gray-500 text-sm">Tidak ada data pendapatan harian</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Tabel Pendapatan Mingguan -->
            <div class="section-card p-6 mb-8">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-8 h-8 bg-pink-100 rounded-lg mr-3">
                        <i class="bi bi-calendar-week text-pink-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Pendapatan Mingguan</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Minggu Mulai</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Penjualan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pemesanan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($mingguan as $minggu => $row)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex items-center justify-center w-8 h-8 bg-orange-100 rounded-lg mr-3">
                                                <i class="bi bi-calendar-range text-orange-600 text-sm"></i>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">{{ $minggu }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="bi bi-shop text-green-500 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900">Rp{{ number_format($row['penjualan'], 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="bi bi-laptop text-blue-500 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900">Rp{{ number_format($row['pemesanan'], 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            <i class="bi bi-cash-stack mr-1"></i>
                                            Rp{{ number_format($row['penjualan'] + $row['pemesanan'], 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="bi bi-calendar-x text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-gray-500 text-sm">Tidak ada data pendapatan mingguan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Tabel Pendapatan Bulanan -->
            <div class="section-card p-6">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-8 h-8 bg-pink-100 rounded-lg mr-3">
                        <i class="bi bi-calendar-month text-pink-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Pendapatan Bulanan</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bulan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Penjualan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pemesanan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bulanan as $bulan => $row)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex items-center justify-center w-8 h-8 bg-indigo-100 rounded-lg mr-3">
                                                <i class="bi bi-calendar-month-fill text-indigo-600 text-sm"></i>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">{{ $bulan }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="bi bi-shop text-green-500 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900">Rp{{ number_format($row['penjualan'], 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="bi bi-laptop text-blue-500 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900">Rp{{ number_format($row['pemesanan'], 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            <i class="bi bi-cash-stack mr-1"></i>
                                            Rp{{ number_format($row['penjualan'] + $row['pemesanan'], 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="bi bi-calendar-x text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-gray-500 text-sm">Tidak ada data pendapatan bulanan</p>
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