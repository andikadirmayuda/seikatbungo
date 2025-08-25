{{-- Custom Bouquet Card Component --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center mb-6">
        <div class="bg-purple-100 p-3 rounded-lg mr-4">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
            </svg>
        </div>
        <div>
            <h3 class="text-xl font-semibold text-gray-900">Detail Custom Bouquet</h3>
            <p class="text-gray-600">Informasi bouquet custom dan referensi</p>
        </div>
    </div>

    <div class="space-y-6">
        @if($item->type === 'custom_bouquet' || (isset($item->details['type']) && $item->details['type'] === 'custom_bouquet'))
            {{-- Product Information --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-medium text-gray-700 mb-2">Informasi Produk</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama: <span
                                class="font-medium text-gray-800">{{ $item->product_name }}</span></p>
                        <p class="text-sm text-gray-600">Jumlah: <span
                                class="font-medium text-gray-800">{{ $item->quantity }}</span></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Harga: <span class="font-medium text-gray-800">Rp
                                {{ number_format($item->price, 0, ',', '.') }}</span></p>
                        @if(isset($item->details['ribbon_color']))
                            <p class="text-sm text-gray-600">Warna Pita: <span
                                    class="font-medium text-gray-800">{{ $item->details['ribbon_color'] }}</span></p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Components Section --}}
            @if(isset($item->details['items']) && is_array($item->details['items']))
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-medium text-gray-700 mb-2">Komponen Bouquet</h4>
                    <div class="space-y-2">
                        @foreach($item->details['items'] as $component)
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-0">
                                <span class="text-sm text-gray-700">{{ $component['name'] }}</span>
                                <span class="text-sm font-medium text-gray-800">{{ $component['quantity'] }}
                                    {{ $component['unit'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Reference Image Section --}}
            @php
                $referenceImage = $item->reference_image ?? ($item->details['reference_image'] ?? null);
            @endphp
            @if($referenceImage)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-medium text-gray-700 mb-2">Gambar Referensi</h4>
                    <div class="mt-2">
                        <img src="{{ Storage::url($referenceImage) }}" alt="Referensi Custom Bouquet"
                            class="max-w-xs rounded-lg shadow-sm cursor-pointer hover:shadow-md transition-shadow duration-200"
                            onclick="openImageModal(this.src, 'Referensi Custom Bouquet')" />
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>