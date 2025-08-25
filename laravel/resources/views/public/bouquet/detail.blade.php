@extends('layouts.public')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-3xl mx-auto">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('public.flowers', ['tab' => 'bouquets']) }}"
                                class="text-sm text-pink-600 hover:text-pink-700">
                                <i class="bi bi-arrow-left mr-2"></i>
                                Kembali ke Daftar Bouquet
                            </a>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Bouquet Image -->
                <div class="relative h-64 sm:h-80">
                    @if($bouquet->image)
                        <img src="{{ asset('storage/' . $bouquet->image) }}" alt="{{ $bouquet->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                            <i class="bi bi-flower3 text-6xl text-gray-400"></i>
                        </div>
                    @endif
                </div>

                <!-- Bouquet Details -->
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $bouquet->name }}</h1>

                    <!-- Category -->
                    <div class="mb-4">
                        <span class="text-sm text-gray-500">Kategori:</span>
                        <span
                            class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                            {{ $bouquet->category->name ?? 'Bouquet' }}
                        </span>
                    </div>

                    <!-- Description -->
                    @if($bouquet->description)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Deskripsi</h3>
                            <p class="text-gray-600">{{ $bouquet->description }}</p>
                        </div>
                    @endif

                    <!-- Components -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Komposisi Bunga</h3>
                        <div class="space-y-2">
                            @forelse($bouquet->components->groupBy('size_id') as $sizeId => $components)
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-medium text-sm mb-2">
                                        {{ $components->first()->size->name ?? 'Ukuran Default' }}
                                    </h4>
                                    <ul class="list-disc list-inside text-sm text-gray-600">
                                        @foreach($components as $component)
                                            <li>{{ $component->quantity }} tangkai {{ $component->product->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Tidak ada data komposisi</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Sizes and Prices -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Pilihan Ukuran & Harga</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse($bouquet->prices as $price)
                                <div class="border rounded-lg p-4 flex justify-between items-center">
                                    <div>
                                        <span class="font-medium">{{ $price->size->name }}</span>
                                        <p class="text-sm text-gray-600">Rp{{ number_format($price->price, 0, ',', '.') }}</p>
                                    </div>
                                    <button
                                        onclick="orderBouquet('{{ $bouquet->id }}', '{{ $price->size_id }}', '{{ $price->price }}')"
                                        class="px-4 py-2 bg-pink-600 text-white text-sm font-medium rounded-md hover:bg-pink-700">
                                        Pesan
                                    </button>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Tidak ada data harga</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Modal -->
    <div id="orderModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Pesan Bouquet</h3>
                <button onclick="closeOrderModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form id="orderForm" action="{{ route('public.cart.add') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="bouquet_id" id="bouquetId">
                <input type="hidden" name="size_id" id="sizeId">
                <input type="hidden" name="price" id="price">

                <!-- Quantity Input -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="updateQuantity(-1)"
                            class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 sm:text-sm"
                            onchange="validateQuantity(this)">
                        <button type="button" onclick="updateQuantity(1)"
                            class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Note Input -->
                <div>
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea id="note" name="note" rows="2"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 sm:text-sm"
                        placeholder="Tambahkan catatan khusus..."></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeOrderModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 rounded-md">
                        Tambah ke Keranjang
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function orderBouquet(bouquetId, sizeId, price) {
            document.getElementById('bouquetId').value = bouquetId;
            document.getElementById('sizeId').value = sizeId;
            document.getElementById('price').value = price;
            document.getElementById('orderModal').classList.remove('hidden');
        }

        function closeOrderModal() {
            document.getElementById('orderModal').classList.add('hidden');
            document.getElementById('quantity').value = 1;
            document.getElementById('note').value = '';
        }

        function updateQuantity(change) {
            const input = document.getElementById('quantity');
            const newValue = parseInt(input.value) + change;
            if (newValue >= 1) {
                input.value = newValue;
            }
        }

        function validateQuantity(input) {
            if (input.value < 1) {
                input.value = 1;
            }
        }

        // Close modal when clicking outside
        document.getElementById('orderModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeOrderModal();
            }
        });
    </script>
@endpush