<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <style>
        html {
            scroll-behavior: smooth;
        }

        /* Prevent sidebar flash before Alpine.js loads */
        [x-cloak] {
            display: none !important;
        }
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ app_name() }}</title>
    <link rel="icon" type="image/png" href="{{ app_logo() }}" sizes="32x32">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @livewireStyles
    @vite(['resources/css/app.css'])

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Scripts -->
    @livewireScripts
    @vite(['resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100" x-data="{
                isSidebarOpen: false,
                init() {
                    // Ensure sidebar is closed immediately on initialization
                    this.isSidebarOpen = false;
                    // Clear any stored state to prevent conflicts
                    if (typeof localStorage !== 'undefined') {
                        localStorage.removeItem('sidebarOpen');
                    }
                    console.log('Sidebar initialized with state:', this.isSidebarOpen);
                },
                toggleSidebar() {
                    console.log('Toggle sidebar clicked, current state:', this.isSidebarOpen);
                    this.isSidebarOpen = !this.isSidebarOpen;
                    console.log('New sidebar state:', this.isSidebarOpen);
                }
            }" x-init="init()">
        <!-- Sidebar -->
        <div x-show="isSidebarOpen" x-cloak x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-2xl border-r border-gray-200 overflow-y-auto backdrop-blur-xl">
            @include('layouts.sidebar')
        </div>

        <!-- Overlay -->
        <div x-show="isSidebarOpen" x-cloak x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="isSidebarOpen = false"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm z-20 lg:hidden"></div>

        <!-- Main Content -->
        <div class="flex-1 transition-all duration-300" :class="{ 'lg:ml-64': isSidebarOpen }">
            <!-- Top Navigation - Sticky Header -->
            <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-gray-200 shadow-sm">
                <div class="flex items-center h-16 px-4">
                    <button @click="toggleSidebar()"
                        class="text-gray-600 hover:text-pink-500 hover:bg-pink-50 focus:outline-none focus:ring-2 focus:ring-pink-500 rounded-lg p-2 transition-all duration-200"
                        title="Toggle Sidebar" type="button">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    {{-- <div class="ml-4 font-semibold text-xl text-gray-800 dark:text-gray-200">
                        {{ app_name() }}
                    </div> --}}

                    <!-- User Dropdown -->
                    <div class="ml-auto flex items-center space-x-4">
                        <!-- Notification Bell -->
                        <div class="relative">
                            <button id="notification-bell"
                                class="relative text-gray-600 hover:text-pink-500 hover:bg-pink-50 focus:outline-none focus:ring-2 focus:ring-pink-500 rounded-lg p-2 transition-all duration-200"
                                title="Notifikasi">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <!-- Notification Badge -->
                                <span id="notification-badge"
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden animate-bounce">
                                </span>
                            </button>
                        </div>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center text-sm font-medium text-gray-700 hover:text-pink-500 hover:bg-pink-50 rounded-lg px-3 py-2 transition-all duration-200">

                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-pink-400 to-pink-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                        <div>{{ Auth::user()->name }}</div>
                                    </div>
                                    <div class="ml-2">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>

            <!-- Page Heading -->
            @isset($header)
                <header
                    class="bg-white/80 backdrop-blur-sm shadow-sm rounded-xl mt-6 mb-6 mx-4 flex items-center px-6 py-4 border border-gray-200">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-pink-400 to-pink-600 rounded-lg mr-4">
                        <i class="bi bi-flower2 text-lg text-white"></i>
                    </div>
                    <div class="flex-1">
                        <div class="text-xl font-bold text-gray-800 font-sans">{{ $header }}</div>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Floating Menu Button for Mobile (appears when scrolled down) -->
        <div x-data="{ 
                showFab: false,
                init() {
                    // Show FAB when user scrolls down
                    window.addEventListener('scroll', () => {
                        this.showFab = window.scrollY > 200;
                    });
                }
            }" x-init="init()">
            <button x-show="showFab" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-y-16 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-16 opacity-0"
                @click="toggleSidebar()"
                class="fixed bottom-6 right-6 z-50 lg:hidden bg-pink-500 hover:bg-pink-600 text-white p-4 rounded-full shadow-lg focus:outline-none focus:ring-4 focus:ring-pink-300"
                title="Menu" style="display: none;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- SweetAlert2 -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @livewireScripts
        <script>
            // SweetAlert delete confirmation
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.delete-confirm').forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const form = this.closest('form');

                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Data yang dihapus tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });

                // Toast notifications
                @if(session('success'))
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'success',
                        title: '{{ session('success') }}'
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'error',
                        title: '{{ session('error') }}'
                    });
                @endif
            });
        </script>

        <!-- Debug script untuk sidebar -->
        <script src="{{ asset('js/sidebar-debug.js') }}"></script>

        <!-- Fallback script untuk sidebar -->
        <script src="{{ asset('js/sidebar-fallback.js') }}"></script>



        <!-- Notification Bell Script -->
        <script>
            function updateNotificationBadge(count) {
                const badge = document.getElementById('notification-badge');
                const bell = document.getElementById('notification-bell');

                if (badge && bell) {
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.classList.remove('hidden');
                        bell.classList.add('animate-pulse');
                    } else {
                        badge.classList.add('hidden');
                        bell.classList.remove('animate-pulse');
                    }
                }
            }

            function checkNotifications() {
                fetch('/api/admin/notifications/pending', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(notifications => {
                        if (!Array.isArray(notifications)) {
                            console.error('Unexpected response format:', notifications);
                            return;
                        }
                        // Update badge dengan jumlah notifikasi
                        updateNotificationBadge(notifications.length);

                        // Jika ada notifikasi baru dan browser mendukung
                        if (notifications.length > 0 && 'Notification' in window && Notification.permission === 'granted') {
                            notifications.forEach(notification => {
                                // Cek apakah notifikasi ini sudah ditampilkan sebelumnya
                                const shown = localStorage.getItem(`notification-${notification.id}`);
                                if (!shown) {
                                    // Tampilkan notifikasi browser
                                    new Notification(notification.message.title, {
                                        body: notification.message.body,
                                        icon: '/logo-seikat-bungo.png'
                                    });
                                    // Tandai notifikasi ini sudah ditampilkan
                                    localStorage.setItem(`notification-${notification.id}`, 'true');
                                    // Mark as delivered di server
                                    fetch(`/api/admin/notifications/${notification.id}/delivered`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                        }
                                    });
                                }
                            });
                        }
                    })
                    .catch(error => console.error('Error checking notifications:', error));
            }

            // Inisialisasi sistem notifikasi
            document.addEventListener('DOMContentLoaded', function () {
                // Request permission untuk notifikasi browser
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }

                // Setup bell click handler
                const bell = document.getElementById('notification-bell');
                if (bell) {
                    bell.addEventListener('click', function () {
                        window.location.href = '{{ route("admin.public-orders.index") }}';
                    });
                }

                // Mulai checking notifications
                checkNotifications();
                setInterval(checkNotifications, 10000); // Check setiap 10 detik
            });
        </script>

        @stack('scripts')
</body>

</html>