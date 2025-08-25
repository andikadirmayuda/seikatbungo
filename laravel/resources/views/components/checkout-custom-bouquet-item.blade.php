@props(['item'])

<div class="custom-bouquet-checkout-item bg-white rounded-lg border border-gray-200 p-4 mb-4">
    <div class="flex items-center gap-2 mb-2">
        <span
            class="inline-block bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs px-2 py-0.5 rounded-full">
            Custom Bouquet
        </span>
        <h4 class="font-medium">{{ $item->name }}</h4>
    </div>

    <div class="space-y-3">
        <!-- Components Summary -->
        <div>
            <span class="text-sm font-medium">Komponen:</span>
            <p class="text-sm text-gray-600 mt-1">
                {{ $item->components_summary }}
            </p>
        </div>

        <!-- Ribbon Color -->
        <div class="flex items-center gap-2">
            <span class="text-sm font-medium">Warna Pita:</span>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full
                    {{ $item->ribbon_color == 'pink' ? 'bg-pink-400' : '' }}
                    {{ $item->ribbon_color == 'red' ? 'bg-red-500' : '' }}
                    {{ $item->ribbon_color == 'purple' ? 'bg-purple-500' : '' }}
                    {{ $item->ribbon_color == 'gold' ? 'bg-yellow-500' : '' }}
                    {{ $item->ribbon_color == 'silver' ? 'bg-gray-400' : '' }}
                    {{ $item->ribbon_color == 'white' ? 'bg-white border border-gray-300' : '' }}">
                </div>
                <span class="text-sm text-gray-600 capitalize">{{ $item->ribbon_color ?? 'Pink' }}</span>
            </div>
        </div>

        <!-- Reference Image if exists -->
        @if($item->reference_image)
            <div>
                <span class="text-sm font-medium">Referensi:</span>
                <div class="mt-2">
                    <img src="{{ Storage::url($item->reference_image) }}" alt="Referensi"
                        class="w-full h-32 object-cover rounded-lg">
                </div>
            </div>
        @endif

        <!-- Price Details -->
        <div class="flex justify-between items-center pt-3 border-t border-gray-200">
            <div class="text-sm">
                <span class="font-medium">{{ $item->quantity }}x</span>
                <span class="text-gray-500">@ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
            </div>
            <div class="font-medium">
                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>