<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-700">
                <i class="bi bi-gear mr-2"></i>
                Penyesuaian Stok
                @if(isset($product) && $product)
                    <span class="text-gray-500 text-lg font-normal">- {{ $product->name }}</span>
                @endif
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

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            ring: 2px;
            ring-color: rgba(244, 63, 94, 0.5);
            border-color: rgb(244, 63, 94);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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

        .current-stock-card {
            background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
    </style>

    <div class="py-8 gradient-bg min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-8 form-enter">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full mb-4 shadow-lg">
                    <i class="bi bi-sliders text-2xl text-white"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    Penyesuaian <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-rose-600">Stok
                        Inventaris</span>
                </h2>
                <p class="text-gray-600">Sesuaikan jumlah stok produk dengan mudah dan akurat</p>
            </div>

            @if(isset($product) && $product)
                <!-- Current Stock Information -->
                <div class="current-stock-card rounded-xl p-6 mb-8 form-enter">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-500 rounded-xl shadow-lg">
                            <i class="bi bi-archive text-2xl text-white"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-800">Stok Saat Ini</h3>
                            <p class="text-2xl font-bold text-green-600">{{ $product->formatted_stock }}</p>
                            <p class="text-sm text-gray-600">{{ $product->name }} ({{ $product->code }})</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Adjustment Form -->
            <div class="section-card p-8 form-enter">
                <div class="mb-8 pb-6 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-pencil-square mr-2 text-pink-500"></i>
                        Form Penyesuaian Stok
                    </h3>
                    <p class="text-gray-500 text-sm mt-1">Isi form berikut untuk menyesuaikan stok produk</p>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6">
                        <div class="flex items-start">
                            <i class="bi bi-exclamation-triangle mr-2 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold mb-2">Terdapat kesalahan:</h4>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li class="text-sm">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" id="adjustForm"
                    action="{{ isset($product) && $product ? route('inventory.adjust', $product) : '' }}"
                    class="space-y-6">
                    @csrf

                    @if(!isset($product) || !$product)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="bi bi-box mr-1 text-pink-500"></i>
                                Pilih Produk
                            </label>
                            <select id="product_id" name="product_id"
                                class="w-full px-4 py-3 border border-pink-200 rounded-xl input-focus focus:outline-none"
                                required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}" data-action="{{ route('inventory.adjust', $prod) }}">
                                        {{ $prod->name }} ({{ $prod->code }})
                                    </option>
                                @endforeach
                            </select>
                            <div id="current-stock-info"
                                class="mt-3 p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl text-green-800 hidden">
                                <div class="flex items-center">
                                    <i class="bi bi-info-circle mr-2"></i>
                                    <span id="stock-info-text"></span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="bi bi-123 mr-1 text-pink-500"></i>
                            Jumlah Stok Baru
                        </label>
                        <input type="number" id="quantity" name="quantity"
                            value="{{ old('quantity', isset($product) && $product ? $product->current_stock : '') }}"
                            class="w-full px-4 py-3 border border-pink-200 rounded-xl input-focus focus:outline-none"
                            placeholder="Masukkan jumlah stok yang baru" required min="0" step="1">
                        <div class="mt-2 p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-700 flex items-start">
                                <i class="bi bi-lightbulb mr-2 mt-0.5 flex-shrink-0"></i>
                                <span>Masukkan jumlah total stok yang baru. Sistem akan menghitung penyesuaiannya secara
                                    otomatis.</span>
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="bi bi-chat-dots mr-1 text-pink-500"></i>
                            Catatan Penyesuaian
                        </label>
                        <textarea id="notes" name="notes" rows="4"
                            class="w-full px-4 py-3 border border-pink-200 rounded-xl input-focus focus:outline-none"
                            placeholder="Masukkan alasan atau catatan untuk penyesuaian stok ini...">{{ old('notes') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Opsional - catatan akan membantu tracking perubahan stok
                        </p>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit"
                            class="bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white font-bold py-3 px-8 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="bi bi-check-circle mr-2"></i>
                            Sesuaikan Stok
                        </button>
                        <a href="{{ route('inventory.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-xl transition-all duration-200">
                            <i class="bi bi-x-circle mr-2"></i>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @if(!isset($product) || !$product)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const select = document.getElementById('product_id');
                const form = document.getElementById('adjustForm');
                const stockInfo = document.getElementById('current-stock-info');
                const stockInfoText = document.getElementById('stock-info-text');

                select.addEventListener('change', function () {
                    const selected = select.options[select.selectedIndex];
                    const action = selected.getAttribute('data-action');
                    form.action = action ? action : '';

                    // Reset info
                    stockInfo.classList.add('hidden');
                    stockInfoText.innerHTML = '';

                    if (select.value) {
                        // Show loading state
                        stockInfoText.innerHTML = '<i class="bi bi-hourglass-split animate-spin mr-2"></i>Memuat informasi stok...';
                        stockInfo.classList.remove('hidden');

                        fetch(`/api/products/${select.value}/stock`)
                            .then(res => res.json())
                            .then(data => {
                                if (data && data.current_stock !== undefined && data.base_unit) {
                                    stockInfoText.innerHTML = `<strong>Stok Saat Ini:</strong> ${data.current_stock} ${data.base_unit}`;
                                    stockInfo.classList.remove('hidden');

                                    // Update quantity input with current stock
                                    document.getElementById('quantity').value = data.current_stock;
                                } else {
                                    stockInfoText.innerHTML = '<i class="bi bi-exclamation-triangle mr-2"></i>Gagal memuat informasi stok';
                                    stockInfo.classList.add('bg-red-50', 'text-red-800');
                                    stockInfo.classList.remove('bg-green-50', 'text-green-800', 'from-green-50', 'to-green-100');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                stockInfoText.innerHTML = '<i class="bi bi-exclamation-triangle mr-2"></i>Terjadi kesalahan saat memuat data';
                                stockInfo.classList.add('bg-red-50', 'text-red-800');
                                stockInfo.classList.remove('bg-green-50', 'text-green-800', 'from-green-50', 'to-green-100');
                            });
                    }
                });

                form.addEventListener('submit', function (e) {
                    if (!select.value) {
                        e.preventDefault();
                        alert('Silakan pilih produk terlebih dahulu!');
                        select.focus();
                    } else if (!form.action) {
                        e.preventDefault();
                        alert('Terjadi kesalahan pada form. Silakan pilih ulang produk.');
                    } else {
                        // Show confirmation
                        const quantity = document.getElementById('quantity').value;
                        const productName = select.options[select.selectedIndex].text;

                        if (!confirm(`Apakah Anda yakin ingin menyesuaikan stok ${productName} menjadi ${quantity}?`)) {
                            e.preventDefault();
                        }
                    }
                });
            });
        </script>
    @endif

</x-app-layout>