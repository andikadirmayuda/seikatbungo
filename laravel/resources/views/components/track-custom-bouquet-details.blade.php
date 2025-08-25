@props(['orderItem'])

<div class="bg-white rounded-xl border border-purple-200 shadow-sm p-4">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center">
            <div class="bg-purple-100 p-2 rounded-lg mr-3">
                <i class="bi bi-flower1 text-purple-600 text-lg"></i>
            </div>
            <h4 class="font-bold text-purple-800 text-sm sm:text-base">Tracking Custom Bouquet</h4>
        </div>
        <span class="bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full">
            Status: {{ ucfirst($orderItem->order->status) }}
        </span>
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

        <!-- Order Progress Timeline -->
        <div class="mt-6 border-t border-purple-200 pt-4">
            <div class="flex items-center mb-2">
                <i class="bi bi-clock-history text-purple-600 mr-2"></i>
                <span class="text-sm font-medium text-purple-700">Progress Pesanan</span>
            </div>
            <div class="relative pt-2">
                <div class="absolute left-2 top-2 bottom-2 w-0.5 bg-purple-200"></div>
                <div class="space-y-4 relative">
                    @foreach($orderItem->order->statusHistory as $history)
                        <div class="flex items-start">
                            <div class="w-4 h-4 rounded-full bg-purple-500 mt-1 -ml-2 z-10"></div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-800">{{ ucfirst($history->status) }}</p>
                                <p class="text-xs text-gray-500">{{ $history->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>