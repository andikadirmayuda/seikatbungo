<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pemesanan - {{ $order->customer_name }} | Seikat Bungo</title>

    <!-- Meta Tags for Social Sharing -->
    <meta name="description" content="Invoice pemesanan {{ $order->customer_name }} dari Seikat Bungo">
    <meta property="og:title" content="Invoice Pemesanan - Seikat Bungo">
    <meta property="og:description" content="Invoice pemesanan digital dari Seikat Bungo">
    <meta property="og:image" content="{{ asset('logo-seikat-bungo.png') }}">
    <meta property="og:type" content="website">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo-seikat-bungo.png') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pink: {
                            50: '#fdf2f8',
                            100: '#fce7f3',
                            500: '#ec4899',
                            600: '#db2777',
                            700: '#be185d',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        @media print {
            body {
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .print-container {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-pink-50 to-rose-50 min-h-screen py-4 px-4 sm:py-8">
    <!-- Back Button (No Print) -->
    {{-- <div class="no-print max-w-3xl mx-auto mb-4">
        <button onclick="window.history.back()"
            class="inline-flex items-center px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            <i class="bi bi-arrow-left mr-2"></i>Kembali
        </button>
    </div> --}}

    <!-- Invoice Container -->
    <div class="max-w-3xl mx-auto">
        <div class="print-container bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

            <!-- Header Brand -->
            <div class="bg-gradient-to-r from-pink-50 via-rose-50 to-pink-50 px-6 py-8 text-center border-b border-pink-100">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('logo-seikat-bungo.png') }}" alt="Seikat Bungo Logo"
                        class="h-20 w-20 object-cover rounded-full border-2 border-pink-200 shadow-sm">
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Seikat Bungo</h1>
                <div class="w-10 h-0.5 bg-pink-400 mx-auto mb-3"></div>
                <div class="text-sm text-gray-600 leading-relaxed">
                    <p>Jln. Angkatan 45 (samping PS Mall)</p>
                    {{-- <p>Kota Palembang, Sumatera Selatan 30139</p> --}}
                    <p class="mt-2">
                        <i class="bi bi-telephone mr-1"></i>0851-1999-0901 |
                        <i class="bi bi-instagram ml-2 mr-1"></i>@seikat.bungo
                    </p>
                </div>

                <!-- Invoice Badge -->
                <div class="mt-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <i class="bi bi-receipt mr-1"></i>Invoice Pemesanan
                    </span>
                </div>
            </div>

            <!-- Transaction Info -->
            <div class="px-6 py-6 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="bi bi-info-circle mr-2 text-blue-600"></i>Informasi Pesanan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-hash text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">No. Invoice</p>
                                @php
$noUrut = str_pad($order->id, 3, '0', STR_PAD_LEFT);
$tgl = date('dmy', strtotime($order->created_at ?? now()));
$noInvoice = '#INV-' . $noUrut . '' . $tgl . '' . $order->id;
                                @endphp
                                <p class="text-sm font-semibold text-blue-700">{{ $noInvoice }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-calendar3 text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Ambil/Kirim</p>
                                <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($order->pickup_date)->format('d/m/Y') }}</p>
                                <p class="text-xs text-rose-600">{{ \Carbon\Carbon::parse($order->pickup_date)->locale('id')->dayName }}</p>
                                @php
$hour = (int) substr($order->pickup_time, 0, 2);
$timeOfDay = match (true) {
    $hour >= 5 && $hour < 11 => 'Pagi',
    $hour >= 11 && $hour < 15 => 'Siang',
    $hour >= 15 && $hour < 18 => 'Sore',
    default => 'Malam'
};
                                @endphp
                                <p class="text-xs text-gray-600">
                                    {{ $order->pickup_time }} WIB
                                    <span class="text-blue-600 ml-1">({{ $timeOfDay }})</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-printer text-pink-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Cetak</p>
                                <p class="text-sm font-semibold text-gray-900">{{ date('d/m/Y') }}</p>
                                <p class="text-xs text-gray-600">{{ date('H:i') }} WIB</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-truck text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Pengiriman</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $order->delivery_method }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-person text-orange-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pemesan</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $order->customer_name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-whatsapp text-yellow-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $order->wa_number }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-person text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Penerima</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $order->receiver_name ?: '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-rose-100 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-whatsapp text-rose-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp Penerima</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $order->receiver_wa ?: '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gradient-to-r from-pink-50 to-rose-50 rounded-lg border border-pink-100">
                            <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-geo-alt text-pink-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</p>
                                <p class="text-sm font-semibold text-pink-600">{{ $order->destination }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="bi bi-clock text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</p>
                            <p class="text-sm font-semibold text-blue-700">{{ $order->pickup_date }} - {{ $order->pickup_time }}</p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                            <i class="bi bi-clipboard-check text-gray-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pesanan</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' :
    ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
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
                                {{ $statusIndo[$order->status] ?? ucfirst($order->status) }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                                {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                @php
$paymentStatusIndo = [
    'pending' => 'Menunggu Pembayaran',
    'waiting_confirmation' => 'Menunggu Konfirmasi',
    'confirmed' => 'Pembayaran Dikonfirmasi',
    'paid' => 'Lunas',
    'failed' => 'Gagal',
    'cancelled' => 'Dibatalkan'
];
                                @endphp
                                {{ $paymentStatusIndo[$order->payment_status ?? 'waiting_confirmation'] ?? ucfirst(str_replace('_', ' ', $order->payment_status ?? 'waiting_confirmation')) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Items Table -->
            <div class="px-6 py-6 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="bi bi-cart-check mr-2 text-green-600"></i>Detail Produk
                </h2>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 via-gray-25 to-gray-50">
                            <tr>
                                <th class="px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-12">
                                    No
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Nama Produk
                                </th>
                                <th class="px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-32">
                                    Tipe Harga
                                </th>
                                <th class="px-4 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider w-32">
                                    Harga
                                </th>
                                <th class="px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-20">
                                    Satuan
                                </th>
                                <th class="px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-16">
                                    Jumlah
                                </th>
                                <th class="px-4 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider w-36">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @php $total = 0; @endphp
                            @foreach($order->items as $index => $item)
                            @php $subtotal = ($item->price ?? 0) * ($item->quantity ?? 0);
    $total += $subtotal; @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 text-center text-sm text-gray-600 font-medium">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                    
                                    <!-- Badge tipe produk -->
                                    <div class="mt-1">
                                        @if($item->item_type === 'custom_bouquet')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-purple-500 to-indigo-500 text-white">
                                                <i class="bi bi-palette mr-1"></i>Custom Bouquet
                                            </span>
                                            
                                            <!-- Detail Custom Bouquet -->
                                            <div class="mt-3 space-y-2 bg-gray-50 p-3 rounded-lg">
                                                @php
        $details = json_decode($item->details ?? '{}', true) ?? [];
                                                @endphp
                                                
                                                @if(!empty($details['flowers']))
                                                    <div class="flex items-start">
                                                        <span class="flex-shrink-0 w-20 text-xs font-medium text-gray-500">Bunga:</span>
                                                        <span class="text-xs text-gray-900">{{ is_array($details['flowers']) ? implode(', ', $details['flowers']) : $details['flowers'] }}</span>
                                                    </div>
                                                @endif
                                                
                                                @if(!empty($details['ribbon']))
                                                    <div class="flex items-start">
                                                        <span class="flex-shrink-0 w-20 text-xs font-medium text-gray-500">Pita:</span>
                                                        <span class="text-xs text-gray-900">{{ $details['ribbon'] }}</span>
                                                    </div>
                                                @endif
                                                
                                                @if(!empty($details['wrapper']))
                                                    <div class="flex items-start">
                                                        <span class="flex-shrink-0 w-20 text-xs font-medium text-gray-500">Pembungkus:</span>
                                                        <span class="text-xs text-gray-900">{{ $details['wrapper'] }}</span>
                                                    </div>
                                                @endif

                                                @if(!empty($item->custom_instructions))
                                                    <div class="flex items-start">
                                                        <span class="flex-shrink-0 w-20 text-xs font-medium text-gray-500">Instruksi:</span>
                                                        <span class="text-xs text-gray-900">{{ $item->custom_instructions }}</span>
                                                    </div>
                                                @endif

                                                @if(!empty($details['reference_image']))
                                                    <div class="flex items-center mt-1">
                                                        <i class="bi bi-image text-pink-500 mr-1"></i>
                                                        <span class="text-xs text-pink-600">Dengan referensi gambar</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($item->item_type === 'bouquet')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-rose-500 to-pink-500 text-white">
                                                <i class="bi bi-flower1 mr-1"></i>Bouquet
                                            </span>
                                            @if(!empty($item->details))
                                                @php
                                                    if (is_string($item->details)) {
                                                        $decoded = json_decode($item->details, true);
                                                        $details = is_array($decoded) ? $decoded : [];
                                                    } elseif (is_array($item->details)) {
                                                        $details = $item->details;
                                                    } else {
                                                        $details = [];
                                                    }
                                                @endphp
                                                <div class="mt-2 space-y-1 bg-gray-50 p-3 rounded-lg">
                                                    @foreach($details as $key => $value)
                                                        @if(!empty($value))
                                                            <div class="flex items-start">
                                                                <span class="flex-shrink-0 w-20 text-xs font-medium text-gray-500">{{ ucfirst($key) }}:</span>
                                                                <span class="text-xs text-gray-900">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- Detail untuk produk non-custom -->
                                    @if($item->item_type === 'bouquet' || $item->item_type === 'flower')
                                        <div class="mt-3 space-y-2">
                                            @if(!empty($item->description))
                                                <div class="flex items-start">
                                                    <span class="flex-shrink-0 w-20 text-xs font-medium text-gray-500">Deskripsi:</span>
                                                    <span class="text-xs text-gray-900">{{ $item->description }}</span>
                                                </div>
                                            @endif
                                            @if(!empty($item->specifications))
                                                <div class="flex items-start">
                                                    <span class="flex-shrink-0 w-20 text-xs font-medium text-gray-500">Spesifikasi:</span>
                                                    <span class="text-xs text-gray-900">{{ $item->specifications }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if($item->item_type === 'custom_bouquet')
                                        <div class="mt-2 space-y-1">
                                            @if(!empty($item->custom_flowers))
                                                <p class="text-xs text-gray-600">
                                                    <span class="font-medium text-pink-600">Bunga:</span>
                                                    {{ $item->custom_flowers }}
                                                </p>
                                            @endif
                                            @if(!empty($item->custom_ribbon))
                                                <p class="text-xs text-gray-600">
                                                    <span class="font-medium text-pink-600">Pita:</span>
                                                    {{ $item->custom_ribbon }}
                                                </p>
                                            @endif
                                            @if(!empty($item->custom_instructions))
                                                <p class="text-xs text-gray-600">
                                                    <span class="font-medium text-pink-600">Instruksi:</span>
                                                    {{ $item->custom_instructions }}
                                                </p>
                                            @endif
                                            @if(!empty($item->reference_image))
                                                <p class="text-xs text-gray-600">
                                                    <span class="font-medium text-pink-600">
                                                        <i class="bi bi-image mr-1"></i>Referensi gambar tersedia
                                                    </span>
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $item->price_type ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right text-sm text-gray-900 font-medium">
                                    <span class="whitespace-nowrap">Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-4 py-4 text-center text-sm text-gray-600">
                                    {{ $item->unit_equivalent ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-center text-sm text-gray-900 font-medium">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-semibold text-gray-900">
                                    <span class="whitespace-nowrap">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden space-y-3">
                    @php $total = 0; @endphp
                    @foreach($order->items as $index => $item)
                    @php $subtotal = ($item->price ?? 0) * ($item->quantity ?? 0);
    $total += $subtotal; @endphp
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header dengan nomor dan nama produk -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center">
                                <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-xs font-semibold text-blue-700 mr-3">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $item->product_name }}</h4>
                                    
                                    <!-- Badge tipe produk untuk mobile -->
                                    @if(isset($item->item_type))
                                        <div class="mt-1">
                                            @if($item->item_type === 'bouquet')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-rose-500 to-pink-500 text-white">
                                                    <i class="bi bi-flower1 mr-1"></i>Bouquet
                                                </span>
                                            @elseif($item->item_type === 'custom_bouquet')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-purple-500 to-indigo-500 text-white">
                                                    <i class="bi bi-palette mr-1"></i>Custom Bouquet
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-green-500 to-teal-500 text-white">
                                                    <i class="bi bi-flower2 mr-1"></i>Bunga
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Detail Custom Bouquet untuk mobile -->
                                    @if($item->item_type === 'custom_bouquet')
                                        <div class="mt-3 space-y-2 bg-gray-50 p-3 rounded-lg">
                                            @if(!empty($item->custom_flowers))
                                                <div class="flex items-start">
                                                    <span class="flex-shrink-0 w-20 text-xs font-medium text-gray-500">Bunga:</span>
                                                    <span class="text-xs text-gray-900">{{ $item->custom_flowers }}</span>
                                                </div>
                                            @endif
                                            @if(!empty($item->custom_ribbon))
                                                <div class="flex items-start">
                                                    <span class="flex-shrink-0 w-20 text-xs font-medium text-gray-500">Pita:</span>
                                                    <span class="text-xs text-gray-900">{{ $item->custom_ribbon }}</span>
                                                </div>
                                            @endif
                                            @if(!empty($item->custom_instructions))
                                                <div class="flex items-start">
                                                    <span class="flex-shrink-0 w-20 text-xs font-medium text-gray-500">Instruksi:</span>
                                                    <span class="text-xs text-gray-900">{{ $item->custom_instructions }}</span>
                                                </div>
                                            @endif
                                            @if(!empty($item->reference_image))
                                                <div class="flex items-center mt-1">
                                                    <i class="bi bi-image text-pink-500 mr-1"></i>
                                                    <span class="text-xs text-pink-600">Dengan referensi gambar</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Detail produk non-custom untuk mobile -->
                                    @if($item->item_type === 'bouquet' || $item->item_type === 'flower')
                                        <div class="mt-3 space-y-2 bg-gray-50 p-3 rounded-lg">
                                            @if(!empty($item->description))
                                                <div class="flex items-start">
                                                    <span class="flex-shrink-0 w-20 text-xs font-medium text-gray-500">Deskripsi:</span>
                                                    <span class="text-xs text-gray-900">{{ $item->description }}</span>
                                                </div>
                                            @endif
                                            @if(!empty($item->specifications))
                                                <div class="flex items-start">
                                                    <span class="flex-shrink-0 w-20 text-xs font-medium text-gray-500">Spesifikasi:</span>
                                                    <span class="text-xs text-gray-900">{{ $item->specifications }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Detail informasi dalam grid -->
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tipe Harga:</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $item->price_type ?? '-' }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-500">Satuan:</span>
                                <span class="font-medium text-gray-900">{{ $item->unit_equivalent ?? '-' }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-500">Harga:</span>
                                <span class="font-medium text-gray-900">Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-500">Jumlah:</span>
                                <span class="font-medium text-gray-900">{{ $item->quantity }}</span>
                            </div>

                            <div class="flex justify-between col-span-2 pt-2 border-t border-gray-200">
                                <span class="text-gray-500 font-medium">Subtotal:</span>
                                <span class="font-semibold text-pink-600">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="px-4 sm:px-6 py-6 bg-gradient-to-r from-pink-50 to-rose-50">
                <div class="space-y-4">
                    @php 
                        $items_total = $total; // items total already calculated above
                        $shipping_fee = $order->shipping_fee ?? 0;
                        $voucher_amount = $order->voucher_amount ?? 0;
                        $grand_total = $items_total + $shipping_fee - $voucher_amount;
                        $total_paid = $order->amount_paid ?? 0;
                        $sisa_pembayaran = $order->payment_status === 'paid' ? 0 : max($grand_total - $total_paid, 0);
                        $display_total_paid = $order->payment_status === 'paid' ? $grand_total : $total_paid;
                    @endphp
                    
                    <!-- Items Total -->
                    <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-100">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 text-center sm:text-left">
                            <div class="flex items-center justify-center sm:justify-start">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="bi bi-box-seam text-gray-600 text-sm"></i>
                                </div>
                                <span class="text-base sm:text-lg font-semibold text-gray-700">Total Produk</span>
                            </div>
                            <span class="text-lg sm:text-xl font-bold text-gray-700">
                                Rp{{ number_format($items_total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    
                    @if($shipping_fee > 0)
                    <!-- Shipping Fee -->
                    <div class="p-4 bg-white rounded-lg shadow-sm border border-orange-100">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 text-center sm:text-left">
                            <div class="flex items-center justify-center sm:justify-start">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="bi bi-truck text-orange-600 text-sm"></i>
                                </div>
                                <span class="text-base sm:text-lg font-semibold text-gray-700">Ongkir</span>
                            </div>
                            <span class="text-lg sm:text-xl font-bold text-orange-600">
                                Rp{{ number_format($shipping_fee, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @endif

                    @if($voucher_amount > 0)
                    <!-- Voucher Discount -->
                    <div class="p-4 bg-white rounded-lg shadow-sm border border-purple-100">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 text-center sm:text-left">
                            <div class="flex items-center justify-center sm:justify-start">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="bi bi-ticket-perforated text-purple-600 text-sm"></i>
                                </div>
                                <span class="text-base sm:text-lg font-semibold text-gray-700">Voucher</span>
                            </div>
                            <span class="text-lg sm:text-xl font-bold text-purple-600">
                                -Rp{{ number_format($voucher_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Grand Total -->
                    <div class="p-4 bg-white rounded-lg shadow-sm border border-pink-100">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 text-center sm:text-left">
                            <div class="flex items-center justify-center sm:justify-start">
                                <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="bi bi-calculator text-pink-600 text-sm"></i>
                                </div>
                                <span class="text-base sm:text-lg font-semibold text-gray-700">Total Keseluruhan</span>
                            </div>
                            <span class="text-lg sm:text-xl font-bold text-pink-600">
                                Rp{{ number_format($grand_total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    @if($total_paid > 0)
                    <!-- Amount Paid -->
                    <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 text-center sm:text-left">
                            <div class="flex items-center justify-center sm:justify-start">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="bi bi-check-circle text-green-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Total Sudah Dibayar</span>
                            </div>
                            <span class="text-base sm:text-lg font-semibold text-green-700">
                                Rp{{ number_format($display_total_paid, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    @if($sisa_pembayaran > 0)
                    <!-- Remaining Payment -->
                    <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 text-center sm:text-left">
                            <div class="flex items-center justify-center sm:justify-start">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="bi bi-exclamation-circle text-red-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Sisa Pembayaran</span>
                            </div>
                            <span class="text-base sm:text-lg font-semibold text-red-700">
                                Rp{{ number_format($sisa_pembayaran, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
            <!-- Payment Proof Section -->
            @if(!empty($order->payment_proof))
            <div class="px-6 py-6 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="bi bi-image mr-2 text-purple-600"></i>Bukti Pembayaran
                </h2>
                <div class="flex justify-center">
                    @php
    $ext = pathinfo($order->payment_proof, PATHINFO_EXTENSION);
                    @endphp
                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                        <img src="{{ asset('storage/' . $order->payment_proof) }}"
                             alt="Bukti Pembayaran"
                             class="max-w-sm max-h-64 rounded-lg shadow-md border border-gray-200"
                             onerror="this.style.display='none'; document.getElementById('payment-proof-error').style.display='block';" />
                    @elseif(strtolower($ext) == 'pdf')
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
                            <i class="bi bi-file-earmark-pdf mr-2"></i>Lihat Bukti Pembayaran (PDF)
                        </a>
                    @else
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
                            <i class="bi bi-download mr-2"></i>Download Bukti Pembayaran
                        </a>
                    @endif
                    <div id="payment-proof-error" style="display:none;" class="text-center p-4 text-red-600 bg-red-50 rounded-lg border border-red-200">
                        <i class="bi bi-exclamation-triangle mr-2"></i>Bukti pembayaran tidak ditemukan di server.
                    </div>
                </div>
            </div>
            @endif

            <!-- Packing Files Section -->
            @if(!empty($order->packing_photo) || !empty($order->packing_files))
            <div class="px-6 py-6 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="bi bi-box-seam mr-2 text-orange-600"></i>Foto & Video Packing
                </h2>
                
                @php
    $packingFiles = [];
    // Prioritize new multiple files format
    if (!empty($order->packing_files)) {
        if (is_string($order->packing_files)) {
            $decoded = json_decode($order->packing_files, true);
            $packingFiles = is_array($decoded) ? $decoded : [];
        } elseif (is_array($order->packing_files)) {
            $packingFiles = $order->packing_files;
        }
    }
    // Fallback to old single photo format only if no packing_files
    elseif (!empty($order->packing_photo)) {
        $packingFiles[] = $order->packing_photo;
    }
                @endphp
                
                @if(count($packingFiles) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
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
                                         onclick="openPackingImageModal('{{ $filePath }}', 'Foto Packing {{ $index + 1 }}')"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
                                    <div style="display:none;" class="text-center p-4 text-red-600 bg-red-50 rounded-lg border border-red-200">
                                        <i class="bi bi-exclamation-triangle mr-2"></i>Foto tidak ditemukan.
                                    </div>
                                    <div class="flex items-center justify-center text-sm text-gray-600">
                                        <i class="bi bi-camera mr-2 text-green-500"></i>
                                        Foto Packing {{ $index + 1 }}
                                    </div>
                                @else
                                    <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center mb-2">
                                        <div class="text-center">
                                            <i class="bi bi-file-earmark text-3xl text-gray-400 mb-2"></i>
                                            <p class="text-sm text-gray-500">File {{ $index + 1 }}</p>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ $filePath }}" target="_blank" 
                                           class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                            <i class="bi bi-download mr-1"></i>
                                            Download File
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex justify-center">
                        <div class="text-center p-4 text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="bi bi-camera text-2xl mb-2"></i>
                            <p class="text-sm">Belum ada foto atau video packing.</p>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Packing Image Modal -->
            <div id="packingImageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4 no-print">
                <div class="relative max-w-4xl max-h-full">
                    <img id="packingModalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
                    <button onclick="closePackingImageModal()" 
                            class="absolute top-4 right-4 text-white bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <div id="packingModalTitle" class="absolute bottom-4 left-4 text-white bg-black bg-opacity-50 px-3 py-1 rounded-lg text-sm"></div>
                </div>
            </div>
            
            <script class="no-print">
                function openPackingImageModal(src, title) {
                    document.getElementById('packingModalImage').src = src;
                    document.getElementById('packingModalTitle').textContent = title;
                    document.getElementById('packingImageModal').classList.remove('hidden');
                }
                
                function closePackingImageModal() {
                    document.getElementById('packingImageModal').classList.add('hidden');
                }
                
                // Close modal when clicking outside
                document.getElementById('packingImageModal')?.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closePackingImageModal();
                    }
                });
                
                // Close modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closePackingImageModal();
                    }
                });
            </script>
            @endif

            <!-- Footer -->
            <div class="px-4 sm:px-6 py-6 text-center border-t border-gray-200 bg-gray-50">
                <div class="space-y-4">
                    <div class="text-center mb-4">
                        <div class="flex items-center justify-center mb-2">
                            <i class="bi bi-heart-fill text-pink-600 text-xl mr-2"></i>
                            <p class="text-lg font-semibold text-gray-900">Terima Kasih!</p>
                        </div>
                        <p class="text-sm text-gray-600">Telah mempercayai Seikat Bungo untuk pesanan Anda</p>
                    </div>

                    <div class="text-sm text-gray-600 space-y-2">
                        <p class="font-medium">Butuh bantuan?</p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4">
                            <a href="tel:085119990901"
                                class="inline-flex items-center text-blue-600 hover:text-blue-700">
                                <i class="bi bi-telephone mr-1"></i>0851-1999-0901
                            </a>
                            <a href="https://instagram.com/seikat.bungo" target="_blank"
                                class="inline-flex items-center text-pink-600 hover:text-pink-700">
                                <i class="bi bi-instagram mr-1"></i>@seikat.bungo
                            </a>
                        </div>
                    </div>

                    <!-- Print Button -->
                    {{-- <div class="no-print mt-6">
                        <button onclick="window.print()"
                            class="inline-flex items-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
                            <i class="bi bi-printer mr-2"></i>Cetak Invoice
                        </button>
                    </div> --}}

                    <!-- Digital Invoice Info -->
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="flex items-center justify-center text-yellow-800">
                            <i class="bi bi-info-circle-fill mr-2"></i>
                            <span class="text-xs">Invoice digital ini sah dan dapat digunakan sebagai bukti pesanan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
