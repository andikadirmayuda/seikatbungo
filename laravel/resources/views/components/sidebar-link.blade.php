@props(['active', 'href'])

@php
    $classes = ($active ?? false)
        ? 'flex items-center px-4 py-3 bg-gradient-to-r from-pink-50 to-pink-100 text-pink-700 border-r-4 border-pink-500 rounded-l-lg font-medium transition-all duration-200'
        : 'flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-pink-600 rounded-lg transition-all duration-200 group';
@endphp

<li>
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if (isset($icon))
            <div
                class="mr-3 {{ ($active ?? false) ? 'text-pink-600' : 'text-gray-400 group-hover:text-pink-500' }} transition-colors duration-200">
                {{ $icon }}
            </div>
        @endif
        <span class="font-medium">{{ $slot }}</span>
    </a>
</li>