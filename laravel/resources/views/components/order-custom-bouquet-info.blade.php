@props(['order'])

@php
    $customBouquetItems = $order->items->filter(function ($item) {
        return $item->item_type === 'custom_bouquet' && (!empty($item->reference_image) || !empty($item->custom_instructions));
    });
@endphp

@if($order->customBouquet || $customBouquetItems->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center mb-6">
            <div class="bg-purple-100 p-3 rounded-lg mr-4">
                <i class="bi bi-palette text-purple-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-semibold text-gray-900">Informasi Custom Bouquet</h3>
                <p class="text-gray-600">Detail bouquet, referensi dan instruksi khusus dari customer</p>
            </div>
        </div>

        @if($order->customBouquet)
            <!-- Basic Custom Bouquet Info -->
            <div class="mb-6">
                <!-- Ribbon Color -->
                <div class="flex items-center gap-2 mb-4 pb-4 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-500">Warna Pita:</span>
                    <div class="flex items-center gap-2">
                        <div
                            class="w-4 h-4 rounded-full
                                    {{ $order->customBouquet->ribbon_color == 'pink' ? 'bg-pink-400' : '' }}
                                    {{ $order->customBouquet->ribbon_color == 'red' ? 'bg-red-500' : '' }}
                                    {{ $order->customBouquet->ribbon_color == 'purple' ? 'bg-purple-500' : '' }}
                                    {{ $order->customBouquet->ribbon_color == 'gold' ? 'bg-yellow-500' : '' }}
                                    {{ $order->customBouquet->ribbon_color == 'silver' ? 'bg-gray-400' : '' }}
                                    {{ $order->customBouquet->ribbon_color == 'white' ? 'bg-white border border-gray-300' : '' }}">
                        </div>
                        <span
                            class="text-sm font-semibold text-gray-900 capitalize">{{ $order->customBouquet->ribbon_color ?? 'Pink' }}</span>
                    </div>
                </div>

                <!-- Custom Bouquet Components -->
                <div class="space-y-3">
                    @foreach($order->customBouquet->items as $component)
                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <img src="{{ $component->product->image_url }}" alt="{{ $component->product->name }}"
                                    class="w-12 h-12 object-cover rounded-lg border border-purple-200">
                                <div>
                                    <h4 class="font-semibold text-purple-900">{{ $component->product->name }}</h4>
                                    <p class="text-sm text-purple-600">{{ $component->quantity }} tangkai</p>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-purple-800">
                                Rp{{ number_format((float) $component->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Total Price -->
                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                    <span class="font-medium text-gray-600">Total Harga Custom Bouquet:</span>
                    <span
                        class="text-lg font-bold text-purple-700">Rp{{ number_format((float) $order->customBouquet->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        @endif

        @if($customBouquetItems->count() > 0)
            <!-- Reference and Instructions -->
            @foreach($customBouquetItems as $item)
                <div class="border border-purple-200 rounded-lg p-6 mb-4 bg-purple-50">
                    <h4 class="font-semibold text-purple-800 mb-4 text-lg">
                        @php
                            $simpleName = preg_replace('/\s*\(.*?\)\s*/', '', $item->product_name);
                            $simpleName = trim($simpleName) ?: 'Custom Bouquet';
                        @endphp
                        {{ $simpleName }}
                    </h4>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Reference Image Section -->
                        @if(!empty($item->reference_image))
                            <div class="bg-white rounded-lg border border-purple-200 p-4">
                                <div class="flex items-center mb-3">
                                    <i class="bi bi-image text-purple-600 text-lg mr-2"></i>
                                    <h5 class="font-semibold text-purple-800">Upload Referensi</h5>
                                </div>

                                <div class="space-y-3">
                                    <div class="relative group cursor-pointer"
                                        onclick="openImageModal('{{ asset('storage/' . $item->reference_image) }}', 'Referensi - {{ $item->product_name }}')">
                                        <img src="{{ asset('storage/' . $item->reference_image) }}" alt="Referensi Custom Bouquet"
                                            class="w-full h-48 object-cover rounded-lg border border-gray-200 transition-transform hover:scale-105">
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                <i class="bi bi-zoom-in text-white text-2xl"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex gap-2">
                                        <button
                                            onclick="openImageModal('{{ asset('storage/' . $item->reference_image) }}', 'Referensi - {{ $item->product_name }}')"
                                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors">
                                            <i class="bi bi-zoom-in mr-1"></i>Lihat
                                        </button>
                                        <a href="{{ asset('storage/' . $item->reference_image) }}"
                                            download="referensi-{{ Str::slug($item->product_name) }}.jpg"
                                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors text-center">
                                            <i class="bi bi-download mr-1"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Custom Instructions Section -->
                        @if(!empty($item->custom_instructions))
                            <div class="bg-white rounded-lg border border-purple-200 p-4">
                                <div class="flex items-center mb-3">
                                    <i class="bi bi-chat-left-text text-purple-600 text-lg mr-2"></i>
                                    <h5 class="font-semibold text-purple-800">Instruksi Khusus</h5>
                                </div>

                                <div class="bg-purple-50 border border-purple-200 rounded p-4">
                                    <p class="text-purple-800 leading-relaxed">"{{ $item->custom_instructions }}"</p>
                                </div>

                                <div class="mt-3 text-sm text-purple-600">
                                    <i class="bi bi-info-circle mr-1"></i>
                                    Instruksi dari customer untuk pembuatan bouquet
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endif