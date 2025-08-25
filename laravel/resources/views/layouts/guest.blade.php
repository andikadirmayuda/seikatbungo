<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset(config('app.logo')) }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-pink min-h-screen flex items-center justify-center overflow-hidden">
    <div class="w-full h-screen flex items-center justify-center bg-pink">
        <div
            class="w-full max-w-md bg-pink rounded-sm shadow-lg p-6 sm:p-8 flex flex-col items-center mx-2 max-h-[95vh] overflow-auto">
            {{ $slot }}
        </div>
    </div>
</body>

</html>