<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-700">
                <i class="bi bi-clock-history mr-2"></i>
                Riwayat Inventaris
            </h1>
            <a href="{{ route('inventory.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-xl transition-all duration-200">
                <i class="bi bi-arrow-left mr-1"></i>
                Kembali
            </a>
        </div>
    </x-slot>

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

        .table-hover:hover {
            background: linear-gradient(135deg, #fdf2f8 0%, #ffffff 100%);
            transform: scale(1.001);
        }

        .source-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .source-purchase {
            background: rgba(34, 197, 94, 0.1);
            color: rgb(34, 197, 94);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .source-sale {
            background: rgba(59, 130, 246, 0.1);
            color: rgb(59, 130, 246);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .source-return {
            background: rgba(245, 158, 11, 0.1);
            color: rgb(245, 158, 11);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .source-adjustment {
            background: rgba(139, 69, 19, 0.1);
            color: rgb(139, 69, 19);
            border: 1px solid rgba(139, 69, 19, 0.2);
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
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full mb-4 shadow-lg">
                    <i class="bi bi-graph-down-arrow text-2xl text-white"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    Riwayat <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-rose-600">{{ $product->name }}</span>
                </h2>
                <p class="text-gray-600">Detail perubahan stok produk dari waktu ke waktu</p>
            </div>

            <!-- Product Information Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 form-enter">
                <div class="stats-card p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                            <i class="bi bi-archive text-2xl text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Stok Saat Ini</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $product->formatted_stock }}</p>
                        </div>
                    </div>
                </div>

                <div class="stats-card p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg">
                            <i class="bi bi-exclamation-triangle text-2xl text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Stok Minimal</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($product->min_stock) }}</p>
                            <p class="text-xs text-gray-500">{{ $product->base_unit }}</p>
                        </div>
                    </div>
                </div>

                <div class="stats-card p-6">
                    <div class="flex items-center">
                        <div
                            class="p-3 bg-gradient-to-br {{ $product->needs_restock ? 'from-red-500 to-red-600' : 'from-blue-500 to-blue-600' }} rounded-xl shadow-lg">
                            <i
                                class="bi {{ $product->needs_restock ? 'bi-exclamation-circle' : 'bi-check-circle' }} text-2xl text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Status Stok</p>
                            @if($product->needs_restock)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="bi bi-exclamation-triangle mr-1"></i>
                                    Stok Menipis
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="bi bi-check-circle mr-1"></i>
                                    Stok Aman
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- History Table -->
            <div class="section-card p-6 form-enter">
                <div class="mb-6 pb-4 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-list-ul mr-2 text-pink-500"></i>
                        Riwayat Perubahan Stok
                    </h3>
                    <p class="text-gray-500 text-sm mt-1">Timeline lengkap aktivitas stok produk ini</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full inventory-table">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-calendar mr-1 text-pink-500"></i>
                                    Tanggal & Waktu
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-tag mr-1 text-pink-500"></i>
                                    Jenis Aktivitas
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-arrow-up-down mr-1 text-pink-500"></i>
                                    Perubahan Jumlah
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-hash mr-1 text-pink-500"></i>
                                    Referensi
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
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ $log->created_at->format('d M Y') }}</span>
                                            <span
                                                class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($log->source === 'purchase')
                                            <span class="source-badge source-purchase">
                                                <i class="bi bi-cart-plus mr-1"></i>
                                                Pembelian
                                            </span>
                                        @elseif($log->source === 'sale')
                                            <span class="source-badge source-sale">
                                                <i class="bi bi-bag-check mr-1"></i>
                                                Penjualan
                                            </span>
                                        @elseif($log->source === 'return')
                                            <span class="source-badge source-return">
                                                <i class="bi bi-arrow-return-left mr-1"></i>
                                                Return
                                            </span>
                                        @else
                                            <span class="source-badge source-adjustment">
                                                <i class="bi bi-gear mr-1"></i>
                                                Penyesuaian
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center">
                                            @if($log->qty > 0)
                                                <div class="flex items-center text-green-600">
                                                    <i class="bi bi-arrow-up-circle mr-2"></i>
                                                    <span class="font-bold">+{{ number_format(abs($log->qty)) }}</span>
                                                </div>
                                            @elseif($log->qty < 0)
                                                <div class="flex items-center text-red-600">
                                                    <i class="bi bi-arrow-down-circle mr-2"></i>
                                                    <span class="font-bold">-{{ number_format(abs($log->qty)) }}</span>
                                                </div>
                                            @else
                                                <div class="flex items-center text-gray-600">
                                                    <i class="bi bi-dash-circle mr-2"></i>
                                                    <span class="font-bold">0</span>
                                                </div>
                                            @endif
                                            <span class="ml-2 text-sm text-gray-500">{{ $product->base_unit }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($log->reference_id)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-md text-sm font-mono">
                                                {{ $log->reference_id }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
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
                                    <td colspan="5" class="px-4 py-8 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="bi bi-clock-history text-4xl text-gray-300 mb-2"></i>
                                            <p class="text-gray-500">Belum ada riwayat aktivitas</p>
                                            <p class="text-sm text-gray-400 mt-1">Aktivitas stok akan muncul di sini</p>
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
        </div>
    </div>
</x-app-layout>