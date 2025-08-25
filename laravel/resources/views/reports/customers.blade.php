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
                    <i class="bi bi-people text-pink-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Pelanggan Online</h1>
                    <p class="text-sm text-gray-500 mt-1">Data pelanggan dari pesanan online</p>
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
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-8 gradient-bg min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cards Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Total Pelanggan Card -->
                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i class="bi bi-people text-8xl text-pink-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg">
                                <i class="bi bi-people text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Total Pelanggan Online</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalCustomer }}</p>
                        <div class="flex items-center mt-2">
                            <i class="bi bi-calendar3 text-xs text-gray-400 mr-1"></i>
                            <p class="text-xs text-gray-500">Periode yang dipilih</p>
                        </div>
                    </div>
                </div>

                <!-- Total Order Card -->
                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i class="bi bi-bag-check text-8xl text-blue-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                <i class="bi bi-bag-check text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Total Order</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalOrder }}</p>
                        <div class="flex items-center mt-2">
                            <i class="bi bi-check-circle text-xs text-blue-500 mr-1"></i>
                            <p class="text-xs text-gray-500">Order berhasil</p>
                        </div>
                    </div>
                </div>

                <!-- Pelanggan Terbaik Card -->
                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i class="bi bi-trophy text-8xl text-yellow-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg">
                                <i class="bi bi-trophy text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Pelanggan Terbaik</p>
                        </div>
                        @if($topCustomer)
                            <p class="text-lg font-bold text-gray-800 line-clamp-1">{{ $topCustomer->name }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex items-center">
                                    <i class="bi bi-bag-check text-xs text-yellow-500 mr-1"></i>
                                    <span class="text-sm text-gray-600">{{ $topCustomer->orders_count }} order</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="bi bi-cash text-xs text-gray-400 mr-1"></i>
                                    <span
                                        class="text-xs text-gray-500">Rp{{ number_format($topCustomer->total_spent, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-400">Tidak ada data</p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Tabel Pelanggan -->
            <div class="section-card">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-pink-50 rounded-lg flex items-center justify-center mr-3">
                                <i class="bi bi-people text-lg text-pink-500"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Daftar Pelanggan Online</h3>
                                <p class="text-gray-500 text-sm">Data pelanggan dikelompokkan berdasarkan nomor WhatsApp
                                    pada periode yang dipilih</p>
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
                                <th class="px-6 py-4 text-left" style="width: 80px;">
                                    <div
                                        class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-hash text-pink-400"></i>
                                        <span>No</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <div
                                        class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-person text-pink-400"></i>
                                        <span>Nama Pelanggan</span>
                                        <div class="flex items-center ml-2 text-xs text-gray-400">
                                            <i class="bi bi-info-circle mr-1"></i>
                                            <span class="normal-case">Berdasarkan No. WA</span>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <div
                                        class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-whatsapp text-pink-400"></i>
                                        <span>No. WhatsApp</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <div
                                        class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-bag-check text-pink-400"></i>
                                        <span>Jumlah Order</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <div
                                        class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-cash text-pink-400"></i>
                                        <span>Total Belanja</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($customers as $index => $customer)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm text-gray-900">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-start">
                                            <div
                                                class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                                <i class="bi bi-person text-pink-600 text-sm"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900 mb-1">{{ $customer->name }}
                                                </div>
                                                @if($customer->names_count > 1)
                                                    <div class="space-y-1">
                                                        <div class="text-xs text-gray-500">
                                                            <i class="bi bi-info-circle mr-1"></i>
                                                            {{ $customer->names_count }} variasi nama:
                                                        </div>
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($customer->all_names as $name)
                                                                <span
                                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                                                {{ $name === $customer->name ? 'bg-pink-100 text-pink-700' : 'bg-gray-100 text-gray-600' }}">
                                                                    {{ $name }}
                                                                    @if($name === $customer->name)
                                                                        <i class="bi bi-star-fill ml-1 text-xs"></i>
                                                                    @endif
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($customer->phone && $customer->phone !== '-')
                                                <div class="flex flex-col">
                                                    <span
                                                        class="bg-green-100 text-green-700 text-sm px-2.5 py-0.5 rounded-lg flex items-center">
                                                        <i class="bi bi-whatsapp mr-1"></i>
                                                        {{ $customer->phone }}
                                                    </span>
                                                    @if($customer->names_count > 1)
                                                        <span class="text-xs text-gray-500 mt-1">
                                                            <i class="bi bi-people-fill mr-1"></i>
                                                            {{ $customer->names_count }} nama berbeda
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-sm">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                            <i class="bi bi-bag-check mr-1"></i>
                                            {{ $customer->orders_count }} order
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-gray-900">
                                            Rp{{ number_format($customer->total_spent, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                                <i class="bi bi-people text-3xl text-gray-300"></i>
                                            </div>
                                            <p class="text-gray-500 font-medium">Tidak ada data pelanggan</p>
                                            <p class="text-sm text-gray-400 mt-1">pada periode ini</p>
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