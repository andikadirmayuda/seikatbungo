<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-pink-100 rounded-lg mr-3">
                    <i class="bi bi-cart-plus text-pink-600 text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Transaksi Penjualan Baru</h1>
                    <p class="text-sm text-gray-500 mt-1">Buat transaksi penjualan baru</p>
                </div>
            </div>
            <a href="{{ route('sales.index') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                <i class="bi bi-arrow-left mr-2"></i>
                <span class="hidden sm:inline">Kembali</span>
                <span class="sm:hidden">Back</span>
            </a>
        </div>
    </x-slot>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <div class="py-6 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-exclamation-triangle-fill mr-2 text-red-600"></i>
                        <span class="font-medium">Terdapat kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Form Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Transaksi</h3>
                    <p class="mt-1 text-sm text-gray-500">Lengkapi data transaksi penjualan</p>
                </div>

                <div class="p-6">
                    <form action="{{ route('sales.store') }}" method="POST" id="saleForm" class="space-y-8">
                        @csrf

                        <!-- Customer & Transaction Info -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-whatsapp mr-2 text-green-600"></i>No. WhatsApp Customer
                                    </label>
                                    <input type="text" name="wa_number"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors"
                                        placeholder="08xxxxxxxxxx" autocomplete="off">
                                    <p class="mt-1 text-xs text-gray-500">Untuk mengirim link invoice ke customer</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-clock mr-2 text-blue-600"></i>Waktu Pemesanan
                                    </label>
                                    <input type="text"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                        value="{{ now()->format('d/m/Y H:i') }}" disabled>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-hash mr-2 text-purple-600"></i>No. Penjualan
                                    </label>
                                    <input type="text"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                        value="(Akan otomatis dibuat)" disabled>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-credit-card mr-2 text-orange-600"></i>Metode Pembayaran
                                    </label>
                                    <select name="payment_method"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors">
                                        <option value="">Pilih Metode Pembayaran</option>
                                        <option value="cash">Cash/Tunai</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="debit">Debit</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Product Selection -->
                        <div class="border-t border-gray-200 pt-8">
                            <h4 class="text-lg font-medium text-gray-900 mb-6">
                                <i class="bi bi-box-seam mr-2 text-blue-600"></i>Pilih Produk
                            </h4>

                            <!-- Product Selection Form with proper layout -->
                            <div class="space-y-6">
                                <!-- Step 1: Category Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        <i class="bi bi-tags mr-2 text-indigo-600"></i>1. Kategori Produk
                                    </label>
                                    <select
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200"
                                        id="categorySelect">
                                        <option value="">-- Semua Kategori --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Step 2: Product Search -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        <i class="bi bi-search mr-2 text-green-600"></i>2. Cari Produk
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="productSearchInput"
                                            class="w-full px-4 py-3 pl-11 pr-11 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200"
                                            placeholder="Ketik nama produk untuk mencari...">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="bi bi-search text-gray-400"></i>
                                        </div>
                                        <button type="button" id="clearSearchBtn"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors duration-150 hidden"
                                            onclick="clearProductSearch()">
                                            <i class="bi bi-x-circle-fill text-lg"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Step 3: Product Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        <i class="bi bi-box mr-2 text-purple-600"></i>3. Pilih Produk
                                    </label>
                                    <div class="relative">
                                        <button type="button" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200 bg-white text-left"
                                            id="productDropdownButton">
                                            <span class="text-gray-500">-- Pilih Produk --</span>
                                        </button>
                                        <div id="productDropdownPanel" 
                                            class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg">
                                            <div class="p-3">
                                                <!-- Search input -->
                                                <div class="mb-3">
                                                    <input type="text" id="productSearch"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                                                        placeholder="Cari produk...">
                                                </div>
                                                <!-- Grid of products -->
                                                <div class="max-h-60 overflow-y-auto">
                                                    <div class="grid grid-cols-2 gap-2" id="productsGrid">
                                                        @foreach($products as $product)
                                                            <button type="button"
                                                                class="product-option text-left px-3 py-2 rounded-md hover:bg-pink-50 transition-colors {{ $product->current_stock == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                                data-product-id="{{ $product->id }}"
                                                                data-category="{{ $product->category_id }}"
                                                                data-name="{{ strtolower($product->name ?? '') }}"
                                                                data-code="{{ strtolower($product->code ?? '') }}"
                                                                data-original-name="{{ $product->name ?? '' }}"
                                                                {{ $product->current_stock == 0 ? 'disabled' : '' }}>
                                                                {{ $product->name }}
                                                                @if($product->current_stock == 0)
                                                                    <span class="text-red-500 text-sm">(Stok Habis)</span>
                                                                @endif
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="productSelect" name="product_id" value="">
                                    </div>

                                    <!-- Search Results Info -->
                                    <div id="searchResultsInfo" class="mt-3 hidden">
                                        <div class="flex items-center p-3 rounded-lg">
                                            <i class="bi bi-info-circle mr-2 flex-shrink-0"></i>
                                            <span id="searchResultsText" class="text-sm font-medium"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Details Form -->
                            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Price Type Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        <i class="bi bi-cash-coin mr-2 text-yellow-600"></i>4. Tipe Harga
                                    </label>
                                    <select
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200"
                                        id="priceTypeSelect">
                                        <option value="">-- Pilih Tipe Harga --</option>
                                    </select>
                                </div>

                                <!-- Quantity Input -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        <i class="bi bi-123 mr-2 text-red-600"></i>5. Jumlah
                                    </label>
                                    <input type="number"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200"
                                        id="quantityInput" min="1" value="1" placeholder="Masukkan jumlah">
                                </div>

                                <!-- Add to Cart Button -->
                                <div class="flex items-end">
                                    <button type="button" id="addItemBtn"
                                        class="w-full px-4 py-3 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="bi bi-plus-circle mr-2"></i>Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>

                            <!-- Quick Search by Barcode -->
                            <div
                                class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-5 border border-blue-200">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
                                            <i class="bi bi-upc-scan text-blue-600 text-lg"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <label class="block text-sm font-medium text-gray-700 mb-3">
                                            <i class="bi bi-lightning-charge mr-2 text-orange-500"></i>Cari Cepat dengan
                                            Kode Produk
                                        </label>
                                        <div class="relative">
                                            <input type="text" id="searchByCodeInput"
                                                class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                                placeholder="Scan atau ketik kode produk untuk pencarian cepat...">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="bi bi-qr-code-scan text-gray-400"></i>
                                            </div>
                                        </div>
                                        <div id="searchByCodeResult" class="mt-3"></div>
                                        <p class="mt-2 text-xs text-gray-500">
                                            <i class="bi bi-info-circle mr-1"></i>
                                            Ketik minimal 2 karakter untuk mencari produk berdasarkan kode
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cart Table -->
                        <div class="border-t border-gray-200 pt-8">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="text-lg font-medium text-gray-900">
                                    <i class="bi bi-cart-check mr-2 text-green-600"></i>Daftar Produk
                                </h4>
                            </div>

                            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                                <table class="min-w-full divide-y divide-gray-200" id="itemsTable">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                Produk
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                Tipe Harga
                                            </th>
                                            <th
                                                class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                Harga
                                            </th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                Jumlah
                                            </th>
                                            <th
                                                class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                Subtotal
                                            </th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        <!-- Items will be added by JavaScript -->
                                        <tr id="emptyRow">
                                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <i class="bi bi-cart text-gray-300 text-4xl mb-4"></i>
                                                    <p class="text-lg font-medium text-gray-900 mb-2">Keranjang kosong
                                                    </p>
                                                    <p class="text-sm">Tambahkan produk untuk memulai transaksi</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="border-t border-gray-200 pt-8">
                            <h4 class="text-lg font-medium text-gray-900 mb-6">
                                <i class="bi bi-calculator mr-2 text-purple-600"></i>Ringkasan Pesanan
                            </h4>

                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Subtotal
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500">Rp</span>
                                            </div>
                                            <input type="text"
                                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-600"
                                                id="subtotalInput" name="subtotal" readonly>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <strong>Total</strong>
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-700 font-semibold">Rp</span>
                                            </div>
                                            <input type="text"
                                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 font-semibold text-lg"
                                                id="totalInput" name="total" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cash Payment Section -->
                        <div id="cashSection" class="border-t border-gray-200 pt-8" style="display:none;">
                            <h4 class="text-lg font-medium text-gray-900 mb-6">
                                <i class="bi bi-cash mr-2 text-green-600"></i>Pembayaran Cash
                            </h4>

                            <div class="bg-green-50 rounded-lg p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Uang yang Diterima
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500">Rp</span>
                                            </div>
                                            <input type="text"
                                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                                id="cashGivenInput" placeholder="0" autocomplete="off">
                                        </div>

                                        <!-- Quick Amount Buttons -->
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <button type="button" onclick="setExactAmount()"
                                                class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors">
                                                Pas
                                            </button>
                                            <button type="button" onclick="addAmount(5000)"
                                                class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                                +5k
                                            </button>
                                            <button type="button" onclick="addAmount(10000)"
                                                class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                                +10k
                                            </button>
                                            <button type="button" onclick="addAmount(20000)"
                                                class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                                +20k
                                            </button>
                                            <button type="button" onclick="addAmount(50000)"
                                                class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                                +50k
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Kembalian
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500">Rp</span>
                                            </div>
                                            <div
                                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 font-semibold flex items-center">
                                                <span id="changeAmount">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="border-t border-gray-200 pt-8">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                                <div class="text-sm text-gray-500">
                                    Pastikan semua data sudah benar sebelum menyimpan transaksi
                                </div>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="{{ route('sales.index') }}"
                                        class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                        <i class="bi bi-x-circle mr-2"></i>Batal
                                    </a>
                                    <button type="submit"
                                        class="inline-flex items-center justify-center px-6 py-2.5 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        id="submitBtn">
                                        <i class="bi bi-save mr-2"></i>Simpan Transaksi
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="items" id="itemsInput">
                        <input type="hidden" name="cash_given" id="hiddenCashGivenInput">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Custom Product Dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownButton = document.getElementById('productDropdownButton');
            const dropdownPanel = document.getElementById('productDropdownPanel');
            const searchInput = document.getElementById('productSearch');
            const productOptions = document.querySelectorAll('.product-option');
            const hiddenInput = document.getElementById('productSelect');

            // Toggle dropdown
            dropdownButton.addEventListener('click', function() {
                dropdownPanel.classList.toggle('hidden');
                if (!dropdownPanel.classList.contains('hidden')) {
                    searchInput.focus();
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!dropdownButton.contains(e.target) && !dropdownPanel.contains(e.target)) {
                    dropdownPanel.classList.add('hidden');
                }
            });

            // Handle product search
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                productOptions.forEach(option => {
                    const productName = option.getAttribute('data-name');
                    const productCode = option.getAttribute('data-code');
                    if (productName.includes(searchTerm) || productCode.includes(searchTerm)) {
                        option.parentElement.classList.remove('hidden');
                    } else {
                        option.parentElement.classList.add('hidden');
                    }
                });
            });

            // Handle product selection
            productOptions.forEach(option => {
                option.addEventListener('click', function() {
                    if (!this.hasAttribute('disabled')) {
                        const productId = this.getAttribute('data-product-id');
                        const productName = this.getAttribute('data-original-name');
                        dropdownButton.innerHTML = `<span class="text-gray-900">${productName}</span>`;
                        hiddenInput.value = productId;
                        hiddenInput.dispatchEvent(new Event('change')); // Trigger change event
                        dropdownPanel.classList.add('hidden');
                    }
                });
            });
        });

        function formatPrice(price) {
            // Ensure price is a number, remove any existing separators
            const numPrice = parseFloat(String(price).replace(/[,.]/g, '')) || 0;
            return Math.round(numPrice).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        let items = [];
        const products = @json($products);

        // Buat mapping harga per produk
        const productPrices = {};
        products.forEach(p => {
            productPrices[p.id] = p.prices;
        });

        // Elements
        const productSelect = document.getElementById('productSelect');
        const priceTypeSelect = document.getElementById('priceTypeSelect');
        const quantityInput = document.getElementById('quantityInput');
        const addItemBtn = document.getElementById('addItemBtn');
        const subtotalInput = document.getElementById('subtotalInput');
        const totalInput = document.getElementById('totalInput');
        const itemsInput = document.getElementById('itemsInput');
        const cashGivenInput = document.getElementById('cashGivenInput');
        const changeAmount = document.getElementById('changeAmount');
        const cashSection = document.getElementById('cashSection');
        const emptyRow = document.getElementById('emptyRow');

        // Product selection handler
        productSelect.onchange = function () {
            let productId = this.value;
            priceTypeSelect.innerHTML = '<option value="">Pilih Tipe Harga</option>';

            if (productPrices[productId]) {
                productPrices[productId].forEach(price => {
                    if (price.type !== 'harga_grosir') {
                        priceTypeSelect.innerHTML += `<option value="${price.type}" data-price="${price.price}">${price.type.replaceAll('_', ' ').toUpperCase()} (Rp ${formatPrice(parseFloat(price.price))})</option>`;
                    }
                });
            }
        };

        // Payment method handler
        document.querySelector('select[name="payment_method"]').onchange = function () {
            if (this.value === 'cash') {
                cashSection.style.display = 'block';
            } else {
                cashSection.style.display = 'none';
            }
        };

        // Cash change calculator
        function updateCashChange() {
            const total = parseInt((totalInput.value || '').replace(/[^0-9]/g, '')) || 0;
            const given = parseInt((cashGivenInput.value || '').replace(/[^0-9]/g, '')) || 0;
            let change = given - total;

            // Update kembalian display
            changeAmount.textContent = formatRupiah(change >= 0 ? change : 0);
            document.getElementById('hiddenCashGivenInput').value = given;

            // Update styling based on payment adequacy
            const changeContainer = changeAmount.parentElement;
            if (given < total && given > 0) {
                changeContainer.classList.remove('text-green-700', 'bg-gray-100');
                changeContainer.classList.add('text-red-700', 'bg-red-50');
                changeAmount.textContent = `KURANG ${formatRupiah(total - given)}`;
            } else if (given >= total && given > 0) {
                changeContainer.classList.remove('text-red-700', 'bg-red-50');
                changeContainer.classList.add('text-green-700', 'bg-green-50');
                changeAmount.textContent = formatRupiah(change);
            } else {
                changeContainer.classList.remove('text-red-700', 'bg-red-50', 'text-green-700', 'bg-green-50');
                changeContainer.classList.add('bg-gray-100');
                changeAmount.textContent = '0';
            }
        }

        // Format currency
        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }

        // Update table display
        function updateTable() {
            let tbody = document.querySelector('#itemsTable tbody');
            tbody.innerHTML = '';
            let subtotal = 0;

            if (items.length === 0) {
                tbody.innerHTML = `
                <tr id="emptyRow">
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="bi bi-cart text-gray-300 text-4xl mb-4"></i>
                            <p class="text-lg font-medium text-gray-900 mb-2">Keranjang kosong</p>
                            <p class="text-sm">Tambahkan produk untuk memulai transaksi</p>
                        </div>
                    </td>
                </tr>
            `;
            } else {
                items.forEach((item, idx) => {
                    subtotal += item.price * item.quantity;
                    tbody.innerHTML += `
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-900">${item.product_name}</td>
                        <td class="px-6 py-4 text-gray-600">${item.price_type.replaceAll('_', ' ').toUpperCase()}</td>
                        <td class="px-6 py-4 text-right text-gray-900">Rp ${formatPrice(item.price)}</td>
                        <td class="px-6 py-4 text-center text-gray-900">${item.quantity}</td>
                        <td class="px-6 py-4 text-right font-semibold text-gray-900">Rp ${formatPrice(item.price * item.quantity)}</td>
                        <td class="px-6 py-4 text-center">
                            <button type="button" 
                                class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-md transition-colors duration-150"
                                onclick="removeItem(${idx})">
                                <i class="bi bi-trash mr-1"></i>Hapus
                            </button>
                        </td>
                    </tr>
                `;
                });
            }

            subtotalInput.value = formatRupiah(subtotal);
            totalInput.value = formatRupiah(subtotal);
            itemsInput.value = JSON.stringify(items);
            updateCashChange();
        }

        // Remove item from cart
        function removeItem(idx) {
            items.splice(idx, 1);
            updateTable();
        }

        // Add item to cart
        addItemBtn.onclick = function () {
            let productId = productSelect.value;
            let quantity = parseInt(quantityInput.value);
            let product = products.find(p => p.id == productId);
            if (!productId || quantity < 1) {
                alert('Lengkapi data produk!');
                return;
            }

            
            let priceType = priceTypeSelect.value;
            let priceTypeOption;
            let priceObj;

            // Ambil harga sesuai pilihan user
            priceObj = (product.prices || []).find(pr => pr.type === priceType);

            if (priceObj) {
                let minGrosirQty = priceObj.min_grosir_qty ? parseInt(priceObj.min_grosir_qty) : 0;

                // Kalau qty >= minimal grosir dari tipe ini, pakai harga grosir
                if (minGrosirQty > 0 && quantity >= minGrosirQty) {
                    let grosirObj = (product.prices || []).find(pr => pr.type === 'harga_grosir');
                    if (grosirObj) {
                        priceType = 'harga_grosir';
                        priceObj = grosirObj;

                        let grosirOption = priceTypeSelect.querySelector(`option[value='harga_grosir']`);
                        if (grosirOption) priceTypeSelect.value = 'harga_grosir';
                        priceTypeOption = grosirOption;
                    }
                }
            }

            // Fallback kalau tidak ketemu
            if (!priceObj) {
                priceObj = (product.prices || []).find(pr => pr.type === priceType);
                priceTypeOption = priceTypeSelect.querySelector(`option[value='${priceType}']`);
            }

            let price = priceObj ? parseFloat(priceObj.price) : 0;
            let unitEquivalent = priceObj && priceObj.unit_equivalent ? parseInt(priceObj.unit_equivalent) : 1;
            let stokTersedia = product.current_stock;
            let totalButuh = quantity * unitEquivalent;

            if (!priceType || !price) {
                alert('Pilih tipe harga yang sesuai!');
                return;
            }


            if (stokTersedia < totalButuh) {
                alert(`Stok produk tidak mencukupi!\nStok tersedia: ${stokTersedia}\nDibutuhkan: ${totalButuh}`);
                return;
            }

            items.push({
                product_id: product.id,
                product_name: product.name,
                price_type: priceType,
                quantity: quantity,
                price: price
            });

            updateTable();

            // Reset form
            productSelect.value = '';
            priceTypeSelect.innerHTML = '<option value="">Pilih Tipe Harga</option>';
            quantityInput.value = '1';
        };

        // Category filter
        document.getElementById('categorySelect').onchange = function () {
            let catId = this.value;
            filterProducts(catId, document.getElementById('productSearchInput').value);
        };

        // Product search functionality with debouncing
        const productSearchInput = document.getElementById('productSearchInput');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const searchResultsInfo = document.getElementById('searchResultsInfo');
        const searchResultsText = document.getElementById('searchResultsText');

        let searchTimeout;

        productSearchInput.addEventListener('input', function () {
            const searchTerm = this.value.trim();
            const categoryId = document.getElementById('categorySelect').value;

            // Show/hide clear button
            if (searchTerm.length > 0) {
                clearSearchBtn.classList.remove('hidden');
            } else {
                clearSearchBtn.classList.add('hidden');
            }

            // Debounce search for better performance
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterProducts(categoryId, searchTerm);
            }, 100);
        });

        // Function to filter products based on category and search term
        function filterProducts(categoryId, searchTerm) {
            const productOptions = Array.from(productSelect.options);
            let visibleCount = 0;
            let totalCount = 0;

            productOptions.forEach(option => {
                if (!option.value) return; // Skip empty option

                totalCount++;
                let shouldShow = true;

                // Filter by category
                if (categoryId && option.getAttribute('data-category') !== categoryId) {
                    shouldShow = false;
                }

                // Filter by search term (name or code)
                if (searchTerm.length > 0 && shouldShow) {
                    const productName = option.getAttribute('data-name') || '';
                    const productCode = option.getAttribute('data-code') || '';
                    const originalName = option.getAttribute('data-original-name') || '';
                    const optionText = option.textContent.toLowerCase();
                    const searchLower = searchTerm.toLowerCase();

                    // Multiple matching strategies
                    const nameMatch = productName.includes(searchLower);
                    const codeMatch = productCode.includes(searchLower);
                    const originalNameMatch = originalName.toLowerCase().includes(searchLower);
                    const textMatch = optionText.includes(searchLower);

                    if (!nameMatch && !codeMatch && !originalNameMatch && !textMatch) {
                        shouldShow = false;
                    }
                }

                option.style.display = shouldShow ? '' : 'none';
                if (shouldShow) visibleCount++;
            });

            // Update search results info with better styling
            updateSearchResultsInfo(searchTerm, categoryId, visibleCount, totalCount);

            // Reset product selection if current selection is hidden
            if (productSelect.value && productSelect.options[productSelect.selectedIndex].style.display === 'none') {
                productSelect.value = '';
                priceTypeSelect.innerHTML = '<option value="">Pilih Tipe Harga</option>';
            }
        }

        // Update search results info display
        function updateSearchResultsInfo(searchTerm, categoryId, visibleCount, totalCount) {
            if (searchTerm.length > 0 || categoryId) {
                searchResultsInfo.classList.remove('hidden');

                let message = '';
                let alertClass = '';
                let iconClass = '';

                if (visibleCount === 0) {
                    message = 'Tidak ada produk yang ditemukan';
                    alertClass = 'bg-red-50 border-red-200 text-red-700';
                    iconClass = 'bi bi-exclamation-triangle text-red-500';
                } else {
                    if (searchTerm.length > 0 && categoryId) {
                        message = `Menampilkan ${visibleCount} dari ${totalCount} produk (kategori + pencarian)`;
                    } else if (searchTerm.length > 0) {
                        message = `Menampilkan ${visibleCount} dari ${totalCount} produk untuk "${searchTerm}"`;
                    } else if (categoryId) {
                        message = `Menampilkan ${visibleCount} dari ${totalCount} produk (kategori dipilih)`;
                    }
                    alertClass = 'bg-blue-50 border-blue-200 text-blue-700';
                    iconClass = 'bi bi-info-circle text-blue-500';
                }

                const infoDiv = searchResultsInfo.querySelector('div');
                infoDiv.className = `flex items-center p-3 rounded-lg border ${alertClass}`;
                infoDiv.querySelector('i').className = `${iconClass} mr-2 flex-shrink-0`;
                searchResultsText.textContent = message;
            } else {
                searchResultsInfo.classList.add('hidden');
            }
        }

        // Clear search function with improved animation
        function clearProductSearch() {
            productSearchInput.value = '';
            clearSearchBtn.classList.add('hidden');
            const categoryId = document.getElementById('categorySelect').value;
            filterProducts(categoryId, '');

            // Smooth visual feedback
            productSearchInput.classList.add('ring-2', 'ring-green-300', 'bg-green-50');
            setTimeout(() => {
                productSearchInput.classList.remove('ring-2', 'ring-green-300', 'bg-green-50');
                productSearchInput.focus();
            }, 300);
        }

        // Auto-select single result on Enter key
        productSearchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();

                const visibleOptions = Array.from(productSelect.options).filter(option =>
                    option.value && option.style.display !== 'none'
                );

                if (visibleOptions.length === 1) {
                    // Success animation
                    this.classList.add('ring-2', 'ring-green-400', 'bg-green-50');

                    setTimeout(() => {
                        productSelect.value = visibleOptions[0].value;
                        productSelect.dispatchEvent(new Event('change'));

                        this.classList.remove('ring-2', 'ring-green-400', 'bg-green-50');
                        this.blur();

                        // Focus next input for better UX
                        setTimeout(() => {
                            if (priceTypeSelect.options.length > 1) {
                                priceTypeSelect.focus();
                            }
                        }, 100);
                    }, 250);
                } else if (visibleOptions.length === 0) {
                    // Error animation
                    this.classList.add('ring-2', 'ring-red-400', 'bg-red-50');
                    setTimeout(() => {
                        this.classList.remove('ring-2', 'ring-red-400', 'bg-red-50');
                    }, 400);
                }
            } else if (e.key === 'Escape') {
                this.blur();
                clearProductSearch();
            }
        });

        // Cash input formatting with real-time formatting
        if (cashGivenInput) {
            cashGivenInput.oninput = function () {
                // Remove non-numeric characters
                let value = this.value.replace(/[^0-9]/g, '');

                // Format with thousand separators
                if (value) {
                    this.value = formatRupiah(parseInt(value));
                } else {
                    this.value = '';
                }

                updateCashChange();
            };
        }

        // Quick amount functions
        function setExactAmount() {
            const total = parseInt((totalInput.value || '').replace(/[^0-9]/g, '')) || 0;
            if (total > 0) {
                cashGivenInput.value = formatRupiah(total);
                updateCashChange();
            }
        }

        function addAmount(amount) {
            const current = parseInt((cashGivenInput.value || '').replace(/[^0-9]/g, '')) || 0;
            const newAmount = current + amount;
            cashGivenInput.value = formatRupiah(newAmount);
            updateCashChange();
        }

        // Form submission validation
        document.getElementById('saleForm').onsubmit = function (e) {
            if (items.length === 0) {
                alert('Tambahkan minimal 1 produk!');
                return false;
            }

            const paymentSelect = document.querySelector('select[name="payment_method"]');
            if (paymentSelect.value === 'cash') {
                const total = parseInt((totalInput.value || '').replace(/[^0-9]/g, '')) || 0;
                const given = parseInt((cashGivenInput.value || '').replace(/[^0-9]/g, '')) || 0;

                if (given < total) {
                    e.preventDefault();
                    alert(`Uang yang diberikan kurang dari total belanja!\n\nTotal: Rp ${formatRupiah(total)}\nDiberikan: Rp ${formatRupiah(given)}\nKurang: Rp ${formatRupiah(total - given)}`);
                    cashGivenInput.focus();
                    cashGivenInput.select();
                    return false;
                }
            }

            return true;
        };

        // Enhanced barcode search functionality
        document.getElementById('searchByCodeInput').addEventListener('input', function () {
            const code = this.value.trim();
            const resultDiv = document.getElementById('searchByCodeResult');

            if (code.length >= 2) {
                const product = products.find(p => p.code && p.code.toLowerCase().includes(code.toLowerCase()));

                if (product) {
                    // Clear other filters
                    document.getElementById('categorySelect').value = '';
                    productSearchInput.value = '';
                    clearSearchBtn.classList.add('hidden');
                    filterProducts('', '');

                    // Select the product
                    productSelect.value = product.id;
                    productSelect.dispatchEvent(new Event('change'));

                    // Show success feedback
                    resultDiv.innerHTML = `
                        <div class="inline-flex items-center px-3 py-2 bg-green-100 border border-green-200 text-green-800 text-sm rounded-lg">
                            <i class="bi bi-check-circle-fill mr-2"></i>
                            Produk ditemukan: <strong>${product.name}</strong>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="inline-flex items-center px-3 py-2 bg-yellow-100 border border-yellow-200 text-yellow-800 text-sm rounded-lg">
                            <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                            Kode tidak ditemukan
                        </div>
                    `;
                }

                // Auto-clear feedback after 3 seconds
                setTimeout(() => {
                    resultDiv.innerHTML = '';
                }, 3000);
            } else {
                resultDiv.innerHTML = '';
            }
        });

        // Global keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            // Ctrl/Cmd + F to focus on product search
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                productSearchInput.focus();
                productSearchInput.select();
            }

            // Ctrl/Cmd + B to focus on barcode search  
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                e.preventDefault();
                document.getElementById('searchByCodeInput').focus();
                document.getElementById('searchByCodeInput').select();
            }
        });

        // Add help text for shortcuts
        const helpContainer = document.createElement('div');
        helpContainer.className = 'mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200';
        helpContainer.innerHTML = `
            <div class="flex items-start space-x-2">
                <div class="flex-shrink-0">
                    <i class="bi bi-lightbulb text-yellow-500 mt-0.5"></i>
                </div>
                <div class="text-xs text-gray-600 space-y-1">
                    <div class="font-medium text-gray-700">Tips Pencarian:</div>
                    <div>• Tekan <kbd class="px-1.5 py-0.5 bg-white border border-gray-300 rounded text-xs font-mono shadow-sm">Ctrl+F</kbd> untuk fokus ke pencarian produk</div>
                    <div>• Tekan <kbd class="px-1.5 py-0.5 bg-white border border-gray-300 rounded text-xs font-mono shadow-sm">Enter</kbd> untuk pilih otomatis jika hanya 1 hasil</div>
                    <div>• Tekan <kbd class="px-1.5 py-0.5 bg-white border border-gray-300 rounded text-xs font-mono shadow-sm">Escape</kbd> untuk bersihkan pencarian</div>
                </div>
            </div>
        `;

        // Append help text after the product search container
        const productSearchContainer = productSearchInput.closest('.space-y-3').parentElement;
        productSearchContainer.appendChild(helpContainer);

        // Initialize
        filterProducts('', '');
        updateTable();

        // Debug info (remove in production)
        console.log(`✅ Search system loaded with ${Array.from(productSelect.options).filter(option => option.value).length} products`);
    </script>
</x-app-layout>