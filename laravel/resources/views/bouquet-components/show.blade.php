<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-3xl text-gray-800 leading-tight mb-2">
                Detail Komponen Bouquet
            </h2>
            <a href="{{ route('bouquet-components.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Informasi Bouquet -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Informasi Bouquet</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-600">Nama Bouquet:</span>
                                        <span class="text-gray-900">{{ $bouquetComponent->bouquet->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-600">Kategori:</span>
                                        <span
                                            class="text-gray-900">{{ $bouquetComponent->bouquet->category->name ?? '-' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-600">Ukuran:</span>
                                        <span class="text-gray-900">{{ $bouquetComponent->size->name }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Produk -->
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Informasi Produk</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-600">Nama Produk:</span>
                                        <span class="text-gray-900">{{ $bouquetComponent->product->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-600">Kategori Produk:</span>
                                        <span
                                            class="text-gray-900">{{ $bouquetComponent->product->category->name ?? '-' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-600">Stok Tersedia:</span>
                                        <span class="text-gray-900">{{ $bouquetComponent->product->current_stock }}
                                            {{ $bouquetComponent->product->base_unit ?? 'pcs' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-600">Harga Satuan:</span>
                                        <span class="text-gray-900">Rp
                                            {{ number_format($bouquetComponent->product->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Komponen -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Detail Komponen</h3>
                                <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-lg p-6">
                                    <div class="text-center mb-4">
                                        <div
                                            class="w-16 h-16 bg-pink-100 rounded-full mx-auto flex items-center justify-center mb-3">
                                            <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z" />
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Jumlah Diperlukan</h4>
                                    </div>

                                    <div class="text-center">
                                        <div class="text-3xl font-bold text-pink-600 mb-2">
                                            {{ $bouquetComponent->quantity }}
                                        </div>
                                        <div class="text-gray-600">
                                            {{ $bouquetComponent->product->base_unit ?? 'pcs' }}
                                        </div>
                                    </div>

                                    <div class="mt-6 pt-4 border-t border-pink-200">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Total Biaya:</span>
                                            <span class="font-semibold text-gray-900">
                                                Rp
                                                {{ number_format($bouquetComponent->quantity * $bouquetComponent->product->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Aksi -->
                            @if(!auth()->user()->hasRole('kasir'))
                                <div class="flex gap-3">
                                    <a href="{{ route('bouquet-components.edit', $bouquetComponent) }}"
                                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-3-5l2.5-2.5M15 5l3 3m-5 6l-3 3-3-1 1-3 3-3z" />
                                        </svg>
                                        Edit Komponen
                                    </a>
                                    <form action="{{ route('bouquet-components.destroy', $bouquetComponent) }}"
                                        method="POST" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus komponen ini?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus Komponen
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>