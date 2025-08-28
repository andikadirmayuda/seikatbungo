<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Title --}}
    <title>Bouquet | Seikat Bungo</title>
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
    <style>
        /* Navigation Styles */
        .nav-tab {
            position: relative;
            overflow: hidden;
            text-align: center;
            white-space: nowrap;
            letter-spacing: 0.3px;
            backdrop-filter: blur(8px);
            font-size: 0.8125rem;
            padding: 0.5rem 0.75rem;
            color: #2D9C8F;
            transition: all 0.3s ease;
        }

        /* Fixed Navigation Container */
        .nav-container {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 40;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(45, 156, 143, 0.1);
        }

        /* Responsive Navigation Heights */
        @media (max-width: 639px) {
            .nav-container {
                top: 3.5rem;
            }

            .nav-tab {
                min-width: 70px !important;
                padding: 0.375rem 0.5rem !important;
                font-size: 0.75rem !important;
            }
        }

        @media (min-width: 640px) and (max-width: 767px) {
            .nav-container {
                top: 4rem;
            }

            .nav-tab {
                min-width: 80px !important;
                padding: 0.5rem 0.625rem !important;
                font-size: 0.8125rem !important;
            }
        }

        @media (min-width: 768px) {
            .nav-container {
                top: 6rem;
            }

            .nav-tab {
                min-width: 120px;
                padding: 0.75rem 1.25rem;
                font-size: 0.875rem;
            }
        }

        .nav-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .nav-tab:hover::before {
            transform: translateX(100%);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(45, 156, 143, 0.1);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(45, 156, 143, 0.1);
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(45, 156, 143, 0.2);
            border-color: rgba(45, 156, 143, 0.3);
        }

        /* Consistent card heights */
        .bouquet-card {
            min-height: 350px;
        }

        @media (min-width: 640px) {
            .bouquet-card {
                min-height: 420px;
            }
        }

        /* Better text sizing for mobile */
        @media (max-width: 639px) {
            .bouquet-card .text-price {
                font-size: 0.875rem;
                line-height: 1.25rem;
                color: #2D9C8F;
            }
        }

        .text-price {
            color: #2D9C8F;
            transition: color 0.3s ease;
        }

        /* Modal animations */
        .modal-enter {
            opacity: 0;
            transform: scale(0.9);
        }

        .modal-enter-active {
            opacity: 1;
            transform: scale(1);
            transition: all 0.3s ease-out;
        }

        /* Chip button styling */
        .chip-btn.active {
            background: linear-gradient(135deg, #2D9C8F, #247A72);
            color: white;
            border-color: #2D9C8F;
            transform: translateY(-2px);
        }

        .chip-btn:hover {
            border-color: #2D9C8F;
            background-color: rgba(45, 156, 143, 0.1);
            color: #247A72;
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
    </style>
    <script>
        // Fungsi telah diganti dengan panggilan langsung ke showGreetingCardModal
        function debugShowGreetingCardModal(flowerId, bouquetName, sizeId, sizeName, price) {
            console.log('Debug showGreetingCardModal params:', {
                flowerId,
                bouquetName,
                sizeId,
                sizeName,
                price
            });
            showGreetingCardModal(flowerId, bouquetName, sizeId, sizeName, price);
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

<body class="font-sans bg-gradient-to-br from-[#F5F5F5] via-white to-[#F5F5F5] min-h-screen text-[#333333]">
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
                                class="text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                                title="Lacak Pesanan">
                                <i class="bi bi-truck text-xl"></i>
                            </a>

                            @if(session('last_public_order_code'))
                                <a href="{{ route('public.order.detail', ['public_code' => session('last_public_order_code')]) }}"
                                    class="relative text-white bg-rose-500 hover:bg-rose-600 p-1.5 rounded-full hover:shadow-lg transition-all duration-200">
                                    <i class="bi bi-receipt-cutoff text-xl"></i>
                                    <span
                                        class="absolute -top-1 -right-1 bg-green-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center font-bold">✓</span>
                                </a>
                            @endif

                            <!-- Cart -->
                            <button onclick="toggleCart()"
                                class="text-gray-600 hover:text-rose-600 relative p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                                title="Keranjang Belanja">
                                <i class="bi bi-bag text-xl"></i>
                                <span id="cartBadge"
                                    class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 text-white text-[10px] rounded-full flex items-center justify-center hidden">0</span>
                            </button>

                            <a href="{{ route('login') }}"
                                class="text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200">
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
                                class="px-2 sm:px-4 py-1.5 text-center {{ $activeTab === 'flowers' ? 'nav-tab nav-hover-effect group relative items-center space-x-2 px-3 py-2 rounded-xl transition-all duration-300 bg-gradient-to-r from-[#2D9C8F] to-[#247A72] text-white shadow-lg nav-active-gradient' : 'text-[#2D9C8F] hover:bg-[#2D9C8F]/10' }}">
                                <span class="text-sm font-medium">BUNGA</span>
                            </a>

                            <a href="{{ route('public.bouquets') }}"
                                class="px-2 sm:px-4 py-1.5 text-center {{ $activeTab === 'bouquets' ? 'nav-tab nav-hover-effect group relative items-center space-x-2 px-3 py-2 rounded-xl transition-all duration-300 bg-gradient-to-r from-[#2D9C8F] to-[#247A72] text-white shadow-lg nav-active-gradient' : 'text-[#2D9C8F] hover:bg-[#2D9C8F]/10' }}">
                                <span class="text-sm font-medium">BOUQUET</span>
                            </a>

                            <a href="{{ route('custom.bouquet.create') }}"
                                class="px-2 sm:px-4 py-1.5 text-center text-[#2D9C8F] hover:bg-[#2D9C8F]/10 rounded-xl transition-all duration-300">
                                <span class="text-sm font-medium">CUSTOM</span>
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
        <!-- Search and Filters -->
        <div class="mb-8 flex flex-col items-center">
            <!-- Enhanced Search Bar with Price Filter -->
            <div class="w-full max-w-2xl mb-4">
                <div class="flex gap-3">
                    <!-- Search Input -->
                    <div class="flex-1 relative">
                        <i
                            class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="text" id="searchInput" placeholder="Cari bouquet impian Anda..."
                            class="w-full pl-12 pr-4 py-4 text-lg border-2 border-[#2D9C8F] rounded-2xl focus:ring-2 focus:ring-[#2D9C8F] focus:border-[#2D9C8F] focus:outline-none shadow-lg transition-all duration-200 bg-white/90"
                            onkeyup="searchBouquets()">
                    </div>

                    <!-- Price Filter Dropdown -->
                    <div class="relative">
                        <select id="priceFilterSelect"
                            class="appearance-none bg-white/90 border-2 border-[#2D9C8F] rounded-2xl px-4 py-4 pr-10 text-gray-700 focus:ring-2 focus:ring-[#2D9C8F] focus:border-[#2D9C8F] focus:outline-none shadow-lg transition-all duration-200 cursor-pointer"
                            onchange="filterByPriceRange()">
                            <option value="">Semua Harga</option>
                            <option value="0-100000">
                                < Rp 100k</option>
                            <option value="100000-300000">Rp 100k - 300k</option>
                            <option value="300000-500000">Rp 300k - 500k</option>
                            <option value="500000-1000000">Rp 500k - 1jt</option>
                            <option value="1000000-999999999">> Rp 1jt</option>
                        </select>
                        <i
                            class="bi bi-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <!-- Enhanced Filter Chips -->
            @if($bouquetCategories->count() > 0)
                <div class="flex flex-wrap gap-3 justify-center mb-4">
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-[#2D9C8F] bg-white text-[#2D9C8F] text-sm font-semibold shadow-md hover:shadow-lg hover:bg-[#2D9C8F]/10 transition-all duration-200 active"
                        data-category="" onclick="selectBouquetCategory(this)">
                        <span class="mr-2">💐</span>Semua
                    </button>
                    @foreach($bouquetCategories as $category)
                        <button type="button"
                            class="chip-btn px-6 py-3 rounded-full border-2 border-[#2D9C8F] bg-white text-[#2D9C8F] text-sm font-semibold shadow-md hover:shadow-lg hover:bg-[#2D9C8F]/10 transition-all duration-200"
                            data-category="{{ $category->id }}" onclick="selectBouquetCategory(this)">
                            <span class="mr-2">💐</span>{{ $category->name }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Bouquet Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6" id="bouquetGrid">
            @forelse($bouquets as $bouquet)
                @php
    // Only show bouquets that have components
    $sizeIdsWithComponents = $bouquet->sizesWithComponents->pluck('id');
    $pricesWithComponents = $bouquet->prices->whereIn('size_id', $sizeIdsWithComponents);
    $minPrice = $pricesWithComponents->min('price') ?? 0;
    $maxPrice = $pricesWithComponents->max('price') ?? 0;
                @endphp
                @if($bouquet->sizesWithComponents->count() > 0)
                    <div class="bouquet-card group" data-name="{{ strtolower($bouquet->name) }}"
                        data-bouquet-category="{{ $bouquet->category_id ?? '' }}" data-min-price="{{ $minPrice }}"
                        data-max-price="{{ $maxPrice }}">
                        <div
                            class="card-hover glass-effect rounded-2xl shadow-lg p-3 sm:p-4 h-full flex flex-col overflow-hidden">
                            <!-- Image -->
                            <div class="relative h-36 sm:h-40 mb-3 sm:mb-4 rounded-xl overflow-hidden">
                                @if($bouquet->image)
                                    <!-- View Button Overlay -->
                                    {{-- <button
                                        onclick="showFullImage('{{ asset('storage/' . $bouquet->image) }}', '{{ $bouquet->name }}')"
                                        class="absolute inset-0 z-20 bg-black/0 group-hover:bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        <span
                                            class="bg-white/90 p-2 rounded-full transform scale-0 group-hover:scale-100 transition-transform duration-300 hover:bg-white">
                                            <i class="bi bi-eye text-[#2D9C8F] text-xl"></i>
                                        </span>
                                    </button> --}}

                                    <img src="{{ asset('storage/' . $bouquet->image) }}" alt="{{ $bouquet->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-gradient-to-br from-rose-100 to-pink-100 rounded-xl">
                                        <i class="bi bi-flower3 text-3xl text-rose-400"></i>
                                    </div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                                <!-- Action Buttons -->
                                <div class="absolute top-2 sm:top-3 right-2 sm:right-3 flex gap-2 z-30">
                                    <!-- Wishlist Button -->
                                    {{-- <button
                                        class="w-6 sm:w-8 h-6 sm:h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        <i class="bi bi-heart text-rose-500 text-xs sm:text-sm"></i>
                                    </button> --}}
                                    <!-- View Button -->
                                    <button
                                        onclick="showFullImage('{{ asset('storage/' . $bouquet->image) }}', '{{ $bouquet->name }}')"
                                        class="w-6 sm:w-8 h-6 sm:h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        <i class="bi bi-eye text-[#2D9C8F] text-xs sm:text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="flex-1 flex flex-col">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-bold text-sm sm:text-base text-gray-800 line-clamp-2 flex-1 leading-tight">
                                        {{ $bouquet->name }}
                                    </h3>
                                    <span
                                        class="ml-2 px-2 py-1 bg-[#2D9C8F]/10 text-[#2D9C8F] rounded-full text-[10px] sm:text-xs font-medium whitespace-nowrap flex-shrink-0">
                                        {{ $bouquet->category->name ?? 'Bouquet' }}
                                    </span>
                                </div>

                                <p class="text-xs sm:text-sm text-[#333333] mb-3 line-clamp-2 leading-relaxed">
                                    {{ $bouquet->description }}
                                </p>

                                <!-- Sizes -->
                                <div class="mb-3">
                                    <span class="text-xs text-[#666666] text-center block mb-2">Ukuran
                                        Tersedia:</span>
                                    <div class="flex flex-wrap gap-1">
                                        @php
        // Define size order
        $sizeOrder = ['Extra Small', 'Small', 'Medium', 'Large'];

        // Sort sizes based on the defined order - only show sizes with components
        $sortedSizes = $bouquet->sizesWithComponents->sortBy(function ($size) use ($sizeOrder) {
            $index = array_search($size->name, $sizeOrder);
            return $index !== false ? $index : 999; // Put unknown sizes at the end
        });
                                        @endphp
                                        @foreach($sortedSizes as $size)
                                            <span
                                                class="inline-block px-2 py-1 bg-gradient-to-r from-[#2D9C8F]/10 to-[#247A72]/10 text-[#2D9C8F] rounded-full text-[10px] sm:text-xs font-medium">
                                                {{ $size->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Price Range -->
                                <div class="mb-3 sm:mb-4">
                                    @php
        // Only show prices for sizes that have components
        $sizeIdsWithComponents = $bouquet->sizesWithComponents->pluck('id');
        $pricesWithComponents = $bouquet->prices->whereIn('size_id', $sizeIdsWithComponents);
        $minPrice = $pricesWithComponents->min('price');
        $maxPrice = $pricesWithComponents->max('price');
                                    @endphp
                                    @if($minPrice && $maxPrice)
                                        <div class="text-center">
                                            @if($minPrice == $maxPrice)
                                                <span class="text-price text-sm sm:text-lg font-bold text-[#2D9C8F]">
                                                    Rp {{ number_format($minPrice, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-price text-sm sm:text-lg font-bold text-[#2D9C8F]">
                                                    Rp {{ number_format($minPrice, 0, ',', '.') }} -
                                                    {{ number_format($maxPrice, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-auto space-y-1.5 sm:space-y-2">
                                    @php
        $availablePrices = $bouquet->prices->whereIn('size_id', $sizeIdsWithComponents);
                                    @endphp
                                    @if($availablePrices->count() == 1)
                                        @php
            $firstPrice = $availablePrices->first();
                                        @endphp
                                        <button
                                            onclick="showGreetingCardModal(
                                                                                                                                                                                                                                                                            '{{ $bouquet->id }}',
                                                                                                                                                                                                                                                                            '{{ $bouquet->name }}',
                                                                                                                                                                                                                                                                            '{{ $firstPrice->size_id ?? 'standard' }}',
                                                                                                                                                                                                                                                                            '{{ $firstPrice->size->name ?? 'Standard' }}',
                                                                                                                                                                                                                                                                            {{ $firstPrice->price }}
                                                                                                                                                                                                                                                                        )"
                                            class="w-full bg-[#F5A623] hover:bg-[#E59420] text-white font-semibold py-1.5 sm:py-2 px-3 sm:px-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-xs sm:text-sm">
                                            <i class="bi bi-cart-plus mr-1 sm:mr-2"></i>Tambah ke Keranjang
                                        </button>
                                    @elseif($availablePrices->count() > 1)
                                        <button
                                            onclick="showBouquetPriceModal('{{ $bouquet->id }}', '{{ $bouquet->name }}', {{ json_encode($availablePrices->values()) }})"
                                            class="w-full bg-[#F5A623] hover:bg-[#E59420] text-white font-semibold py-1.5 sm:py-2 px-3 sm:px-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-xs sm:text-sm"
                                            data-bouquet-id="{{ $bouquet->id }}" data-bouquet-name="{{ $bouquet->name }}"
                                            data-bouquet-prices="{{ htmlspecialchars(json_encode($availablePrices->values()), ENT_QUOTES, 'UTF-8') }}">
                                            <i class="bi bi-cart-plus mr-1 sm:mr-2"></i>Pilih Ukuran
                                        </button>
                                    @endif

                                    <button onclick="showBouquetDetailPanel({{ $bouquet->id }})"
                                        class="block w-full text-center border-2 border-[#2D9C8F] text-[#2D9C8F] hover:bg-[#2D9C8F]/10 font-semibold py-1.5 sm:py-2 px-3 sm:px-4 rounded-xl transition-all duration-200 text-xs sm:text-sm">
                                        <i class="bi bi-eye mr-1 sm:mr-2"></i>Lihat Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-20 h-20 bg-rose-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="bi bi-flower3 text-2xl text-rose-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Tidak ada bouquet yang tersedia saat
                        ini</h3>
                    <p class="text-gray-500 text-sm">Silakan coba lagi nanti atau hubungi kami untuk informasi
                        lebih lanjut.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Enhanced Footer -->
    <footer class="bg-gradient-to-r from-[#2D9C8F] to-[#247A72] text-white py-12 mt-16">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="w-16 h-16 bg-white/20 rounded-full mx-auto mb-4 flex items-center justify-center">
                <img src="{{ asset('logo-seikat-bungo.png') }}" alt="Logo" class="brand-logo rounded-full w-12 h-12">
            </div>
            <h3 class="text-2xl font-bold mb-2">Seikat Bungo</h3>
            <p class="text-white mb-4 max-w-2xl mx-auto">
                Menghadirkan keindahan bunga segar berkualitas premium untuk setiap momen berharga dalam
                hidup Anda
            </p>
            <div class="flex justify-center space-x-6 mb-6">
                <a href="https://www.instagram.com/seikatbungo/"
                    class="text-white hover:text-white transition-colors">
                    <i class="bi bi-instagram text-xl"></i>
                </a>
                <a href="https://www.tiktok.com/@seikatbungo" class="text-white hover:text-white transition-colors"
                    target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-tiktok text-xl"></i>
                </a>
                <a href="https://wa.me/6285119990901?text=Halo%20Seikat%20Bungo%20!"
                    class="text-white hover:text-white transition-colors" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-whatsapp text-xl"></i>
                </a>
            </div>
            <p class="text-white text-sm">© 2025 Seikat Bungo. All rights reserved.</p>
        </div>
    </footer>

    <!-- Include Cart Components -->
    @include('public.partials.cart-modal')
    @include('components.bouquet-price-modal')
    @include('components.bouquet-detail-panel')
    @include('components.full-image-modal')

    <script src="{{ asset('js/cart.js') }}?v={{ time() }}"></script>
    <script>
        // Search functionality
        function searchBouquets() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const bouquetCards = document.querySelectorAll('.bouquet-card');

            bouquetCards.forEach(card => {
                const bouquetName = card.dataset.name;
                const isVisible = bouquetName.includes(searchTerm);
                card.style.display = isVisible ? 'block' : 'none';
            });
        }

        // Category filter
        function filterByCategory() {
            applyAllFilters();
        }

        // Category chips
        function selectBouquetCategory(button) {
            // Remove active class from all buttons
            document.querySelectorAll('.chip-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-gradient-to-r', 'from-rose-500', 'to-pink-500', 'text-white');
                btn.classList.add('bg-white', 'text-gray-700');
            });

            // Add active class to clicked button
            button.classList.add('active', 'bg-gradient-to-r', 'from-rose-500', 'to-pink-500', 'text-white');
            button.classList.remove('bg-white', 'text-gray-700');

            // Filter bouquets
            const selectedCategory = button.dataset.category;

            // Apply all filters
            applyAllFilters();
        }

        // Price range filter functions
        function filterByPriceRange() {
            applyAllFilters();
        }

        // Combined filter function
        function applyAllFilters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const priceFilter = document.getElementById('priceFilterSelect').value;

            // Parse price range from dropdown
            let minPrice = 0;
            let maxPrice = Number.MAX_SAFE_INTEGER;

            if (priceFilter) {
                const [min, max] = priceFilter.split('-').map(p => parseInt(p));
                minPrice = min;
                maxPrice = max;
            }

            // Get selected category
            const selectedCategoryBtn = document.querySelector('.chip-btn.active');
            const selectedCategory = selectedCategoryBtn ? selectedCategoryBtn.dataset.category : '';

            const bouquetCards = document.querySelectorAll('.bouquet-card');
            let visibleCount = 0;

            bouquetCards.forEach(card => {
                const bouquetName = card.dataset.name;
                const cardCategory = card.dataset.bouquetCategory;
                const cardMinPrice = parseInt(card.dataset.minPrice) || 0;
                const cardMaxPrice = parseInt(card.dataset.maxPrice) || 0;

                // Check all filter conditions
                const matchesSearch = bouquetName.includes(searchTerm);
                const matchesCategory = !selectedCategory || cardCategory === selectedCategory;
                const matchesPrice = (cardMinPrice <= maxPrice) && (cardMaxPrice >= minPrice);

                const isVisible = matchesSearch && matchesCategory && matchesPrice;
                card.style.display = isVisible ? 'block' : 'none';

                if (isVisible) visibleCount++;
            });

            // Show/hide no results message
            showNoResultsMessage(visibleCount === 0);
        }

        function showNoResultsMessage(show) {
            let noResultsDiv = document.getElementById('noResultsMessage');

            if (show && !noResultsDiv) {
                // Create no results message
                noResultsDiv = document.createElement('div');
                noResultsDiv.id = 'noResultsMessage';
                noResultsDiv.className = 'col-span-full text-center py-12';
                noResultsDiv.innerHTML = `
                    <div class="w-20 h-20 bg-rose-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="bi bi-search text-2xl text-rose-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Tidak ada bouquet yang ditemukan</h3>
                    <p class="text-gray-500">Coba ubah filter pencarian atau rentang harga Anda</p>
                `;
                document.getElementById('bouquetGrid').appendChild(noResultsDiv);
            } else if (!show && noResultsDiv) {
                noResultsDiv.remove();
            }
        }

        // Update search function to use combined filter
        function searchBouquets() {
            applyAllFilters();
        }

        // Show bouquet price modal  
        function showBouquetPriceModal(bouquetId, bouquetName, prices) {
            // Debug logging
            console.log('showBouquetPriceModal called with:');
            console.log('- bouquetId:', bouquetId);
            console.log('- bouquetName:', bouquetName);
            console.log('- prices (raw):', prices);

            // Parse prices if it's a string
            if (typeof prices === 'string') {
                try {
                    prices = JSON.parse(prices);
                    console.log('- prices (parsed):', prices);
                } catch (e) {
                    console.error('Error parsing prices:', e);
                    alert('Error: Data harga tidak valid');
                    return;
                }
            }

            // Pastikan modal element tersedia
            const modal = document.getElementById('bouquetPriceModal');
            if (!modal) {
                console.error('Bouquet price modal not found');
                alert('Modal tidak ditemukan. Silakan refresh halaman.');
                return;
            }

            // Check if prices array is valid
            if (!Array.isArray(prices) || prices.length === 0) {
                console.error('Invalid prices data:', prices);
                alert('Data harga tidak tersedia untuk bouquet ini.');
                return;
            }

            // Call the modal function
            console.log('Calling showBouquetPriceModalComponent...');
            showBouquetPriceModalComponent(bouquetId, bouquetName, prices);
        }
    </script>

    <!-- Include Greeting Card Modal -->
    @include('components.greeting-card-modal')

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