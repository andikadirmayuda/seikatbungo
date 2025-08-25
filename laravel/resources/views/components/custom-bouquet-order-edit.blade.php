@props(['orderItem'])

<div class="custom-bouquet-order-edit">
    <h3 class="text-lg font-medium mb-4">Edit Custom Bouquet</h3>

    <form action="{{ route('order.update.custom-bouquet', $orderItem->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Basic Info -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Nama Custom Bouquet</label>
            <input type="text" name="name" value="{{ $orderItem->customBouquet->name }}"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
        </div>

        <!-- Components (Read Only) -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Komponen</label>
            <div class="bg-gray-50 rounded-md p-3 text-sm text-gray-600">
                {{ $orderItem->customBouquet->getComponentsSummary() }}
            </div>
        </div>

        <!-- Ribbon Color -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Warna Pita</label>
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
            <label class="block text-sm font-medium text-gray-700">Instruksi Khusus</label>
            <textarea name="special_instructions" rows="3"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">{{ $orderItem->customBouquet->special_instructions }}</textarea>
        </div>

        <!-- Quantity -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
            <input type="number" name="quantity" value="{{ $orderItem->quantity }}" min="1"
                   class="w-32 rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
