<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-700">
                {{ isset($product) ? 'Edit Produk' : 'Tambah Produk' }}
            </h1>
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
    </style>

    <div class="py-8 gradient-bg min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-8 form-enter">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full mb-4 shadow-lg">
                    <i class="bi bi-box-seam text-2xl text-white"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    {{ isset($product) ? 'Edit' : 'Tambah' }} <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-rose-600">Produk</span>
                </h2>
                <p class="text-gray-600">
                    {{ isset($product) ? 'Perbarui informasi produk dengan lengkap' : 'Lengkapi informasi produk dengan detail' }}
                </p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6 form-enter">
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

            <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}"
                method="POST" class="space-y-8 form-enter" enctype="multipart/form-data">
                @csrf
                @if(isset($product)) @method('PUT') @endif

                <!-- Basic Information Section -->
                <div class="section-card p-6">
                    <div class="mb-6 pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <i class="bi bi-info-circle mr-2 text-pink-500"></i>
                            Informasi Dasar
                        </h3>
                        <p class="text-gray-500 text-sm mt-1">Data utama produk</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="bi bi-image mr-1 text-pink-500"></i>
                                Gambar Produk
                            </label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="w-full px-4 py-3 border border-pink-200 rounded-xl input-focus focus:outline-none">
                            @if(isset($product) && $product->image)
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar Produk"
                                        class="h-24 rounded-xl border border-gray-200 shadow-sm">
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="bi bi-tags mr-1 text-pink-500"></i>
                                Kategori
                            </label>
                            <select name="category_id" id="category_id"
                                class="w-full px-4 py-3 border border-pink-200 rounded-xl input-focus focus:outline-none"
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ (old('category_id', $product->category_id ?? '') == $id) ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="bi bi-hash mr-1 text-pink-500"></i>
                                Kode Produk
                            </label>
                            <input type="text" name="code" id="code" value="{{ old('code', $product->code ?? '') }}"
                                class="w-full px-4 py-3 border border-pink-200 rounded-xl input-focus focus:outline-none"
                                placeholder="Kode akan dibuat otomatis jika kosong">
                            <p class="text-xs text-gray-500 mt-1">Opsional - sistem akan generate otomatis jika kosong
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="bi bi-box mr-1 text-pink-500"></i>
                                Nama Produk
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}"
                                class="w-full px-4 py-3 border border-pink-200 rounded-xl input-focus focus:outline-none"
                                placeholder="Masukkan nama produk" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="bi bi-rulers mr-1 text-pink-500"></i>
                                Satuan Dasar
                            </label>
                            <div class="mt-2 space-x-6">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="base_unit" value="tangkai" {{ old('base_unit', $product->base_unit ?? 'tangkai') == 'tangkai' ? 'checked' : '' }}
                                        class="form-radio text-pink-600 focus:ring-pink-500">
                                    <span class="ml-2 text-gray-700">Tangkai</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="base_unit" value="item" {{ old('base_unit', $product->base_unit ?? '') == 'item' ? 'checked' : '' }}
                                        class="form-radio text-pink-600 focus:ring-pink-500">
                                    <span class="ml-2 text-gray-700">Item</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="bi bi-check-circle mr-1 text-pink-500"></i>
                                Status
                            </label>
                            <label class="inline-flex items-center mt-2">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                                    class="form-checkbox text-pink-600 focus:ring-pink-500 rounded">
                                <span class="ml-2 text-gray-700">Produk Aktif</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="bi bi-card-text mr-1 text-pink-500"></i>
                            Deskripsi Produk
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-3 border border-pink-200 rounded-xl input-focus focus:outline-none"
                            placeholder="Masukkan deskripsi produk (opsional)">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Stock Information Section -->
                <div class="section-card p-6">
                    <div class="mb-6 pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <i class="bi bi-graph-up mr-2 text-pink-500"></i>
                            Informasi Stok
                        </h3>
                        <p class="text-gray-500 text-sm mt-1">Kelola stok dan inventory produk</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="bi bi-archive mr-1 text-pink-500"></i>
                                Stok Saat Ini
                            </label>
                            <input type="number" name="current_stock" id="current_stock"
                                value="{{ old('current_stock', $product->current_stock ?? 0) }}"
                                class="w-full px-4 py-3 border border-pink-200 rounded-xl input-focus focus:outline-none"
                                placeholder="0" min="0">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="bi bi-exclamation-triangle mr-1 text-pink-500"></i>
                                Minimal Stok
                            </label>
                            <input type="number" name="min_stock" id="min_stock"
                                value="{{ old('min_stock', $product->min_stock ?? 10) }}"
                                class="w-full px-4 py-3 border border-pink-200 rounded-xl input-focus focus:outline-none"
                                placeholder="10" min="0">
                            <p class="text-xs text-gray-500 mt-1">Peringatan akan muncul jika stok di bawah nilai ini
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Price Section -->
                <div class="section-card p-6">
                    <div class="mb-6 pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <i class="bi bi-currency-dollar mr-2 text-pink-500"></i>
                            Harga Produk
                        </h3>
                        <p class="text-gray-500 text-sm mt-1">Atur berbagai jenis harga untuk produk</p>
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
                                        Unit Equivalent
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        <i class="bi bi-hash mr-1"></i>
                                        Qty
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                        <i class="bi bi-star mr-1"></i>
                                        Default
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $qtyTypes = ['ikat_3', 'ikat_5', 'ikat_10', 'ikat_20', 'normal'];
                                    $noGrosirTypes = ['tangkai', 'reseller', 'promo', 'harga_grosir', 'custom'];
                                @endphp
                                @foreach($priceTypes as $type)
                                                                <tr class="hover:bg-gray-50 transition-colors">
                                                                    <td class="px-6 py-4">
                                                                        <div class="flex items-center">
                                                                            <div
                                                                                class="w-3 h-3 bg-gradient-to-r from-pink-400 to-rose-500 rounded-full mr-3">
                                                                            </div>
                                                                            <span
                                                                                class="font-medium text-gray-800">{{ ucwords(str_replace('_', ' ', $type)) }}</span>
                                                                        </div>
                                                                        <input type="hidden" name="prices[{{ $type }}][type]" value="{{ $type }}">
                                                                    </td>
                                                                    <td class="px-6 py-4">
                                                                        <div class="relative">
                                                                            <div
                                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                                <span class="text-gray-500 text-sm">Rp</span>
                                                                            </div>
                                                                            <input type="text" name="prices[{{ $type }}][price]"
                                                                                value="{{ old("prices.$type.price", isset($existingPrices[$type]->price) ? number_format($existingPrices[$type]->price, 0, ',', '.') : '') }}"
                                                                                class="w-full pl-10 pr-4 py-3 border border-pink-200 rounded-xl input-focus focus:outline-none price-input {{ $errors->has("prices.$type.price") ? 'border-red-300' : '' }}"
                                                                                placeholder="0" autocomplete="off">
                                                                            @error("prices.$type.price")
                                                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                                            @enderror
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-6 py-4">
                                                                        <input type="number" name="prices[{{ $type }}][unit_equivalent]" value="{{ old(
                                        "prices.$type.unit_equivalent",
                                        $existingPrices[$type]->unit_equivalent ??
                                        $defaultUnitEquivalents[$type]
                                    ) }}" class="w-full px-4 py-3 border border-pink-200 rounded-xl input-focus focus:outline-none {{ $errors->has("prices.$type.unit_equivalent") ? 'border-red-300' : '' }}"
                                                                            min="1">
                                                                        @error("prices.$type.unit_equivalent")
                                                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                                        @enderror
                                                                    </td>
                                                                    <td class="px-6 py-4">
                                                                        @if($type === 'harga_grosir')
                                                                            <span class="text-gray-400">-</span>
                                                                        @elseif(in_array($type, $qtyTypes))
                                                                            <div class="flex flex-col gap-1">
                                                                                <input type="number" name="prices[{{ $type }}][min_grosir_qty]"
                                                                                    value="{{ old("prices.$type.min_grosir_qty", $existingPrices[$type]->min_grosir_qty ?? 0) }}"
                                                                                    class="w-full px-4 py-2 border border-blue-200 rounded-xl input-focus focus:outline-none mt-1"
                                                                                    min="0" placeholder="Minimal Grosir">
                                                                                <span class="text-xs text-blue-500">Minimal Grosir</span>
                                                                            </div>
                                                                        @else
                                                                            <span class="text-gray-400">-</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="px-6 py-4">
                                                                        <input type="radio" name="default_price_type" value="{{ $type }}" {{ (old('default_price_type', $existingPrices[$type]->is_default ?? false) ? $type : '') === $type ? 'checked' : '' }}
                                                                            class="form-radio text-pink-600 focus:ring-pink-500 w-5 h-5">
                                                                    </td>
                                                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <div class="flex items-start">
                            <i class="bi bi-info-circle text-blue-500 mr-2 mt-0.5"></i>
                            <div class="text-sm text-blue-700">
                                <p><strong>Catatan:</strong></p>
                                <ul class="mt-1 list-disc list-inside space-y-1">
                                    <li>Harga bersifat opsional - kosongkan jika tidak digunakan</li>
                                    <li>Unit Equivalent menentukan berapa unit dasar dalam satu jenis harga</li>
                                    <li>Pilih satu harga sebagai default untuk perhitungan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-6">
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="bi bi-x-circle mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="bi bi-check-circle mr-2"></i>
                        {{ isset($product) ? 'Update Produk' : 'Simpan Produk' }}
                    </button>
                </div>
            </form>
            <script>
                // Format input harga dengan titik ribuan
                document.querySelectorAll('.price-input').forEach(function (input) {
                    input.addEventListener('input', function (e) {
                        let value = this.value.replace(/[^\d]/g, '');
                        if (value) {
                            this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        } else {
                            this.value = '';
                        }
                    });

                    // Saat form submit, ubah ke format angka tanpa titik
                    input.form && input.form.addEventListener('submit', function () {
                        document.querySelectorAll('.price-input').forEach(function (i) {
                            i.value = i.value.replace(/\./g, '');
                        });
                    });
                });

                // Auto scroll to error sections
                @if($errors->any())
                    document.addEventListener('DOMContentLoaded', function () {
                        const firstError = document.querySelector('.border-red-300');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstError.focus();
                        }
                    });
                @endif
            </script>
        </div>
    </div>
</x-app-layout>