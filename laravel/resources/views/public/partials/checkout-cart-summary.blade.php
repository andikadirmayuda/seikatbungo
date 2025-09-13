{{-- Ringkasan Keranjang (Cart Summary) Partial --}}
<div class="bg-white rounded-2xl shadow-lg border border-rose-100 p-6 form-enter w-full">
    <!-- Summary Header -->
    <div class="mb-6 pb-4 border-b border-gray-100">
        <h3 class="text-xl font-bold text-gray-800 flex items-center">
            <i class="bi bi-bag mr-2 text-rose-500"></i>
            Ringkasan Keranjang
        </h3>
    </div>

    <!-- Cart Items -->
    <div class="space-y-4 mb-6">
        @php $total = 0; @endphp
        @foreach($cartData as $item)
            @php 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            @endphp
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        @if(isset($item['type']) && $item['type'] === 'bouquet')
                            <span class="inline-block bg-gradient-to-r from-rose-500 to-pink-500 text-white text-xs px-2 py-1 rounded-full">
                                Bouquet
                            </span>
                        @elseif(isset($item['type']) && $item['type'] === 'custom_bouquet')
                            <span class="inline-block bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs px-2 py-1 rounded-full">
                                Custom Bouquet
                            </span>
                        @else
                            <span class="inline-block bg-gradient-to-r from-green-500 to-teal-500 text-white text-xs px-2 py-1 rounded-full">
                                Bunga
                            </span>
                        @endif
                    </div>
                    <h4 class="font-semibold text-gray-800 text-sm">{{ $item['product_name'] }}</h4>
                    @if($item['price_type'] !== 'default')
                        <p class="text-xs text-gray-500">({{ ucfirst($item['price_type']) }})</p>
                    @endif
                    @if(isset($item['greeting_card']) && !empty($item['greeting_card']))
                        <div class="mt-2 p-2 bg-pink-50 border border-pink-200 rounded-lg">
                            <div class="flex items-center text-pink-700 mb-1">
                                <i class="bi bi-card-text mr-1 text-xs"></i>
                                <span class="font-medium text-xs">Kartu Ucapan:</span>
                            </div>
                            <p class="text-pink-800 italic text-xs whitespace-pre-wrap break-words" style="word-break: break-all;">"{{ $item['greeting_card'] }}"</p>
                        </div>
                    @endif
                    @if(isset($item['type']) && $item['type'] === 'custom_bouquet' && isset($item['components_summary']))
                        <div class="mt-2 p-2 bg-purple-50 border border-purple-200 rounded-lg">
                            <div class="flex items-center text-purple-700 mb-1">
                                <i class="bi bi-palette mr-1 text-xs"></i>
                                <span class="font-medium text-xs">Komponen:</span>
                            </div>
                            <p class="text-purple-800 text-xs">
                                @if(is_array($item['components_summary']))
                                    {{ implode(', ', array_slice($item['components_summary'], 0, 3)) }}
                                    @if(count($item['components_summary']) > 3)
                                        , +{{ count($item['components_summary']) - 3 }} lainnya
                                    @endif
                                @else
                                    {{ $item['components_summary'] }}
                                @endif
                            </p>
                            <!-- Ribbon Color -->
                            @if(isset($item['type']) && $item['type'] === 'custom_bouquet' && !empty($item['ribbon_color']))
                                <div class="flex items-center gap-2 mt-2 pt-2 border-t border-purple-200">
                                    <i class="bi bi-palette2 text-xs text-purple-600"></i>
                                    <span class="text-xs font-medium text-purple-700">Warna Pita:</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded-full" 
                                            style="background-color: {{ App\Enums\RibbonColor::getColorCode($item['ribbon_color']) }};"></div>
                                        <span class="text-xs text-purple-800">
                                            {{ App\Enums\RibbonColor::getColorName($item['ribbon_color']) }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                    @if(isset($item['type']) && $item['type'] === 'custom_bouquet' && isset($item['image']) && !empty($item['image']))
                        <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center text-blue-700 mb-1">
                                <i class="bi bi-image mr-1 text-xs"></i>
                                <span class="font-medium text-xs">Referensi:</span>
                            </div>
                            <img src="{{ asset('storage/' . $item['image']) }}" alt="Reference" class="w-16 h-16 rounded object-cover">
                        </div>
                    @endif
                </div>
                <div class="text-right">
                    <div class="text-sm font-semibold text-gray-800">
                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-500">x {{ $item['quantity'] }}</div>
                    <div class="text-sm font-bold text-rose-600 mt-1">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Voucher Section -->
    <div class="border-t border-gray-200 pt-4 mb-4">
        <!-- Enhanced voucher input section with better styling -->
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="bi bi-ticket-perforated mr-1 text-rose-500"></i>
                Kode Voucher Diskon
            </label>
            <form action="{{ route('voucher.validate') }}" method="POST" class="flex gap-2" id="voucherForm">
                @csrf
                <input type="hidden" name="total_amount" value="{{ $totalAmount }}">
                <div class="flex-1">
                    <input type="text" name="voucher_code" class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none" placeholder="Masukkan kode voucher" required>
                </div>
                <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-rose-500 to-orange-500 text-white font-semibold rounded-xl hover:from-rose-600 hover:to-orange-600 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="bi bi-check2 mr-1"></i>
                    Gunakan
                </button>
            </form>
            <!-- Voucher tips -->
            <div class="mt-2 text-xs text-gray-500 flex items-center">
                <i class="bi bi-lightbulb mr-1"></i>
                Dapatkan diskon menarik dengan memasukkan kode voucher
            </div>
        </div>
        @if(session('voucher_error'))
            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-center text-red-700">
                    <i class="bi bi-exclamation-triangle mr-2"></i>
                    <span class="text-sm font-medium">{{ session('voucher_error') }}</span>
                </div>
            </div>
        @endif
        @if(session('applied_voucher'))
            <div class="mt-4">
                <!-- Dynamic voucher design by type -->
                @php
                    $voucher = session('applied_voucher');
                    $type = $voucher['type'] ?? '';
                    $icon = 'bi-ticket-perforated';
                    $label = 'Voucher Diskon';
                    $mainValue = '';
                    $mainDesc = '';
                    $extra = '';
                    switch ($type) {
                        case 'percent':
                        case 'percentage':
                            $icon = 'bi-percent';
                            $label = 'Diskon Persentase';
                            $mainValue = ($voucher['value'] ?? 0) . '%';
                            $mainDesc = 'Potongan langsung dari total belanja';
                            break;
                        case 'nominal':
                            $icon = 'bi-cash-coin';
                            $label = 'Diskon Nominal';
                            $mainValue = 'Rp ' . number_format($voucher['value'] ?? 0, 0, ',', '.');
                            $mainDesc = 'Potongan harga langsung';
                            break;
                        case 'cashback':
                            $icon = 'bi-wallet2';
                            $label = 'Cashback';
                            $mainValue = 'Rp ' . number_format($voucher['value'] ?? 0, 0, ',', '.');
                            $mainDesc = 'Cashback setelah transaksi selesai';
                            break;
                        case 'shipping':
                            $icon = 'bi-truck';
                            $label = 'Potongan Ongkir';
                            $mainValue = 'Rp ' . number_format($voucher['value'] ?? 0, 0, ',', '.');
                            $mainDesc = 'Potongan biaya pengiriman';
                            break;
                        case 'seasonal':
                            $icon = 'bi-calendar-heart';
                            $label = 'Voucher Event';
                            $mainValue = $voucher['description'] ?? 'Voucher Musiman';
                            $mainDesc = $voucher['event_name'] ?? 'Event Spesial';
                            break;
                        case 'first_purchase':
                            $icon = 'bi-stars';
                            $label = 'Voucher Pembelian Pertama';
                            $mainValue = 'Rp ' . number_format($voucher['value'] ?? 0, 0, ',', '.');
                            $mainDesc = 'Khusus untuk pembelian pertama';
                            break;
                        case 'loyalty':
                            $icon = 'bi-gem';
                            $label = 'Voucher Member';
                            $mainValue = 'Rp ' . number_format($voucher['value'] ?? 0, 0, ',', '.');
                            $mainDesc = 'Khusus member/loyal customer';
                            $extra = $voucher['member_level'] ?? '';
                            break;
                        default:
                            $icon = 'bi-ticket-perforated';
                            $label = 'Voucher Diskon';
                            $mainValue = $voucher['description'] ?? '';
                            $mainDesc = '';
                    }
                @endphp
                <style>
                    .voucher-custom {
                        position: relative;
                        width: 100%;
                        max-width: 100%;
                        height: 110px;
                        background: linear-gradient(to right, #0b3b36 0%, #0b3b36 35%, #f4511e 35%, #f4511e 100%);
                        border-radius: 10px;
                        display: flex;
                        color: white;
                        overflow: hidden;
                        margin: 0 auto 12px auto;
                    }
                    .voucher-custom::before,
                    .voucher-custom::after {
                        content: "";
                        position: absolute;
                        left: 35%;
                        width: 28px;
                        height: 28px;
                        background: white;
                        border-radius: 50%;
                        transform: translateX(-50%);
                    }
                    .voucher-custom::before {
                        top: -14px;
                    }
                    .voucher-custom::after {
                        bottom: -14px;
                    }
                    .voucher-left-custom {
                        flex: 35%;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        font-weight: bold;
                        font-size: 13px;
                        color: #f4511e;
                        background: transparent;
                    }
                    .voucher-right-custom {
                        flex: 65%;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        text-align: center;
                        padding: 6px;
                    }
                    .voucher-right-custom h2 {
                        margin: 0;
                        font-size: 14px;
                        font-weight: bold;
                    }
                    .voucher-right-custom h1 {
                        margin: 2px 0 2px 0;
                        font-size: 28px;
                        font-weight: bold;
                    }
                    .voucher-right-custom small {
                        background: #0b3b36;
                        color: white;
                        padding: 2px 6px;
                        border-radius: 5px;
                        font-size: 10px;
                        margin-top: 2px;
                    }
                    .voucher-remove-btn {
                        position: absolute;
                        top: 6px;
                        right: 6px;
                        background: #ff9800;
                        color: #fff;
                        border: none;
                        border-radius: 50%;
                        width: 22px;
                        height: 22px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        cursor: pointer;
                        transition: background 0.2s;
                        font-size: 13px;
                        box-shadow: 0 2px 6px rgba(255,152,0,0.2);
                    }
                    .voucher-remove-btn {
                        background: #f4511e;
                    }
                </style>
                <div style="display: flex; justify-content: flex-end; align-items: flex-start; margin-bottom: 2px;">
                    <form action="{{ route('checkout.remove-voucher') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="voucher-remove-btn" title="Hapus Voucher" style="position:static;top:auto;right:auto;margin-bottom:2px;"><i class="bi bi-x-lg"></i></button>
                    </form>
                </div>
                <div class="voucher-custom">
                    <div class="voucher-left-custom">
                        {{ $voucher['code'] ?? 'VOUCHER' }}
                    </div>
                    <div class="voucher-right-custom">
                        <h2>{{ strtoupper($label ?? 'VOUCHER') }}</h2>
                        <h1>
                            @if($type === 'cashback')
                                {{ (int) ($voucher['value'] ?? 0) >= 1000 ? ((int) ($voucher['value'] ?? 0) / 1000) : $voucher['value'] }} <span style="font-size:20px;">rb</span>
                            @else
                                {{ $mainValue }}
                            @endif
                        </h1>
                        <small>*min. belanja {{ isset($voucher['minimum_spend']) ? number_format($voucher['minimum_spend'], 0, ',', '.') : '-' }}</small>
                    </div>
                </div>
                <!-- Success message -->
                <div class="mt-3 text-center">
                    <span class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 rounded-xl text-sm font-semibold"><i class="bi bi-check-circle mr-2"></i>Voucher berhasil diterapkan!</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Total -->
    <div class="border-t border-gray-200 pt-4">
        <div class="flex justify-between items-center">
            <span class="text-lg font-bold text-gray-800">Total:</span>
            <div class="text-right">
                @if(session('applied_voucher'))
                    <div class="text-sm text-gray-500 line-through">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </div>
                    <span class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-pink-600">
                        Rp {{ number_format($total - session('applied_voucher.discount'), 0, ',', '.') }}
                    </span>
                @else
                    <span class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-pink-600">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
