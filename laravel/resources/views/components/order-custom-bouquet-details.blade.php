@props(['orderItem'])

<div class="bg-white rounded-xl border border-purple-200 shadow-sm p-4">
    <div class="flex items-center mb-4">
        <div class="bg-purple-100 p-2 rounded-lg mr-3">
            <i class="bi bi-flower1 text-purple-600 text-lg"></i>
        </div>
        <h4 class="font-bold text-purple-800 text-sm sm:text-base">Custom Bouquet Details</h4>
    </div>

    <div class="space-y-4">
        <!-- Components Summary -->
        <div>
            <span class="text-sm font-medium text-purple-700">Komponen:</span>
            <p class="text-sm text-gray-600 mt-1">
                {{ $orderItem->customBouquet->getComponentsSummary() }}
            </p>
        </div>

        <!-- Ribbon Color -->
        <div class="flex items-center gap-2">
            <span class="text-sm font-medium text-purple-700">Warna Pita:</span>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full 
                    {{ $orderItem->customBouquet->ribbon_color == 'pink' ? 'bg-pink-400' : '' }}
                    {{ $orderItem->customBouquet->ribbon_color == 'red' ? 'bg-red-500' : '' }}
                    {{ $orderItem->customBouquet->ribbon_color == 'purple' ? 'bg-purple-500' : '' }}
                    {{ $orderItem->customBouquet->ribbon_color == 'gold' ? 'bg-yellow-500' : '' }}
                    {{ $orderItem->customBouquet->ribbon_color == 'silver' ? 'bg-gray-400' : '' }}
                    {{ $orderItem->customBouquet->ribbon_color == 'white' ? 'bg-white border border-gray-300' : '' }}">
                </div>
                <span
                    class="text-sm text-purple-800 capitalize">{{ $orderItem->customBouquet->ribbon_color ?? 'Pink' }}</span>
            </div>
        </div>

        <!-- Reference Image -->
        @if($orderItem->customBouquet->reference_image)
            <div>
                <span class="text-sm font-medium text-purple-700">Referensi:</span>
                <div class="mt-2">
                    <img src="{{ Storage::url($orderItem->customBouquet->reference_image) }}" alt="Referensi"
                        class="w-full h-32 object-cover rounded-lg">
                </div>
            </div>
        @endif

        <!-- Special Instructions -->
        @if($orderItem->customBouquet->special_instructions)
            <div>
                <span class="text-sm font-medium text-purple-700">Instruksi Khusus:</span>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $orderItem->customBouquet->special_instructions }}
                </p>
            </div>
        @endif

        <!-- Price Details -->
        <div class="flex justify-between items-center pt-3 border-t border-purple-200">
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