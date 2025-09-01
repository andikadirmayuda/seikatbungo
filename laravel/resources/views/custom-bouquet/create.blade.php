<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Bouquet | Seikat Bungo</title>
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

        // Fungsi untuk toggle menu mobile
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            if (mobileMenu.classList.contains('hidden')) {
                // Tampilkan menu
                mobileMenu.classList.remove('hidden');
                mobileMenu.classList.add('animate-fade-in-down');
            } else {
                // Sembunyikan menu
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('animate-fade-in-down');
            }
        }

        // Tutup menu mobile ketika user klik di luar menu
        document.addEventListener('click', function (event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const hamburgerButton = event.target.closest('[onclick="toggleMobileMenu()"]');

            if (!hamburgerButton && !mobileMenu.contains(event.target) && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Sinkronkan badge cart mobile dengan desktop
        const updateCartBadges = () => {
            const desktopBadge = document.getElementById('cartBadge');
            const mobileBadge = document.getElementById('cartBadgeMobile');
            if (desktopBadge && mobileBadge) {
                mobileBadge.textContent = desktopBadge.textContent;
                mobileBadge.classList.toggle('hidden', desktopBadge.classList.contains('hidden'));
            }
        };

        // Observer untuk memantau perubahan pada badge desktop
        const observer = new MutationObserver(updateCartBadges);
        const desktopBadge = document.getElementById('cartBadge');
        if (desktopBadge) {
            observer.observe(desktopBadge, {
                attributes: true,
                childList: true,
                characterData: true
            });
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

        /* Ribbon Color Selection Styles */
        .ribbon-color-btn {
            position: relative;
            transition: all 0.2s ease-in-out;
        }

        .ribbon-color-btn:hover {
            transform: translateY(-2px);
        }

        .ribbon-color-btn.selected-ribbon::after {
            content: '✓';
            position: absolute;
            top: -8px;
            right: -8px;
            background: #10B981;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #2D9C8F 0%, #FFFFFF 50%, #247A72 100%);
        }

        /* Konsisten dengan bouquets: gradient utama */
        .bg-gradient-main {
            background: linear-gradient(to bottom right, #F5F5F5, white, #F5F5F5);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(245, 166, 35, 0.25);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(45, 156, 143, 0.1);
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(39, 90, 89, 0.2);
            border-color: rgba(39, 90, 89, 0.3);
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

        /* Consistent card heights */
        .product-card {
            min-height: 300px;
        }

        @media (min-width: 640px) {
            .product-card {
                min-height: 350px;
            }
        }

        /* Better text sizing for mobile */
        @media (max-width: 639px) {
            .product-card .text-price {
                font-size: 0.875rem;
                line-height: 1.25rem;
            }
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

        /* Enhanced Navigation Styles */
        .nav-tab {
            position: relative;
            overflow: hidden;
        }

        .nav-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .nav-tab:hover::before {
            left: 100%;
        }

        /* Enhanced responsive design */
        @media (max-width: 640px) {
            .nav-mobile-text {
                font-size: 0.75rem;
                line-height: 1rem;
            }

            .nav-icon {
                width: 1.5rem;
                height: 1.5rem;
            }
        }

        @media (min-width: 641px) and (max-width: 768px) {
            .nav-tablet-spacing {
                margin-left: 1rem;
                margin-right: 1rem;
            }
        }

        /* Smooth hover animations */
        .nav-hover-effect {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-hover-effect:hover {
            transform: translateY(-2px);
        }

        /* Active tab gradient animation */
        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .nav-active-gradient {
            background-size: 200% 200%;
            animation: gradientShift 3s ease infinite;
        }

        /* Custom Bouquet Specific Styles */
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        /* Enhanced chip button styles - konsisten dengan bouquets */
        .chip-btn {
            border: 2px solid #2D9C8F;
            background: #fff;
            color: #2D9C8F;
            font-weight: 600;
            transition: all 0.2s;
        }

        .chip-btn.active {
            background: linear-gradient(135deg, #2D9C8F, #247A72);
            color: #fff;
            border-color: #2D9C8F;
            transform: translateY(-2px);
        }

        .chip-btn:hover {
            border-color: #2D9C8F;
            background-color: rgba(45, 156, 143, 0.1);
            color: #247A72;
        }

        .category-tab {
            border: 2px solid #2D9C8F;
            background: #fff;
            color: #2D9C8F;
            font-weight: 600;
            transition: all 0.2s;
        }

        .category-tab.active {
            border-color: #2D9C8F;
            color: #fff;
            background: linear-gradient(135deg, #2D9C8F, #247A72);
        }

        #selectedItems:empty:before {
            content: '';
        }

        .animate-pulse-slow {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Horizontal Builder Styles */
        .horizontal-builder {
            transition: all 0.3s ease-in-out;
        }

        .horizontal-builder .stats-card {
            transition: transform 0.2s ease-in-out;
        }

        .horizontal-builder .stats-card:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .horizontal-builder .flex-col {
                gap: 1rem;
            }

            .horizontal-builder .stats-card {
                min-width: 100px;
            }
        }

        /* Better mobile experience for selected items */
        @media (max-width: 640px) {
            .selected-item-card {
                padding: 0.75rem;
            }

            .selected-item-card .quantity-controls {
                gap: 0.5rem;
            }
        }

        /* Custom Notification Styles */
        .notification-progress {
            width: 0%;
            transition: width 3000ms linear;
        }

        /* Notification entrance animation */
        .fixed.top-1\/2 {
            animation: notificationSlideIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        /* Backdrop animation */
        .fixed.inset-0 {
            animation: backdropFadeIn 0.3s ease;
            transition: opacity 0.3s ease;
        }

        /* Mobile responsive notification */
        @media (max-width: 640px) {
            .notification-mobile {
                max-width: 280px;
                padding: 1rem;
                border-radius: 0.5rem;
            }

            .notification-mobile .text-xl {
                font-size: 1.25rem;
            }

            .notification-mobile .text-sm {
                font-size: 0.875rem;
                line-height: 1.25rem;
            }
        }

        @keyframes notificationSlideIn {
            0% {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.8);
            }

            100% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes backdropFadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        /* Confirm Dialog Styles */
        .confirm-dialog-backdrop {
            animation: backdropFadeIn 0.2s ease;
        }

        .confirm-dialog {
            animation: confirmDialogSlideIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes confirmDialogSlideIn {
            0% {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.8);
            }

            100% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        /* Fade in animation untuk mobile menu */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fadeInDown 0.2s ease-out;
        }
    </style>
</head>

<body
    class="min-h-screen bg-gradient-to-br from-[#F5F5F5] via-white to-[#F5F5F5] text-[#333333] flex flex-col font-sans overflow-x-hidden">
    @include('public.partials.cart-modal')
    @include('public.partials.cart-panel')

    <!-- Header -->
    <header class="w-full glass-effect border-b border-gray-100 sticky top-0 z-40 transition-transform duration-300"
        id="main-header">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col items-center">
                <!-- Top Bar -->
                <div class="w-full flex items-center justify-between h-20 md:h-24 pl-7 mt-2">
                    <!-- Brand Text - Left -->
                    <div class="flex items-center">
                        <a href="{{ route('public.flowers') }}" class="flex items-center">
                            <div>
                                <h1 class="text-sm md:text-xl lg:text-2xl font-bold text-gray-800">Seikat Bungo</h1>
                                <p class="text-[7px] sm:text-xs text-gray-500">Since 2025</p>
                            </div>
                        </a>
                    </div>

                    <!-- Logo - Center -->
                    <div class="absolute left-1/2 transform -translate-x-1/2">
                        <img src="{{ asset('logo-seikat-bungo.png') }}" alt="Logo" {{--
                            class="brand-logo w-24 h-24 md:w-28 md:h-28 lg:w-32 lg:h-32 rounded-full shadow-lg hover:shadow-xl transition-all duration-300">
                        --}}
                        class="brand-logo w-20 h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 scale-90 rounded-full shadow-lg
                        hover:shadow-xl
                        transition-all duration-300">

                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center pr-7">
                        <!-- Desktop Menu -->
                        <div class="hidden md:flex items-center space-x-3">
                            <!-- Track Order -->
                            <a href="{{ route('public.order.track') }}"
                                class="text-[#275a59] hover:text-[#59aaa1] relative p-2 rounded-full hover:bg-[#59aaa1]/10 transition-all duration-200"
                                title="Lacak Pesanan">
                                <i class="bi bi-truck text-xl"></i>
                            </a>

                            @if(session('last_public_order_code'))
                                <a href="{{ route('public.order.detail', ['public_code' => session('last_public_order_code')]) }}"
                                    class="relative text-white bg-[#59aaa1] hover:bg-[#59aaa1]/10 p-1.5 rounded-full hover:shadow-lg transition-all duration-200">
                                    <i class="bi bi-receipt-cutoff text-xl"></i>
                                    <span
                                        class="absolute -top-1 -right-1 bg-green-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center font-bold">✓</span>
                                </a>
                            @endif

                            <!-- Cart -->
                            <button onclick="toggleCart()"
                                class="text-[#275a59] hover:text-[#59aaa1] relative p-2 rounded-full hover:bg-[#59aaa1]/10 transition-all duration-200"
                                title="Keranjang Belanja">
                                <i class="bi bi-bag text-xl"></i>
                                <span id="cartBadge"
                                    class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 text-white text-[10px] rounded-full flex items-center justify-center hidden">0</span>
                            </button>

                            <a href="{{ route('login') }}"
                                class="text-[#275a59] hover:text-[#59aaa1] p-2 rounded-full hover:bg-[#59aaa1]/10 transition-all duration-200">
                                <i class="bi bi-person-circle text-xl"></i>
                            </a>
                        </div>

                        <!-- Mobile Menu Button -->
                        <div class="md:hidden flex items-center space-x-2">
                            <!-- Cart Button - Always Visible -->
                            <button onclick="toggleCart()"
                                class="text-gray-600 hover:text-rose-600 relative p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                                title="Keranjang Belanja">
                                <i class="bi bi-bag text-xl"></i>
                                <span id="cartBadgeMobile"
                                    class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 text-white text-[10px] rounded-full flex items-center justify-center hidden">0</span>
                            </button>

                            <!-- Hamburger Button -->
                            <button onclick="toggleMobileMenu()"
                                class="text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200">
                                <i class="bi bi-list text-2xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Mobile Menu Dropdown -->
                    <div id="mobileMenu"
                        class="hidden fixed top-[80px] right-0 w-48 bg-white shadow-lg rounded-bl-lg z-50">
                        <div class="py-2">
                            <a href="{{ route('public.order.track') }}"
                                class="flex items-center px-4 py-2 text-gray-600 hover:bg-rose-50 hover:text-rose-600">
                                <i class="bi bi-truck mr-2"></i>
                                <span>Lacak Pesanan</span>
                            </a>

                            @if(session('last_public_order_code'))
                                <a href="{{ route('public.order.detail', ['public_code' => session('last_public_order_code')]) }}"
                                    class="flex items-center px-4 py-2 text-gray-600 hover:bg-rose-50 hover:text-rose-600">
                                    <i class="bi bi-receipt-cutoff mr-2"></i>
                                    <span>Pesanan Terakhir</span>
                                </a>
                            @endif

                            <a href="{{ route('login') }}"
                                class="flex items-center px-4 py-2 text-gray-600 hover:bg-rose-50 hover:text-rose-600">
                                <i class="bi bi-person-circle mr-2"></i>
                                <span>Login</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Navigation -->
                <div class="w-full mt-4">
                    <nav class="flex items-center justify-center">
                        <div class="flex items-center justify-center space-x-2 sm:space-x-4 md:space-x-8">
                            <a href="{{ route('public.flowers') }}"
                                class="px-2 sm:px-4 py-1.5 text-center text-[#2D9C8F] hover:bg-[#2D9C8F]/10 rounded-xl transition-all duration-300">
                                <span class="text-sm font-medium">BUNGA POTONG</span>
                            </a>

                            <a href="{{ route('public.bouquets') }}"
                                class="px-2 sm:px-4 py-1.5 text-center text-[#2D9C8F] hover:bg-[#2D9C8F]/10 rounded-xl transition-all duration-300">
                                <span class="text-sm font-medium">BOUQUET</span>
                            </a>

                            <a href="{{ route('custom.bouquet.create') }}"
                                class="px-2 sm:px-4 py-1.5 text-center nav-tab nav-hover-effect group relative items-center space-x-2 px-3 py-2 rounded-xl transition-all duration-300 bg-gradient-to-r from-[#2D9C8F] to-[#247A72] text-white shadow-lg nav-active-gradient">
                                <span class="text-sm font-medium">CUSTOM RANGKAI</span>
                            </a>
                        </div>
                    </nav>
                </div>
                <!-- Add spacing below main navigation -->
                <div class="mb-6"></div>
            </div>
        </div>

        </div>
    </header>

    <!-- Main Content -->
    <div class="w-full max-w-6xl mx-auto px-4 py-6">
        <!-- Horizontal Bouquet Builder -->
        <div class="mb-6 horizontal-builder">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Builder Header -->
                <div class="bg-gradient-to-r from-[#2D9C8F] to-[#247A72] text-white p-4 relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute inset-0"
                            style="background-image: radial-gradient(circle at 20% 20%, white 2px, transparent 2px); background-size: 20px 20px;">
                        </div>
                    </div>
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex items-center mb-2 md:mb-0">
                                <span class="text-3xl mr-3">🛒</span>
                                <div>
                                    <h2 class="text-xl font-bold flex items-center">
                                        Custom Bouquet Impianmu
                                        {{-- <span
                                            class="ml-3 bg-white/20 rounded-full px-3 py-1 text-xs font-medium">v2.0
                                        </span> --}}
                                    </h2>
                                    <p class="text-sm opacity-90 font-medium">Komponen yang dipilih</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <!-- Quick Stats -->
                                <div class="bg-white/10 rounded-lg px-4 py-2 text-center stats-card">
                                    <div class="text-xs opacity-80">Items</div>
                                    <div class="text-lg font-bold" id="itemCount">0</div>
                                </div>
                                <div class="bg-white/10 rounded-lg px-4 py-2 text-center stats-card">
                                    <div class="text-xs opacity-80">Total Harga</div>
                                    <div class="text-lg font-bold" id="builderHeaderPrice">
                                        Rp {{ number_format((float) ($customBouquet->total_price ?? 0), 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Builder Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Selected Items (Left) -->
                        <div class="lg:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <span class="text-xl mr-2">📋</span>
                                Item yang Dipilih
                            </h3>
                            <div id="selectedItems" class="space-y-3 max-h-80 overflow-y-auto custom-scrollbar">
                                <div class="text-center py-8 text-gray-500">
                                    <div class="relative inline-block">
                                        <div class="text-5xl mb-3 animate-pulse-slow">🌹</div>
                                        <div
                                            class="absolute inset-0 bg-gradient-to-r from-rose-100 via-pink-100 to-purple-100 rounded-full opacity-20 transform rotate-12">
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium">Belum ada komponen yang dipilih</p>
                                    <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                                        💡 Klik <span class="text-purple-600 font-semibold">"+ Tambah ke Bouquet"</span>
                                        pada produk di bawah
                                    </p>
                                    <div class="mt-4 flex justify-center">
                                        <div
                                            class="bg-gradient-to-r from-purple-100 to-pink-100 rounded-full px-4 py-2">
                                            <span class="text-xs text-purple-700 font-medium">Mulai membangun bouquet
                                                impian Anda</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions & Reference (Right) -->
                        <div class="lg:col-span-1">
                            <!-- Reference Upload -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <span class="text-lg mr-2">📸</span>
                                    Upload Referensi
                                    <span
                                        class="ml-2 text-xs bg-[#F5A623] text-white px-2 py-1 rounded-full">Opsional</span>
                                </label>
                                <div class="relative">
                                    <input type="file" id="referenceImage" accept="image/*" class="hidden">
                                    <button type="button" id="uploadReferenceBtn"
                                        class="w-full border-2 border-dashed border-[#F5A623] hover:border-[#E59420] rounded-xl p-4 text-center transition-all duration-300 bg-gradient-to-br from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 group">
                                        <div class="text-[#F5A623] group-hover:text-[#E59420]">
                                            <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            <span class="text-sm font-medium">Upload gambar referensi</span>
                                            <p class="text-xs mt-1 text-gray-500">JPG, PNG hingga 5MB</p>
                                        </div>
                                    </button>
                                </div>
                                <div id="referencePreview" class="mt-3 hidden">
                                    <div class="relative rounded-xl overflow-hidden">
                                        <img id="referenceImagePreview" class="w-full h-32 object-cover"
                                            alt="Reference">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent">
                                        </div>
                                    </div>
                                    <button type="button" id="removeReferenceBtn"
                                        class="mt-2 w-full text-xs text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 py-2 rounded-lg transition-colors">
                                        🗑️ Hapus gambar referensi
                                    </button>
                                </div>
                            </div>

                            <!-- Ribbon Color Selection -->
                            <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">🎀 Pilih Warna Pita</h4>
                                <div class="grid grid-cols-4 gap-2 max-h-48 overflow-y-auto custom-scrollbar"
                                    id="ribbonColorSelector">
                                    @foreach(App\Enums\RibbonColor::cases() as $color)
                                        <button type="button" data-color="{{ $color->value }}"
                                            class="ribbon-color-btn flex flex-col items-center p-2 rounded-lg border-2 transition-all duration-200 hover:scale-105"
                                            :class="{ 'border-2 border-gray-400 ring-2 ring-offset-1 ring-gray-400': selectedRibbonColor === '{{ $color->value }}', 'border-gray-200 hover:border-gray-300': selectedRibbonColor !== '{{ $color->value }}' }">
                                            <div class="w-6 h-6 rounded-full mb-1"
                                                style="background-color: {{ App\Enums\RibbonColor::getColorCode($color->value) }}">
                                            </div>
                                            <span
                                                class="text-xs text-center">{{ App\Enums\RibbonColor::getColorName($color->value) }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-3">
                                <button type="button" id="addToMainCartBtn"
                                    class="w-full bg-gradient-to-r from-[#58B8AB] via-[#247A72] to-[#2D9C8F] hover:from-[#FFC65A] hover:via-[#E59420] hover:to-[#F5A623] text-white font-bold py-4 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none group relative overflow-hidden"
                                    disabled>
                                    <div
                                        class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300">
                                    </div>
                                    <div class="relative flex items-center justify-center">
                                        <span class="text-xl mr-2">🛒</span>
                                        <span>Tambah ke Keranjang Utama</span>
                                    </div>
                                </button>

                                <div class="flex justify-center">
                                    <button type="button" id="clearBuilderBtn"
                                        class="bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 font-medium py-2 px-4 rounded-lg transition-all duration-200 text-sm">
                                        🗑️ Kosongkan Item
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Status Bar -->
        <div class="mb-6 flex items-center justify-center gap-4 text-sm">
            <span class="bg-[#59aaa1] text-white px-3 py-1 rounded-full">
                <i class="bi bi-palette2 mr-1"></i>
                Draft ID: #{{ $customBouquet->id }}
            </span>
            <span class="bg-[#59aaa1] text-white px-3 py-1 rounded-full font-semibold" id="totalPrice">
                <i class="bi bi-currency-dollar mr-1"></i>
                Rp {{ number_format((float) ($customBouquet->total_price ?? 0), 0, ',', '.') }}
            </span>
        </div>

        <!-- Search and Filters -->
        <div class="mb-8 flex flex-col items-center">
            <!-- Enhanced Filter Chips -->
            <div class="flex flex-wrap gap-3 justify-center">
                <button
                    class="category-tab chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200 active"
                    data-category="">
                    <span class="mr-2">🌺</span>Semua Produk
                </button>
                <button type="button"
                    class="category-tab chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                    data-category="Fresh Flowers">
                    <span class="mr-2">🌿</span>Fresh Flowers
                </button>
                <button type="button"
                    class="category-tab chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                    data-category="Artificial">
                    <span class="mr-2">🍁</span>Artificial
                </button>
                <button type="button"
                    class="category-tab chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                    data-category="Daun">
                    <span class="mr-2">🍃</span>Daun
                </button>
                <button type="button"
                    class="category-tab chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                    data-category="Aksesoris">
                    <span class="mr-2">🎀</span>Aksesoris
                </button>
            </div>
        </div>


        <!-- Product Selection -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Products Grid Header -->
            <div class="bg-gradient-to-r from-[#58B8AB] to-[#2D9C8F] px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-white">🌸 Pilih Komponen Bouquet</h3>
                <p class="text-sm text-white">Klik produk untuk menambahkan ke bouquet Anda</p>
            </div>
            <!-- Products Grid -->
            <div class="p-6">
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-6">
                    {{-- <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4"
                        id="productsGrid"> --}}
                        @foreach($products as $product)
                            @php
                                $customPrices = $product->prices->filter(function ($price) {
                                    return in_array($price->type, ['custom_ikat', 'custom_tangkai', 'custom_khusus']);
                                });

                                // Prioritaskan tipe harga dalam urutan tertentu
                                $priceTypes = ['custom_ikat', 'custom_tangkai', 'custom_khusus'];
                                $defaultPrice = null;
                                foreach ($priceTypes as $type) {
                                    $price = $customPrices->firstWhere('type', $type);
                                    if ($price) {
                                        $defaultPrice = $price;
                                        break;
                                    }
                                }
                            @endphp
                            @if($defaultPrice && $product->current_stock > 0)
                                <div class="product-card glass-effect card-hover rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer"
                                    data-category="{{ $product->category->name ?? '' }}" data-product-id="{{ $product->id }}">
                                    <!-- Product Image -->
                                    <div
                                        class="aspect-w-1 aspect-h-1 bg-gradient-to-br from-[#F5F5F5] to-[#2D9C8F]/10 rounded-t-2xl overflow-hidden">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                                class="w-full h-32 object-cover">
                                        @else
                                            <div
                                                class="w-full h-32 bg-gradient-to-br from-[#2D9C8F]/10 to-[#247A72]/10 flex items-center justify-center">
                                                <span class="text-4xl text-[#2D9C8F]">🌸</span>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Product Info -->
                                    <div class="p-4">
                                        <h3 class="font-bold text-gray-800 text-sm mb-1">{{ $product->name }}</h3>
                                        <p class="text-xs text-[#2D9C8F] mb-2 font-medium">
                                            {{ $product->category->name ?? 'Uncategorized' }}
                                        </p>
                                        <!-- Stock Info -->
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xs badge-stock">
                                                📦 {{ $product->current_stock }} {{ $product->base_unit }} tersedia
                                            </span>
                                        </div>
                                        <!-- Price Preview (show only custom prices) -->
                                        @foreach($customPrices as $price)
                                            <div class="text-sm mb-1">
                                                <span class="text-price">
                                                    Rp {{ number_format($price->price, 0, ',', '.') }}
                                                </span>
                                                <span class="text-gray-500 text-xs">
                                                    /{{ ucwords(str_replace('_', ' ', $price->type)) }}
                                                </span>
                                            </div>
                                        @endforeach
                                        <!-- Add Button -->
                                        <button
                                            class="w-full mt-3 btn-add-bouquet text-white bg-[#2D9C8F] text-sm py-2 px-3 rounded-xl transition-all duration-200 add-product-btn"
                                            data-product-id="{{ $product->id }}">
                                            <i class="bi bi-cart-plus mr-1"></i>Tambah ke Bouquet
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    @if($products->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">🌸</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada produk tersedia</h3>
                            <p class="text-gray-500">Silakan periksa kembali nanti atau hubungi admin</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <!-- Product Selection Modal -->
    <div id="productModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-purple-500 to-indigo-500 text-white p-4 rounded-t-xl">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold" id="modalProductName">Pilih Opsi Produk</h3>
                    <button type="button" class="text-white hover:text-gray-200" id="closeModalBtn">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div id="modalProductDetails">
                    <!-- Product details will be loaded here -->
                </div>

                <!-- Price Options -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Harga:</label>
                    <div id="priceOptions" class="space-y-2">
                        <!-- Price options will be loaded here -->
                    </div>
                </div>

                <!-- Quantity -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah:</label>
                    <div class="flex items-center space-x-3">
                        <button type="button" id="decreaseQty"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">-</button>
                        <input type="number" id="quantity" value="1" min="1"
                            class="w-20 text-center border border-gray-300 rounded-md py-1">
                        <button type="button" id="increaseQty"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">+</button>
                    </div>
                    <div id="stockWarning" class="text-xs text-amber-600 mt-1 hidden">
                        ⚠️ Stok terbatas
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-3">
                    <button type="button" id="cancelModalBtn"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded-md transition-colors">
                        Batal
                    </button>
                    <button type="button" id="addToBuilderBtn"
                        class="flex-1 bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded-md transition-colors">
                        Tambah ke Builder
                    </button>
                </div>
            </div>
        </div>
    </div>

    </div>

    <script>
        // Global variables
        let currentCustomBouquetId = {{ $customBouquet->id }};
        let selectedProduct = null;
        let selectedPriceType = null;
        let currentStock = 0;

        // CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.addEventListener('DOMContentLoaded', function () {
            initializeCustomBouquetBuilder();
        });

        function initializeCustomBouquetBuilder() {
            // Category filtering
            initializeCategoryTabs();

            // Product selection
            initializeProductSelection();

            // Modal functionality
            initializeModal();

            // Reference image upload
            initializeReferenceUpload();

            // Builder actions
            initializeBuilderActions();

            // Load existing items if any
            loadCustomBouquetDetails();
        }

        function initializeCategoryTabs() {
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.addEventListener('click', function () {
                    // Update active tab
                    document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Filter products
                    const categoryName = this.dataset.category;
                    filterProducts(categoryName);
                });
            });
        }

        function filterProducts(categoryName) {
            document.querySelectorAll('.product-card').forEach(card => {
                const cardCategory = card.dataset.category;
                if (!categoryName || cardCategory === categoryName) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function initializeProductSelection() {
            document.querySelectorAll('.add-product-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const productId = this.dataset.productId;
                    openProductModal(productId);
                });
            });
        }

        function initializeModal() {
            const modal = document.getElementById('productModal');
            const closeBtn = document.getElementById('closeModalBtn');
            const cancelBtn = document.getElementById('cancelModalBtn');

            [closeBtn, cancelBtn].forEach(btn => {
                btn.addEventListener('click', closeProductModal);
            });

            // Quantity controls
            document.getElementById('decreaseQty').addEventListener('click', () => adjustQuantity(-1));
            document.getElementById('increaseQty').addEventListener('click', () => adjustQuantity(1));
            document.getElementById('quantity').addEventListener('input', validateQuantity);

            // Add to builder
            document.getElementById('addToBuilderBtn').addEventListener('click', addToBuilder);
        }

        function initializeReferenceUpload() {
            const uploadBtn = document.getElementById('uploadReferenceBtn');
            const fileInput = document.getElementById('referenceImage');
            const removeBtn = document.getElementById('removeReferenceBtn');

            uploadBtn.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', uploadReferenceImage);
            removeBtn.addEventListener('click', removeReferenceImage);
        }

        function initializeBuilderActions() {
            document.getElementById('addToMainCartBtn').addEventListener('click', function (e) {
                e.preventDefault();
                addToMainCart();
            });
            document.getElementById('clearBuilderBtn').addEventListener('click', function (e) {
                e.preventDefault();
                clearBuilder();
            });
        }

        async function openProductModal(productId) {
            try {
                const response = await fetch(`/product/${productId}/details`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    selectedProduct = data.product;
                    populateModal(data.product);
                    document.getElementById('productModal').classList.remove('hidden');
                    document.getElementById('productModal').classList.add('flex');
                } else {
                    showNotification('Error loading product details', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error loading product details', 'error');
            }
        }

        function populateModal(product) {
            document.getElementById('modalProductName').textContent = product.name;
            currentStock = product.current_stock;

            // Product details
            const detailsHtml = `
        <div class="mb-4">
            <h4 class="font-medium text-gray-900">${product.name}</h4>
            <p class="text-sm text-gray-600">${product.description || ''}</p>
            <div class="mt-2 flex items-center text-sm">
                <span class="text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    📦 ${product.current_stock} ${product.base_unit} tersedia
                </span>
            </div>
        </div>
    `;
            document.getElementById('modalProductDetails').innerHTML = detailsHtml;

            // Filter dan urutkan harga berdasarkan prioritas
            const priceTypes = ['custom_ikat', 'custom_tangkai', 'custom_khusus'];
            const customPrices = product.prices.filter(price => priceTypes.includes(price.type))
                .sort((a, b) => {
                    return priceTypes.indexOf(a.type) - priceTypes.indexOf(b.type);
                });

            if (customPrices.length === 0) {
                showNotification('Produk ini tidak memiliki harga custom yang valid', 'error');
                closeProductModal();
                return;
            }

            // Generate HTML untuk opsi harga
            const priceOptionsHtml = customPrices.map((price, index) => `
        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
            <input type="radio" name="price_type" value="${price.type}" class="text-rose-500 focus:ring-rose-500" 
                   ${index === 0 ? 'checked' : ''}>
            <div class="ml-3 flex-1">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-900">${price.display_name}</span>
                    <span class="text-sm font-semibold text-rose-600">Rp ${price.price.toLocaleString('id-ID')}</span>
                </div>
                <div class="text-xs text-gray-500">Setara ${price.unit_equivalent} ${product.base_unit}</div>
            </div>
        </label>
    `).join('');

            document.getElementById('priceOptions').innerHTML = priceOptionsHtml;

            // Selalu gunakan harga pertama yang tersedia sebagai default
            selectedPriceType = customPrices[0].type;

            // Price selection listeners
            document.querySelectorAll('input[name="price_type"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    selectedPriceType = this.value;
                    validateQuantity();
                });
            });

            // Reset quantity
            document.getElementById('quantity').value = 1;
            validateQuantity();
        }

        function adjustQuantity(delta) {
            const qtyInput = document.getElementById('quantity');
            const newValue = Math.max(1, parseInt(qtyInput.value) + delta);
            qtyInput.value = newValue;
            validateQuantity();
        }

        function validateQuantity() {
            const qty = parseInt(document.getElementById('quantity').value) || 1;
            const price = selectedProduct.prices.find(p => p.type === selectedPriceType);
            const requiredStock = qty * (price ? price.unit_equivalent : 1);

            const stockWarning = document.getElementById('stockWarning');
            const addBtn = document.getElementById('addToBuilderBtn');

            if (requiredStock > currentStock) {
                stockWarning.textContent = `⚠️ Tidak cukup stok. Dibutuhkan ${requiredStock} ${selectedProduct.base_unit}, tersedia ${currentStock}`;
                stockWarning.classList.remove('hidden');
                addBtn.disabled = true;
                addBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                stockWarning.classList.add('hidden');
                addBtn.disabled = false;
                addBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        function closeProductModal() {
            document.getElementById('productModal').classList.add('hidden');
            document.getElementById('productModal').classList.remove('flex');
            selectedProduct = null;
            selectedPriceType = null;
        }

        async function addToBuilder() {
            if (!selectedProduct || !selectedPriceType) {
                showNotification('Silakan pilih tipe harga terlebih dahulu', 'warning');
                return;
            }

            // Validasi tipe harga
            if (!['custom_ikat', 'custom_tangkai', 'custom_khusus'].includes(selectedPriceType)) {
                showNotification('Tipe harga tidak valid untuk custom bouquet', 'error');
                return;
            }

            const quantity = parseInt(document.getElementById('quantity').value) || 1;


            // Scroll ke atas halaman dengan animasi smooth custom agar lebih profesional
            smoothScrollToTop();
            // Fungsi scroll smooth ke atas dengan animasi custom
            function smoothScrollToTop(duration = 600) {
                const start = window.scrollY || window.pageYOffset;
                const startTime = performance.now();
                function scrollStep(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const ease = progress < 0.5
                        ? 2 * progress * progress
                        : -1 + (4 - 2 * progress) * progress;
                    window.scrollTo(0, start * (1 - ease));
                    if (progress < 1) {
                        requestAnimationFrame(scrollStep);
                    }
                }
                requestAnimationFrame(scrollStep);
            }

            try {
                console.log('Adding item to builder:', {
                    productId: selectedProduct.id,
                    priceType: selectedPriceType,
                    quantity: quantity
                });

                const response = await fetch('/custom-bouquet/add-item', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        custom_bouquet_id: currentCustomBouquetId,
                        product_id: selectedProduct.id,
                        price_type: selectedPriceType,
                        quantity: quantity
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    closeProductModal();
                    loadCustomBouquetDetails();
                    updateTotalPrice(data.total_price);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error adding item to builder', 'error');
            }
        }

        async function loadCustomBouquetDetails() {
            try {
                const response = await fetch(`/custom-bouquet/${currentCustomBouquetId}/details`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    displaySelectedItems(data.custom_bouquet.items);
                    updateTotalPrice(data.custom_bouquet.total_price);

                    // Update reference image if exists
                    if (data.custom_bouquet.reference_image_url) {
                        displayReferenceImage(data.custom_bouquet.reference_image_url);
                    }
                }
            } catch (error) {
                console.error('Error loading custom bouquet details:', error);
            }
        }

        function displaySelectedItems(items) {
            const container = document.getElementById('selectedItems');
            const addToCartBtn = document.getElementById('addToMainCartBtn');
            const itemCountEl = document.getElementById('itemCount');

            // Update item count
            itemCountEl.textContent = items.length;

            if (items.length === 0) {
                container.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <div class="relative">
                    <div class="text-5xl mb-3 animate-pulse-slow">🌹</div>
                    <div class="absolute inset-0 bg-gradient-to-r from-rose-100 via-pink-100 to-purple-100 rounded-full opacity-20 transform rotate-12"></div>
                </div>
                <p class="text-sm font-medium">Belum ada komponen yang dipilih</p>
                <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                    💡 Klik <span class="text-purple-600 font-semibold">"+ Tambah ke Bouquet"</span> pada produk di sebelah kiri
                </p>
                <div class="mt-4 flex justify-center">
                    <div class="bg-gradient-to-r from-[#E59420] to-[#F5A623] rounded-full px-4 py-2">
                        <span class="text-xs text-[#333333] font-medium">Mulai membangun bouquet impian Anda</span>
                    </div>
                </div>
            </div>
        `;
                addToCartBtn.disabled = true;
                addToCartBtn.classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }

            const itemsHtml = items.map(item => `
        <div class="bg-gradient-to-r from-white to-purple-50 rounded-lg p-3 border border-purple-100 hover:border-purple-200 transition-colors selected-item-card" data-item-id="${item.id}">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-900">${item.product_name}</h4>
                    <p class="text-xs text-purple-600 bg-purple-50 inline-block px-2 py-1 rounded-full mt-1">${item.price_type_display ?? item.price_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</p>
                    <div class="flex items-center mt-2 space-x-2 quantity-controls">
                        <button class="quantity-btn bg-gray-200 hover:bg-gray-300 text-gray-700 w-6 h-6 rounded-full text-xs flex items-center justify-center transition-colors" 
                                onclick="updateItemQuantity(${item.id}, ${item.quantity - 1})">-</button>
                        <span class="text-sm font-bold px-2 bg-white rounded border">${item.quantity}</span>
                        <button class="quantity-btn bg-gray-200 hover:bg-gray-300 text-gray-700 w-6 h-6 rounded-full text-xs flex items-center justify-center transition-colors" 
                                onclick="updateItemQuantity(${item.id}, ${item.quantity + 1})">+</button>
                    </div>
                </div>
                <div class="text-right ml-3">
                    <div class="text-sm font-bold text-purple-600">Rp ${item.subtotal.toLocaleString('id-ID')}</div>
                    <button class="text-xs text-red-600 hover:text-red-800 mt-1 bg-red-50 hover:bg-red-100 px-2 py-1 rounded transition-colors" onclick="removeItem(${item.id})">
                        🗑️ Hapus
                    </button>
                </div>
            </div>
        </div>
    `).join('');

            container.innerHTML = itemsHtml;
            addToCartBtn.disabled = false;
            addToCartBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        async function updateItemQuantity(itemId, newQuantity) {
            if (newQuantity < 1) return;

            try {
                const response = await fetch('/custom-bouquet/update-item', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        quantity: newQuantity
                    })
                });

                const data = await response.json();

                if (data.success) {
                    loadCustomBouquetDetails();
                    updateTotalPrice(data.total_price);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error updating quantity', 'error');
            }
        }

        async function removeItem(itemId) {
            // Show custom confirmation dialog
            const confirmed = await showConfirmDialog(
                '🗑️ Hapus Item?',
                'Apakah Anda yakin ingin menghapus item ini dari bouquet?',
                'Ya, Hapus',
                'Batal'
            );

            if (!confirmed) return;

            try {
                const response = await fetch('/custom-bouquet/remove-item', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        item_id: itemId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    loadCustomBouquetDetails();
                    updateTotalPrice(data.total_price);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error removing item', 'error');
            }
        }

        async function uploadReferenceImage() {
            const fileInput = document.getElementById('referenceImage');
            const file = fileInput.files[0];

            if (!file) return;

            const formData = new FormData();
            formData.append('custom_bouquet_id', currentCustomBouquetId);
            formData.append('reference_image', file);

            try {
                const response = await fetch('/custom-bouquet/upload-reference', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    displayReferenceImage(data.image_url);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error uploading image', 'error');
            }
        }

        function displayReferenceImage(imageUrl) {
            document.getElementById('referenceImagePreview').src = imageUrl;
            document.getElementById('referencePreview').classList.remove('hidden');
            document.getElementById('uploadReferenceBtn').classList.add('hidden');
        }

        function removeReferenceImage() {
            document.getElementById('referencePreview').classList.add('hidden');
            document.getElementById('uploadReferenceBtn').classList.remove('hidden');
            document.getElementById('referenceImage').value = '';
        }

        function updateTotalPrice(totalPrice) {
            const formattedPrice = `Rp ${totalPrice.toLocaleString('id-ID')}`;
            document.getElementById('totalPrice').textContent = formattedPrice;
            document.getElementById('builderHeaderPrice').textContent = formattedPrice;

            // Update item count display element if exists
            const itemCountEl = document.getElementById('itemCount');
            if (itemCountEl && itemCountEl.textContent) {
                // Item count is updated elsewhere
            }
        }

        async function addToMainCart(e) {
            if (e) e.preventDefault();

            console.log('addToMainCart called'); // Debug log

            // Check if custom bouquet has items
            const selectedItems = document.querySelectorAll('#selectedItems .bg-gradient-to-r');
            if (selectedItems.length === 0) {
                showNotification('🌸 Bouquet masih kosong! Silakan pilih dan tambahkan beberapa bunga terlebih dahulu.', 'warning');
                return;
            }

            try {
                // Set status to finalized first
                const finalizeResponse = await fetch(`/custom-bouquet/${currentCustomBouquetId}/finalize`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken
                    }
                });

                const finalizeData = await finalizeResponse.json();
                if (!finalizeData.success) {
                    throw new Error(finalizeData.message || 'Gagal memfinalisasi bouquet');
                }

                // Now add to cart
                const cartResponse = await fetch('/cart/add-custom-bouquet', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken
                    },
                    body: JSON.stringify({
                        custom_bouquet_id: currentCustomBouquetId,
                        quantity: 1
                    })
                });

                const cartData = await cartResponse.json();
                if (cartData.success) {
                    console.log('Cart success:', cartData); // Debug log
                    showNotification('Custom bouquet berhasil ditambahkan ke keranjang!', 'success');
                    updateCart(); // Update cart display
                    // Otomatis buka panel keranjang seperti halaman Fresh Flowers
                    if (typeof toggleCart === 'function') {
                        setTimeout(() => { toggleCart(); }, 350); // beri delay agar updateCart selesai
                    }
                    // User stays on the current page to continue building or viewing their bouquet
                } else {
                    console.error('Cart error:', cartData); // Debug log
                    throw new Error(cartData.message || 'Gagal menambahkan ke keranjang');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                showNotification('Terjadi kesalahan: ' + error.message, 'error');
            }
        }

        // Ribbon Color Management
        let selectedRibbonColor = '{{ $customBouquet->ribbon_color }}';

        // Initialize ribbon color buttons
        document.addEventListener('DOMContentLoaded', function () {
            const ribbonButtons = document.querySelectorAll('.ribbon-color-btn');
            ribbonButtons.forEach(btn => {
                if (btn.dataset.color === selectedRibbonColor) {
                    btn.classList.add('selected-ribbon');
                }
                btn.addEventListener('click', handleRibbonColorChange);
            });
        });

        async function handleRibbonColorChange(e) {
            const newColor = e.currentTarget.dataset.color;
            const oldColor = selectedRibbonColor;

            // Update UI first for responsiveness
            document.querySelectorAll('.ribbon-color-btn').forEach(btn => {
                btn.classList.remove('selected-ribbon');
                if (btn.dataset.color === newColor) {
                    btn.classList.add('selected-ribbon');
                }
            });

            try {
                const response = await fetch(`/custom-bouquet/${currentCustomBouquetId}/ribbon`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ribbon_color: newColor
                    })
                });

                const data = await response.json();

                if (data.success) {
                    selectedRibbonColor = newColor;
                    showNotification('✨ Warna pita berhasil diubah', 'success');
                } else {
                    // Revert UI if failed
                    document.querySelectorAll('.ribbon-color-btn').forEach(btn => {
                        btn.classList.remove('selected-ribbon');
                        if (btn.dataset.color === oldColor) {
                            btn.classList.add('selected-ribbon');
                        }
                    });
                    showNotification('Gagal mengubah warna pita', 'error');
                }
            } catch (error) {
                console.error('Error updating ribbon color:', error);
                showNotification('Terjadi kesalahan saat mengubah warna pita', 'error');
            }
        }

        async function clearBuilder() {
            // Show custom confirmation dialog
            const confirmed = await showConfirmDialog(
                '🗑️ Kosongkan Item?',
                'Apakah Anda yakin ingin mengosongkan semua item dari Custom Bouquet? Semua item yang telah dipilih akan dihapus.',
                'Ya, Kosongkan',
                'Batal'
            );

            if (!confirmed) return;

            try {
                const response = await fetch('/custom-bouquet/clear', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        custom_bouquet_id: currentCustomBouquetId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('Builder berhasil dikosongkan!', 'success');

                    // Reset display
                    displaySelectedItems([]);
                    updateTotalPrice(0);

                    // Remove reference image if exists
                    removeReferenceImage();

                    // Reload custom bouquet details to ensure clean state
                    loadCustomBouquetDetails();
                } else {
                    showNotification(data.message || 'Gagal mengosongkan builder', 'error');
                }
            } catch (error) {
                console.error('Error clearing builder:', error);
                showNotification('Terjadi kesalahan saat mengosongkan builder', 'error');
            }
        }

        function showConfirmDialog(title, message, confirmText = 'Ya', cancelText = 'Batal') {
            return new Promise((resolve) => {
                // Create backdrop overlay
                const backdrop = document.createElement('div');
                backdrop.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 backdrop-blur-sm';

                // Create confirmation dialog
                const dialog = document.createElement('div');
                dialog.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl z-50 w-11/12 max-w-md';

                dialog.innerHTML = `
                    <div class="p-6 text-center">
                        <div class="mb-4">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">${title}</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">${message}</p>
                        </div>
                        <div class="flex space-x-3">
                            <button id="cancelBtn" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 hover:text-gray-900 font-medium py-2 px-4 rounded-lg transition-all duration-200">
                                ${cancelText}
                            </button>
                            <button id="confirmBtn" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200">
                                ${confirmText}
                            </button>
                        </div>
                    </div>
                `;

                // Add to body
                document.body.appendChild(backdrop);
                document.body.appendChild(dialog);

                // Add animation
                setTimeout(() => {
                    backdrop.style.opacity = '1';
                    dialog.style.transform = 'translate(-50%, -50%) scale(1)';
                }, 10);

                // Handle buttons
                const confirmBtn = dialog.querySelector('#confirmBtn');
                const cancelBtn = dialog.querySelector('#cancelBtn');

                const cleanup = () => {
                    backdrop.style.opacity = '0';
                    dialog.style.transform = 'translate(-50%, -50%) scale(0.95)';
                    setTimeout(() => {
                        backdrop.remove();
                        dialog.remove();
                    }, 200);
                };

                confirmBtn.addEventListener('click', () => {
                    cleanup();
                    resolve(true);
                });

                cancelBtn.addEventListener('click', () => {
                    cleanup();
                    resolve(false);
                });

                backdrop.addEventListener('click', () => {
                    cleanup();
                    resolve(false);
                });
            });
        }

        function showNotification(message, type = 'info') {
            // Create backdrop overlay
            const backdrop = document.createElement('div');
            backdrop.className = 'fixed inset-0 bg-black bg-opacity-40 z-40 backdrop-blur-sm';

            // Create notification element - more responsive sizing
            const notification = document.createElement('div');
            notification.className = `fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 p-3 sm:p-4 md:p-6 rounded-lg shadow-2xl z-50 ${getNotificationColor(type)} w-11/12 max-w-xs sm:max-w-sm md:max-w-md text-center border notification-mobile`;

            // Add icon based on type - smaller icons for mobile
            const icon = getNotificationIcon(type);
            notification.innerHTML = `
                <div class="flex flex-col items-center space-y-2 sm:space-y-3">
                    <div class="text-lg sm:text-xl md:text-2xl">${icon}</div>
                    <div class="text-xs sm:text-sm md:text-base font-semibold leading-tight px-2">${message}</div>
                    <div class="w-full bg-white/20 rounded-full h-0.5 sm:h-1 mt-2 sm:mt-3">
                        <div class="bg-white h-0.5 sm:h-1 rounded-full transition-all duration-3000 notification-progress"></div>
                    </div>
                </div>
            `;

            // Jika notifikasi sukses penambahan ke builder, scroll ke atas halaman
            if (typeof message === 'string' && message.toLowerCase().includes('berhasil')) {
                setTimeout(() => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 400); // beri delay agar notifikasi sempat tampil
            }

            // Add both backdrop and notification to body
            document.body.appendChild(backdrop);
            document.body.appendChild(notification);

            // Add progress animation
            const progressBar = notification.querySelector('.notification-progress');
            setTimeout(() => {
                progressBar.style.width = '100%';
            }, 100);

            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translate(-50%, -50%) scale(0.9)';
                backdrop.style.opacity = '0';
                setTimeout(() => {
                    backdrop.remove();
                    notification.remove();
                }, 300);
            }, 3000);

            // Click backdrop to close
            backdrop.addEventListener('click', () => {
                notification.style.opacity = '0';
                notification.style.transform = 'translate(-50%, -50%) scale(0.9)';
                backdrop.style.opacity = '0';
                setTimeout(() => {
                    backdrop.remove();
                    notification.remove();
                }, 300);
            });
        }

        function getNotificationColor(type) {
            switch (type) {
                case 'success': return 'bg-gradient-to-br from-green-500 to-emerald-600 text-white';
                case 'error': return 'bg-gradient-to-br from-red-500 to-rose-600 text-white';
                case 'warning': return 'bg-gradient-to-br from-yellow-500 to-orange-600 text-white';
                default: return 'bg-gradient-to-br from-blue-500 to-indigo-600 text-white';
            }
        }

        function getNotificationIcon(type) {
            switch (type) {
                case 'success': return '✅';
                case 'error': return '❌';
                case 'warning': return '⚠️';
                default: return 'ℹ️';
            }
        }

        function addCustomBouquetToCart() {
            // Check if custom bouquet has items
            const selectedItems = document.querySelectorAll('#selectedItems .bg-gradient-to-r');
            if (selectedItems.length === 0) {
                showNotification('🌸 Bouquet masih kosong! Silakan pilih dan tambahkan beberapa bunga terlebih dahulu.', 'warning');
                return;
            }

            // Set status to finalized first
            fetch(`/custom-bouquet/${currentCustomBouquetId}/finalize`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Now add to cart
                        return fetch('/cart/add-custom-bouquet', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': csrfToken
                            },
                            body: JSON.stringify({
                                custom_bouquet_id: currentCustomBouquetId,
                                quantity: 1
                            })
                        });
                    } else {
                        throw new Error(data.message || 'Gagal memfinalisasi bouquet');
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Custom bouquet berhasil ditambahkan ke keranjang!', 'success');
                        updateCart(); // Update cart display

                        // User stays on current page to continue building bouquet
                    } else {
                        throw new Error(data.message || 'Gagal menambahkan ke keranjang');
                    }
                })
                .catch(error => {
                    console.error('Error adding to cart:', error);
                    showNotification('Terjadi kesalahan: ' + error.message, 'error');
                });
        }
    </script>

    <!-- Cart JavaScript -->
    <script src="{{ asset('js/cart.js') }}"></script>

    <script>
        // Header hide/show on scroll
        let lastScrollY = window.scrollY;
        const header = document.getElementById('main-header');
        let ticking = false;

        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    const currentScrollY = window.scrollY;

                    // Jika scroll ke bawah dan sudah scroll lebih dari 100px
                    if (currentScrollY > lastScrollY && currentScrollY > 100) {
                        header.style.transform = 'translateY(-100%)';
                    } else {
                        header.style.transform = 'translateY(0)';
                    }

                    lastScrollY = currentScrollY;
                    ticking = false;
                });

                ticking = true;
            }
        });
    </script>

</body>

</html>