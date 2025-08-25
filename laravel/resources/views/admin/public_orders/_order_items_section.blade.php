<!-- Order Items Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center mb-6">
        <div class="bg-purple-100 p-2 rounded-lg mr-3">
            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M5 3a2 2 0 012-2h6a2 2 0 012 2v2H5V3z" />
                <path fill-rule="evenodd"
                    d="M3 6h14l-1 9H4L3 6zm11 11a2 2 0 11-4 0 2 2 0 014 0zm-6 0a2 2 0 11-4 0 2 2 0 014 0z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <h3 class="text-xl font-semibold text-gray-900">Detail Item Pesanan</h3>
            <p class="text-sm text-gray-600">Daftar item yang dipesan</p>
        </div>
    </div>

    <div class="space-y-4">
        @foreach($order->items as $item)
            @include('admin.public_orders._item_detail', ['item' => $item])
        @endforeach
    </div>

    <!-- Order Summary -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="space-y-2">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Subtotal:</span>
                <span
                    class="text-sm font-medium text-gray-900">Rp{{ number_format($order->items->sum(function ($item) {
    return $item->price * $item->quantity; }), 0, ',', '.') }}</span>
            </div>
            @if($order->shipping_fee > 0)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Ongkir:</span>
                    <span
                        class="text-sm font-medium text-gray-900">Rp{{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                <span class="text-base font-medium text-gray-900">Total:</span>
                <span
                    class="text-base font-medium text-gray-900">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>