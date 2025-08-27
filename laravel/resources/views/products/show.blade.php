<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-700">Detail Produk</h1>
            <a href="{{ route('products.index') }}"
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

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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

        .info-item {
            padding: 1rem;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #fdf2f8 0%, #ffffff 100%);
            border: 1px solid rgba(244, 63, 94, 0.1);
            transition: all 0.2s ease;
        }

        .info-item:hover {
            background: linear-gradient(135deg, #fce7f3 0%, #ffffff 100%);
            border-color: rgba(244, 63, 94, 0.2);
        }
    </style>

    <div class="py-8 gradient-bg min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-8 form-enter">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full mb-4 shadow-lg">
                    <i class="bi bi-eye text-2xl text-white"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    Detail <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-rose-600">Produk</span>
                </h2>
                <p class="text-gray-600">Informasi lengkap tentang produk</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Product Image & Basic Info -->
                <div class="lg:col-span-1">
                    <div class="section-card p-6 form-enter">
                        <div class="mb-6 text-center">
                            @if($product->image)
                                <div class="relative inline-block">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar Produk"
                                        class="w-full max-w-xs h-64 object-cover rounded-xl shadow-lg border border-gray-200">
                                    <div class="absolute top-2 right-2">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div
                                    class="w-full max-w-xs h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mx-auto">
                                    <div class="text-center">
                                        <i class="bi bi-image text-4xl text-gray-400 mb-2"></i>
                                        <p class="text-gray-500 text-sm">Tidak ada gambar</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="text-center border-t border-gray-100 pt-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $product->name }}</h3>
                            <p class="text-pink-600 font-semibold">{{ $product->code }}</p>
                            <p class="text-gray-500 text-sm mt-2">{{ $product->category->name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Detailed Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Product Information -->
                    <div class="section-card p-6 form-enter">
                        <div class="mb-6 pb-4 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                <i class="bi bi-info-circle mr-2 text-pink-500"></i>
                                Informasi Produk
                            </h3>
                            <p class="text-gray-500 text-sm mt-1">Detail dan spesifikasi produk</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="info-item">
                                <div class="flex items-center mb-2">
                                    <i class="bi bi-hash text-pink-500 mr-2"></i>
                                    <span class="text-sm font-semibold text-gray-700">Kode Produk</span>
                                </div>
                                <p class="text-gray-800 font-medium">{{ $product->code }}</p>
                            </div>

                            <div class="info-item">
                                <div class="flex items-center mb-2">
                                    <i class="bi bi-box text-pink-500 mr-2"></i>
                                    <span class="text-sm font-semibold text-gray-700">Nama Produk</span>
                                </div>
                                <p class="text-gray-800 font-medium">{{ $product->name }}</p>
                            </div>

                            <div class="info-item">
                                <div class="flex items-center mb-2">
                                    <i class="bi bi-tags text-pink-500 mr-2"></i>
                                    <span class="text-sm font-semibold text-gray-700">Kategori</span>
                                </div>
                                <p class="text-gray-800 font-medium">{{ $product->category->name }}</p>
                            </div>

                            <div class="info-item">
                                <div class="flex items-center mb-2">
                                    <i class="bi bi-rulers text-pink-500 mr-2"></i>
                                    <span class="text-sm font-semibold text-gray-700">Satuan Dasar</span>
                                </div>
                                <p class="text-gray-800 font-medium">{{ ucfirst($product->base_unit) }}</p>
                            </div>

                            <div class="info-item md:col-span-2">
                                <div class="flex items-center mb-2">
                                    <i class="bi bi-card-text text-pink-500 mr-2"></i>
                                    <span class="text-sm font-semibold text-gray-700">Deskripsi</span>
                                </div>
                                <p class="text-gray-800">{{ $product->description ?: 'Tidak ada deskripsi' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Information -->
                    <div class="section-card p-6 form-enter">
                        <div class="mb-6 pb-4 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                <i class="bi bi-graph-up mr-2 text-pink-500"></i>
                                Informasi Stok
                            </h3>
                            <p class="text-gray-500 text-sm mt-1">Status ketersediaan dan inventory</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="info-item text-center">
                                <div class="flex items-center justify-center mb-2">
                                    <i class="bi bi-archive text-pink-500 text-2xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-gray-700 mb-1">Stok Saat Ini</p>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format($product->current_stock) }}
                                </p>
                            </div>

                            <div class="info-item text-center">
                                <div class="flex items-center justify-center mb-2">
                                    <i class="bi bi-exclamation-triangle text-pink-500 text-2xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-gray-700 mb-1">Minimal Stok</p>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format($product->min_stock) }}</p>
                            </div>

                            <div class="info-item text-center">
                                <div class="flex items-center justify-center mb-2">
                                    <i class="bi bi-check-circle text-pink-500 text-2xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-gray-700 mb-1">Status</p>
                                <span
                                    class="px-3 py-1 text-sm font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                        </div>

                        @if($product->needs_restock)
                            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                                <div class="flex items-center">
                                    <i class="bi bi-exclamation-triangle text-yellow-600 mr-2"></i>
                                    <span class="text-yellow-800 font-medium">Peringatan: Stok produk rendah!</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Price Information -->
            <div class="section-card p-6 form-enter mt-8">
                <div class="mb-6 pb-4 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-currency-dollar mr-2 text-pink-500"></i>
                        Daftar Harga
                    </h3>
                    <p class="text-gray-500 text-sm mt-1">Struktur harga produk berdasarkan jenis</p>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-pink-50 to-rose-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-tag mr-1"></i>
                                    Jenis Harga
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-cash mr-1"></i>
                                    Harga (Rp)
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-calculator mr-1"></i>
                                    Satuan Setara
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-123 mr-1"></i>
                                    Minimal Grosir
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                    <i class="bi bi-star mr-1"></i>
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($product->prices as $price)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="w-3 h-3 bg-gradient-to-r from-pink-400 to-rose-500 rounded-full mr-3">
                                            </div>
                                            <span
                                                class="font-medium text-gray-800">{{ ucwords(str_replace('_', ' ', $price->type)) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-lg font-semibold text-gray-900">
                                            Rp {{ number_format($price->price, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-700">{{ $price->unit_equivalent }}
                                            {{ $product->base_unit }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(!is_null($price->min_grosir_qty) && $price->min_grosir_qty !== '')
                                            <span class="text-pink-700 font-semibold">{{ $price->min_grosir_qty }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($price->is_default)
                                            <span
                                                class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 flex items-center w-fit">
                                                <i class="bi bi-star-fill mr-1"></i>
                                                Default
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="bi bi-cash-stack text-4xl text-gray-300 mb-2"></i>
                                            <p class="text-gray-500 font-medium">Tidak ada data harga</p>
                                            <p class="text-gray-400 text-sm">Silakan tambahkan harga untuk produk ini</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            {{-- @if(!auth()->user()->hasRole('kasir')) --}}
            @if(!auth()->user()->hasRole(['kasir', 'karyawan']))
                <div class="mt-8 flex justify-center space-x-4 form-enter">
                    <a href="{{ route('products.edit', $product) }}"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="bi bi-pencil-square mr-2"></i>
                        Edit Produk
                    </a>

                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.')"
                        class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="bi bi-trash mr-2"></i>
                            Hapus Produk
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>