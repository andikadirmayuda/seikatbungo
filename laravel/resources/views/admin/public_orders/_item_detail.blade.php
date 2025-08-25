@php
    $cleanName = preg_replace('/\s*\(Komponen:.*?\)\s*/', '', $item->product_name);
    $cleanName = trim($cleanName) ?: $item->product_name;
    $isCustomBouquet = $item->type === 'custom_bouquet' || (isset($item->details['type']) && $item->details['type'] === 'custom_bouquet');
@endphp

<div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-4">
    <!-- Product Header -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-gray-900">{{ $cleanName }}</h3>
            <span class="text-sm font-medium text-gray-700">{{ $item->quantity }}x</span>
        </div>
    </div>

    <!-- Product Details -->
    <div class="p-4">
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <p class="text-sm text-gray-600">Harga Satuan:</p>
                <p class="text-sm font-medium text-gray-900">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Subtotal:</p>
                <p class="text-sm font-medium text-gray-900">
                    Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
            </div>
        </div>

        @if($isCustomBouquet)
            <!-- Custom Bouquet Details -->
            <div class="mt-4 border-t border-gray-200 pt-4">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Detail Custom Bouquet:</h4>

                <!-- Components List -->
                @if(isset($item->details['items']) && is_array($item->details['items']))
                    <div class="space-y-2 mb-4">
                        <p class="text-sm font-medium text-gray-700">Komponen:</p>
                        @foreach($item->details['items'] as $component)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ $component['name'] }}</span>
                                <span class="text-gray-900">{{ $component['quantity'] }} {{ $component['unit'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Ribbon Color -->
                @if(isset($item->details['ribbon_color']))
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700">Warna Pita:</p>
                        <p class="text-sm text-gray-600">{{ $item->details['ribbon_color'] }}</p>
                    </div>
                @endif

                <!-- Reference Image -->
                @php
                    $referenceImage = $item->reference_image ?? ($item->details['reference_image'] ?? null);
                @endphp
                @if($referenceImage)
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Gambar Referensi:</p>
                        <img src="{{ Storage::url($referenceImage) }}" alt="Referensi Custom Bouquet"
                            class="w-32 h-32 object-cover rounded-lg shadow-sm cursor-pointer"
                            onclick="openImageModal(this.src, 'Referensi Custom Bouquet')">
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>