@props(['orderItem'])

<div class="bg-white rounded-xl border border-purple-200 shadow-sm p-4">
    <div class="flex items-center mb-4">
        <div class="bg-purple-100 p-2 rounded-lg mr-3">
            <i class="bi bi-flower1 text-purple-600 text-lg"></i>
        </div>
        <h4 class="font-bold text-purple-800 text-sm sm:text-base">Edit Custom Bouquet</h4>
    </div>

    <form action="{{ route('public.orders.custom-bouquet.update', $orderItem) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Components Summary (Read-only) -->
        <div>
            <span class="text-sm font-medium text-purple-700">Komponen:</span>
            <p class="text-sm text-gray-600 mt-1">
                {{ $orderItem->customBouquet->getComponentsSummary() }}
            </p>
        </div>

        <!-- Ribbon Color Selection -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-purple-700">Warna Pita</label>
            <div class="grid grid-cols-3 gap-2">
                @foreach(['pink', 'red', 'purple', 'gold', 'silver', 'white'] as $color)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="ribbon_color" value="{{ $color }}" 
                               {{ ($orderItem->customBouquet->ribbon_color ?? 'pink') == $color ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="p-2 text-center border-2 rounded-lg peer-checked:border-purple-500 peer-checked:bg-purple-50">
                            <div class="w-8 h-8 mx-auto mb-1 rounded-full
                                      {{ $color == 'pink' ? 'bg-pink-400' : '' }}
                                      {{ $color == 'red' ? 'bg-red-500' : '' }}
                                      {{ $color == 'purple' ? 'bg-purple-500' : '' }}
                                      {{ $color == 'gold' ? 'bg-yellow-500' : '' }}
                                      {{ $color == 'silver' ? 'bg-gray-400' : '' }}
                                      {{ $color == 'white' ? 'bg-white border-2' : '' }}">
                            </div>
                            <span class="text-sm capitalize">{{ $color }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Special Instructions -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-purple-700">Instruksi Khusus</label>
            <textarea name="special_instructions" rows="3"
                      class="w-full px-3 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ $orderItem->customBouquet->special_instructions }}</textarea>
        </div>

        <!-- Quantity -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-purple-700">Jumlah</label>
            <div class="flex items-center gap-2">
                <button type="button" onclick="decrementQty()"
                        class="p-2 rounded-lg border border-purple-200 hover:bg-purple-50">
                    <i class="bi bi-dash"></i>
                </button>
                <input type="number" name="quantity" value="{{ $orderItem->quantity }}"
                       class="w-20 text-center border-purple-200 rounded-lg"
                       min="1" required>
                <button type="button" onclick="incrementQty()"
                        class="p-2 rounded-lg border border-purple-200 hover:bg-purple-50">
                    <i class="bi bi-plus"></i>
                </button>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-purple-200">
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-medium py-2 px-4 rounded-lg hover:from-purple-600 hover:to-indigo-600 transition-colors">
                Update Custom Bouquet
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function decrementQty() {
        const input = document.querySelector('input[name="quantity"]');
        if (input.value > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }

    function incrementQty() {
        const input = document.querySelector('input[name="quantity"]');
        input.value = parseInt(input.value) + 1;
    }
</script>
@endpush
