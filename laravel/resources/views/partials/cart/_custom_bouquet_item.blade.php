@props(['item'])

<div class="flex items-start space-x-4">
    <!-- Product Image/Icon -->
    <div class="flex-shrink-0">
        @if($item->reference_image)
            <img src="{{ Storage::url($item->reference_image) }}" alt="Custom Bouquet"
                class="w-16 h-16 rounded-lg object-cover">
        @else
            <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-purple-100 to-indigo-100 
                                        flex items-center justify-center">
                <span class="text-2xl">🌸</span>
            </div>
        @endif
    </div>

    <!-- Product Details -->
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2">
            <span class="inline-block bg-gradient-to-r from-purple-500 to-indigo-500 
                         text-white text-xs px-2 py-0.5 rounded-full">
                Custom
            </span>
            <h4 class="text-sm font-medium text-gray-900 truncate">
                {{ $item->name }}
            </h4>
        </div>

        <!-- Components Summary -->
        <p class="mt-1 text-sm text-gray-500">
            {{ $item->components_summary }}
        </p>

        <!-- Ribbon Color -->
        <p class="mt-1 text-sm text-gray-500 flex items-center gap-2">
            Pita:
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 rounded-full inline-block"
                    style="background-color: {{ App\Enums\RibbonColor::getColorCode($item->ribbon_color) }}">
                </span>
                <span>
                    {{ App\Enums\RibbonColor::getColorName($item->ribbon_color) }}
                </span>
            </span>
        </p>

        <!-- Price -->
        <div class="mt-2 flex items-center gap-4">
            <span class="text-sm font-medium text-gray-900">
                Rp {{ number_format($item->price, 0, ',', '.') }}
            </span>
            <div class="flex items-center gap-2">
                <button onclick="updateCartItemQty('{{ $item->id }}', -1)" class="text-gray-500 hover:text-gray-700">
                    <i class="bi bi-dash-circle"></i>
                </button>
                <span class="text-sm font-medium">{{ $item->quantity }}</span>
                <button onclick="updateCartItemQty('{{ $item->id }}', 1)" class="text-gray-500 hover:text-gray-700">
                    <i class="bi bi-plus-circle"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Remove Button -->
    <button onclick="removeFromCart('{{ $item->id }}')" class="flex-shrink-0 text-gray-400 hover:text-red-500">
        <i class="bi bi-x-lg"></i>
    </button>
</div>