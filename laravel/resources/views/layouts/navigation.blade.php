<nav x-data="{ open: false }" class="bg-[#181f2a] text-white border-b border-[#181f2a] shadow-lg font-sans">
    <!-- Primary Navigation Menu -->

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center space-x-4">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <i class="bi bi-flower2 text-2xl text-pink-400 mr-2"></i>
                    <span class="font-bold text-lg text-white">Seikat Bungo</span>
                </a>
                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:flex ml-8">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <i class="bi bi-house-door-fill mr-1"></i> <span class="text-white">{{ __('Dashboard') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                        <i class="bi bi-cart-fill mr-1"></i> <span class="text-white">{{ __('Orders') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('order-histories.index')"
                        :active="request()->routeIs('order-histories.*')">
                        <i class="bi bi-clock-history mr-1"></i> <span
                            class="text-white">{{ __('Order History') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                        <i class="bi bi-people-fill mr-1"></i> <span class="text-white">{{ __('Customers') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        <i class="bi bi-box-seam mr-1"></i> <span class="text-white">{{ __('Products') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('bouquet.orders.index')" :active="request()->routeIs('bouquet.orders.*')">
                        <i class="bi bi-bag-heart mr-1"></i> <span class="text-white">Pemesanan Buket</span>
                    </x-nav-link>
                    <x-nav-link :href="route('bouquet.sales.index')" :active="request()->routeIs('bouquet.sales.*')">
                        <i class="bi bi-cash-coin mr-1"></i> <span class="text-white">Penjualan Buket</span>
                    </x-nav-link>
                    <x-nav-link :href="route('bouquets.index')" :active="request()->routeIs('bouquets.*')">
                        <i class="bi bi-flower1 mr-1"></i> <span class="text-white">Master Buket</span>
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-white bg-black hover:bg-gray-800 focus:outline-none transition ease-in-out duration-150 shadow">
                            <i class="bi bi-person-circle text-xl mr-2"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down ml-2"></i>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="bi bi-person-lines-fill mr-1"></i> {{ __('Profile') }}
                        </x-dropdown-link>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="bi bi-box-arrow-right mr-1"></i> {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                {{ __('Orders') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('order-histories.index')"
                :active="request()->routeIs('order-histories.*')">
                {{ __('Order History') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                {{ __('Customers') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                {{ __('Products') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('bouquet.orders.index')"
                :active="request()->routeIs('bouquet.orders.*')">
                Pemesanan Buket
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('bouquet.sales.index')" :active="request()->routeIs('bouquet.sales.*')">
                Penjualan Buket
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('bouquets.index')" :active="request()->routeIs('bouquets.*')">
                Master Buket
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>