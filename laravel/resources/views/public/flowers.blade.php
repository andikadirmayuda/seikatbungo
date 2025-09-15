<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Title --}}
    <title>Product | Seikat Bungo</title>
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
        // Helper function untuk format harga yang aman
        function safeFormatPrice(price) {
            // Ensure price is a number, remove any existing separators
            const numPrice = parseFloat(String(price).replace(/[,.]/g, '')) || 0;
            return numPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Fungsi untuk menambah ke keranjang dengan pilihan harga (global)
        function addToCartWithPrice(flowerId, priceType) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ product_id: flowerId, price_type: priceType })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal menambah ke keranjang. Status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        closeCartPriceModal();
                        updateCart();
                        toggleCart();
                    }
                })
                .catch(error => {
                    alert('Terjadi masalah: ' + error.message);
                });
        }
        // Handler untuk tombol tambah ke keranjang dengan modal harga (hanya satu kali di bawah)
        function handleAddToCart(flowerId) {
            const prices = window.flowerPrices[flowerId] || [];
            if (prices.length === 1) {
                // Jika hanya 1 harga, langsung tambahkan
                addToCartWithPrice(flowerId, prices[0].type);
            } else if (prices.length > 1) {
                // Jika ada beberapa harga, tampilkan modal
                openCartPriceModal(flowerId, prices);
            } else {
                alert('Harga produk tidak tersedia.');
            }
        }
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
        /* Popup Tutorial Responsive for Mobile */
        @media (max-width: 640px) {
            #orderTutorialPopup .max-w-md {
                max-width: 92vw !important;
                width: 98vw !important;
                padding: 0.75rem !important;
                margin: 0 1vw !important;
                border-radius: 12px !important;
            }
            #orderTutorialPopup .p-6 {
                padding: 0.75rem !important;
            }
            #orderTutorialPopup h2 {
                font-size: 0.95rem !important;
                margin-bottom: 0.5rem !important;
            }
            #orderTutorialPopup ul {
                font-size: 0.85rem !important;
                margin-bottom: 0.75rem !important;
            }
            #orderTutorialPopup a {
                font-size: 0.85rem !important;
                padding: 0.4rem 0.7rem !important;
                border-radius: 8px !important;
            }
            #orderTutorialPopup svg {
                width: 32px !important;
                height: 32px !important;
                margin-bottom: 0.5rem !important;
            }
            #orderTutorialPopup .mb-3 {
                margin-bottom: 0.5rem !important;
            }
            #orderTutorialPopup .text-lg {
                font-size: 1rem !important;
            }
        }
        body,
        .font-sans {
            font-family: 'Figtree', sans-serif;
        }

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

        /* Active tab indicator animation */
        @keyframes dotPulse {
            0% {
                transform: translate(-50%, -50%) scale(0.8);
                opacity: 0.5;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.2);
                opacity: 1;
            }

            100% {
                transform: translate(-50%, -50%) scale(0.8);
                opacity: 0.5;
            }
        }

        .nav-tab span.absolute {
            animation: dotPulse 2s infinite;
        }

        /* Responsive navigation adjustments */
        @media (max-width: 640px) {
            .nav-tab {
                min-width: 100px;
            }

            .nav-tab span {
                font-size: 0.8125rem;
            }
        }

        @media (min-width: 641px) {
            .nav-tab {
                min-width: 120px;
            }

            .nav-tab:hover {
                transform: translateY(-1px);
            }
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #2D9C8F 0%, #FFFFFF 50%, #247A72 100%);
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

        /* Label Promo & Grosir - Modern Style */
        .flower-card .relative,
        .bouquet-card .relative {
            position: relative;
        }

        .promo-label {
            position: absolute;
            top: 10px;
            left: -35px;
            /* geser supaya miringnya pas */
            background: linear-gradient(135deg, #275a59, #59aaa1);
            color: #ffffff;
            font-weight: bold;
            padding: 5px 40px;
            transform: rotate(-45deg);
            box-shadow: 0 2px 5px #F5A623;
            font-size: 14px;
            text-transform: uppercase;
            z-index: 10;
        }

        .grosir-label {
            position: absolute;
            top: 250px;
            right: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #F5A623;
            font-weight: 700;
            font-size: 13px;
            padding: 0;
            background: none;
            border: none;
            z-index: 20;
            opacity: 1;
            min-width: 70px;
            min-height: 32px;
        }

        @media (max-width: 640px) {
            .grosir-label {
                top: 150px;
                right: 8px;
                min-width: 54px;
                min-height: 22px;
            }

            .grosir-label-svg {
                width: 54px;
                height: 22px;
            }

            .grosir-label-text {
                font-size: 8px;
                padding: 0 8px;
            }
        }

        @media (min-width: 641px) and (max-width: 1023px) {
            .grosir-label {
                top: 120px;
                right: 12px;
                min-width: 60px;
                min-height: 28px;
            }

            .grosir-label-svg {
                width: 60px;
                height: 28px;
            }

            .grosir-label-text {
                font-size: 11px;
                padding: 0 12px;
            }
        }

        .grosir-label-svg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .grosir-label-text {
            position: relative;
            z-index: 1;
            padding: 0 16px;
            white-space: nowrap;
            font-size: 8px;
            font-weight: 700;
            color: #275a59;
            /* text-shadow: 0 1px 2px #fff, 0 0 2px #fff; */
        }

        /* Pastikan parent element memiliki position relative */
        .flower-card {
            position: relative;
            overflow: hidden;
        }

        /* Price Tag Styles and Animations */
        .price-tag {
            color: #2D9C8F;
            font-weight: bold;
            background: rgba(45, 156, 143, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid rgba(45, 156, 143, 0.2);
        }

        .add-to-cart-btn {
            background: #F5A623;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background: #E59420;
            transform: translateY(-2px);
        }

        @keyframes swing {

            0%,
            100% {
                transform: rotate(0deg) translateX(-50%);
            }

            25% {
                transform: rotate(2deg) translateX(-50%);
            }

            75% {
                transform: rotate(-2deg) translateX(-50%);
            }
        }

        .animate-swing {
            animation: swing 3s ease-in-out infinite;
        }

        @keyframes bounce-slow {

            0%,
            100% {
                transform: rotate(12deg) translateY(0px);
                animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
            }

            50% {
                transform: rotate(12deg) translateY(-4px);
                animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
            }
        }

        .animate-bounce-slow {
            animation: bounce-slow 2s infinite;
        }

        @keyframes slide-shine {
            0% {
                transform: translateX(-100%) skewX(-12deg);
            }

            100% {
                transform: translateX(200%) skewX(-12deg);
            }
        }

        .animate-slide-shine {
            animation: slide-shine 3s ease-in-out infinite;
        }

        /* Compact Price Tag */
        /* Removed old compact tag styles */

        /* Subtle animations - removed unused animations */

        /* Flexible card heights - auto-adjust based on content */
        .flower-card,
        .bouquet-card {
            min-height: auto;
            /* Remove fixed height for better flexibility */
        }

        /* Consistent content spacing */
        .product-content {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* Better text handling for long names */
        .product-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Limit to 2 lines for better layout */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.25;
            height: 2.5em;
            /* Fixed height for consistency */
            word-wrap: break-word;
            overflow-wrap: break-word;
            margin-bottom: 0.125rem;
            /* Very close to description */
        }

        /* Compact spacing for product cards */
        .flower-card .product-title+p,
        .bouquet-card .product-title+p {
            margin-top: 0.125rem;
            /* Very close to title */
            margin-bottom: 0.25rem;
        }

        .flower-card .text-price,
        .bouquet-card .text-price {
            margin-top: 0.25rem;
            /* Reduced top margin for price */
        }

        /* Responsive category badge */
        .category-badge {
            font-size: 0.6rem;
            padding: 0.2rem 0.5rem;
            white-space: nowrap;
            display: inline-block;
            cursor: help;
            transition: all 0.2s ease;
            color: #FFFFFF;
            background-color: #F5A623;
            border: 1px solid #FFFFFF;
        }

        .category-badge:hover {
            background-color: #E59420;
            color: white;
            transform: scale(1.05);
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {

            .flower-card,
            .bouquet-card {
                min-height: auto;
            }

            .product-title {
                font-size: 0.875rem;
                line-height: 1.2;
                height: 2.4em;
                /* Fixed height for mobile */
                -webkit-line-clamp: 2;
            }

            .category-badge {
                font-size: 0.55rem;
                padding: 0.15rem 0.4rem;
                max-width: none;
                /* Remove max-width to allow full text display */
                white-space: nowrap;
                /* Ensure text stays on one line */
            }

            .flower-card .text-price,
            .bouquet-card .text-price {
                font-size: 0.875rem;
                line-height: 1.25rem;
            }

            /* Compact layout for mobile 2-column */
            .card-hover {
                padding: 0.5rem;
            }

            .relative.h-36 {
                height: 120px;
            }

            .mb-3 {
                margin-bottom: 0.25rem;
                /* Reduced spacing on mobile */
            }

            .text-xs {
                font-size: 0.65rem;
            }

            /* Custom scrollbar for mobile: lebih kecil */
            ::-webkit-scrollbar {
                width: 4px;
                height: 4px;
            }
            ::-webkit-scrollbar-thumb {
                background: #b5e2dd;
                border-radius: 4px;
            }
            ::-webkit-scrollbar-track {
                background: #f3f3f3;
                border-radius: 4px;
            }
            /* Firefox */
            html {
                scrollbar-width: thin;
                scrollbar-color: #b5e2dd #f3f3f3;
            }
        }

        /* Desktop optimizations */
        @media (min-width: 641px) {
            .product-title {
                font-size: 0.95rem;
                line-height: 1.25;
                height: 2.5em;
                /* Fixed height for desktop */
                -webkit-line-clamp: 2;
            }

            .category-badge {
                font-size: 0.65rem;
                padding: 0.2rem 0.5rem;
            }
        }

        /* Large screen optimizations */
        @media (min-width: 1024px) {
            .product-title {
                font-size: 1rem;
                line-height: 1.3;
                height: 2.6em;
                /* Slightly more space for larger screens */
            }

            .category-badge {
                font-size: 0.7rem;
            }
        }

        /* Grid responsiveness improvements */
        .product-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        @media (min-width: 640px) {
            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1.5rem;
            }
        }

        @media (min-width: 1024px) {
            .product-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1280px) {
            .product-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        /* Card content improvements */
        .card-content {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 0.75rem;
        }

        @media (min-width: 640px) {
            .card-content {
                padding: 1rem;
            }
        }

        /* Title and category wrapper */
        .title-section {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        /* Auto-sizing for title and category */
        .title-text {
            flex: 1;
            min-width: 0;
            /* Allow text to shrink */
        }

        .category-text {
            flex-shrink: 0;
            /* Don't shrink category badge */
        }

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

    /* Professional Name Styles */
    .professional-name {
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 4px 10px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
        color: #fff;
        text-decoration: none;
        transition: all 0.3s ease-in-out;
    }

    .professional-name:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: #fff;
    box-shadow: 0 0 10px rgba(255,255,255,0.4);
    transform: translateY(-2px);
}
.professional-name:hover i {
    color: #247A72; /* hijau soft */
}

    </style>
</head>

<body class="min-h-screen gradient-bg text-black flex flex-col font-sans overflow-x-hidden">
    <!-- Popup Tutorial Pemesanan -->
    <div id="orderTutorialPopup" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
        style="display:none;">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 relative animate-fade-in-down">
            <button onclick="closeOrderTutorialPopup()"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-xl font-bold">&times;</button>
            <div class="flex flex-col items-center">
                <div class="mb-3">
                    <svg width="60" height="60" fill="none" viewBox="0 0 60 60">
                        <circle cx="30" cy="30" r="30" fill="#2D9C8F" opacity="0.1" />
                        <path d="M30 16v20M30 44h.02" stroke="#2D9C8F" stroke-width="3" stroke-linecap="round" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold mb-2 text-center">Cara Memesan di Seikat Bungo</h2>
                <ul class="text-sm text-gray-700 mb-4 list-disc list-inside text-left">
                    <li>Pilih produk bunga yang Anda inginkan.</li>
                    <li>Klik tombol <b>Tambah ke Keranjang</b>.</li>
                    <li>Lanjutkan ke halaman keranjang untuk checkout.</li>
                </ul>
                <a href="/tutorial"
                    class="mt-2 inline-block bg-[#2D9C8F] text-white px-4 py-2 rounded-lg font-semibold hover:bg-[#247A72] transition">Lihat
                    Tutorial Lengkap</a>
            </div>
        </div>
    </div>
    @include('public.partials.cart-modal')
    @include('public.partials.cart-panel')
    @include('components.full-image-modal')

    <script>
        // Tampilkan popup saat halaman dibuka (sekali per sesi)
        document.addEventListener('DOMContentLoaded', function () {
            if (!sessionStorage.getItem('orderTutorialPopupShown')) {
                document.getElementById('orderTutorialPopup').style.display = 'flex';
                // Sembunyikan tombol scroll saat popup muncul
                var scrollTopBtn = document.getElementById('scrollToTopBtn');
                var scrollBottomBtn = document.getElementById('scrollToBottomBtn');
                if (scrollTopBtn) scrollTopBtn.style.display = 'none';
                if (scrollBottomBtn) scrollBottomBtn.style.display = 'none';
                sessionStorage.setItem('orderTutorialPopupShown', '1');
            }
        });
        function closeOrderTutorialPopup() {
            document.getElementById('orderTutorialPopup').style.display = 'none';
            // Tampilkan kembali tombol scroll setelah popup ditutup
            var scrollTopBtn = document.getElementById('scrollToTopBtn');
            var scrollBottomBtn = document.getElementById('scrollToBottomBtn');
            if (scrollTopBtn) scrollTopBtn.style.display = '';
            if (scrollBottomBtn) scrollBottomBtn.style.display = '';
        }
    </script>

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
                                class="px-2 sm:px-4 py-1.5 text-center {{ $activeTab === 'flowers' ? 'bg-[#2D9C8F] text-white rounded-xl' : 'text-[#2D9C8F] hover:bg-[#2D9C8F]/10' }}">
                                <span
                                    class="text-sm font-medium {{ $activeTab === 'flowers' ? 'text-white' : '' }}">BUNGA
                                    POTONG</span>
                            </a>

                            <a href="{{ route('public.bouquets') }}"
                                class="px-2 sm:px-4 py-1.5 text-center {{ $activeTab === 'bouquets' ? 'nav-tab nav-hover-effect group relative items-center space-x-2 px-3 py-2 rounded-xl transition-all duration-300 bg-gradient-to-r from-[#2D9C8F] to-[#247A72] shadow-lg nav-active-gradient' : 'text-[#2D9C8F] hover:bg-[#2D9C8F]/10' }}">
                                <span
                                    class="text-sm font-medium {{ $activeTab === 'bouquets' ? 'text-white' : '' }}">BOUQUET</span>
                            </a>

                            <a href="{{ route('custom.bouquet.create') }}"
                                class="px-2 sm:px-4 py-1.5 text-center text-[#2D9C8F] hover:bg-[#2D9C8F]/10 rounded-xl transition-all duration-300">
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
        <!-- Tab Navigation (Duplicate removed, using header navigation) -->

        <!-- Search and Filters -->
        <div class="mb-8 flex flex-col items-center">
            <!-- Enhanced Search Bar with Price Filter (Consistent with bouquets) -->
            <div class="w-full max-w-2xl mb-4">
                <div class="flex gap-3">
                    <!-- Search Input -->
                    <div class="flex-1 relative">
                        <i
                            class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="text" id="searchInput"
                            placeholder="{{ $activeTab === 'flowers' ? 'Cari bunga impian Anda...' : 'Cari bouquet impian Anda...' }}"
                            class="w-full pl-12 pr-4 py-4 text-lg border-2 border-[#275a59] rounded-2xl focus:ring-2 focus:ring-[#59aaa1] focus:border-[#59aaa1] focus:outline-none shadow-lg transition-all duration-200 bg-white/90"
                            oninput="filterItems()">
                    </div>
                    <!-- Price Filter Dropdown (only for flowers tab) -->
                    @if($activeTab === 'flowers')
                        <div class="relative">
                            <select id="priceFilterSelect"
                                class="appearance-none bg-white/90 border-2 border-[#2D9C8F] rounded-2xl px-4 py-4 pr-10 text-gray-700 focus:ring-2 focus:ring-[#2D9C8F] focus:border-[#2D9C8F] focus:outline-none shadow-lg transition-all duration-200 cursor-pointer"
                                onchange="filterByPriceRange()">
                                <option value="">Semua Harga</option>
                                <option value="0-10000">&lt; Rp 10k</option>
                                <option value="10000-30000">Rp 10k - 30k</option>
                                <option value="30000-50000">Rp 30k - 50k</option>
                                <option value="50000-100000">Rp 50k - 100k</option>
                                <option value="100000-999999999">&gt; Rp 100k</option>
                            </select>
                            <i
                                class="bi bi-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Enhanced Filter Chips Dinamis -->
            {{-- @if($activeTab === 'flowers' && isset($flowerCategories) && $flowerCategories->count() > 0)
                <div class="flex flex-wrap gap-3 justify-center mb-4">
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-[#275a59] bg-white text-[#275a59] text-sm font-semibold shadow-md hover:shadow-lg hover:bg-[#59aaa1]/10 transition-all duration-200 active"
                        data-category="" onclick="selectCategory(this)">
                        <span class="mr-2"></span>Semua
                    </button>
                    @foreach($flowerCategories as $category)
                        <button type="button"
                            class="chip-btn px-6 py-3 rounded-full border-2 border-[#275a59] bg-white text-[#275a59] text-sm font-semibold shadow-md hover:shadow-lg hover:bg-[#fffffff]/10 transition-all duration-200"
                            data-category="{{ $category->name }}" onclick="selectCategory(this)">
                            <span class="mr-2"></span>{{ $category->name }}
                        </button>
                    @endforeach
                </div>
            @endif --}}

             {{-- @if($flowerCategories->count() > 0) --}}
            {{-- @if($activeTab === 'flowers' && isset($flowerCategories) && $flowerCategories->count() > 0)
                <div class="flex flex-wrap gap-3 justify-center mb-4">
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-[#2D9C8F] bg-white text-[#2D9C8F] text-sm font-semibold shadow-md hover:shadow-lg hover:bg-[#2D9C8F]/10 transition-all duration-200 active"
                        data-category="" onclick="selectCategory(this)">
                        <span class="mr-2">🌸</span>Semua
                    </button>
                    @foreach($flowerCategories as $category)
                        <button type="button"
                            class="chip-btn px-6 py-3 rounded-full border-2 border-[#2D9C8F] bg-white text-[#2D9C8F] text-sm font-semibold shadow-md hover:shadow-lg hover:bg-[#2D9C8F]/10 transition-all duration-200"
                            data-category="{{ $category->name }}" onclick="selectCategory(this)">
                            <span class="mr-2">🌿</span>{{ $category->name }}
                        </button>
                    @endforeach
                </div>
            @endif --}}
            
            @if($activeTab === 'flowers')
                <div class="flex flex-wrap gap-3 justify-center">
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-[#275a59] bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-[#59aaa1]/10 transition-all duration-200"
                        data-category="" onclick="selectCategory(this)">
                        <span class="mr-2">🌸</span>Semua
                    </button>
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-[#275a59] bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-[#59aaa1]/10 transition-all duration-200 active"
                        data-category="Fresh Flowers" onclick="selectCategory(this)">
                        <span class="mr-2">🌿</span>Fresh Flowers
                    </button>
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-[#275a59] bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-[#59aaa1]/10 transition-all duration-200"
                        data-category="Artificial" onclick="selectCategory(this)">
                        <span class="mr-2">🍁</span>Artificial
                    </button>
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-[#275a59] bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-[#59aaa1]/10 transition-all duration-200"
                        data-category="Daun" onclick="selectCategory(this)">
                        <span class="mr-2">🍃</span>Daun
                    </button>
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-[#275a59] bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-[#59aaa1]/10 transition-all duration-200"
                        data-category="Aksesoris" onclick="selectCategory(this)">
                        <span class="mr-2">🎀</span>Aksesoris
                    </button>
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-[#275a59] bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-[#59aaa1]/10 transition-all duration-200"
                        data-category="Papan Akrilik" onclick="selectCategory(this)">
                        <span class="mr-2">🪧</span>Papan Akrilik
                    </button>
                </div>
            @else
                <div class="flex flex-wrap gap-3 justify-center">
                    @foreach($bouquetCategories as $category)
                        <button type="button"
                            class="chip-btn px-6 py-3 rounded-full border-2 border-[#275a59] bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-[#59aaa1]/10 transition-all duration-200"
                            data-category="{{ (int) $category->id }}" onclick="selectBouquetCategory(this)">
                            <span class="mr-2">📦</span>{{ $category->name }}
                        </button>
                    @endforeach
                </div>
            @endif
            

            </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-6">
            @if($activeTab === 'flowers')
                @forelse($flowers as $flower)
                    <div class="flower-card group" data-category="{{ $flower->category->name ?? 'lainnya' }}"
                        data-name="{{ strtolower($flower->name) }}" data-flower-id="{{ (int) $flower->id }}">
                        <div
                            class="card-hover glass-effect rounded-2xl shadow-lg p-3 sm:p-4 h-full flex flex-col overflow-hidden">
                            <!-- Image -->
                            <div class="relative h-48 md:h-80 sm:h-40 mb-3 sm:mb-4 rounded-xl overflow-hidden">
                                @if($flower->image)
                                    <img src="{{ asset('storage/' . $flower->image) }}" alt="{{ $flower->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#275a59]/10 to-[#59aaa1]/10 rounded-xl">
                                        {{-- <i class="bi bi-flower1 text-3xl text-[#275a59]">🌸</i> --}}
                                        <span class="text-4xl">🌸</span>
                                    </div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>

                                <!-- Label Promo - Ribbon Style -->
                                @if($flower->prices->where('type', 'promo')->isNotEmpty())
                                    <div class="promo-label">
                                        PROMO
                                    </div>
                                @endif
                                <!-- Label Grosir - Ribbon Style -->
                                @if($flower->prices->where('type', 'harga_grosir')->isNotEmpty())
                                    <div class="grosir-label">
                                        <svg class="grosir-label-svg" viewBox="0 0 120 40" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10,20 Q5,5 20,10 Q30,0 40,10 Q60,0 80,10 Q100,0 110,10 Q120,20 110,30 Q120,40 100,35 Q80,40 70,30 Q60,40 40,30 Q20,40 10,30 Q0,20 10,20"
                                                fill="#247A72" stroke="#E59420" stroke-width="3" />
                                        </svg>
                                        <span class="grosir-label-text text-white">GROSIR</span>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="absolute top-2 sm:top-3 right-2 sm:right-3 flex gap-2 z-30">
                                    <!-- Wishlist Button -->
                                    {{-- <button
                                        class="w-6 sm:w-8 h-6 sm:h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        <i class="bi bi-heart text-rose-500 text-xs sm:text-sm"></i>
                                    </button> --}}
                                    <!-- View Button -->
                                    <button
                                        onclick="showFullImage('{{ asset('storage/' . $flower->image) }}', '{{ $flower->name }}')"
                                        class="w-6 sm:w-8 h-6 sm:h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        <i class="bi bi-eye text-[#2D9C8F] text-xs sm:text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="product-content flex-1">
                                <!-- Category Badge - Centered -->
                                <div class="flex justify-center mb-2">
                                    <span class="category-badge rounded-full px-3 py-1 text-xs font-medium"
                                        title="{{ $flower->category->name ?? 'Umum' }}">
                                        {{ $flower->category->name ?? 'Umum' }}
                                    </span>
                                </div>

                                <!-- Product Title - Centered -->
                                <h3 class="product-title font-bold text-gray-800 text-center leading-none mt-5">
                                    {{ $flower->name }}
                                </h3>

                                <!-- Price - Centered -->
                                <div>
                                    @php
        // Siapkan array harga untuk JS
        $jsPrices = $flower->prices->map(function ($price) {
            return [
                'id' => $price->id,
                'type' => $price->type,
                'label' => __(ucwords(str_replace('_', ' ', $price->type))),
                'price' => (int) $price->price // Pastikan price adalah integer
            ];
        });
                                    @endphp
                                    <div class="text-center">
                                        <div class="text-price text-sm sm:text-lg font-bold text-[#275a59] text-center">
                                            @php
        $minPrice = $jsPrices->min('price');
        $maxPrice = $jsPrices->max('price');
                                            @endphp
                                            @if($minPrice === $maxPrice)
                                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($minPrice, 0, ',', '.') }} -
                                                {{ number_format($maxPrice, 0, ',', '.') }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 text-center mb-1">
                                            Beberapa pilihan harga
                                            {{-- {{ $jsPrices->count() }} pilihan harga --}}
                                        </div>
                                    </div>
                                </div>

                                <!-- Stock -->
                                <div class="mb-2">
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="text-gray-500">Stok:</span>
                                        <span
                                            class="font-semibold sm:text-sm {{ $flower->current_stock > 10 ? 'text-orange-600' : ($flower->current_stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                            @php
        // Cari harga ikat yang tersedia, prioritas ikat 3, ikat 5, ikat 10, ikat 20
        $ikatPrice = $flower->prices->firstWhere('type', 'ikat_3')
            ?: $flower->prices->firstWhere('type', 'ikat 3')
            ?: $flower->prices->firstWhere('type', 'ikat_5')
            ?: $flower->prices->firstWhere('type', 'ikat 5')
            ?: $flower->prices->firstWhere('type', 'ikat_10')
            ?: $flower->prices->firstWhere('type', 'ikat 10')
            ?: $flower->prices->firstWhere('type', 'ikat_20')
            ?: $flower->prices->firstWhere('type', 'ikat 20');

        $ikatCount = 0;
        $ikatLabel = '';

        if ($ikatPrice && $ikatPrice->unit_equivalent > 0) {
            $ikatCount = floor($flower->current_stock / $ikatPrice->unit_equivalent);
            $unitSize = $ikatPrice->unit_equivalent;
            $ikatLabel = " / {$ikatCount} ikat";
        }

        // Gunakan base_unit dari database atau default ke 'tangkai'
        $baseUnit = $flower->base_unit ?? 'tangkai';
                                            @endphp
                                            {{ $flower->current_stock }} {{ $baseUnit }}{{ $ikatLabel }}
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-gradient-to-r from-[#275a59] to-[#59aaa1] h-1.5 rounded-full transition-all duration-300"
                                            style="width: {{ min(($flower->current_stock / 50) * 100, 100) }}%"></div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                @php $isOut = (int) $flower->current_stock <= 0; @endphp
                                <button onclick="{{ $isOut ? 'return false' : 'handleAddToCart(' . (int) $flower->id . ')' }}"
                                    class="mt-auto w-full {{ $isOut ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-gradient-to-r from-[#275a59] to-[#59aaa1] hover:from-[#1f4645] hover:to-[#4a8f87] text-white shadow-md hover:shadow-lg' }} font-semibold py-1.5 sm:py-2 px-3 sm:px-4 rounded-xl transition-all duration-200 text-xs sm:text-sm"
                                    {{ $isOut ? 'disabled' : '' }}>
                                    @if($isOut)
                                        <i class="bi bi-x-circle mr-1 sm:mr-2"></i>Stok Habis
                                    @else
                                        <i class="bi bi-cart-plus mr-1 sm:mr-2"></i>Tambah ke Keranjang
                                    @endif
                                </button>
                                <script>
                                    window.flowerPrices = window.flowerPrices || {};
                                    try {
                                        const pricesData = @json($jsPrices);
                                        // Validasi dan sanitasi data harga, filter tipe rangkaian
                                        const sanitizedPrices = pricesData
                                            .filter(price => !['custom_ikat', 'custom_tangkai', 'custom_khusus'].includes(price.type))
                                            .map(price => ({
                                                id: parseInt(price.id) || 0,
                                                type: price.type || '',
                                                label: price.label || '',
                                                price: parseFloat(String(price.price).replace(/[,.]/g, '')) || 0
                                            }));
                                        window.flowerPrices[{{ (int) $flower->id }}] = sanitizedPrices;
                                    } catch (error) {
                                        console.error('Error parsing flower prices:', error);
                                        window.flowerPrices[{{ (int) $flower->id }}] = [];
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="w-20 h-20 bg-rose-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="bi bi-flower1 text-2xl text-rose-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">Tidak ada bunga yang tersedia saat ini</h3>
                        <p class="text-gray-500 text-sm">Silakan coba lagi nanti atau hubungi kami untuk informasi lebih lanjut.
                        </p>
                    </div>
                @endforelse
            @else
                @forelse($bouquets as $bouquet)
                    <div class="bouquet-card group" data-name="{{ strtolower($bouquet->name) }}"
                        data-bouquet-category="{{ $bouquet->category_id ? (int) $bouquet->category_id : '' }}">
                        <div
                            class="card-hover glass-effect rounded-2xl shadow-lg p-3 sm:p-4 h-full flex flex-col overflow-hidden">
                            <!-- Image -->
                            <div class="relative h-36 sm:h-40 mb-3 sm:mb-4 rounded-xl overflow-hidden">
                                @if($bouquet->image)
                                    <img src="{{ asset('storage/' . $bouquet->image) }}" alt="{{ $bouquet->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    {{-- gradient placeholder --}}
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-gradient-to-br from-rose-100 to-pink-100 rounded-xl">
                                        <i class="bi bi-flower3 text-3xl text-rose-400"></i>
                                    </div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                                <!-- Wishlist Button -->
                                <!-- Action Buttons -->
                                <div class="absolute top-2 sm:top-3 right-2 sm:right-3 flex gap-2 z-30">
                                    <!-- Wishlist Button -->
                                    {{-- <button
                                        class="w-6 sm:w-8 h-6 sm:h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        <i class="bi bi-heart text-rose-500 text-xs sm:text-sm"></i>
                                    </button> --}}
                                    <!-- View Button -->
                                    <button
                                        onclick="showFullImage('{{ asset('storage/' . $flower->image) }}', '{{ $flower->name }}')"
                                        class="w-6 sm:w-8 h-6 sm:h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        <i class="bi bi-eye text-[#2D9C8F] text-xs sm:text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="product-content flex-1">
                                <!-- Category Badge - Left Aligned -->
                                <div class="flex justify-start mb-3">
                                    <span
                                        class="category-badge bg-rose-100 text-rose-700 rounded-full px-3 py-1 text-xs font-medium"
                                        title="{{ $bouquet->category->name ?? 'Bouquet' }}">
                                        {{ $bouquet->category->name ?? 'Bouquet' }}
                                    </span>
                                </div>

                                <!-- Product Title - Left Aligned -->
                                <h3 class="product-title font-bold text-gray-800 mb-0 text-left leading-tight">
                                    {{ $bouquet->name }}
                                </h3>

                                <!-- Description - Left Aligned with no spacing from title -->
                                <p class="text-xs sm:text-sm text-gray-600 mb-3 line-clamp-2 leading-tight text-left -mt-1">
                                    {{ $bouquet->description }}
                                </p>

                                <!-- Sizes - Left Aligned -->
                                <div class="mb-1">
                                    <span class="text-xs text-gray-500 block mb-1 text-left">Ukuran Tersedia:</span>
                                    <div class="flex flex-wrap gap-1 justify-start">
                                        @foreach($bouquet->sizes as $size)
                                            <span
                                                class="inline-block px-2 py-1 bg-gradient-to-r from-[#275a59]/10 to-[#59aaa1]/10 text-[#275a59] rounded-full text-[10px] sm:text-xs font-medium">
                                                {{ $size->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Price Range - Left Aligned -->
                                <div class="mb-1">
                                    @php
        $minPrice = $bouquet->prices->min('price');
        $maxPrice = $bouquet->prices->max('price');
                                    @endphp
                                    <div class="text-left">
                                        <div class="text-price text-sm sm:text-lg font-bold text-rose-600">
                                            @if($minPrice === $maxPrice)
                                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($minPrice, 0, ',', '.') }} -
                                                {{ number_format($maxPrice, 0, ',', '.') }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">rentang harga</div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <a href="{{ route('public.bouquet.detail', $bouquet->id) }}"
                                    class="mt-auto w-full bg-gradient-to-r from-[#275a59] to-[#59aaa1] hover:from-[#1f4645] hover:to-[#4a8f87] text-white font-semibold py-1.5 sm:py-2 px-3 sm:px-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-xs sm:text-sm text-center block">
                                    <i class="bi bi-eye mr-1 sm:mr-2"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="w-20 h-20 bg-rose-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="bi bi-flower3 text-2xl text-rose-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">Tidak ada bouquet yang tersedia saat ini</h3>
                        <p class="text-gray-500 text-sm">Silakan coba lagi nanti atau hubungi kami untuk informasi lebih lanjut.
                        </p>
                    </div>
                @endforelse
            @endif
        </div>

        <!-- Call to Action untuk Bouquet (hanya tampil di tab flowers) -->
        @if($activeTab === 'flowers')
            <div class="mt-16 text-center">
                <div
                    class="bg-gradient-to-br from-[#59aaa1]/20 via-white to-[#275a59]/20 rounded-3xl p-8 shadow-lg border border-[#275a59]/20">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-[#275a59] to-[#59aaa1] rounded-full mb-6 shadow-lg">
                        <i class="bi bi-flower3 text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Lihat Koleksi Bouquet Kami</h3>
                    <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                        Rangkaian bunga cantik yang dirancang khusus untuk momen spesial Anda.
                        Berbagai ukuran dan kategori tersedia untuk setiap kebutuhan.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ route('public.bouquets') }}"
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-[#275a59] to-[#59aaa1] hover:from-[#1f4645] hover:to-[#4a8f87] text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                            <i class="bi bi-flower3 mr-2"></i>
                            Lihat Semua Bouquet
                        </a>
                        <span class="text-sm text-gray-500">
                            atau klik tab "💐 Bouquet" di atas
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Enhanced Footer -->
    <footer class="bg-gradient-to-r from-[#2D9C8F] to-[#247A72] text-white py-12 mt-16">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="w-16 h-16 bg-white/20 rounded-full mx-auto mb-4 flex items-center justify-center">
                {{-- <span class="text-white font-bold text-2xl">F</span> --}}
                <img src="{{ asset('logo-seikat-bungo.png') }}" alt="Logo" class="brand-logo rounded-full w-12 h-12">
            </div>
            <h3 class="text-2xl font-bold mb-2">Seikat Bungo</h3>
            <p class="text-white mb-4 max-w-2xl mx-auto">
                Menghadirkan keindahan bunga segar berkualitas premium untuk setiap momen berharga dalam hidup Anda
            </p>
            <div class="flex justify-center space-x-6 mb-6">
                <a href="https://www.instagram.com/seikat.bungo/" class="text-white hover:text-white transition-colors">
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
            <br>
            <br>
            <div class="text-xs text-white flex items-center justify-center mt-4">
                <i class="bi bi-laptop mr-2"></i>
                Designed and Developed by :
                <a href="https://www.instagram.com/adrmyd/" target="_blank" class="professional-name ml-2">
                    <i class="bi bi-code-slash mr-1"></i> adrmyd
                </a>
            </div>
        </div>
    </footer>
    <!-- Scroll to Bottom Button -->
    <button id="scrollToBottomBtn" title="Scroll ke bawah"
    class="fixed top-1/2 right-6 -translate-y-1/2 z-50 bg-[#2D9C8F] text-white rounded-full shadow-lg p-3 flex items-center justify-center transition-all duration-300 opacity-0 pointer-events-none hover:bg-[#247A72] focus:outline-none"
    style="width: 30px; height: 30px;">
        <i class="bi bi-arrow-down text-2xl"></i>
    </button>
        

        <!-- Scroll to Top Button -->
        <button id="scrollToTopBtn" title="Kembali ke atas"
            class="fixed top-1/2 right-6 -translate-y-1/2 z-50 bg-[#2D9C8F] text-white rounded-full shadow-lg p-3 flex items-center justify-center transition-all duration-300 opacity-0 pointer-events-none hover:bg-[#247A72] focus:outline-none"
            style="width: 30px; height: 30px;">
            <i class="bi bi-arrow-up text-2xl"></i>
        </button>

    <script src="{{ asset('js/cart.js') }}?v={{ time() }}"></script>
    <script>
        // Scroll to Bottom Button Logic
            const scrollToBottomBtn = document.getElementById('scrollToBottomBtn');
            function checkScrollButtons() {
                // Scroll to Top
                if (window.scrollY > 200) {
                    scrollToTopBtn.classList.add('opacity-100');
                    scrollToTopBtn.classList.remove('opacity-0', 'pointer-events-none');
                } else {
                    scrollToTopBtn.classList.remove('opacity-100');
                    scrollToTopBtn.classList.add('opacity-0', 'pointer-events-none');
                }
                // Scroll to Bottom
                const scrollable = document.documentElement.scrollHeight - window.innerHeight;
                if (window.scrollY < scrollable - 100) {
                    scrollToBottomBtn.classList.add('opacity-100');
                    scrollToBottomBtn.classList.remove('opacity-0', 'pointer-events-none');
                } else {
                    scrollToBottomBtn.classList.remove('opacity-100');
                    scrollToBottomBtn.classList.add('opacity-0', 'pointer-events-none');
                }
            }
            window.addEventListener('scroll', checkScrollButtons);
            // Inisialisasi saat load
            document.addEventListener('DOMContentLoaded', checkScrollButtons);
            scrollToBottomBtn.addEventListener('click', function () {
                window.scrollTo({ top: document.documentElement.scrollHeight, behavior: 'smooth' });
            });

        // Scroll to Top Button Logic
        const scrollToTopBtn = document.getElementById('scrollToTopBtn');
        window.addEventListener('scroll', function () {
            if (window.scrollY > 200) {
                scrollToTopBtn.classList.add('opacity-100');
                scrollToTopBtn.classList.remove('opacity-0', 'pointer-events-none');
            } else {
                scrollToTopBtn.classList.remove('opacity-100');
                scrollToTopBtn.classList.add('opacity-0', 'pointer-events-none');
            }
        });
        scrollToTopBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    

        let selectedCategory = '';
        let selectedBouquetCategory = '';
        let selectedPriceRange = '';
        function filterItems() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const activeTab = '{{ $activeTab }}';
            if (activeTab === 'flowers') {
                document.querySelectorAll('.flower-card').forEach(card => {
                    const name = card.getAttribute('data-name');
                    const category = card.getAttribute('data-category');
                    const matchSearch = name.includes(search);
                    const matchCategory = !selectedCategory || category === selectedCategory;
                    // Price filter
                    let matchPrice = true;
                    if (selectedPriceRange) {
                        const [min, max] = selectedPriceRange.split('-').map(Number);
                        // Ambil harga terendah dari data attribute (atau 0 jika tidak ada)
                        let price = 0;
                        try {
                            const prices = window.flowerPrices[card.getAttribute('data-flower-id')] || [];
                            if (Array.isArray(prices) && prices.length > 0) {
                                price = Math.min(...prices.map(p => parseInt(p.price)));
                            }
                        } catch (e) { }
                        matchPrice = price >= min && price <= max;
                    }
                    card.style.display = (matchSearch && matchCategory && matchPrice) ? '' : 'none';
                });
            } else {
                document.querySelectorAll('.bouquet-card').forEach(card => {
                    const name = card.getAttribute('data-name');
                    const category = card.getAttribute('data-bouquet-category');
                    const matchSearch = name.includes(search);
                    const matchCategory = !selectedBouquetCategory || category === selectedBouquetCategory;
                    card.style.display = (matchSearch && matchCategory) ? '' : 'none';
                });
            }
        }

        function filterByPriceRange() {
            selectedPriceRange = document.getElementById('priceFilterSelect').value;
            filterItems();
        }
        function selectCategory(btn) {
            selectedCategory = btn.getAttribute('data-category');
            document.querySelectorAll('.chip-btn').forEach(button => {
                button.classList.remove('bg-[#275a59]', 'text-white', 'border-[#275a59]');
                button.classList.add('bg-white', 'text-gray-700', 'border-[#275a59]');
            });
            btn.classList.add('bg-[#275a59]', 'text-white', 'border-[#275a59]');
            btn.classList.remove('bg-white', 'text-gray-700', 'border-[#275a59]');
            filterItems();
        }
        function selectBouquetCategory(btn) {
            selectedBouquetCategory = btn.getAttribute('data-category');
            console.log('Selected bouquet category:', selectedBouquetCategory);
            document.querySelectorAll('.chip-btn').forEach(button => {
                button.classList.remove('bg-rose-500', 'text-white', 'border-rose-500');
                button.classList.add('bg-white', 'text-gray-700', 'border-rose-200');
            });
            btn.classList.add('bg-rose-500', 'text-white', 'border-rose-500');
            btn.classList.remove('bg-white', 'text-gray-700', 'border-rose-200');
            filterItems();
        }
        document.addEventListener('DOMContentLoaded', function () {
            const activeTab = '{{ $activeTab }}';
            if (activeTab === 'flowers') {
                // Pilih tombol Fresh Flowers sebagai default
                const freshBtn = Array.from(document.querySelectorAll('.chip-btn')).find(btn => btn.getAttribute('data-category') === 'Fresh Flowers');
                if (freshBtn) selectCategory(freshBtn);
            } else {
                const firstCategoryBtn = document.querySelector('.chip-btn');
                if (firstCategoryBtn) selectBouquetCategory(firstCategoryBtn);
            }
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('load', function () {
                    this.style.opacity = '1';
                });
            });
            let searchTimeout;
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(filterItems, 300);
            });
        });
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