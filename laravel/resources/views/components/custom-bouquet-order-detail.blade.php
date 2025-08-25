<?php 
// resources/views/components/custom-bouquet-order-detail.blade.php
?>
@props(['item'])

<div
    class="bg-gradient-to-br from-purple-50 via-purple-50 to-indigo-50 border-2 border-purple-200 rounded-2xl shadow-lg overflow-hidden mb-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-4 sm:px-6 py-3 sm:py-4">
        <div class="flex items-center">
            <div
                class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full mr-3 sm:mr-4">
                <i class="bi bi-palette text-white text-lg sm:text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-white text-base sm:text-lg">Custom Bouquet</h3>
                <p class="text-purple-100 text-xs sm:text-sm">
                    @php
                        // Extract simple product name without components info
                        $simpleName = preg_replace('/\s*\(.*?\)\s*/', '', $item->product_name);
                        $simpleName = trim($simpleName) ?: 'Custom Bouquet';
                    @endphp
                    {{ $simpleName }}
                </p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-4 sm:p-6 space-y-6">
        <!-- Reference Image Section -->
        @php
            $referenceImage = null;
            if (!empty($item->reference_image)) {
                $referenceImage = $item->reference_image;
            } elseif (!empty($item->custom_bouquet_id)) {
                $customBouquet = \App\Models\CustomBouquet::find($item->custom_bouquet_id);
                if ($customBouquet && !empty($customBouquet->reference_image)) {
                    $referenceImage = $customBouquet->reference_image;
                }
            }
        @endphp

        @if($referenceImage)
            <div class="bg-white rounded-xl border border-purple-200 p-4 sm:p-6 shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-2 rounded-lg mr-3">
                        <i class="bi bi-image text-purple-600 text-lg"></i>
                    </div>
                    <h4 class="font-bold text-purple-800 text-sm sm:text-base">Gambar Referensi</h4>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Image Preview -->
                    <div class="space-y-3">
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $referenceImage) }}" alt="Referensi Custom Bouquet"
                                class="w-full h-64 sm:h-80 object-cover rounded-lg border-2 border-purple-200 shadow-md cursor-pointer transition-transform hover:scale-105"
                                onclick="openImageModal('{{ asset('storage/' . $referenceImage) }}')">
                            <!-- Zoom overlay -->
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <i class="bi bi-zoom-in text-white text-3xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button onclick="openImageModal('{{ asset('storage/' . $referenceImage) }}')"
                                class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-sm">
                                <i class="bi bi-zoom-in mr-2"></i>Lihat Gambar
                            </button>
                            <a href="{{ asset('storage/' . $referenceImage) }}" download="referensi-custom-bouquet.jpg"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-sm text-center">
                                <i class="bi bi-download mr-2"></i>Download
                            </a>
                        </div>

                        <!-- Simple Status -->
                        <div class="text-center">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Terupload
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Custom Instructions Section -->
        @if(!empty($item->custom_instructions))
            <div class="bg-white rounded-xl border border-purple-200 p-4 sm:p-6 shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-2 rounded-lg mr-3">
                        <i class="bi bi-chat-left-text text-purple-600 text-lg"></i>
                    </div>
                    <h4 class="font-bold text-purple-800 text-sm sm:text-base">Instruksi Khusus</h4>
                </div>

                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="text-sm sm:text-base text-purple-800 leading-relaxed font-medium">
                        "{{ $item->custom_instructions }}"
                    </div>
                </div>

                <div class="mt-4 text-xs sm:text-sm text-purple-600">
                    <i class="bi bi-info-circle mr-1"></i>
                    Instruksi ini akan disampaikan kepada tim florist untuk memastikan bouquet sesuai dengan keinginan Anda.
                </div>
            </div>
        @endif

        <!-- Custom Bouquet Components Section -->
        @if($item->custom_bouquet_id)
            @php
                $customBouquet = \App\Models\CustomBouquet::with(['items.product'])->find($item->custom_bouquet_id);
            @endphp

            @if($customBouquet && $customBouquet->items->count() > 0)
                <div class="bg-white rounded-xl border border-purple-200 p-4 sm:p-6 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-2 rounded-lg mr-3">
                            <i class="bi bi-flower1 text-purple-600 text-lg"></i>
                        </div>
                        <h4 class="font-bold text-purple-800 text-sm sm:text-base">Komponen Bouquet</h4>
                    </div>

                    <!-- Ribbon Color Display -->
                    <div class="flex flex-wrap items-center gap-2 mb-4 pb-4 border-b border-purple-100">
                        <span class="text-xs sm:text-sm font-medium text-purple-700">Warna Pita:</span>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 sm:w-4 sm:h-4 rounded-full"
                                style="background-color: {{ App\Enums\RibbonColor::getColorCode($customBouquet->ribbon_color) }}">
                            </div>
                            <span class="text-xs sm:text-sm text-purple-800">
                                {{ App\Enums\RibbonColor::getColorName($customBouquet->ribbon_color) }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach($customBouquet->items as $component)
                            <div
                                class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white border border-purple-100 rounded-lg p-3 sm:p-4 hover:bg-purple-50 transition-colors">
                                <div class="flex items-start sm:items-center space-x-3 sm:space-x-4 w-full sm:w-auto mb-3 sm:mb-0">
                                    <!-- Icon Bunga -->
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                            <i class="bi bi-flower1 text-purple-600 text-base sm:text-lg"></i>
                                        </div>
                                    </div>
                                    <!-- Informasi Produk (Kiri) -->
                                    <div class="flex flex-col min-w-0 flex-1 sm:flex-initial">
                                        <h4 class="font-medium text-purple-800 text-sm sm:text-base">
                                            {{ $component->product->name }}
                                        </h4>
                                        <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">
                                                {{ $component->price_type_display }}
                                            </span>
                                            @if($component->formatted_quantity)
                                                <span class="text-xs text-purple-500 font-medium">
                                                    {{ $component->formatted_quantity }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Jumlah -->
                                <div class="flex-shrink-0 w-full sm:w-auto">
                                    <div
                                        class="bg-purple-100 px-3 py-2 rounded-lg flex sm:flex-col items-center justify-between sm:justify-center sm:min-w-[80px]">
                                        <span
                                            class="font-semibold text-purple-800 text-sm sm:text-base sm:mb-0.5 order-2 sm:order-1">
                                            {{ $component->quantity }}
                                        </span>
                                        <span class="text-xs text-purple-600 font-medium order-1 sm:order-2">
                                            {{ $component->product->base_unit ?? 'pcs' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- <div class="mt-4 p-3 bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-purple-800 text-sm sm:text-base">Total Harga:</span>
                            <span class="font-bold text-lg text-purple-800">Rp
                                {{ number_format((float) ($customBouquet->total_price ?? 0), 0, ',', '.') }}</span>
                        </div>
                    </div> --}}

                    <div class="mt-3 text-xs sm:text-sm text-purple-600">
                        <i class="bi bi-info-circle mr-1"></i>
                        Komponen-komponen yang Anda pilih untuk custom bouquet.
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>