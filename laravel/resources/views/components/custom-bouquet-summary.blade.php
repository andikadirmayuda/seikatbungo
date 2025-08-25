@props(['customBouquet'])

<div class="custom-bouquet-summary">
    <div class="flex items-center gap-2 mb-2">
        <span
            class="inline-block bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs px-2 py-0.5 rounded-full">Custom</span>
        <h3 class="font-medium">{{ $customBouquet->name }}</h3>
    </div>

    <div class="text-sm text-gray-600 space-y-1">
        <!-- Components List -->
        <div>
            <span class="font-medium">Komponen:</span>
            {{ $customBouquet->getComponentsSummary() }}
        </div>

        <!-- Ribbon Color -->
        <div>
            <span class="font-medium">Warna Pita:</span>
            <span class="capitalize">{{ $customBouquet->ribbon_color ?? 'Pink' }}</span>
        </div>

        @if($customBouquet->reference_image)
            <!-- Reference Image -->
            <div class="mt-2">
                <span class="font-medium">Referensi:</span>
                <img src="{{ Storage::url($customBouquet->reference_image) }}" alt="Referensi Custom Bouquet"
                    class="mt-1 w-full h-24 object-cover rounded-lg">
            </div>
        @endif

        @if($customBouquet->special_instructions)
            <!-- Special Instructions -->
            <div class="mt-2">
                <span class="font-medium">Instruksi Khusus:</span>
                <p class="mt-1 text-sm text-gray-500">{{ $customBouquet->special_instructions }}</p>
            </div>
        @endif
    </div>
</div>