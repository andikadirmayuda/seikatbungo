@props(['orderItem'])

<div class="custom-bouquet-order-item">
    <!-- Header -->
    <div class="flex items-center gap-2 mb-2">
        <span
            class="inline-block bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs px-2 py-0.5 rounded-full">
            Custom Bouquet
        </span>
        <h4 class="font-medium">{{ $orderItem->customBouquet->name }}</h4>
    </div>

    <!-- Details -->
    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
        <!-- Components -->
        <div>
            <span class="text-sm font-medium">Komponen:</span>
            <p class="text-sm text-gray-600 mt-1">
                {{ $orderItem->customBouquet->getComponentsSummary() }}
            </p>
        </div>

        <!-- Ribbon Color -->
        <div>
            <span class="text-sm font-medium">Warna Pita:</span>
            <span class="text-sm text-gray-600 capitalize">
                {{ $orderItem->customBouquet->ribbon_color ?? 'Pink' }}
            </span>
        </div>

        <!-- Reference Image -->
        @if($orderItem->customBouquet->reference_image)
            <div>
                <span class="text-sm font-medium">Referensi:</span>
                <div class="mt-2">
                    <img src="{{ Storage::url($orderItem->customBouquet->reference_image) }}" alt="Referensi"
                        class="w-full h-32 object-cover rounded-lg">
                </div>
            </div>
        @endif

        <!-- Special Instructions -->
        @if($orderItem->customBouquet->special_instructions)
            <div>
                <span class="text-sm font-medium">Instruksi Khusus:</span>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $orderItem->customBouquet->special_instructions }}
                </p>
            </div>
        @endif

        <!-- Price Details -->
        <div class="flex justify-between items-center pt-3 border-t border-gray-200">
            <div class="text-sm">
                <span class="font-medium">{{ $orderItem->quantity }}x</span>
                <span class="text-gray-500">@ Rp {{ number_format($orderItem->unit_price, 0, ',', '.') }}</span>
            </div>
            <div class="font-medium">
                Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>