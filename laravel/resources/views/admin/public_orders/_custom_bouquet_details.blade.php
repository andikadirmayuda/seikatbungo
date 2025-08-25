@if(isset($item->details) && is_array($item->details))
    <div class="mt-4 space-y-4">
        <h4 class="text-sm font-medium text-gray-900">Daftar Bunga:</h4>
        @if(isset($item->details['items']) && is_array($item->details['items']))
            <div class="space-y-2">
                @foreach($item->details['items'] as $bouquetItem)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">{{ $bouquetItem['name'] }}</span>
                        <span class="text-gray-900 font-medium">{{ $bouquetItem['quantity'] }} {{ $bouquetItem['unit'] }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">Tidak ada data item custom bouquet.</p>
        @endif

        @if(isset($item->details['ribbon_color']))
            <div class="mt-2">
                <span class="text-sm font-medium text-gray-600">Warna Pita:</span>
                <span class="text-sm text-gray-900 ml-2">{{ $item->details['ribbon_color'] }}</span>
            </div>
        @endif

        @if($item->reference_image || (isset($item->details['reference_image'])))
            <div class="mt-2">
                <span class="text-sm font-medium text-gray-600">Referensi:</span>
                <div class="mt-1">
                    @if($item->reference_image)
                        <img src="{{ Storage::url($item->reference_image) }}" alt="Referensi Custom Bouquet"
                            class="w-32 h-32 object-cover rounded-lg">
                    @elseif(isset($item->details['reference_image']))
                        <img src="{{ Storage::url($item->details['reference_image']) }}" alt="Referensi Custom Bouquet"
                            class="w-32 h-32 object-cover rounded-lg">
                    @endif
                </div>
            </div>
        @endif
    </div>
@endif