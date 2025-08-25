<x-nav-link :href="route('order-histories.index')" :active="request()->routeIs('order-histories.*')">
    {{ __('Order History') }}
</x-nav-link>
