<!-- Side Cart Panel (Keranjang Belanja) -->
<div id="sideCart"
    class="fixed right-0 top-0 h-full w-80 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50">
    <div class="h-full flex flex-col">
        <!-- Cart Header -->
        <div
            class="p-4 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-rose-50 to-pink-50">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="bi bi-bag mr-2"></i> Keranjang Belanja
            </h3>
            <button onclick="toggleCart()" class="text-gray-500 hover:text-gray-700">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-4" id="cartItems">
            <!-- Cart Information Panel -->
            @include('public.partials.unified-cart-info')

            <!-- Cart Items List -->
            @if(session('cart'))
                @foreach(session('cart') as $id => $item)
                    <div class="mb-4 p-3 bg-white rounded-lg border border-gray-200 hover:border-rose-200 transition-colors">
                        <div class="flex justify-between">
                            <!-- Item Details -->
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 text-sm">{{ $item['name'] }}</h4>

                                @if(isset($item['type']) && $item['type'] === 'custom_bouquet')
                                    <!-- Custom Bouquet Details -->
                                    <div class="mt-1 text-xs text-gray-600">
                                        @if(is_array($item['components_summary']))
                                            {{ implode(', ', $item['components_summary']) }}
                                        @else
                                            {{ $item['components_summary'] }}
                                        @endif

                                        <!-- Ribbon Color -->
                                        @if(isset($item['ribbon_color']))
                                            <div class="flex items-center gap-2 mt-2">
                                                <span class="text-xs text-purple-700">Pita:</span>
                                                <div
                                                    class="w-3 h-3 rounded-full
                                                                                {{ $item['ribbon_color'] == 'pink' ? 'bg-pink-400' : '' }}
                                                                                {{ $item['ribbon_color'] == 'red' ? 'bg-red-500' : '' }}
                                                                                {{ $item['ribbon_color'] == 'purple' ? 'bg-purple-500' : '' }}
                                                                                {{ $item['ribbon_color'] == 'gold' ? 'bg-yellow-500' : '' }}
                                                                                {{ $item['ribbon_color'] == 'silver' ? 'bg-gray-400' : '' }}
                                                                                {{ $item['ribbon_color'] == 'white' ? 'bg-white border border-gray-300' : '' }}
                                                                                {{ $item['ribbon_color'] == 'yellow' ? 'bg-yellow-300' : '' }}
                                                                                {{ $item['ribbon_color'] == 'brown' ? 'bg-brown-500' : '' }}
                                                                                {{ $item['ribbon_color'] == 'navy' ? 'bg-blue-800' : '' }}
                                                                                {{ $item['ribbon_color'] == 'light-blue' ? 'bg-blue-300' : '' }}
                                                                                {{ $item['ribbon_color'] == 'dark-blue' ? 'bg-blue-900' : '' }}
                                                                                {{ $item['ribbon_color'] == 'dark-purple' ? 'bg-purple-800' : '' }}
                                                                                {{ $item['ribbon_color'] == 'light-purple' ? 'bg-purple-300' : '' }}
                                                                                {{ $item['ribbon_color'] == 'maroon' ? 'bg-red-800' : '' }}
                                                                                {{ $item['ribbon_color'] == 'tosca' ? 'bg-teal-400' : '' }}
                                                                                {{ $item['ribbon_color'] == 'light-green' ? 'bg-green-300' : '' }}
                                                                                {{ $item['ribbon_color'] == 'dark-green' ? 'bg-green-800' : '' }}
                                                                                {{ $item['ribbon_color'] == 'lime-green' ? 'bg-lime-300' : '' }}
                                                                                {{ $item['ribbon_color'] == 'dark-gray' ? 'bg-gray-600' : '' }}
                                                                                {{ $item['ribbon_color'] == 'dark-brown' ? 'bg-brown-800' : '' }}
                                                                                {{ $item['ribbon_color'] == 'gold' ? 'bg-yellow-500' : '' }}">
                                                </div>
                                                <span class="text-xs text-purple-800 capitalize">{{ $item['ribbon_color'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Price Details -->
                            <div class="text-right ml-4">
                                <div class="text-sm font-semibold text-gray-800">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-gray-500">x {{ $item['qty'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <!-- Cart Footer -->
        <div class="border-t border-gray-200 p-4 bg-white">
            <div class="flex justify-between mb-4">
                <span class="font-semibold">Total:</span>
                <span class="font-bold text-rose-600" id="cartTotal">Rp 0</span>
            </div>
            <a href="{{ route('public.checkout') }}"
                class="block w-full bg-gradient-to-r from-rose-500 to-pink-500 text-white text-center py-3 rounded-xl font-semibold hover:from-rose-600 hover:to-pink-600 transition-all duration-200">
                Lanjut ke Checkout
            </a>
        </div>
    </div>
</div>