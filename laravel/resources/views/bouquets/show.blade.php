<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Buket
            </h2>
            <a href="{{ route('bouquets.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Informasi Dasar -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Informasi Buket</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Buket</label>
                                    <p class="mt-1 text-gray-900">{{ $bouquet->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                    <p class="mt-1 text-gray-900">{{ $bouquet->category->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                    <p class="mt-1 text-gray-900">{{ $bouquet->description ?: 'Tidak ada deskripsi' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Gambar Buket</h3>
                            @if($bouquet->image)
                                <img src="{{ asset('storage/' . $bouquet->image) }}" alt="{{ $bouquet->name }}"
                                    class="rounded-lg shadow-md max-w-full h-auto">
                            @else
                                <div class="bg-gray-100 rounded-lg p-4 text-center">
                                    Tidak ada gambar
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Daftar Ukuran dan Harga -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Ukuran dan Harga</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ukuran</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Harga</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($bouquet->prices as $price)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $price->size->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                Rp {{ number_format($price->price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                                                Belum ada harga yang ditentukan
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Daftar Komponen per Ukuran -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Komponen per Ukuran</h3>
                        @forelse($componentsBySize as $sizeId => $sizeData)
                            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-lg mb-3">Ukuran: {{ $sizeData['size']->name }}</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-white">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Produk</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($sizeData['components'] as $component)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ $component->product->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ $component->quantity }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-gray-50">
                                            <tr>
                                                <td colspan="2" class="px-6 py-4 text-right">
                                                    <span class="font-medium">Total Biaya Komponen:</span>
                                                    <span class="ml-2">Rp
                                                        {{ number_format($sizeData['total_cost'], 0, ',', '.') }}</span>
                                                    <br>
                                                    <span class="font-medium">Harga Jual:</span>
                                                    <span class="ml-2">Rp
                                                        {{ number_format($sizeData['price'], 0, ',', '.') }}</span>
                                                    <br>
                                                    <span class="font-medium">Margin:</span>
                                                    <span
                                                        class="ml-2 {{ ($sizeData['price'] - $sizeData['total_cost']) > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                        Rp
                                                        {{ number_format($sizeData['price'] - $sizeData['total_cost'], 0, ',', '.') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-500 text-center py-4">
                                Belum ada komponen yang ditambahkan
                            </div>
                        @endforelse
                    </div>

                    <!-- Tombol Aksi -->
                    @if(!auth()->user()->hasRole('kasir'))
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('bouquet-components.create', ['bouquet' => $bouquet->id]) }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Tambah Komponen
                            </a>
                            <a href="{{ route('bouquets.edit', $bouquet) }}"
                                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Edit Buket
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>