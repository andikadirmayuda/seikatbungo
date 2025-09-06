<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Seikat Bungo</title>
    <link rel="icon" href="{{ asset(config('app.logo')) }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Figtree Font -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Figtree', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body,
        .font-sans {
            font-family: 'Figtree', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #fdf2f8 0%, #ffffff 50%, #f0fdf4 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            ring: 2px;
            ring-color: rgba(244, 63, 94, 0.5);
            border-color: rgb(244, 63, 94);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Animation untuk form */
        .form-enter {
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="min-h-screen gradient-bg font-sans">
    <!-- Header -->
    <header class="w-full glass-effect border-b border-gray-100 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Section -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('public.flowers') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-rose-500 rounded-full flex items-center justify-center">
                            <img src="{{ asset('logo-seikat-bungo.png') }}" alt="Logo" class="rounded-full w-9 h-9">
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-gray-800">Seikat Bungo</h1>
                            <p class="text-xs text-gray-500">Since 2025</p>
                        </div>
                    </a>
                </div>

                <!-- Back Button -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('public.flowers') }}"
                        class="text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                        title="Kembali Berbelanja">
                        <i class="bi bi-arrow-left text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Page Header -->
        <div class="text-center mb-8 form-enter">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-rose-500 to-pink-600 rounded-full mb-4 shadow-lg"
                style="background: #247A72;">
                <i class="bi bi-cart-check text-2xl text-white"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                Checkout <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-pink-600"
                    style="color: #247A72;">Pesanan</span>
            </h1>
            <p class="text-gray-600">Lengkapi data pesanan Anda untuk melanjutkan</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6 form-enter">
                <div class="flex items-center">
                    <i class="bi bi-exclamation-triangle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if(empty($cartData))
            <div class="bg-white rounded-2xl shadow-lg border border-rose-100 p-8 text-center form-enter">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                    <i class="bi bi-cart-x text-3xl text-yellow-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Keranjang Belanja Kosong</h3>
                <p class="text-gray-600 mb-6">Silakan tambahkan produk ke keranjang terlebih dahulu</p>
                <a href="{{ route('public.flowers') }}"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-semibold rounded-xl hover:from-rose-600 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="bi bi-shop mr-2"></i>
                    Mulai Berbelanja
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-12">
                <!-- Form Section -->
                <div class="lg:col-span-3">
                    <form method="POST" action="{{ route('public.checkout.process') }}"
                        class="bg-white rounded-2xl shadow-lg border border-rose-100 p-6 form-enter">
                        @csrf

                        @if(session('debug'))
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-3 rounded-xl mb-6">
                                <i class="bi bi-info-circle mr-2"></i>
                                Debug: {{ json_encode(session('debug')) }}
                            </div>
                        @endif

                        <!-- Form Header -->
                        <div class="mb-6 pb-4 border-b border-gray-100">
                            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                <i class="bi bi-person-lines-fill mr-2 text-rose-500"></i>
                                Data Pemesanan
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Isi data dengan lengkap dan benar</p>
                        </div>

                        <!-- Form Fields -->
                        <div class="space-y-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-person mr-1 text-rose-500"></i>
                                    Nama Lengkap Pemesan
                                </label>
                                <input type="text" name="customer_name"
                                    class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                    placeholder="Masukkan nama lengkap Anda" required>
                            </div>

                            <!-- No. WhatsApp -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-whatsapp mr-1 text-rose-500"></i>
                                    No. WhatsApp Pemesan
                                </label>
                                <input type="text" name="wa_number"
                                    class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                    placeholder="Contoh: 08123456789" required>
                            </div>

                            <!-- Nama Penerima (Opsional) -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-person-check mr-1 text-rose-500"></i>
                                    Nama Penerima <span class="text-gray-400 font-normal">(Opsional/Jika Ada)</span>
                                </label>
                                <input type="text" name="receiver_name"
                                    class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                    placeholder="Masukkan nama penerima jika berbeda dengan pemesan">
                            </div>

                            <!-- No. WhatsApp Penerima (Opsional) -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-whatsapp mr-1 text-rose-500"></i>
                                    No. WhatsApp Penerima <span class="text-gray-400 font-normal">(Opsional/Jika Ada)</span>
                                </label>
                                <input type="text" name="receiver_wa"
                                    class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                    placeholder="Masukkan nomor WA penerima jika berbeda dengan pemesan">
                            </div>

                            <!-- Tanggal & Waktu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="bi bi-calendar-event mr-1 text-rose-500"></i>
                                        Tanggal Ambil/Kirim
                                    </label>
                                    <input type="date" name="pickup_date" id="pickup_date"
                                        class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                        required>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        Hari: <span id="day_name" class="font-medium text-rose-600">-</span>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="bi bi-clock mr-1 text-rose-500"></i>
                                        Waktu Ambil/Pengiriman
                                    </label>
                                    <input type="time" name="pickup_time"
                                        class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                        required>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        Waktu: <span id="pickup_time_display" class="font-medium text-rose-600">-</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Metode Pengiriman -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-truck mr-1 text-rose-500"></i>
                                    Metode Pengiriman
                                </label>
                                <select name="delivery_method" id="delivery_method"
                                    class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                    required>
                                    <option value="">Pilih metode pengiriman</option>
                                    <option value="Ambil Langsung Ke Toko">🏪 (1) Ambil Langsung Ke Toko</option>
                                    <option value="Gosend (Dipesan Pribadi)">🚗 (2) Gosend (Dipesan Pribadi)</option>
                                    <option value="Gocar (Dipesan Pribadi)">🚕 (3) Gocar (Dipesan Pribadi)</option>
                                    <option value="Gosend (Pesan Dari Toko)">🛻 (4) Gosend (Pesan Dari Toko)</option>
                                    <option value="Gocar (Pesan Dari Toko)">🚕 (5) Gocar (Pesan Dari Toko)</option>
                                    <option value="Travel (Di Pesan Sendiri)">🚌 (6) Travel (Di Pesan Sendiri - Khusus Luar
                                        Kota)</option>
                                </select>
                            </div>

                            <!-- Tujuan Pengiriman -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-geo-alt mr-1 text-rose-500"></i>
                                    Tujuan Pengiriman
                                </label>
                                <textarea name="destination"
                                    class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                    rows="3" placeholder="Masukkan alamat lengkap pengiriman"></textarea>
                            </div>

                            <!-- Catatan Pesanan -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-chat-left-text mr-1 text-rose-500"></i>
                                    Catatan untuk Pesanan <span class="text-gray-400 font-normal">(Opsional)</span>
                                </label>
                                <textarea name="notes"
                                    class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                    rows="4"
                                    placeholder="Contoh: Bunga untuk acara ulang tahun, warna dominan pink, jangan terlalu besar, dll."></textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="bi bi-info-circle mr-1"></i>
                                    Berikan detail khusus yang Anda inginkan untuk pesanan ini
                                </p>
                            </div>

                            @php
                                $hasCustomBouquet = false;
                                foreach ($cartData as $item) {
                                    if (isset($item['type']) && $item['type'] === 'custom_bouquet') {
                                        $hasCustomBouquet = true;
                                        break;
                                    }
                                }
                            @endphp

                            @if($hasCustomBouquet)
                                <!-- Instruksi Custom Bouquet -->
                                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                                    <label class="block text-sm font-semibold text-purple-700 mb-2">
                                        <i class="bi bi-palette mr-1 text-purple-500"></i>
                                        Instruksi Khusus untuk Custom Bouquet <span
                                            class="text-purple-400 font-normal">(Opsional)</span>
                                    </label>
                                    <textarea name="custom_instructions"
                                        class="w-full px-4 py-3 border border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none"
                                        rows="3"
                                        placeholder="Contoh: Kartu Ucapan, Tambahkan pita warna emas, bungkus dengan kertas transparan, dominasi warna ungu, dll."></textarea>
                                    <p class="text-xs text-purple-600 mt-1">
                                        <i class="bi bi-lightbulb mr-1"></i>
                                        Berikan detail tambahan untuk custom bouquet Anda (Kartu Ucapan,warna preferensi, style
                                        wrapping, dll.)
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Info Note -->
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <div class="flex items-start">
                                <i class="bi bi-info-circle text-blue-600 mr-2 mt-0.5"></i>
                                <div class="text-sm text-blue-700">
                                    <p class="font-semibold mb-1">Informasi Penting:</p>
                                    <p>Setelah mengirim pesanan, Anda akan diarahkan ke halaman detail pesanan untuk
                                        memantau status dan proses pembayaran.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full mt-6 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl card-hover">
                            <i class="bi bi-send mr-2"></i>
                            Kirim Pesanan & Lihat Detail
                        </button>
                    </form>
                </div>

                <!-- Order Summary Section -->
                <div class="md:col-span-1 lg:col-span-2 w-full">
                    <div class="bg-white rounded-2xl shadow-lg border border-rose-100 p-6 form-enter sticky top-24 w-full">
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
                                                        <p class="text-pink-800 italic text-xs whitespace-pre-wrap">"{{ $item['greeting_card'] }}"</p>
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
                                                                        style="background-color: {{ App\Enums\RibbonColor::getColorCode($item['ribbon_color']) }};">
                                                                    </div>
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
                                            switch($type) {
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
                                        <div class="flex w-full max-w-md mx-auto rounded-2xl shadow-xl bg-white glass-effect border border-gray-200 overflow-hidden relative" style="min-height:120px;">
                                            <!-- Left: Logo & Info -->
                                            <div class="flex flex-col justify-center items-center w-2/5 py-5 px-4 bg-gradient-to-br from-emerald-500 to-teal-700 text-white relative">
                                                <div class="rounded-full bg-white/30 p-2 mb-2">
                                                    <i class="bi {{ $icon }} text-2xl text-white"></i>
                                                </div>
                                                <div class="font-bold text-lg tracking-wide">{{ $voucher['code'] }}</div>
                                                <div class="text-xs font-semibold mt-1">{{ $label }}</div>
                                                <div class="text-xs opacity-80 mt-1">{{ $mainDesc }}</div>
                                                @if($extra)
                                                    <div class="text-xs mt-1 bg-white/20 rounded px-2 py-1">{{ $extra }}</div>
                                                @endif
                                            </div>
                                            <!-- Middle: Potongan Dotted -->
                                            <div class="flex flex-col justify-center items-center w-2 bg-white">
                                                <div class="h-6 w-2 rounded-full bg-gray-100 mb-1"></div>
                                                <div class="h-6 w-2 rounded-full bg-gray-100 mb-1"></div>
                                                <div class="h-6 w-2 rounded-full bg-gray-100"></div>
                                            </div>
                                            <!-- Right: Diskon & Info -->
                                            <div class="flex-1 py-5 px-4 flex flex-col justify-center items-start bg-gradient-to-br from-orange-400 to-rose-500 text-white relative">
                                                <div class="font-bold text-2xl mb-1 flex items-center"><i class="bi {{ $icon }} mr-2"></i>{{ $mainValue }}</div>
                                                <div class="text-xs font-semibold mb-1">Min. Belanja Rp {{ number_format($voucher['minimum_spend'] ?? 0,0,',','.') }}</div>
                                                <div class="text-xs mb-1"><i class="bi bi-calendar-event mr-1"></i>
                                                    {{ $voucher['validity'] ?? '' }}
                                                </div>
                                                <form action="{{ route('checkout.remove-voucher') }}" method="POST" class="absolute top-2 right-2">
                                                    @csrf
                                                    <button type="submit" title="Hapus Voucher" class="bg-white/30 hover:bg-white/50 text-white rounded-full p-1 transition"><i class="bi bi-x-lg"></i></button>
                                                </form>
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
                    </div>
                </div>
        @endif
    </div>

    <script>
        // Fungsi untuk mendapatkan nama hari dalam Bahasa Indonesia
        function getDayName(date) {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            return days[date.getDay()];
        }

        // Event listener untuk input tanggal
        document.getElementById('pickup_date').addEventListener('change', function() {
            const dateValue = this.value;
            if (dateValue) {
                const selectedDate = new Date(dateValue);
                const dayName = getDayName(selectedDate);
                document.getElementById('day_name').textContent = dayName;
            } else {
                document.getElementById('day_name').textContent = '-';
            }
        });
    </script>

    <script>
        const pickupTimeInput = document.querySelector('input[name="pickup_time"]');
        const pickupTimeDisplay = document.getElementById('pickup_time_display');

        pickupTimeInput.addEventListener('input', () => {
            const timeValue = pickupTimeInput.value;
            if (!timeValue) {
                pickupTimeDisplay.textContent = "-";
                return;
            }

            // Pisahkan jam & menit
            const [hours, minutes] = timeValue.split(":").map(Number);
            let period = "";

            if (hours >= 4 && hours < 11) {
                period = "Pagi";       // 04:00 - 10:59
            } else if (hours >= 11 && hours < 15) {
                period = "Siang";      // 11:00 - 14:59
            } else if (hours >= 15 && hours < 18) {
                period = "Sore";       // 15:00 - 17:59
            } else {
                period = "Malam";      // 18:00 - 03:59
            }

            // Format jam supaya ada leading zero (contoh 08:05)
            const formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;

            pickupTimeDisplay.textContent = `${formattedTime} (${period})`;
        });
    </script>
</body>

</html>
