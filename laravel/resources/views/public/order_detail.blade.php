<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan - Seikat Bungo</title>
    <link rel="icon" href="{{ asset(config('app.logo')) }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Notification Styles -->
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
</head>

<style>

/* Professional Name Styles */
    .professional-name {
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 1.5px 6px;
        font-size: 0.60rem;
        border: 1px solid rgba(0, 0, 0, 0.18);
        border-radius: 5px;
        background: rgba(255, 255, 255, 0.05);
        color: #666666;
        text-decoration: none;
        transition: all 0.3s ease-in-out;
    }

    @media (max-width: 600px) {
        .professional-name {
            font-size: 0.52rem;
            padding: 1px 4px;
            border-width: 1px;
        }
    }
    .professional-name:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: #58B8AB;
    box-shadow: 0 0 10px rgba(255,255,255,0.4);
    transform: translateY(-2px);
    color: #58B8AB;
    }
    .professional-name:hover i {
        color: #58B8AB; /* hijau soft */
    }

</style>

<body class="bg-gray-100 min-h-screen">
    <div
        class="container mx-auto px-2 sm:px-4 py-4 sm:py-8 flex justify-center items-center min-h-screen text-[13px] sm:text-base">
        <div class="bg-white rounded-2xl shadow-xl p-2 sm:p-10 w-full max-w-5xl mx-auto">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative">
                    <div class="flex items-center">
                        <i class="bi bi-check-circle-fill mr-2"></i>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="text-center mb-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-pink-600 tracking-tight mb-1 flex items-center justify-center gap-2"
                    style="color:#247A72">
                    <i class="bi bi-flower1 text-pink-400 text-2xl" style="color:#247A72"></i> Seikat Bungo
                </h1>
                <p class="text-gray-500 text-xs sm:text-base font-medium">Detail Pemesanan</p>
                <div class="mt-2 mb-4">
                    <span class="text-sm text-gray-600">Kode Pesanan:</span>
                    <span class="font-mono font-bold text-lg text-pink-600 bg-pink-50 px-3 py-1 rounded-lg border"style="color:#E59420">{{ $order->public_code }}</span>
                </div>
            </div>
            <!-- Status Badge -->
            <div class="flex flex-col sm:flex-row gap-2 justify-center items-center mb-6">
                @php
$statusIndo = [
    'pending' => 'Menunggu',
    'confirmed' => 'Dikonfirmasi',
    'processing' => 'Diproses',
    'processed' => 'Diproses',
    'packing' => 'Sedang Dikemas',
    'ready' => 'Sudah Siap',
    'shipping' => 'Dikirim',
    'shipped' => 'Dikirim',
    'delivered' => 'Terkirim',
    'completed' => 'Selesai',
    'cancelled' => 'Dibatalkan'
];
                @endphp
                <span
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full font-semibold text-white text-xs sm:text-sm shadow"
                    style="background:#247A72;">
                    <i class="bi bi-clipboard-check"></i> {{ $statusIndo[$order->status] ?? ucfirst($order->status) }}
                </span>
                @php
$paymentStatusMap = [
    'waiting_confirmation' => 'Menunggu Konfirmasi Stok',
    'ready_to_pay' => 'Siap Dibayar',
    'waiting_payment' => 'Menunggu Pembayaran',
    'waiting_verification' => 'Menunggu Verifikasi Pembayaran',
    'paid' => 'Lunas',
    'rejected' => 'Pembayaran Ditolak',
    'cancelled' => 'Dibatalkan',
];
$paymentBg = match ($order->payment_status) {
    'paid' => '#16a34a',
    'ready_to_pay' => '#f59e42',
    'waiting_confirmation' => '#64748b',
    'waiting_payment' => '#f59e42',
    'waiting_verification' => '#f59e42',
    'rejected' => '#dc2626',
    'cancelled' => '#6b7280',
    default => '#64748b',
};
                @endphp
                <span
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full font-semibold text-white text-xs sm:text-sm shadow" 
                    style="background:{{ $paymentBg }};">
                    <i class="bi bi-cash-coin"></i>
                    {{ $paymentStatusMap[$order->payment_status] ?? ucfirst($order->payment_status) }}
                </span>
            </div>
            <!-- Stepper Status Responsive Split for Mobile -->
            <div class="w-full mb-8">
                @php
// Base steps untuk flow normal pesanan
$baseSteps = [
    'pending' => 'Pesanan Diterima',
    'processing' => 'Diproses',
    'packing' => 'Dikemas',
    'ready' => 'Sudah Siap',
    'shipped' => 'Dikirim',
    'done' => 'Selesai',
];

// Tambahkan status dibatalkan hanya jika pesanan dibatalkan
$steps = $baseSteps;
if (in_array(strtolower($order->status), ['cancelled', 'canceled'])) {
    $steps['cancelled'] = 'Dibatalkan';
}

$statusMap = [
    'pending' => 'pending',
    'processed' => 'processing',
    'processing' => 'processing',
    'packing' => 'packing',
    'ready' => 'ready',
    'shipped' => 'shipped',
    'done' => 'done',
    'completed' => 'done',
    'cancelled' => 'cancelled',
    'canceled' => 'cancelled',
];
$currentStatus = strtolower($order->status);
$currentStatus = $statusMap[$currentStatus] ?? $currentStatus;
$stepKeys = array_keys($steps);
$currentIndex = array_search($currentStatus, $stepKeys);
                @endphp
                <!-- Mobile: 2 rows, Desktop: 1 row -->
                @php 
                    $isCancelled = in_array(strtolower($order->status), ['cancelled', 'canceled']);
                @endphp
                
                @if($isCancelled)
                    <!-- Special layout untuk pesanan dibatalkan di desktop -->
                    <div class="hidden sm:flex justify-center">
                        <div class="inline-flex items-center px-6 py-3 bg-red-100 border border-red-300 rounded-xl">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-x text-white text-lg"></i>
                            </div>
                            <span class="text-red-700 font-semibold text-lg">Pesanan Dibatalkan</span>
                        </div>
                    </div>
                @else
                    <!-- Layout normal untuk desktop -->
                    <div class="hidden sm:flex flex-nowrap justify-between items-center w-full gap-2 px-1">
                        @foreach($steps as $key => $label)
                            <div class="flex flex-col items-center min-w-[44px] max-w-[60px] flex-shrink-0">
                                <div
                                    class="rounded-full w-8 h-8 flex items-center justify-center mb-1 text-[15px] font-bold {{ $currentStatus === $key || $currentIndex > array_search($key, $stepKeys) ? 'bg-pink-600 text-white shadow-lg' : 'bg-gray-200 text-gray-400' }}">
                                    {{ $loop->iteration }}
                                </div>
                                <div class="text-xs text-center font-medium leading-tight {{ $currentStatus === $key || $currentIndex > array_search($key, $stepKeys) ? 'text-pink-600' : 'text-gray-400' }}" style="color:#2D9C8F; word-break:break-word;">{{ $label }}</div>
                            </div>
                            @if(!$loop->last)
                                <div
                                    class="h-1 w-5 bg-gray-200 mt-4 flex-shrink-0 {{ $currentIndex >= $loop->index ? 'bg-pink-500' : '' }}">
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
                <div class="flex flex-col gap-1 sm:hidden">
                    @php 
                                                $stepCount = count($steps);
$isCancelled = in_array(strtolower($order->status), ['cancelled', 'canceled']);
                    @endphp
                    
                    @if($isCancelled)
                        <!-- Special layout untuk pesanan dibatalkan -->
                        <div class="text-center">
                            <div class="inline-flex items-center px-4 py-2 bg-red-100 border border-red-300 rounded-lg">
                                <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center mr-2">
                                    <i class="bi bi-x text-white text-sm"></i>
                                </div>
                                <span class="text-red-700 font-semibold text-sm">Pesanan Dibatalkan</span>
                            </div>
                        </div>
                    @else
                        <!-- Layout normal untuk 6 steps (3 + 3) -->
                        <div class="grid grid-cols-3 w-full gap-0 px-1">
                            @foreach(array_slice($stepKeys, 0, 3) as $i => $key)
                                <div class="flex flex-col items-center">
                                    <div
                                        class="rounded-full w-6 h-6 flex items-center justify-center mb-0.5 text-[11px] font-bold {{ $currentStatus === $key || $currentIndex > array_search($key, $stepKeys) ? 'bg-pink-600 text-white shadow-lg' : 'bg-gray-200 text-gray-400' }}">
                                        {{ $i + 1 }}
                                    </div>
                                    <div class="text-[9px] text-center font-medium leading-tight {{ $currentStatus === $key || $currentIndex > array_search($key, $stepKeys) ? 'text-pink-600' : 'text-gray-400' }}"
                                        style="word-break:break-word;">{{ $steps[$key] }}</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="grid grid-cols-3 w-full gap-0 px-1 mt-1">
                            @foreach(array_slice($stepKeys, 3, 3) as $i => $key)
                                <div class="flex flex-col items-center">
                                    <div
                                        class="rounded-full w-6 h-6 flex items-center justify-center mb-0.5 text-[11px] font-bold {{ $currentStatus === $key || $currentIndex > array_search($key, $stepKeys) ? 'bg-pink-600 text-white shadow-lg' : 'bg-gray-200 text-gray-400' }}">
                                        {{ $i + 4 }}
                                    </div>
                                    <div class="text-[9px] text-center font-medium leading-tight {{ $currentStatus === $key || $currentIndex > array_search($key, $stepKeys) ? 'text-pink-600' : 'text-gray-400' }}"
                                        style="word-break:break-word;">{{ $steps[$key] }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <!-- Info Pemesanan (3 Columns) -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-center items-stretch gap-2 sm:gap-4 w-full mb-2">
                    <!-- Kiri -->
                    <div
                        class="flex-1 min-w-0 max-w-full bg-gray-50 rounded-xl p-3 sm:p-4 flex flex-col justify-center items-start shadow-sm border border-gray-100 text-xs sm:text-sm mb-2 sm:mb-0">
                        <div class="mb-1 sm:mb-2">
                            <span class="text-gray-500">Nama Pemesan</span><br>
                            <span class="font-bold text-gray-800 break-words">{{ $order->customer_name }}</span>
                        </div>
                        <div class="mb-1 sm:mb-2">
                            <span class="text-gray-500">Nama Penerima</span><br>
                            <span class="font-bold text-gray-800 break-words">{{ $order->receiver_name ?: '-' }}</span>
                        </div>
                        <div class="mb-1 sm:mb-2">
                            <span class="text-gray-500">Tanggal Ambil/Kirim</span><br>
                            <span class="font-bold text-gray-800 break-words">{{ \Carbon\Carbon::parse($order->pickup_date)->format('d-m-Y') }}</span>
                            <span class="text-sm text-red-600 ml-2">({{ \Carbon\Carbon::parse($order->pickup_date)->locale('id')->dayName }})</span>
                        </div>
                        <div class="mb-1 sm:mb-2">
                            <span class="text-gray-500">Metode Pengiriman</span><br>
                            <span class="font-bold text-gray-800 break-words">{{ $order->delivery_method }}</span>
                        </div>
                    </div>
                    <!-- Tengah -->
                    <div
                        class="flex-1 min-w-0 max-w-full bg-gray-50 rounded-xl p-3 sm:p-4 flex flex-col justify-center items-start shadow-sm border border-gray-100 text-xs sm:text-sm mb-2 sm:mb-0">
                        <div class="mb-1 sm:mb-2">
                            <span class="text-gray-500">No. WhatsApp Pemesan</span><br>
                            <span class="font-bold text-gray-800 break-words">{{ $order->wa_number }}</span>
                        </div>
                        <div class="mb-1 sm:mb-2">
                            <span class="text-gray-500">No. WhatsApp Penerima</span><br>
                            <span class="font-bold text-gray-800 break-words">{{ $order->receiver_wa ?: '-' }}</span>
                        </div>
                        <div class="mb-1 sm:mb-2">
                            <span class="text-gray-500">Waktu Ambil/Pengiriman</span><br>
                            <span class="font-bold text-gray-800 break-words">{{ $order->pickup_time }}</span>
                            @php
$hour = (int) substr($order->pickup_time, 0, 2);
$timeOfDay = match (true) {
    $hour >= 5 && $hour < 11 => 'Pagi',
    $hour >= 11 && $hour < 15 => 'Siang',
    $hour >= 15 && $hour < 18 => 'Sore',
    default => 'Malam'
};
                            @endphp
                            <span class="text-sm text-blue-600 ml-2">({{ $timeOfDay }})</span>
                        </div>
                        <div class="mb-1 sm:mb-2">
                            <span class="text-gray-500">Tujuan Pengiriman</span><br>
                            <span class="font-bold text-gray-800 break-words">{{ $order->destination }}</span>
                        </div>
                    </div>
                    <!-- Kanan: Informasi Penting -->
                    <div class="flex-1 min-w-0 max-w-full flex flex-col justify-center items-center">
                        <div
                            class="w-full h-full flex flex-col justify-center items-center border-2 border-gray-300 rounded-xl p-3 sm:p-4 bg-white shadow-sm text-xs sm:text-sm">
                            <div class="text-center font-bold text-red-600 text-xs sm:text-base mb-1 sm:mb-2">Informasi
                                Penting !!!</div>
                            <div class="text-xs sm:text-sm text-gray-700 leading-relaxed text-center break-words">
                                {{ $order->info ?? 'Harap Dibaca seluruh informasinya, Jika ada pertanyaan silahkan hubungi kami' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Catatan Pesanan -->
            @if(!empty($order->notes))
                <div class="mb-4 sm:mb-6">
                    <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-blue-100 border border-blue-200 rounded-lg sm:rounded-xl shadow-sm overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-3 sm:px-6 py-2 sm:py-3" style="background:#247A72">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 bg-white bg-opacity-20 rounded-full mr-2 sm:mr-3">
                                    <i class="bi bi-chat-left-text text-white text-xs sm:text-sm"></i>
                                </div>
                                <h3 class="font-bold text-white text-sm sm:text-base lg:text-lg">Catatan Pesanan</h3>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="p-3 sm:p-4 lg:p-6">
                            <div class="bg-white rounded-lg p-3 sm:p-4 lg:p-5 border border-gray-100 shadow-sm">
                                <div class="items-start">
                                    <div class=" min-w-0">
                                        <div class="text-gray-800 text-xs sm:text-sm lg:text-base leading-relaxed whitespace-pre-wrap break-words break-all font-medium italic">
                                            {{ $order->notes }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <h2 class="text-base sm:text-lg font-semibold mb-4 mt-2 flex items-center gap-2">
                <i class="bi bi-box-seam"></i> Produk Dipesan
            </h2>
            
            @php 
                                $itemsTotal = 0;
// Calculate totals from items only
foreach ($order->items as $item) {
    $subtotal = ($item->price ?? 0) * ($item->quantity ?? 0);
    $itemsTotal += $subtotal;
}

// Check if delivery method needs shipping fee
$needsShippingFee = in_array($order->delivery_method, [
    'Gosend (Pesan Dari Toko)',
    'Gocar (Pesan Dari Toko)'
]);

// Check if admin has set shipping fee
$shippingFee = $order->shipping_fee ?? 0;
$shippingFeeSet = $shippingFee > 0;

// Determine if we should show grand total
$showGrandTotal = !$needsShippingFee || $shippingFeeSet;

// Calculate grand total with voucher discount
$voucherAmount = $order->voucher_amount ?? 0;
$grandTotal = ($itemsTotal - $voucherAmount) + $shippingFee;

// Payment calculations
$totalPaid = $order->amount_paid ?? 0;
$sisa = $order->payment_status === 'paid' ? 0 : max($grandTotal - $totalPaid, 0);
$displayTotalPaid = $order->payment_status === 'paid' ? $grandTotal : $totalPaid;
            @endphp

            <!-- Desktop: Table Layout -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full mb-6 text-xs sm:text-base table-fixed border rounded-lg overflow-hidden">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="py-3 px-4 w-[28%] whitespace-nowrap font-semibold">Nama</th>
                            <th class="py-3 px-4 w-[14%] whitespace-nowrap font-semibold">Tipe Harga</th>
                            <th class="py-3 px-4 w-[14%] text-right whitespace-nowrap font-semibold">Harga</th>
                            <th class="py-3 px-4 w-[14%] text-right whitespace-nowrap font-semibold">Satuan</th>
                            <th class="py-3 px-4 w-[14%] text-right whitespace-nowrap font-semibold">Jumlah</th>
                            <th class="py-3 px-4 w-[16%] text-right whitespace-nowrap font-semibold">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($order->items as $item)
                                                    @php 
                                                        $subtotal = ($item->price ?? 0) * ($item->quantity ?? 0);
    $cleanName = preg_replace('/\s*\(Komponen:.*?\)\s*/', '', $item->product_name);
    $cleanName = trim($cleanName) ?: $item->product_name;
                                                    @endphp
                                                    <tr>
                                                        <td class="py-3 px-4 break-words whitespace-pre-wrap align-top">{{ $cleanName }}</td>
                                                        <td class="py-3 px-4 break-words whitespace-normal align-top">
                                                            @php
    $priceType = $item->price_type ?? '-';
    if (Str::startsWith($priceType, 'ikat_')) {
        $jumlah = (int) str_replace('ikat_', '', $priceType);
        echo 'Per Ikat (isi ' . $jumlah . ' Tangkai)';
    } else {
        echo $priceType;
    }
                                                            @endphp
                                                        </td>
                                                        <td class="py-3 px-4 text-right align-top whitespace-nowrap">Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                                                        <td class="py-3 px-4 text-right align-top whitespace-nowrap">{{ $item->unit_equivalent ?? '-' }}</td>
                                                        <td class="py-3 px-4 text-right align-top whitespace-nowrap">{{ $item->quantity }}</td>
                                                        <td class="py-3 px-4 text-right align-top whitespace-nowrap">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                                                    </tr>
                                                    @if(!empty($item->greeting_card))
                                                        <tr>
                                                            <td colspan="6" class="px-4 py-2">
                                                                <div class="mt-2 p-2 bg-pink-50 border border-pink-200 rounded-lg">
                                                                    <div class="flex items-start">
                                                                        <i class="bi bi-card-text text-pink-400 mr-2"></i>
                                                                        <div class="text-sm text-pink-700 italic leading-relaxed break-words" style="word-break: break-all;">
                                                                            <strong>Kartu Ucapan: </strong> {{ $item->greeting_card }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    {{-- Tambahkan detail bouquet jika item adalah bouquet --}}
                                                    @if(isset($item->bouquet) && $item->bouquet)
                                                                                <tr>
                                                                                    <td colspan="6" class="px-4 py-2">
                                                                                        <div class="my-4 rounded-xl border border-gray-200 bg-white shadow-lg">
                                                                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-0 items-stretch">
                                                                                                <div class="flex items-center justify-center p-6 sm:p-8">
                                                                                                    @if($item->bouquet->image)
                                                                                                        <div class="relative group w-32 h-32">
                                                                                                            <img src="{{ asset('storage/' . $item->bouquet->image) }}" alt="{{ $item->bouquet->name }}" class="w-32 h-32 object-cover rounded-2xl border-2 border-rose-200 shadow cursor-pointer group-hover:opacity-80 transition duration-200"
                                                                                                            onclick="openImageModal('{{ asset('storage/' . $item->bouquet->image) }}', 'Gambar Bouquet')">
                                                                                                        </div>
                                                                                                    @else
                                                                                                        <div class="w-32 h-32 bg-gray-200 rounded-2xl flex items-center justify-center border-2 border-gray-300">
                                                                                                            <i class="bi bi-flower3 text-5xl text-rose-300"></i>
                                                                                                        </div>
                                                                                                    @endif
                                                                                                </div>
                                                                                                <div class="col-span-2 flex flex-col justify-center p-6 sm:p-8">
                                                                                                    <div class="grid grid-cols-2 gap-x-6 gap-y-2">
                                                                                                        <div>
                                                                                                            <span class="text-xs text-gray-500 font-medium">Nama Bouquet</span>
                                                                                                            <div class="font-bold text-lg text-rose-700">{{ $item->bouquet->name }}</div>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <span class="text-xs text-gray-500 font-medium">Kategori</span>
                                                                                                            <div class="font-semibold text-rose-600">{{ $item->bouquet->category->name ?? '-' }}</div>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <span class="text-xs text-gray-500 font-medium">Ukuran</span>
                                                                                                            <div class="font-semibold text-rose-600">{{ $item->price_type ?? '-' }}</div>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <span class="text-xs text-gray-500 font-medium">Harga</span>
                                                                                                            <div class="font-bold text-base text-green-700">Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}</div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            @php
            $components = $item->size_id
                ? $item->bouquet->components()->where('size_id', $item->size_id)->with('product')->get()
                : $item->bouquet->components()->with('product')->get();
                                                                                            @endphp
                                                                                            <div class="px-6 pb-6 pt-2">
                                                                                                <span class="text-xs text-gray-500 font-medium">Komponen Bunga (Ukuran {{ $item->price_type ?? '-' }})</span>
                                                                                                    {{-- <div class="mb-2 p-2 bg-gray-50 border border-gray-200 rounded text-xs text-gray-600">
                                                                                                        <strong>Debug:</strong>
                                                                                                        Bouquet ID: {{ $item->bouquet_id ?? '-' }} | Size ID: {{ $item->size_id ?? '-' }} | Komponen Count: {{ $components->count() }}
                                                                                                    </div> --}}
                                                                                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mt-2">
                                                                                                    @forelse($components as $component)
                                                                                                        <div class="flex items-center bg-white border border-gray-100 rounded-lg p-3 shadow-sm">
                                                                                                            @if($component->product && $component->product->image)
                                                                                                                <img src="{{ asset('storage/' . $component->product->image) }}" alt="{{ $component->product->name }}" class="w-9 h-9 object-cover rounded-lg mr-3 border border-gray-200">
                                                                                                            @else
                                                                                                                <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center mr-3 border border-gray-200">
                                                                                                                    <i class="bi bi-flower1 text-gray-400 text-xl"></i>
                                                                                                                </div>
                                                                                                            @endif
                                                                                                            <div>
                                                                                                                <span class="font-semibold text-rose-700 text-sm">{{ $component->product->name ?? '-' }}</span>
                                                                                                                <span class="text-xs text-gray-500 block">Jumlah: {{ $component->quantity }} {{ $component->product->base_unit ?? 'tangkai' }}</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @empty
                                                                                                        <div class="text-xs text-gray-400 italic">Tidak ada komponen bunga untuk ukuran ini.</div>
                                                                                                    @endforelse
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                    @endif
                        @endforeach
                        </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <th colspan="5" class="text-right px-4 py-3 font-semibold">Total Produk</th>
                            <th class="px-4 py-3 text-right font-bold text-green-600">Rp{{ number_format($itemsTotal, 0, ',', '.') }}</th>
                        </tr>
                        @if(($order->voucher_amount ?? 0) > 0)
                        <tr>
                            <th colspan="5" class="text-right px-4 py-3 font-semibold">Potongan Voucher</th>
                            <th class="px-4 py-3 text-right font-bold text-red-600">
                                -Rp{{ number_format($order->voucher_amount, 0, ',', '.') }}
                            </th>
                        </tr>
                        @endif
                        
                        @if($needsShippingFee && !$shippingFeeSet)
                            <!-- Pemberitahuan menunggu ongkir -->
                            <tr class="bg-yellow-50">
                                <th colspan="6" class="px-4 py-4 text-center">
                                    <div class="flex flex-col items-center space-y-2">
                                        <div class="flex items-center text-yellow-700">
                                            <i class="bi bi-clock-history mr-2 text-lg"></i>
                                            <span class="font-semibold">Menunggu Admin Menghitung Ongkir</span>
                                        </div>
                                        <p class="text-sm text-yellow-600">
                                            Total keseluruhan akan ditampilkan setelah admin menentukan biaya ongkir untuk metode <strong>{{ $order->delivery_method }}</strong>
                                        </p>
                                        <p class="text-xs text-yellow-500 italic">
                                            Mohon jangan transfer dulu sampai total final tersedia
                                        </p>
                                    </div>
                                </th>
                            </tr>
                        @elseif($shippingFee > 0)
                            <tr>
                                <th colspan="5" class="text-right px-4 py-3 font-semibold">Ongkir</th>
                                <th class="px-4 py-3 text-right font-bold text-orange-600">Rp{{ number_format($shippingFee, 0, ',', '.') }}</th>
                            </tr>
                            <tr class="bg-purple-50">
                                <th colspan="5" class="text-right px-4 py-3 font-semibold text-purple-700">Total Keseluruhan</th>
                                <th class="px-4 py-3 text-right font-bold text-purple-700">Rp{{ number_format($grandTotal, 0, ',', '.') }}</th>
                            </tr>
                        @endif
                        
                        @if($showGrandTotal)
                            <tr>
                                <th colspan="5" class="text-right px-4 py-3 font-semibold">Total Sudah Dibayar</th>
                                <th class="px-4 py-3 text-right font-bold text-blue-600">Rp{{ number_format($displayTotalPaid, 0, ',', '.') }}</th>
                            </tr>
                            @if($sisa > 0)
                                <tr>
                                    <th colspan="5" class="text-right px-4 py-3 font-semibold">Sisa Pembayaran</th>
                                    <th class="px-4 py-3 text-right font-bold text-red-600">Rp{{ number_format($sisa, 0, ',', '.') }}</th>
                                </tr>
                            @endif
                        @endif
                        </tfoot>
                    </table>
                </div>
                
                @if($item->price_type === 'Custom')
                    <tr>
                        <td colspan="6" class="px-4 py-2">
                            <x-custom-bouquet-order-detail :item="$item" />
                        </td>
                    </tr>
                @endif
                <!-- Mobile: Card Layout -->
            <div class="sm:hidden space-y-3 mb-6">
                @foreach($order->items as $item)
                    @php 
                        $subtotal = ($item->price ?? 0) * ($item->quantity ?? 0);
                        $cleanName = preg_replace('/\s*\(Komponen:.*?\)\s*/', '', $item->product_name);
                        $cleanName = trim($cleanName) ?: $item->product_name;
                    @endphp
                    <!-- Bouquet Detail Card for Mobile -->
                    @if(isset($item->bouquet) && $item->bouquet)
                        <div class="bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden mb-3">
                            <div class="flex flex-col items-center p-4">
                                <div class="relative group w-28 h-28 mb-3">
                                    @if($item->bouquet->image)
                                        <img src="{{ asset('storage/' . $item->bouquet->image) }}" alt="{{ $item->bouquet->name }}" class="w-28 h-28 object-cover rounded-2xl border-2 border-rose-200 shadow cursor-pointer group-hover:opacity-80 transition duration-200" onclick="openImageModal('{{ asset('storage/' . $item->bouquet->image) }}')">
                                        {{-- <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                                            <span class="bg-black bg-opacity-60 text-white text-xs px-3 py-1 rounded-lg">Lihat gambar</span>
                                        </div> --}}
                                    @else
                                        <div class="w-28 h-28 bg-gray-200 rounded-2xl flex items-center justify-center border-2 border-gray-300">
                                            <i class="bi bi-flower3 text-5xl text-rose-300"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="w-full grid grid-cols-2 gap-x-4 gap-y-2 mb-2 text-center">
                                    <div>
                                        <span class="text-xs text-gray-500 font-medium">Nama Bouquet</span>
                                        <div class="font-bold text-base text-rose-700">{{ $item->bouquet->name }}</div>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500 font-medium">Kategori</span>
                                        <div class="font-semibold text-rose-600">{{ $item->bouquet->category->name ?? '-' }}</div>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500 font-medium">Ukuran</span>
                                        <div class="font-semibold text-rose-600">{{ $item->price_type ?? '-' }}</div>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500 font-medium">Harga</span>
                                        <div class="font-bold text-base text-green-700">Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                                @php
                                        $components = $item->size_id
                                            ? $item->bouquet->components()->where('size_id', $item->size_id)->with('product')->get()
                                            : $item->bouquet->components()->with('product')->get();
                                @endphp
                                <div class="w-full mt-2">
                                    <span class="text-xs text-gray-500 font-medium">Komponen Bunga (Ukuran {{ $item->price_type ?? '-' }})</span>
                                    <div class="grid grid-cols-1 gap-2 mt-2">
                                        @forelse($components as $component)
                                            <div class="flex items-center bg-white border border-gray-100 rounded-lg p-2 shadow-sm">
                                                @if($component->product && $component->product->image)
                                                    <img src="{{ asset('storage/' . $component->product->image) }}" alt="{{ $component->product->name }}" class="w-8 h-8 object-cover rounded mr-2 border border-gray-200">
                                                @else
                                                    <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-2 border border-gray-200">
                                                        <i class="bi bi-flower1 text-gray-400 text-lg"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <span class="font-semibold text-rose-700 text-sm">{{ $component->product->name ?? '-' }}</span>
                                                    <span class="text-xs text-gray-500 block">Jumlah: {{ $component->quantity }} {{ $component->product->base_unit ?? 'tangkai' }}</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-xs text-gray-400 italic">Tidak ada komponen bunga untuk ukuran ini.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Product Card (non-bouquet) -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                            <!-- Product Header -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-3 py-3 border-b border-gray-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0 pr-2">
                                        <h3 class="font-semibold text-gray-800 text-sm leading-relaxed break-words whitespace-normal">
                                            {{ $cleanName }}
                                        </h3>
                                        @if($item->price_type && $item->price_type !== '-')
                                            <span class="inline-block mt-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-md font-medium">
                                                @php
                                                    $priceType = $item->price_type;
                                                    if (Str::startsWith($priceType, 'ikat_')) {
                                                        $jumlah = (int) str_replace('ikat_', '', $priceType);
                                                        echo 'Per Ikat (isi ' . $jumlah . ' Tangkai)';
                                                    } else {
                                                        echo $priceType;
                                                    }
                                                @endphp
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Product Details -->
                            <div class="p-3">
                                <!-- Harga dan Satuan -->
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                    <div class="flex-1">
                                        <span class="text-gray-500 text-xs">Harga Satuan</span>
                                        <p class="text-gray-800 text-sm font-semibold">Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="flex-1 text-right">
                                        <span class="text-gray-500 text-xs">Satuan</span>
                                        <p class="text-gray-800 text-sm font-semibold">{{ $item->unit_equivalent ?? '-' }}</p>
                                    </div>
                                </div>
                                <!-- Jumlah dan Subtotal -->
                                <div class="flex justify-between items-center py-1.5">
                                    <div class="flex-1">
                                        <span class="text-gray-500 text-xs">Jumlah</span>
                                        <p class="text-gray-800 text-sm font-semibold">{{ $item->quantity }}</p>
                                    </div>
                                    <div class="flex-1 text-right">
                                        <span class="text-gray-500 text-xs">Subtotal</span>
                                        <p class="text-green-600 text-sm font-bold">Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <!-- Kartu Ucapan (Mobile) -->
                                @if(!empty($item->greeting_card))
                                    <div class="mt-2 p-2 bg-pink-50 border border-pink-200 rounded-lg">
                                        <div class="flex items-start">
                                            <i class="bi bi-card-text text-pink-400 mr-2"></i>
                                            <div class="text-sm text-pink-700 italic leading-relaxed break-words" style="word-break: break-all;">
                                                <strong>Kartu Ucapan: </strong> {{ $item->greeting_card }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
                
                <!-- Total Summary Card -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mt-4">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-3 py-2.5">
                        <h3 class="text-white font-semibold text-sm flex items-center">
                            <i class="bi bi-calculator mr-2"></i>
                            Ringkasan Pembayaran
                        </h3>
                    </div>
                    <div class="p-3 space-y-2">
                        <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                            <span class="text-gray-600 text-sm">Total Produk</span>
                            <span class="text-green-600 text-sm font-bold">Rp{{ number_format($itemsTotal, 0, ',', '.') }}</span>
                        </div>

                        @if($order->voucher_amount > 0)
                        <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                            <span class="text-gray-600 text-sm">Potongan Voucher</span>
                            <span class="text-purple-600 text-sm font-bold">-Rp{{ number_format($order->voucher_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        @if($needsShippingFee && !$shippingFeeSet)
                            <!-- Pemberitahuan menunggu ongkir (Mobile) -->
                            <div class="py-3 px-2 bg-yellow-50 border border-yellow-200 rounded-lg -mx-1">
                                <div class="text-center space-y-2">
                                    <div class="flex items-center justify-center text-yellow-700">
                                        <i class="bi bi-clock-history mr-2"></i>
                                        <span class="text-sm font-semibold">Menunggu Admin Menghitung Ongkir</span>
                                    </div>
                                    <p class="text-xs text-yellow-600">
                                        Total keseluruhan akan ditampilkan setelah admin menentukan biaya ongkir untuk <strong>{{ $order->delivery_method }}</strong>
                                    </p>
                                    <p class="text-xs text-yellow-500 italic">
                                        Mohon jangan transfer dulu sampai total final tersedia
                                    </p>
                                </div>
                            </div>
                        @elseif($shippingFee > 0)
                            <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                <span class="text-gray-600 text-sm">Ongkir</span>
                                <span class="text-orange-600 text-sm font-bold">Rp{{ number_format($shippingFee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-1.5 border-b border-gray-100 bg-purple-50 -mx-3 px-3">
                                <span class="text-gray-700 text-sm font-semibold">Total Keseluruhan</span>
                                <span class="text-purple-600 text-sm font-bold">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        
                        @if($showGrandTotal)
                            <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                <span class="text-gray-600 text-sm">Sudah Dibayar</span>
                                <span class="text-blue-600 text-sm font-bold">Rp{{ number_format($displayTotalPaid, 0, ',', '.') }}</span>
                            </div>
                            @if($sisa > 0)
                                <div class="flex justify-between items-center py-1.5">
                                    <span class="text-gray-600 text-sm">Sisa Pembayaran</span>
                                    <span class="text-red-600 text-sm font-bold">Rp{{ number_format($sisa, 0, ',', '.') }}</span>
                                </div>
                            @else
                                <div class="flex justify-between items-center py-1.5">
                                    <span class="text-gray-600 text-sm">Status</span>
                                    <span class="text-green-600 text-sm font-bold flex items-center">
                                        <i class="bi bi-check-circle-fill mr-1 text-xs"></i>
                                        Lunas
                                    </span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Custom Bouquet Information Card -->
            @php
$customBouquetItems = $order->items->filter(function ($item) {
    return $item->type === 'custom_bouquet';
});
            @endphp
            
            @if($customBouquetItems->count() > 0)
                <div class="my-6 sm:my-8">
                    @foreach($customBouquetItems as $item)
                        <x-custom-bouquet-order-detail :item="$item" />

                            <!-- Content -->
                            <div class="p-4 sm:p-6 space-y-6">
                                <!-- Reference Image Section -->
                                @if(!empty($item->reference_image))
                                    <div class="bg-white rounded-xl border border-purple-200 p-4 sm:p-6 shadow-sm">
                                        <div class="flex items-center mb-4">
                                            <div class="bg-purple-100 p-2 rounded-lg mr-3">
                                                <i class="bi bi-image text-purple-600 text-lg"></i>
                                            </div>
                                            <h4 class="font-bold text-purple-800 text-sm sm:text-base">Upload Referensi</h4>
                                        </div>

                                        <div class="grid grid-cols-1 gap-6">
                                            <!-- Image Preview -->
                                            <div class="space-y-3">
                                                <div class="relative group">
                                                    <img src="{{ asset('storage/' . $item->reference_image) }}" 
                                                         alt="Referensi Custom Bouquet" 
                                                         class="w-full h-64 sm:h-80 object-cover rounded-lg border-2 border-purple-200 shadow-md cursor-pointer transition-transform hover:scale-105"
                                                         onclick="openImageModal('{{ asset('storage/' . $item->reference_image) }}')">
                                                    <!-- Zoom overlay -->
                                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                            <i class="bi bi-zoom-in text-white text-3xl"></i>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Action buttons -->
                                                <div class="flex flex-col sm:flex-row gap-2">
                                                    <button onclick="openImageModal('{{ asset('storage/' . $item->reference_image) }}')"
                                                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-sm">
                                                        <i class="bi bi-zoom-in mr-2"></i>Lihat Gambar
                                                    </button>
                                                    <a href="{{ asset('storage/' . $item->reference_image) }}" 
                                                       download="referensi-custom-bouquet.jpg"
                                                       class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-sm text-center">
                                                        <i class="bi bi-download mr-2"></i>Download
                                                    </a>
                                                </div>

                                                <!-- Simple Status -->
                                                <div class="text-center">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        <i class="bi bi-check-circle-fill mr-1"></i>
                                                        Terupload
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Custom Instructions Section -->
                                @if(!empty($item->custom_instructions))
                                    <div class="bg-white rounded-xl border border-purple-200 p-4 sm:p-6 shadow-sm">
                                        <div class="flex items-center mb-4">
                                            <div class="bg-purple-100 p-2 rounded-lg mr-3">
                                                <i class="bi bi-chat-left-text text-purple-600 text-lg"></i>
                                            </div>
                                            <h4 class="font-bold text-purple-800 text-sm sm:text-base">Instruksi Khusus</h4>
                                        </div>

                                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                            <div class="text-sm sm:text-base text-purple-800 leading-relaxed font-medium">
                                                "{{ $item->custom_instructions }}"
                                            </div>
                                        </div>

                                        <div class="mt-4 text-xs sm:text-sm text-purple-600">
                                            <i class="bi bi-info-circle mr-1"></i>
                                            Instruksi ini akan disampaikan kepada tim florist untuk memastikan bouquet sesuai dengan keinginan Anda.
                                        </div>
                                    </div>
                                @endif

                                <!-- Custom Bouquet Details Section -->
                                @if($item->type === 'custom_bouquet')
                                    <x-custom-bouquet-order-detail :item="$item" />

                                            <div class="space-y-3">
                                                @foreach($customBouquet->items as $component)
                                                    <div class="flex items-center justify-between bg-purple-50 border border-purple-100 rounded-lg p-3">
                                                        <div class="flex items-center">
                                                            @if($component->product->image)
                                                                <img src="{{ asset('storage/' . $component->product->image) }}" 
                                                                     alt="{{ $component->product->name }}" 
                                                                     class="w-8 h-8 sm:w-10 sm:h-10 object-cover rounded mr-3">
                                                            @else
                                                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                                                    <i class="bi bi-flower1 text-gray-400 text-sm"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <span class="font-medium text-purple-800 text-sm sm:text-base">{{ $component->product->name }}</span>
                                                                <span class="text-xs sm:text-sm text-purple-600 block">{{ $component->price_type_display }} - {{ $component->formatted_quantity }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <span class="font-bold text-purple-800 text-sm sm:text-base">{{ $component->quantity }}</span>
                                                            <span class="text-xs sm:text-sm text-purple-600 ml-1">{{ $component->product->base_unit ?? 'pcs' }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="mt-4 p-3 bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-lg">
                                                <div class="flex justify-between items-center">
                                                    <span class="font-semibold text-purple-800 text-sm sm:text-base">Total Harga:</span>
                                                    <span class="font-bold text-lg text-purple-800">Rp {{ number_format((float) ($customBouquet->total_price ?? 0), 0, ',', '.') }}</span>
                                                </div>
                                            </div>

                                            <div class="mt-3 text-xs sm:text-sm text-purple-600">
                                                <i class="bi bi-info-circle mr-1"></i>
                                                Komponen-komponen yang Anda pilih untuk custom bouquet.
                                            </div>
                                        </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Debug Info (remove in production) -->
            {{-- @if($sisa > 0)
                <div class="my-4 p-2 bg-gray-100 rounded text-xs">
                    <strong>Debug Info:</strong> Sisa: Rp{{ number_format($sisa, 0, ',', '.') }}, 
                    Payment Status: {{ $order->payment_status }}, 
                    Show Payment: {{ in_array($order->payment_status, ['waiting_confirmation', 'ready_to_pay', 'waiting_payment', 'waiting_verification']) ? 'Yes' : 'No' }}
                </div>
            @endif --}}

            <!-- Informasi Pembayaran Section -->
            @if($showGrandTotal && $sisa > 0 && $order->payment_status !== 'paid')
                                                    <div "my-8">
                                                        <div class="bg-gradient-to-r from-orange-50 to-yellow-50 border-2 border-orange-200 rounded-2xl p-4 sm:p-6">
                                                            <div class="flex items-center justify-center mb-4">
                                                                <div class="bg-orange-500 rounded-full p-2 mr-3">
                                                                    <i class="bi bi-credit-card-2-front text-white text-lg"></i>
                                                                </div>
                                                                <h3 class="text-lg sm:text-xl font-bold text-orange-800">Informasi Pembayaran</h3>
                                                            </div>

                                                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                                                <!-- Transfer Bank BCA -->
                                                                <div class="bg-white rounded-xl border-2 border-blue-200 p-4 shadow-sm">
                                                                    <div class="flex items-center mb-3">
                                                                        <div class="bg-blue-600 rounded-lg p-2 mr-3">
                                                                            <i class="bi bi-bank text-white"></i>
                                                                        </div>
                                                                        <h4 class="font-bold text-blue-800 text-sm sm:text-base">Transfer Bank BCA</h4>
                                                                    </div>

                                                                    <div class="space-y-2 text-xs sm:text-sm">
                                                                        <div class="flex justify-between">
                                                                            <span class="text-gray-600">No. Rekening:</span>
                                                                            <span class="font-bold text-blue-800">6521066528</span>
                                                                        </div>
                                                                        <div class="flex justify-between">
                                                                            <span class="text-gray-600">Atas Nama:</span>
                                                                            <span class="font-bold text-blue-800">MUHAMMAD RIDHO PUTRA</span>
                                                                        </div>
                                                                        <div class="flex justify-between">
                                                                            <span class="text-gray-600">Bank:</span>
                                                                            <span class="font-bold text-blue-800">BCA (Bank Central Asia)</span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-3">
                                                                        <div class="text-center">
                                                                            <span class="text-xs text-gray-600">Jumlah Transfer:</span>
                                                                            <div class="text-lg sm:text-xl font-bold text-green-600">
                                                                                Rp{{ number_format($sisa, 0, ',', '.') }}
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="mt-3 text-center">
                                                                        <button onclick="copyToClipboard('6521066528')" 
                                                                                class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded-lg transition duration-200" style="background:#E59420">
                                                                            <i class="bi bi-clipboard"></i> Salin No. Rekening
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <!-- Petunjuk Pembayaran -->
                                                                <div class="bg-white rounded-xl border-2 border-green-200 p-4 shadow-sm">
                                                                    <div class="flex items-center mb-3">
                                                                        <div class="bg-green-600 rounded-lg p-2 mr-3">
                                                                            <i class="bi bi-list-check text-white"></i>
                                                                        </div>
                                                                        <h4 class="font-bold text-green-800 text-sm sm:text-base">Petunjuk Pembayaran:</h4>
                                                                    </div>

                                                                    <ol class="text-xs sm:text-sm space-y-2 text-gray-700">
                                                                        <li class="flex items-start">
                                                                            <span class="bg-green-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">1</span>
                                                                            <span>Transfer sesuai jumlah yang bertanda</span>
                                                                        </li>
                                                                        <li class="flex items-start">
                                                                            <span class="bg-green-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">2</span>
                                                                            <span>Foto bukti transfer</span>
                                                                        </li>
                                                                        <li class="flex items-start">
                                                                            <span class="bg-green-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">3</span>
                                                                            <span>Kirim bukti transfer via WhatsApp</span>
                                                                        </li>
                                                                        <li class="flex items-start">
                                                                            <span class="bg-green-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">4</span>
                                                                            <span>Tunggu konfirmasi dari admin</span>
                                                                        </li>
                                                                    </ol>

                                                                    <div class="mt-4 text-center">
                                                                        @php
                $waMessage = "🌸 *Halo, Seikat Bungo*\n\n";
                // $waMessage .= "══════════\n\n";
                $waMessage .= "Saya ingin mengirim bukti pembayaran untuk:\n\n";
                $waMessage .= "📋 *Pesanan :* {$order->public_code}\n";
                $waMessage .= "🔗 *Link :* " . url("/order/{$order->public_code}") . "\n\n";
                $waMessage .= "👤 *Nama Pemesan :* {$order->customer_name}\n";
                $waMessage .= "📱 *WhatsApp Pemesan :* {$order->wa_number}\n\n";
                // if ($order->receiver_name) {
                //     $waMessage .= "👥 *Nama Penerima :* {$order->receiver_name}\n";
                // }
                // if ($order->receiver_wa) {
                //     $waMessage .= "📲 *WhatsApp Penerima :* {$order->receiver_wa}\n";
                // }
                // $waMessage .= "📅 *Tanggal :* " . \Carbon\Carbon::parse($order->pickup_date)->format('d-m-Y') . "\n";
                // $waMessage .= "⏰ *Waktu :* {$order->pickup_time}\n";
                // $waMessage .= "🚚 *Pengiriman :* {$order->delivery_method}\n";
                // $waMessage .= "📍 *Tujuan :* {$order->destination}\n\n";

                // Tambahkan breakdown harga dengan ongkir
                $waMessage .= "💰 *Detail Harga:*\n";
                $waMessage .= "• Total Produk: Rp " . number_format($itemsTotal, 0, ',', '.') . "\n";
                if ($shippingFee > 0) {
                    $waMessage .= "• Ongkir: Rp " . number_format($shippingFee, 0, ',', '.') . "\n";
                }
                if ($order->voucher_amount > 0) {
                    $waMessage .= "• Potongan Voucher: -Rp " . number_format($order->voucher_amount, 0, ',', '.') . "\n";
                }
                $waMessage .= "• *Total Keseluruhan: Rp " . number_format($grandTotal, 0, ',', '.') . "*\n\n";

                if ($showGrandTotal) {
                    $waMessage .= "💰 *Total Pesanan :* Rp " . number_format($grandTotal, 0, ',', '.') . "\n\n";
                    // $waMessage .= "═══════════\n";
                    $waMessage .= "Mohon konfirmasi pembayaran 🙏\n";
                } else {
                    $waMessage .= "⏳ *Status :* Menunggu admin menghitung ongkir\n\n";
                    // $waMessage .= "═══════════\n";
                    $waMessage .= "Mohon tunggu info total final dari admin 🙏\n";
                }

                $waMessage .= "Terima kasih 😊";
                $encodedMessage = urlencode($waMessage);
                                                                        @endphp
                                                                        <a href="https://wa.me/6285119990901?text={{ $encodedMessage }}" 
                                                                           target="_blank"
                                                                           class="bg-green-500 hover:bg-green-600 text-white text-xs px-4 py-2 rounded-lg transition duration-200 inline-flex items-center gap-2 shadow-md hover:shadow-lg">
                                                                            <i class="bi bi-whatsapp text-lg"></i> 
                                                                            <span class="font-medium">Kirim Bukti Transfer</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Button Petunjuk Pembayaran yang Prominent -->
                                                            <div class="mt-6 text-center">
                                                                <button onclick="scrollToPetunjukPembayaran()" 
                                                                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 inline-flex items-center gap-3" style="background:#E59420">
                                                                    <i class="bi bi-info-circle-fill text-xl"></i>
                                                                    <span class="text-base">Lihat Petunjuk Pembayaran Lengkap</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <script>
                                                    function scrollToPetunjukPembayaran() {
                                                        // Cari element yang berisi "Petunjuk Pembayaran"
                                                        const elements = document.querySelectorAll('h4');
                                                        for (let element of elements) {
                                                            if (element.textContent.includes('Petunjuk Pembayaran')) {
                                                                element.scrollIntoView({ 
                                                                    behavior: 'smooth', 
                                                                    block: 'center' 
                                                                });

                                                                // Highlight effect
                                                                const parentCard = element.closest('.bg-white');
                                                                if (parentCard) {
                                                                    parentCard.style.transition = 'all 0.3s ease';
                                                                    parentCard.style.transform = 'scale(1.02)';
                                                                    parentCard.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';

                                                                    setTimeout(() => {
                                                                        parentCard.style.transform = 'scale(1)';
                                                                        parentCard.style.boxShadow = '';
                                                                    }, 1000);
                                                                }
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    </script>
            @endif
            
            <!-- Pesan untuk pesanan yang sudah lunas -->
            @if($order->payment_status === 'paid' && $sisa == 0)
                <div class="my-8">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-4 sm:p-6">
                        <div class="flex items-center justify-center mb-4">
                            <div class="bg-green-500 rounded-full p-3 mr-3">
                                <i class="bi bi-check-circle text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-green-800">Pembayaran Lunas!</h3>
                        </div>

                        <div class="text-center">
                            <p class="text-green-700 text-sm sm:text-base mb-3">
                                Terima kasih! Pembayaran pesanan Anda telah diterima dengan lengkap.
                            </p>
                            <p class="text-green-600 text-xs sm:text-sm">
                                Pesanan Anda sedang diproses. Silakan pantau status pesanan di halaman ini.
                            </p>
                        </div>

                        @if($order->status === 'pending')
                            <div class="mt-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="bi bi-clock mr-1"></i>
                                    Menunggu diproses oleh admin
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if(!empty($order->payment_proof))
                <div class="my-8 text-center">
                    <h3 class="font-semibold text-base mb-2 flex items-center gap-2 justify-center"><i
                            class="bi bi-receipt"></i> Bukti Pembayaran</h3>
                    @php
    $ext = pathinfo($order->payment_proof, PATHINFO_EXTENSION);
                    @endphp
                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran"
                            class="mx-auto rounded shadow max-h-64 border mb-2 cursor-pointer hover:opacity-90 transition-opacity"
                            style="max-width:300px;"
                            onclick="openImageModal('{{ asset('storage/' . $order->payment_proof) }}', 'Bukti Pembayaran')"
                            onerror="this.style.display='none'; document.getElementById('payment-proof-error').style.display='block';" />
                        <div class="flex flex-col items-center justify-center text-xs text-gray-600 gap-1 mb-2">
                            <a href="{{ asset('storage/' . $order->payment_proof) }}" download class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="bi bi-download mr-1"></i>Download Bukti Pembayaran
                            </a>
                        </div>
                    @elseif(strtolower($ext) == 'pdf')
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                            class="text-blue-600 underline">Lihat Bukti Pembayaran (PDF)</a>
                    @else
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                            class="text-blue-600 underline">Download Bukti Pembayaran</a>
                    @endif
                    <div id="payment-proof-error" style="display:none; color:red;">Bukti pembayaran tidak ditemukan di
                        server.</div>
                </div>
            @endif

            @if(!empty($order->packing_photo) || !empty($order->packing_files))
                <div class="my-8 text-center">
                    <h3 class="font-semibold text-base mb-4 flex items-center gap-2 justify-center">
                        <i class="bi bi-camera text-pink-500"></i> 
                        <i class="bi bi-box-seam text-blue-500"></i>
                        Foto & Video Packing
                    </h3>

                    @php
    $packingFiles = [];

    // Prioritize new multiple files format
    if (!empty($order->packing_files)) {
        $files = is_string($order->packing_files) ? json_decode($order->packing_files, true) : $order->packing_files;
        if (is_array($files)) {
            $packingFiles = $files;
        }
    }
    // Fallback to old single photo format only if no packing_files
    elseif (!empty($order->packing_photo)) {
        $packingFiles[] = $order->packing_photo;
    }
                    @endphp

                    @if(count($packingFiles) > 0)
                        <div class="max-w-4xl mx-auto @if(count($packingFiles) === 1) flex justify-center @else grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 @endif">
                            @foreach($packingFiles as $index => $file)
                                @php
            $filePath = asset('storage/' . $file);
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm']);
            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                                @endphp

                                <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm">
                                    @if($isVideo)
                                        <video controls class="w-full h-48 rounded-lg object-cover bg-black mb-2"
                                               onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <source src="{{ $filePath }}" type="video/{{ $ext }}">
                                            Browser Anda tidak mendukung video.
                                        </video>
                                        <div style="display:none;" class="text-center p-4 text-red-600 bg-red-50 rounded-lg border border-red-200">
                                            <i class="bi bi-exclamation-triangle mr-2"></i>Video tidak ditemukan.
                                        </div>
                                        <div class="flex items-center justify-center text-sm text-gray-600">
                                            <i class="bi bi-play-circle mr-2 text-blue-500"></i>
                                            Video Packing {{ $index + 1 }}
                                        </div>
                                    @elseif($isImage)
                                        <img src="{{ $filePath }}" alt="Foto Packing {{ $index + 1 }}"
                                             class="w-full h-48 object-cover rounded-lg border border-gray-200 mb-2 cursor-pointer hover:opacity-90 transition-opacity"
                                             onclick="openImageModal('{{ $filePath }}', 'Foto Packing {{ $index + 1 }}')"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
                                        <div style="display:none;" class="text-center p-4 text-red-600 bg-red-50 rounded-lg border border-red-200">
                                            <i class="bi bi-exclamation-triangle mr-2"></i>Foto tidak ditemukan.
                                        </div>
                                        <div class="flex flex-col items-center justify-center text-sm text-gray-600 gap-1">
                                            <div>
                                                <i class="bi bi-camera mr-2 text-green-500"></i>
                                                Foto Packing {{ $index + 1 }}
                                            </div>
                                            <a href="{{ $filePath }}" download class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 mt-1">
                                                <i class="bi bi-download mr-1"></i>Download Foto
                                            </a>
                                        </div>
                                    @else
                                        <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center mb-2">
                                            <div class="text-center">
                                                <i class="bi bi-file-earmark text-3xl text-gray-400 mb-2"></i>
                                                <p class="text-sm text-gray-500">File {{ $index + 1 }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ $filePath }}" target="_blank" 
                                           class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                            <i class="bi bi-download mr-1"></i>
                                            Download File
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center p-8 text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="bi bi-camera text-4xl mb-3"></i>
                            <p>Belum ada foto atau video packing.</p>
                        </div>
                    @endif
                </div>

                <!-- Image Modal for Order Detail -->
                <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
                    <div class="relative max-w-4xl max-h-full">
                        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
                        <button onclick="closeImageModal()" 
                                class="absolute top-4 right-4 text-white bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 transition-colors">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <div id="modalTitle" class="absolute bottom-4 left-4 text-white bg-black bg-opacity-50 px-3 py-1 rounded-lg text-sm"></div>
                    </div>
                </div>

                <script>
                    function openImageModal(src, title) {
                        document.getElementById('modalImage').src = src;
                        document.getElementById('modalTitle').textContent = title;
                        document.getElementById('imageModal').classList.remove('hidden');
                    }

                    function closeImageModal() {
                        document.getElementById('imageModal').classList.add('hidden');
                    }

                    // Close modal when clicking outside
                    document.getElementById('imageModal').addEventListener('click', function(e) {
                        if (e.target === this) {
                            closeImageModal();
                        }
                    });

                    // Close modal with Escape key
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            closeImageModal();
                        }
                    });
                </script>
            @endif
            <div class="text-center text-gray-500 text-xs sm:text-sm mt-8">
                <p class="font-medium">Terima kasih telah memesan di Seikat Bungo!</p>
                {{-- <p class="mt-1 sm:mt-2">Jika ada pertanyaan, silakan hubungi admin kami.</p> --}}
                <p class="mt-2 sm:mt-4">Seikat Bungo &copy; {{ date('Y') }}</p>
                {{-- <div class="w-100 h-0.5 bg-pink-400 mx-auto mb-3 mt-6"></div> --}}
                <br>
                <br>
            <div class="text-gray flex items-center justify-center mt-4" style="font-size:0.65rem;">
                <i class="bi bi-laptop mr-2"></i>
                Designed and Developed by :
                <a href="https://www.instagram.com/adrmyd/" target="_blank" class="professional-name ml-2">
                    <i class="bi bi-code-slash mr-1"></i> adrmyd
                </a>
            </div>
            </div>
        </div>
    </div>
    <div class="w-full flex justify-center mt-4 mb-4">
        <a href="{{ route('public.flowers') }}"
            class="inline-flex items-center gap-2 bg-pink-500 hover:bg-pink-600 text-white font-bold px-4 py-2 rounded-lg shadow transition" style="background:#247A72">
            <i class="bi bi-arrow-left-circle"></i> Kembali ke Daftar Bunga
        </a>
    </div>
    
    <script>
        function copyToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function() {
                    showCopySuccess();
                }, function(err) {
                    fallbackCopyTextToClipboard(text);
                });
            } else {
                fallbackCopyTextToClipboard(text);
            }
        }

        function fallbackCopyTextToClipboard(text) {
            var textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                var successful = document.execCommand('copy');
                if (successful) {
                    showCopySuccess();
                }
            } catch (err) {
                console.error('Fallback: Could not copy text: ', err);
            }
            document.body.removeChild(textArea);
        }

        function showCopySuccess() {
            // Use global toast notification system if available
            if (typeof showToast === 'function') {
                showToast('Nomor rekening berhasil disalin!', 'success');
            } else {
                // Fallback to simple alert
                alert('Nomor rekening berhasil disalin!');
            }
        }

        // Image Modal Functions
        function openImageModal(imageSrc, title = 'Gambar Referensi') {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalDownloadBtn = document.getElementById('modalDownloadBtn');
            
            modalImage.src = imageSrc;
            modalTitle.textContent = title;
            modalDownloadBtn.href = imageSrc;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the image
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('imageModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeImageModal();
                    }
                });
            }
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeImageModal();
                }
            });
        });
    </script>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center p-4" style="display: none;">
        <div class="relative max-w-4xl max-h-full bg-white rounded-lg overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-purple-600 text-white p-4 flex items-center justify-between">
                <h3 id="modalTitle" class="font-bold text-lg">Gambar Referensi</h3>
                <button onclick="closeImageModal()" class="text-white hover:text-gray-300 text-2xl">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4 max-h-96 overflow-auto">
                <img id="modalImage" src="" alt="Gambar Referensi" class="w-full h-auto rounded-lg">
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 p-4 flex justify-end space-x-3">
                <button onclick="closeImageModal()" 
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200">
                    <i class="bi bi-x-circle mr-2"></i>Tutup
                </button>
                <a id="modalDownloadBtn" href="" download="referensi-custom-bouquet.jpg"
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200">
                    <i class="bi bi-download mr-2"></i>Download
                </a>
            </div>
        </div>
    </div>
    </script>
    
    <!-- Include cart.js for toast notifications -->
    <script src="{{ asset('js/cart.js') }}?v={{ time() }}"></script>
</body>

</html>