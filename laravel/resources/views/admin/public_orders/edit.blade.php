<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="bg-pink-100 p-2 rounded-full">
                <svg class="w-6 h-6 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Pesanan</h1>
                <p class="text-sm text-gray-600">Edit pesanan pelanggan</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Navigation Back -->
        <div class="mb-6">
            <a href="{{ route('admin.public-orders.show', $order->id) }}"
                class="inline-flex items-center text-sm text-gray-600 hover:text-pink-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Detail Pesanan
            </a>
        </div>

        <!-- Form Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-gray-900">Informasi Pelanggan</h1>
            <p class="mt-1 text-sm text-gray-600">Mohon lengkapi data pesanan dengan benar</p>
        </div>

        <form action="{{ route('admin.public-orders.update', $order->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Customer Information -->
            <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                        <input type="text" name="customer_name"
                            value="{{ old('customer_name', $order->customer_name) }}"
                            placeholder="Masukkan nama pelanggan" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. WhatsApp</label>
                        <input type="text" name="wa_number" value="{{ old('wa_number', $order->wa_number) }}"
                            placeholder="Contoh: 08123456789"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Penerima <span
                                class="text-gray-400">(Opsional)</span></label>
                        <input type="text" name="receiver_name"
                            value="{{ old('receiver_name', $order->receiver_name) }}"
                            placeholder="Masukkan nama penerima jika berbeda dengan pemesan"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. WhatsApp Penerima <span
                                class="text-gray-400">(Opsional)</span></label>
                        <input type="text" name="receiver_wa" value="{{ old('receiver_wa', $order->receiver_wa) }}"
                            placeholder="Masukkan nomor WA penerima jika berbeda dengan pemesan"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Pengambilan</label>
                        <input type="date" name="pickup_date" value="{{ old('pickup_date', $order->pickup_date) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Waktu Pengambilan</label>
                        <input type="time" name="pickup_time" value="{{ old('pickup_time', $order->pickup_time) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Metode Pengiriman</label>
                        <select name="delivery_method" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                            <option value="">Pilih metode pengiriman</option>
                            <option value="Ambil Langsung Ke Toko" {{ old('delivery_method', $order->delivery_method) == 'Ambil Langsung Ke Toko' ? 'selected' : '' }}>
                                🏪 (1) Ambil Langsung Ke Toko
                            </option>
                            <option value="Gosend (Dipesan Pribadi)" {{ old('delivery_method', $order->delivery_method) == 'Gosend (Dipesan Pribadi)' ? 'selected' : '' }}>
                                🛵 (2) Gosend (Dipesan Pribadi)
                            </option>
                            <option value="Gocar (Dipesan Pribadi)" {{ old('delivery_method', $order->delivery_method) == 'Gocar (Dipesan Pribadi)' ? 'selected' : '' }}>
                                🚗 (3) Gocar (Dipesan Pribadi)
                            </option>
                            <option value="Gosend (Pesan Dari Toko)" {{ old('delivery_method', $order->delivery_method) == 'Gosend (Pesan Dari Toko)' ? 'selected' : '' }}>
                                🛵 (4) Gosend (Pesan Dari Toko)
                            </option>
                            <option value="Gocar (Pesan Dari Toko)" {{ old('delivery_method', $order->delivery_method) == 'Gocar (Pesan Dari Toko)' ? 'selected' : '' }}>
                                🚗 (5) Gocar (Pesan Dari Toko)
                            </option>
                            <option value="Travel (Di Pesan Sendiri - Khusus Luar Kota)" {{ old('delivery_method', $order->delivery_method) == 'Travel (Di Pesan Sendiri - Khusus Luar Kota)' ? 'selected' : '' }}>
                                🚐 (6) Travel (Di Pesan Sendiri - Khusus Luar Kota)
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tujuan/Alamat</label>
                        <textarea name="destination" rows="3" placeholder="Masukkan alamat lengkap tujuan pengiriman..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">{{ old('destination', $order->destination) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">
                            Berikan detail alamat yang jelas (nama jalan, nomor rumah, patokan) untuk memudahkan
                            pengiriman
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="notes" rows="3" placeholder="Tambahkan catatan khusus untuk pesanan ini..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Daftar Produk</h3>
                            <p class="mt-1 text-sm text-gray-500">Pilih produk yang akan dipesan</p>
                        </div>
                        <button type="button" onclick="addProductRow()"
                            class="inline-flex items-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white text-sm font-medium rounded-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Produk
                        </button>
                    </div>

                    <div id="products-container" class="space-y-4">
                        @foreach($order->items as $index => $item)
                            <div class="product-row bg-gray-50 rounded-lg p-4 relative">
                                <button type="button" onclick="removeProductRow(this)"
                                    class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Produk</label>
                                        <select name="items[{{ $index }}][product_id]" onchange="updatePriceTypes(this)"
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                            <option value="">Pilih Produk</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                    data-prices="{{ json_encode($product->prices) }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tipe Harga</label>
                                        <select name="items[{{ $index }}][price_type]" onchange="updatePrice(this)" required
                                            class="price-type-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                            @foreach($products->find($item->product_id)->prices as $price)
                                                <option value="{{ $price->type }}" data-price="{{ $price->price }}"
                                                    data-unit-equivalent="{{ $price->unit_equivalent }}" {{ $item->price_type == $price->type ? 'selected' : '' }}>
                                                    {{ $price->type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Harga</label>
                                        <input type="number" name="items[{{ $index }}][price]"
                                            value="{{ old("items.{$index}.price", $item->price) }}" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                        <input type="number" name="items[{{ $index }}][quantity]"
                                            value="{{ old("items.{$index}.quantity", $item->quantity) }}" required min="1"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                        <input type="hidden" name="items[{{ $index }}][unit_equivalent]"
                                            value="{{ $item->unit_equivalent }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Total dan Tombol Submit -->
                <div class="bg-gray-50 px-6 py-4">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-base font-medium text-gray-900">Total Pesanan</span>
                        <span class="text-xl font-bold text-gray-900" id="totalAmount">
                            Rp{{ number_format($order->items->sum(function ($item) {
    return $item->price * $item->quantity; }), 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.public-orders.show', $order->id) }}"
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
        </form>
    </div>

    <template id="product-row-template">
        <div class="product-row bg-gray-50 rounded-lg p-4 relative">
            <button type="button" onclick="removeProductRow(this)"
                class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Produk</label>
                    <select name="items[INDEX][product_id]" onchange="updatePriceTypes(this)" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                        <option value="">Pilih Produk</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-prices="{{ json_encode($product->prices) }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe Harga</label>
                    <select name="items[INDEX][price_type]" onchange="updatePrice(this)" required
                        class="price-type-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                        <option value="">Pilih Tipe Harga</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga</label>
                    <input type="number" name="items[INDEX][price]" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                    <input type="number" name="items[INDEX][quantity]" required min="1"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                    <input type="hidden" name="items[INDEX][unit_equivalent]" value="1">
                </div>
            </div>
        </div>
    </template>

    <script>
        let productRowIndex = {{ count($order->items) }};

        function addProductRow() {
            const template = document.getElementById('product-row-template');
            const container = document.getElementById('products-container');
            const clone = template.content.cloneNode(true);

            // Replace INDEX placeholder with actual index
            const elements = clone.querySelectorAll('[name*="INDEX"]');
            elements.forEach(element => {
                element.name = element.name.replace('INDEX', productRowIndex);
            });

            container.appendChild(clone);
            productRowIndex++;
        }

        function removeProductRow(button) {
            const row = button.closest('.product-row');
            row.remove();
        }

        function updatePriceTypes(productSelect) {
            const row = productSelect.closest('.product-row');
            const priceTypeSelect = row.querySelector('.price-type-select');
            const prices = JSON.parse(productSelect.options[productSelect.selectedIndex].dataset.prices || '[]');

            // Clear existing options
            priceTypeSelect.innerHTML = '<option value="">Pilih Tipe Harga</option>';

            // Add new options based on product prices
            prices.forEach(price => {
                const option = new Option(price.type, price.type);
                option.dataset.price = price.price;
                option.dataset.unitEquivalent = price.unit_equivalent;
                priceTypeSelect.add(option);
            });

            // Update price if there's only one price type
            if (prices.length === 1) {
                priceTypeSelect.value = prices[0].type;
                updatePrice(priceTypeSelect);
            }
        }

        function updatePrice(priceTypeSelect) {
            const row = priceTypeSelect.closest('.product-row');
            const selectedOption = priceTypeSelect.options[priceTypeSelect.selectedIndex];
            const priceInput = row.querySelector('input[name$="[price]"]');
            const unitEquivalentInput = row.querySelector('input[name$="[unit_equivalent]"]');

            if (selectedOption.dataset.price) {
                priceInput.value = selectedOption.dataset.price;
                unitEquivalentInput.value = selectedOption.dataset.unitEquivalent;
            }
        }
    </script>
</x-app-layout>