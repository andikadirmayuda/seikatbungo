<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-700">
                <i class="bi bi-box-seam mr-2"></i>
                Manajemen Inventaris
            </h1>
            {{-- @if(!auth()->user()->hasRole('kasir')) --}}
            @if(!auth()->user()->hasRole(['kasir', 'karyawan']))
                <div class="flex gap-3">
                    <a href="{{ route('inventory.adjust.form') }}"
                        class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-xl transition-all duration-200">
                        <i class="bi bi-pencil-square mr-1"></i>
                        Sesuaikan Stok
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #fdf2f8 0%, #ffffff 50%, #f0fdf4 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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

        .table-hover:hover {
            background: linear-gradient(135deg, #fdf2f8 0%, #ffffff 100%);
            transform: scale(1.001);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-in {
            background: rgba(34, 197, 94, 0.1);
            color: rgb(34, 197, 94);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .status-out {
            background: rgba(239, 68, 68, 0.1);
            color: rgb(239, 68, 68);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .status-adjust {
            background: rgba(59, 130, 246, 0.1);
            color: rgb(59, 130, 246);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        /* Enhanced Table Styling for Better Notes Display */
        .inventory-table {
            table-layout: auto;
        }

        .inventory-table td {
            vertical-align: top;
            min-height: 3rem;
        }

        .notes-cell {
            min-width: 200px;
            max-width: 300px;
        }

        .notes-content {
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
            line-height: 1.5;
        }

        .notes-card {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            transition: all 0.2s ease;
        }

        .notes-card:hover {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-color: rgba(59, 130, 246, 0.3);
        }

        /* Custom Pagination Styling */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .pagination .page-item {
            display: inline-block;
        }

        .pagination .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            padding: 0.5rem;
            margin: 0 0.125rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: rgb(107, 114, 128);
            background: white;
            border: 1px solid rgba(244, 63, 94, 0.1);
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .pagination .page-link:hover {
            color: rgb(244, 63, 94);
            background: rgba(244, 63, 94, 0.05);
            border-color: rgba(244, 63, 94, 0.2);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .pagination .page-item.active .page-link {
            color: white;
            background: linear-gradient(135deg, rgb(244, 63, 94), rgb(225, 29, 72));
            border-color: rgb(244, 63, 94);
            box-shadow: 0 4px 6px -1px rgba(244, 63, 94, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            color: rgb(156, 163, 175);
            background: rgb(249, 250, 251);
            border-color: rgb(229, 231, 235);
            cursor: not-allowed;
        }

        .pagination .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Previous/Next pagination styling */
        .pagination .page-link[rel="prev"],
        .pagination .page-link[rel="next"] {
            width: auto;
            padding: 0.5rem 0.75rem;
            font-weight: 600;
        }
    </style>

    <div class="py-8 gradient-bg min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-8 form-enter">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full mb-4 shadow-lg">
                    <i class="bi bi-graph-up text-2xl text-white"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    Dashboard <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-rose-600">Inventaris</span>
                </h2>
                <p class="text-gray-600">Kelola dan pantau stok produk dengan mudah</p>
            </div>

            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 form-enter">
                <div class="stats-card p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                            <i class="bi bi-box-seam text-2xl text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Produk</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $products->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="stats-card p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                            <i class="bi bi-activity text-2xl text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Aktivitas</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($logs->total()) }}</p>
                        </div>
                    </div>
                </div>

                <div class="stats-card p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg">
                            <i class="bi bi-exclamation-triangle text-2xl text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Stok Menipis</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $products->where('needs_restock', true)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Stock Activities -->
            <div class="section-card p-6 form-enter mb-8">
                <div class="mb-6 pb-4 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-clock-history mr-2 text-pink-500"></i>
                        Aktivitas Stok Terbaru
                    </h3>
                    <p class="text-gray-500 text-sm mt-1">Riwayat perubahan stok produk</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full inventory-table">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-calendar mr-1 text-pink-500"></i>
                                    Tanggal
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-box mr-1 text-pink-500"></i>
                                    Produk
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-tag mr-1 text-pink-500"></i>
                                    Kategori
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-arrow-up-down mr-1 text-pink-500"></i>
                                    Aktivitas
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-123 mr-1 text-pink-500"></i>
                                    Jumlah
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-rulers mr-1 text-pink-500"></i>
                                    Satuan
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-info-circle mr-1 text-pink-500"></i>
                                    Sumber
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-chat-dots mr-1 text-pink-500"></i>
                                    Catatan
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($logs as $log)
                            <tr class="table-hover transition-all duration-200">
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $log->created_at->format('d M Y') }}</span>
                                        <span class="text-xs text-gray-400">{{ $log->created_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-pink-100 to-rose-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="bi bi-box text-pink-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $log->product->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-md text-xs font-medium">
                                        {{ $log->product->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    @if($log->qty > 0)
                                        <span class="status-badge status-in">
                                            <i class="bi bi-arrow-up mr-1"></i>
                                            Masuk
                                        </span>
                                    @elseif($log->qty < 0)
                                        <span class="status-badge status-out">
                                            <i class="bi bi-arrow-down mr-1"></i>
                                            Keluar
                                        </span>
                                    @else
                                        <span class="status-badge status-adjust">
                                            <i class="bi bi-gear mr-1"></i>
                                            Penyesuaian
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ number_format(abs($log->qty)) }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600">{{ $log->product->base_unit ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    <span class="capitalize">{{ $log->source }}</span>
                                </td>
                                <td class="px-4 py-4 notes-cell">
                                    @if($log->notes)
                                        <div class="notes-card rounded-lg p-3 border border-gray-200">
                                            <p class="notes-content text-sm text-gray-700">{{ $log->notes }}</p>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic text-sm">Tidak ada catatan</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="bi bi-inbox text-4xl text-gray-300 mb-2"></i>
                                        <p class="text-gray-500">Belum ada aktivitas stok</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        {{ $logs->links() }}
                    </div>
                </div>
                @endif
            </div>
            <!-- Current Stock List -->
            <div class="section-card p-6 form-enter">
                <div class="mb-6 pb-4 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-archive mr-2 text-pink-500"></i>
                        Daftar Stok Produk Saat Ini
                    </h3>
                    <p class="text-gray-500 text-sm mt-1">Pantau dan kelola stok semua produk</p>
                </div>

                <!-- Advanced Filter Form -->
                <div class="bg-gradient-to-r from-pink-50 to-rose-50 rounded-xl p-6 mb-6">
                    <form method="GET" action="" class="space-y-4">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-search mr-1 text-pink-500"></i>
                                    Pencarian Produk
                                </label>
                                <input type="text" name="search" id="search" 
                                       value="{{ $filter_search ?? '' }}" 
                                       class="w-full px-4 py-3 border-0 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all duration-200"
                                       placeholder="Cari berdasarkan kode atau nama produk...">
                            </div>
                            <div class="w-full md:w-1/3">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-tags mr-1 text-pink-500"></i>
                                    Filter Kategori
                                </label>
                                <select name="category_id" id="category_id" 
                                        class="w-full px-4 py-3 border-0 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all duration-200">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categoriesWithProducts as $cat)
                                        <option value="{{ $cat->id }}" {{ ($filter_category ?? '') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" 
                                        class="bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <i class="bi bi-funnel mr-1"></i>
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-box mr-1 text-pink-500"></i>
                                    Produk
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-qr-code mr-1 text-pink-500"></i>
                                    Kode
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-tag mr-1 text-pink-500"></i>
                                    Kategori
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-archive mr-1 text-pink-500"></i>
                                    Stok Saat Ini
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-rulers mr-1 text-pink-500"></i>
                                    Satuan
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-gear mr-1 text-pink-500"></i>
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($products as $product)
                            <tr class="table-hover transition-all duration-200">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-pink-100 to-rose-100 rounded-xl flex items-center justify-center mr-3">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                                     class="w-10 h-10 rounded-xl object-cover">
                                            @else
                                                <i class="bi bi-box text-pink-600"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                            @if($product->needs_restock)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                    <i class="bi bi-exclamation-triangle mr-1"></i>
                                                    Stok Menipis
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-md text-sm font-mono">
                                        {{ $product->code }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-1 bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 rounded-md text-xs font-medium">
                                        {{ $product->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <span class="text-lg font-bold {{ $product->needs_restock ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $product->formatted_stock }}
                                        </span>
                                        @if($product->needs_restock)
                                            <i class="bi bi-exclamation-triangle text-red-500 ml-2"></i>
                                        @else
                                            <i class="bi bi-check-circle text-green-500 ml-2"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">{{ $product->base_unit }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('inventory.history', $product) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="bi bi-clock-history mr-1"></i>
                                            History
                                        </a>
                                        @if(!auth()->user()->hasRole(['kasir', 'karyawan']))
                                            <a href="{{ route('inventory.adjust-form', $product) }}" 
                                               class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                                <i class="bi bi-pencil-square mr-1"></i>
                                                Sesuaikan
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="bi bi-archive text-4xl text-gray-300 mb-2"></i>
                                        <p class="text-gray-500">Tidak ada produk ditemukan</p>
                                        <p class="text-sm text-gray-400 mt-1">Coba ubah filter pencarian Anda</p>
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
