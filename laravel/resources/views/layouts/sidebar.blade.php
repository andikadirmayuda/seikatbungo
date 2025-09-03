<nav class="font-sans">
    <div class="px-4 py-6 bg-white h-full min-h-screen">
        <!-- Logo Section -->
        <div class="flex flex-col items-center justify-center mb-6 pb-4 border-b border-gray-200">
            <div
                class="w-12 h-12 bg-gradient-to-br from-pink-400 to-pink-600 rounded-xl flex items-center justify-center mb-3">
                <img src="{{ app_logo() }}" alt="{{ app_name() }}" class="h-8 w-auto">
            </div>
            <h3 class="text-gray-800 text-lg text-center font-bold">{{ app_name() }}</h3>
        </div>

        <ul class="space-y-1">


            <!-- DASHBOARD -->
            <li class="mb-2">
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-pink-500 mb-1">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </div>
                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <x-slot name="icon">
                        <i class="bi bi-house-door-fill text-lg mr-1"></i>
                    </x-slot>
                    {{ __('Dashboard') }}
                </x-sidebar-link>


            </li>


            <!-- MANAJEMEN PENGGUNA -->
            <li class="mt-4 mb-1 text-xs font-bold uppercase tracking-wider text-pink-500 flex items-center gap-2"><i
                    class="bi bi-people"></i> Manajemen Pengguna</li>
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('customers service') || auth()->user()->hasRole('kasir') || auth()->user()->hasRole('karyawan'))
                <x-sidebar-link :href="route('online-customers.index')" :active="request()->routeIs('online-customers.*')">
                    <x-slot name="icon">
                        <i class="bi bi-people-fill text-lg mr-1"></i>
                    </x-slot>
                    {{ __('Pelanggan') }}
                </x-sidebar-link>
            @endif


            <!-- MANAJEMEN PRODUK -->
            <li class="mt-4 mb-1 text-xs font-bold uppercase tracking-wider text-pink-500 flex items-center gap-2"><i
                    class="bi bi-box-seam"></i> Manajemen Produk</li>

            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('karyawan'))
                {{-- Kategori hanya untuk owner, admin, karyawan --}}
                <x-sidebar-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                    <x-slot name="icon">
                        <i class="bi bi-tags-fill text-lg mr-1"></i>
                    </x-slot>
                    {{ __('Kategori') }}
                </x-sidebar-link>
            @endif

            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('karyawan') || auth()->user()->hasRole('kasir'))
                {{-- Produk untuk owner, admin, karyawan, kasir --}}
                <x-sidebar-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                    <x-slot name="icon">
                        <i class="bi bi-box-seam text-lg mr-1"></i>
                    </x-slot>
                    {{ __('Produk') }}
                </x-sidebar-link>
            @endif

            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('karyawan') || auth()->user()->hasRole('kasir'))
                {{-- Inventaris untuk owner, admin, karyawan, kasir --}}
                <x-sidebar-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')">
                    <x-slot name="icon">
                        <i class="bi bi-archive-fill text-lg mr-1"></i>
                    </x-slot>
                    {{ __('Inventaris') }}
                </x-sidebar-link>
            @endif

            <!-- PROMOSI & VOUCHER -->
            <li class="mt-4 mb-1 text-xs font-bold uppercase tracking-wider text-pink-500 flex items-center gap-2">
                <i class="bi bi-ticket-perforated"></i> Promosi & Voucher
            </li>
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin'))
                <x-sidebar-link :href="route('admin.vouchers.index')" :active="request()->routeIs('admin.vouchers.*')">
                    <x-slot name="icon">
                        <i class="bi bi-ticket-perforated-fill text-lg mr-1"></i>
                    </x-slot>
                    {{ __('Voucher') }}
                </x-sidebar-link>
            @endif

            <!-- PEMESANAN & PENJUALAN -->
            <li class="mt-4 mb-1 text-xs font-bold uppercase tracking-wider text-pink-500 flex items-center gap-2"><i
                    class="bi bi-cart"></i> Pemesanan & Penjualan</li>
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('kasir'))
                <x-sidebar-link :href="route('admin.public-orders.index')"
                    :active="request()->routeIs('admin.public-orders.*')">
                    <x-slot name="icon">
                        <i class="bi bi-globe2 text-lg mr-1"></i>
                    </x-slot>
                    Pesanan Publik
                </x-sidebar-link>
            @endif
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('kasir'))
                <x-sidebar-link :href="route('sales.index')" :active="request()->routeIs('sales.*')">
                    <x-slot name="icon">
                        <i class="bi bi-cash-stack text-lg mr-1"></i>
                    </x-slot>
                    {{ __('Penjualan') }}
                </x-sidebar-link>
            @endif

            <!-- MANAJEMEN BUKET -->
            <li class="mt-4 mb-1 text-xs font-bold uppercase tracking-wider text-pink-500 flex items-center gap-2"><i
                    class="bi bi-flower1"></i> Manajemen Buket</li>
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('kasir'))
                <x-sidebar-link :href="route('bouquets.index')" :active="request()->routeIs('bouquets.*')">
                    <x-slot name="icon">
                        <i class="bi bi-flower1 text-lg mr-1"></i>
                    </x-slot>
                    Bouquet
                </x-sidebar-link>
            @endif
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('kasir'))
                <x-sidebar-link :href="route('bouquet-categories.index')"
                    :active="request()->routeIs('bouquet-categories.*')">
                    <x-slot name="icon">
                        <i class="bi bi-bookmark-heart text-lg mr-1"></i>
                    </x-slot>
                    Kategori Bouquet
                </x-sidebar-link>
                <x-sidebar-link :href="route('bouquet-sizes.index')" :active="request()->routeIs('bouquet-sizes.*')">
                    <x-slot name="icon">
                        <i class="bi bi-rulers text-lg mr-1"></i>
                    </x-slot>
                    Ukuran Bouquet
                </x-sidebar-link>
                <x-sidebar-link :href="route('bouquet-components.index')"
                    :active="request()->routeIs('bouquet-components.*')">
                    <x-slot name="icon">
                        <i class="bi bi-boxes text-lg mr-1"></i>
                    </x-slot>
                    Komponen Bouquet
                </x-sidebar-link>
            @endif

            <!-- LAPORAN -->
            <li class="mt-4 mb-1 text-xs font-bold uppercase tracking-wider text-pink-500 flex items-center gap-2"><i
                    class="bi bi-clipboard-data"></i> Laporan</li>
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('kasir'))
                <x-sidebar-link :href="route('reports.sales')" :active="request()->routeIs('reports.sales')">
                    <x-slot name="icon">
                        <i class="bi bi-bar-chart-fill text-lg mr-1"></i>
                    </x-slot>
                    Laporan Penjualan
                </x-sidebar-link>
            @endif
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('kasir') || auth()->user()->hasRole('customers service'))
                <x-sidebar-link :href="route('reports.orders')" :active="request()->routeIs('reports.orders')">
                    <x-slot name="icon">
                        <i class="bi bi-receipt-cutoff text-lg mr-1"></i>
                    </x-slot>
                    Laporan Pemesanan
                </x-sidebar-link>
            @endif
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin'))
                <x-sidebar-link :href="route('reports.stock')" :active="request()->routeIs('reports.stock')">
                    <x-slot name="icon">
                        <i class="bi bi-box2-heart text-lg mr-1"></i>
                    </x-slot>
                    Laporan Stok
                </x-sidebar-link>
            @endif
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('customers service'))
                <x-sidebar-link :href="route('reports.customers')" :active="request()->routeIs('reports.customers')">
                    <x-slot name="icon">
                        <i class="bi bi-people-fill text-lg mr-1"></i>
                    </x-slot>
                    Laporan Pelanggan
                </x-sidebar-link>
            @endif
            @if(auth()->user()->hasRole('owner'))
                <x-sidebar-link :href="route('reports.income')" :active="request()->routeIs('reports.income')">
                    <x-slot name="icon">
                        <i class="bi bi-cash-stack text-lg mr-1"></i>
                    </x-slot>
                    Laporan Pendapatan
                </x-sidebar-link>
            @endif
        </ul>
    </div>
</nav>