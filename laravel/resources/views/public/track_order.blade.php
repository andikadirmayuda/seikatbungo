<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Title --}}
    <title>Lacak Pesanan | Seikat Bungo</title>
    <link rel="icon" href="{{ asset(config('app.logo')) }}" type="image/png">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Figtree Font -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />
    <!-- Notification Styles -->
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
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

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
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
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }

        /* Brand logo dengan efek hover */
        .brand-logo {
            transition: all 0.3s ease;
        }

        .brand-logo:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(244, 63, 94, 0.3);
        }

        /* Animation untuk order detail icon */
        .order-detail-pulse {
            animation: orderPulse 2s infinite;
        }

        @keyframes orderPulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }
        }

        .notification-badge {
            animation: bounce 1s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-3px);
            }

            60% {
                transform: translateY(-1px);
            }
        }

        /* Status badges */
        .status-badge {
            @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
        }

        .status-draft {
            @apply bg-gray-100 text-gray-800;
        }

        .status-pending {
            @apply bg-yellow-100 text-yellow-800;
        }

        .status-processing {
            @apply bg-blue-100 text-blue-800;
        }

        .status-shipped {
            @apply bg-purple-100 text-purple-800;
        }

        .status-delivered {
            @apply bg-green-100 text-green-800;
        }

        .status-cancelled {
            @apply bg-red-100 text-red-800;
        }

        .order-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .order-card:hover {
            border-left-color: #f43f5e;
            transform: translateX(4px);
        }

        .search-container {
            position: relative;
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .mobile-responsive {
                padding: 1rem;
            }

            .mobile-card {
                margin: 0.5rem 0;
                border-radius: 0.75rem;
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
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

<body class="min-h-screen gradient-bg text-black flex flex-col font-sans overflow-x-hidden">
    @include('public.partials.cart-modal')
    @include('public.partials.cart-panel')

    <!-- Header -->
    <header class="w-full glass-effect border-b border-gray-100 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Top Bar -->
            <div class="flex items-center justify-between h-16">
                <!-- Brand Section -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('public.flowers') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('logo-seikat-bungo.png') }}" alt="Logo"
                            class="brand-logo w-10 h-10 rounded-full">
                        <div>
                            <h1 class="text-lg font-bold text-gray-800">Seikat Bungo</h1>
                            <p class="text-xs text-gray-500">Since 2025</p>
                        </div>
                    </a>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-4">
                    <!-- Track Order - Active -->
                    <a href="{{ route('public.order.track') }}"
                        class="text-white bg-rose-500 hover:bg-rose-600 p-2 rounded-full hover:shadow-lg transition-all duration-200"
                        title="Lacak Pesanan">
                        <i class="bi bi-truck text-xl"></i>
                    </a>

                    <!-- Order Detail - Muncul setelah checkout -->
                    @if(session('last_public_order_code'))
                        <a href="{{ route('public.order.detail', ['public_code' => session('last_public_order_code')]) }}"
                            class="relative text-white bg-rose-500 hover:bg-rose-600 p-2 rounded-full hover:shadow-lg transition-all duration-200 order-detail-pulse"
                            title="Lihat Detail Pesanan Terbaru - Kode: {{ session('last_public_order_code') }}">
                            <i class="bi bi-receipt-cutoff text-xl"></i>
                            <span
                                class="absolute -top-1 -right-1 bg-green-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold notification-badge">
                                ✓
                            </span>
                        </a>
                    @endif

                    <!-- Cart -->
                    {{-- <button onclick="toggleCart()"
                        class="text-gray-600 hover:text-rose-600 relative p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                        title="Keranjang Belanja">
                        <i class="bi bi-bag text-xl"></i>
                        <span id="cartBadge"
                            class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-xs rounded-full flex items-center justify-center hidden">0</span>
                    </button> --}}

                    <!-- Kembali ke Beranda -->
                    <a href="{{ route('public.flowers') }}"
                        class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 hover:text-gray-900 font-medium rounded-lg border border-gray-300 transition-all duration-200 hover:scale-105 hover:shadow-md"
                        title="Kembali ke Beranda">
                        <i class="bi bi-house-door"></i>
                        <span>Beranda</span>
                    </a>

                    <!-- Kembali ke Beranda (Mobile) -->
                    <a href="{{ route('public.flowers') }}"
                        class="sm:hidden text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                        title="Kembali ke Beranda">
                        <i class="bi bi-house-door text-xl"></i>
                    </a>

                    {{-- <a href="{{ route('login') }}"
                        class="text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                        title="Login">
                        <i class="bi bi-person-circle text-xl"></i>
                    </a> --}}
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="bg-white">
        <div class="max-w-4xl mx-auto px-4 py-8 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Lacak Pesanan Anda</h2>
            <p class="text-gray-600 mb-2">Pantau status dan detail pesanan bunga Anda dengan mudah</p>
            {{-- <p class="text-sm text-gray-500 flex items-center justify-center gap-2">
                <i class="bi bi-clock text-rose-400"></i>
                Terakhir diperbarui:
                {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
            </p> --}}
        </div>
    </div>

    <!-- Main Content -->
    <div class="w-full max-w-6xl mx-auto px-4 py-6 flex-1">
        <!-- Search Section -->
        <div class="mb-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                    <div class="text-center mb-6">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-rose-100 to-pink-200 rounded-full mb-4">
                            <i class="bi bi-search text-rose-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Cari Pesanan Anda</h3>
                        <p class="text-gray-600">Masukkan nomor WhatsApp yang digunakan saat pemesanan</p>
                    </div>

                    <form method="GET" action="{{ route('public.order.track') }}" class="space-y-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                <i class="bi bi-whatsapp text-green-500 mr-2"></i>
                                Nomor WhatsApp
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="bi bi-telephone text-gray-400"></i>
                                </div>
                                <input type="text" name="wa_number"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Contoh: 08123456789" value="{{ $wa_number ?? '' }}" required
                                    pattern="[0-9]+" title="Masukkan nomor WhatsApp (hanya angka)">
                            </div>
                            <p class="text-sm text-gray-500 flex items-center gap-2">
                                <i class="bi bi-info-circle"></i>
                                Gunakan nomor yang sama saat melakukan pemesanan
                            </p>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 hover:scale-105 hover:shadow-lg flex items-center justify-center gap-3">
                            <i class="bi bi-search"></i>
                            <span>Lacak Pesanan</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        @if($wa_number)
            <div class="fade-in">
                @if($orders->isEmpty())
                    <!-- No Orders Found -->
                    <div class="max-w-2xl mx-auto">
                        <div class="bg-white rounded-xl shadow-lg p-8 text-center card-hover">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-6">
                                <i class="bi bi-inbox text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Pesanan Ditemukan</h3>
                            <p class="text-gray-600 mb-6">
                                Tidak ada pesanan yang ditemukan untuk nomor WhatsApp <strong>{{ $wa_number }}</strong>
                            </p>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-left">
                                <h4 class="font-medium text-blue-900 mb-2 flex items-center gap-2">
                                    <i class="bi bi-lightbulb"></i>
                                    Tips untuk melacak pesanan:
                                </h4>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• Pastikan nomor WhatsApp yang dimasukkan sama dengan saat pemesanan</li>
                                    <li>• Coba tanpa kode negara (+62) atau dengan format berbeda</li>
                                    <li>• Hubungi customer service jika masih bermasalah</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Orders List -->
                    <div class="space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Pesanan Anda</h2>
                                <p class="text-gray-600 mt-1">
                                    Ditemukan {{ $orders->count() }} pesanan untuk <strong>{{ $wa_number }}</strong>
                                </p>
                            </div>
                            <div class="mt-4 sm:mt-0">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="bi bi-check-circle mr-2"></i>
                                    {{ $orders->count() }} Pesanan
                                </span>
                            </div>
                        </div>

                        <div class="grid gap-6">
                            @foreach($orders as $order)
                                <div
                                    class="order-card bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                        <div class="flex-1 space-y-4">
                                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
                                                <div class="space-y-2">
                                                    <div class="flex items-center gap-3">
                                                        <h3 class="text-lg font-semibold text-gray-900">
                                                            #{{ $order->public_code }}
                                                        </h3>
                                                        <span class="status-badge status-{{ strtolower($order->status) }}">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                                        <span class="flex items-center gap-1">
                                                            <i class="bi bi-calendar3"></i>
                                                            {{ $order->created_at->format('d M Y') }}
                                                        </span>
                                                        <span class="flex items-center gap-1">
                                                            <i class="bi bi-clock"></i>
                                                            {{ $order->created_at->format('H:i') }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="mt-4 sm:mt-0 sm:text-right">
                                                    <div class="text-lg font-bold text-gray-900">
                                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $order->items->count() }} item{{ $order->items->count() > 1 ? 's' : '' }}
                                                    </div>
                                                </div>
                                            </div>

                                            @if($order->customer_name)
                                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                                    <i class="bi bi-person"></i>
                                                    <span>{{ $order->customer_name }}</span>
                                                </div>
                                            @endif

                                            @if($order->notes)
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <div class="flex items-start gap-2">
                                                        <i class="bi bi-chat-left-text text-gray-400 mt-0.5"></i>
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-700">Catatan:</p>
                                                            <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-6 lg:mt-0 lg:ml-6 flex flex-col sm:flex-row gap-3">
                                            <a href="{{ route('public.order.detail', ['public_code' => $order->public_code]) }}"
                                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-rose-500 hover:bg-rose-600 text-white font-medium rounded-lg transition-all duration-200 hover:scale-105">
                                                <i class="bi bi-eye"></i>
                                                <span>Lihat Detail</span>
                                            </a>

                                            @if(in_array($order->status, ['pending', 'processing']))
                                                <button onclick="trackOrder('{{ $order->public_code }}')"
                                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg border border-gray-200 transition-all duration-200 hover:scale-105">
                                                    <i class="bi bi-geo-alt"></i>
                                                    <span>Lacak Status</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Help Section -->
        <div class="mt-16">
            <div class="bg-white rounded-xl shadow-lg p-8 card-hover">
                <div class="text-center mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Butuh Bantuan?</h3>
                    <p class="text-gray-600">Tim customer service kami siap membantu Anda</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-4">
                            <i class="bi bi-whatsapp text-green-600 text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">WhatsApp</h4>
                        <p class="text-gray-600 text-sm mb-3">Chat langsung dengan tim kami</p>
                        <a href="https://wa.me/+6282177929879"
                            class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-medium">
                            <i class="bi bi-arrow-right"></i>
                            Hubungi Kami
                        </a>
                    </div>

                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-4">
                            <i class="bi bi-telephone text-blue-600 text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Telepon</h4>
                        <p class="text-gray-600 text-sm mb-3">Hubungi customer service</p>
                        <a href="tel:+6282177929879"
                            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                            <i class="bi bi-arrow-right"></i>
                            (+62) 821-7792-9879
                        </a>
                    </div>

                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full mb-4">
                            <i class="bi bi-envelope text-purple-600 text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Email</h4>
                        <p class="text-gray-600 text-sm mb-3">Kirim pertanyaan Anda</p>
                        <a href="mailto:support@seikatbungo.com"
                            class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-700 font-medium">
                            <i class="bi bi-arrow-right"></i>
                            Kirim Email
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Enhanced animations and interactions
            document.addEventListener('DOMContentLoaded', function () {
                // Add loading states for better UX
                const searchForm = document.querySelector('form');
                const searchButton = searchForm.querySelector('button[type="submit"]');

                searchForm.addEventListener('submit', function () {
                    searchButton.innerHTML = `
                    <div class="inline-flex items-center gap-3">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                        <span>Mencari...</span>
                    </div>
                `;
                    searchButton.disabled = true;
                });

                // Add number formatting for phone input
                const phoneInput = document.querySelector('input[name="wa_number"]');
                if (phoneInput) {
                    phoneInput.addEventListener('input', function (e) {
                        // Remove non-numeric characters
                        let value = e.target.value.replace(/\D/g, '');
                        e.target.value = value;
                    });
                }
            });

            function trackOrder(orderCode) {
                // This function can be enhanced to show real-time tracking
                alert(`Melacak pesanan ${orderCode}...\n\nFitur ini akan segera tersedia!`);
            }
        </script>

        <!-- Cart JavaScript -->
        <script src="{{ asset('js/cart.js') }}?v={{ time() }}"></script>

</body>

</html>