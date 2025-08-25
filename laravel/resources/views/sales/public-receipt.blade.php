<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Digital - {{ $sale->order_number }} | Seikat Bungo</title>

    <!-- Meta Tags for Social Sharing -->
    <meta name="description"
        content="Struk digital pembelian {{ $sale->order_number }} dari Seikat Bungo - Total: Rp {{ number_format($sale->total, 0, ',', '.') }}">
    <meta property="og:title" content="Struk Digital - {{ $sale->order_number }}">
    <meta property="og:description" content="Bukti pembelian digital dari Seikat Bungo">
    <meta property="og:image" content="{{ asset('logo-seikat-bungo.png') }}">
    <meta property="og:type" content="website">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo-seikat-bungo.png') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pink: {
                            50: '#fdf2f8',
                            100: '#fce7f3',
                            500: '#ec4899',
                            600: '#db2777',
                            700: '#be185d',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        @media print {
            body {
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .print-container {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 via-pink-50 to-rose-50 min-h-screen py-4 px-4 sm:py-8">

    <!-- Back Button (No Print) -->
    {{-- <div class="no-print max-w-3xl mx-auto mb-4">
        <button onclick="window.history.back()"
            class="inline-flex items-center px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            <i class="bi bi-arrow-left mr-2"></i>Kembali
        </button>
    </div> --}}

    <!-- Receipt Container -->
    <div class="max-w-3xl mx-auto">
        <div class="print-container bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

            <!-- Header Brand -->
            <div
                class="bg-gradient-to-r from-pink-50 via-rose-50 to-pink-50 px-6 py-8 text-center border-b border-pink-100">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('logo-seikat-bungo.png') }}" alt="Seikat Bungo Logo"
                        class="h-20 w-20 object-cover rounded-full border-2 border-pink-200 shadow-sm">
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Seikat Bungo</h1>
                <div class="w-10 h-0.5 bg-pink-400 mx-auto mb-3"></div>
                <div class="text-sm text-gray-600 leading-relaxed">
                    <p>Jl. Lunjuk Jaya No.5, Bukit Lama, Kec. Ilir Bar. I</p>
                    <p>Kota Palembang, Sumatera Selatan 30139</p>
                    <p class="mt-2">
                        <i class="bi bi-telephone mr-1"></i>0821-7792-9879 |
                        <i class="bi bi-instagram ml-2 mr-1"></i>@seikat.bungo
                    </p>
                </div>

                <!-- Digital Receipt Badge -->
                <div class="mt-4">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="bi bi-shield-check mr-1"></i>Struk Digital Resmi
                    </span>
                </div>
            </div>

            <!-- Transaction Info -->
            <div class="px-6 py-6 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="bi bi-info-circle mr-2 text-blue-600"></i>Informasi Transaksi
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-hash text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">No. Transaksi</p>
                                <p class="text-sm font-semibold text-blue-700">{{ $sale->order_number }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-calendar3 text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu
                                </p>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($sale->order_time)->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    {{ \Carbon\Carbon::parse($sale->order_time)->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-credit-card text-orange-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Pembayaran
                                </p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $sale->payment_method === 'transfer' ? 'bg-blue-100 text-blue-800' :
    ($sale->payment_method === 'cash' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($sale->payment_method) }}
                                </span>
                            </div>
                        </div>

                        <div
                            class="flex items-center p-3 bg-gradient-to-r from-pink-50 to-rose-50 rounded-lg border border-pink-100">
                            <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-cash-stack text-pink-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pembayaran
                                </p>
                                <p class="text-lg font-bold text-pink-600">
                                    Rp {{ number_format($sale->total, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Items Table -->
            <div class="px-6 py-6 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="bi bi-cart-check mr-2 text-green-600"></i>Item Pembelian
                </h2>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 via-gray-25 to-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-12">
                                    No
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Nama Produk
                                </th>
                                <th
                                    class="px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-32">
                                    Tipe Harga
                                </th>
                                <th
                                    class="px-4 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider w-32">
                                    Harga
                                </th>
                                <th
                                    class="px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-16">
                                    Jumlah
                                </th>
                                <th
                                    class="px-4 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider w-36">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($sale->items as $i => $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 text-center text-sm text-gray-600 font-medium">
                                        {{ $i + 1 }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->product)
                                            <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                            @if($item->product->code)
                                                <div class="text-xs text-gray-500 mt-1">{{ $item->product->code }}</div>
                                            @endif
                                        @else
                                            <div class="text-sm font-medium text-gray-900 italic text-red-600">Produk Dihapus
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">ID: {{ $item->product_id }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst(str_replace('_', ' ', $item->price_type)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right text-sm text-gray-900 font-medium">
                                        <span class="whitespace-nowrap">Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-center text-sm text-gray-900 font-medium">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-4 py-4 text-right text-sm font-semibold text-gray-900">
                                        <span class="whitespace-nowrap">Rp
                                            {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden space-y-3">
                    @foreach($sale->items as $i => $item)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <!-- Header dengan nomor dan nama produk -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <span
                                        class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-xs font-semibold text-blue-700 mr-3">
                                        {{ $i + 1 }}
                                    </span>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900">
                                            @if($item->product)
                                                {{ $item->product->name }}
                                                @if($item->product->code)
                                                    <span class="text-xs text-gray-500 font-normal"> |
                                                        {{ $item->product->code }}</span>
                                                @endif
                                            @else
                                                <span class="italic text-red-600">Produk Dihapus</span>
                                                <span class="text-xs text-gray-500 font-normal"> | ID:
                                                    {{ $item->product_id }}</span>
                                            @endif
                                        </h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail informasi dalam grid -->
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Tipe Harga:</span>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $item->price_type)) }}
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500">Jumlah:</span>
                                    <span class="font-medium text-gray-900">{{ $item->quantity }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500">Harga:</span>
                                    <span class="font-medium text-gray-900">Rp
                                        {{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500">Subtotal:</span>
                                    <span class="font-semibold text-pink-600">Rp
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Payment Summary -->
            <div class="px-4 sm:px-6 py-6 bg-gradient-to-r from-pink-50 to-rose-50">
                <div class="space-y-4">
                    <!-- Total -->
                    <div class="p-4 bg-white rounded-lg shadow-sm border border-pink-100">
                        <div
                            class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 text-center sm:text-left">
                            <div class="flex items-center justify-center sm:justify-start">
                                <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="bi bi-calculator text-pink-600 text-sm"></i>
                                </div>
                                <span class="text-base sm:text-lg font-semibold text-gray-700">Total Pembayaran</span>
                            </div>
                            <span class="text-lg sm:text-xl font-bold text-pink-600">
                                Rp {{ number_format($sale->total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    @if($sale->payment_method === 'cash' && $sale->cash_given)
                        <!-- Cash Given -->
                        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                            <div
                                class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 text-center sm:text-left">
                                <div class="flex items-center justify-center sm:justify-start">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="bi bi-cash text-green-600 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Uang Diterima</span>
                                </div>
                                <span class="text-base sm:text-lg font-semibold text-green-700">
                                    Rp {{ number_format($sale->cash_given, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Change -->
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div
                                class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 text-center sm:text-left">
                                <div class="flex items-center justify-center sm:justify-start">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="bi bi-arrow-return-left text-blue-600 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Kembalian</span>
                                </div>
                                <span class="text-base sm:text-lg font-semibold text-blue-700">
                                    Rp {{ number_format($sale->cash_given - $sale->total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="px-4 sm:px-6 py-6 text-center border-t border-gray-200 bg-gray-50">
                <div class="space-y-4">
                    <div class="text-center mb-4">
                        <div class="flex items-center justify-center mb-2">
                            <i class="bi bi-heart-fill text-pink-600 text-xl mr-2"></i>
                            <p class="text-lg font-semibold text-gray-900">Terima Kasih!</p>
                        </div>
                        <p class="text-sm text-gray-600">Telah berbelanja di Seikat Bungo</p>
                    </div>

                    <div class="text-sm text-gray-600 space-y-2">
                        <p class="font-medium">Butuh bantuan?</p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4">
                            <a href="tel:082177929879"
                                class="inline-flex items-center text-blue-600 hover:text-blue-700">
                                <i class="bi bi-telephone mr-1"></i>0821-7792-9879
                            </a>
                            <a href="https://instagram.com/seikat.bungo" target="_blank"
                                class="inline-flex items-center text-pink-600 hover:text-pink-700">
                                <i class="bi bi-instagram mr-1"></i>@seikat.bungo
                            </a>
                        </div>
                    </div>

                    <!-- Print Button -->
                    <div class="no-print mt-6">
                        <button onclick="window.print()"
                            class="inline-flex items-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
                            <i class="bi bi-printer mr-2"></i>Cetak Struk
                        </button>
                    </div>

                    <!-- Digital Receipt Info -->
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="flex items-center justify-center text-yellow-800">
                            <i class="bi bi-info-circle-fill mr-2"></i>
                            <span class="text-xs">Struk digital ini sah dan dapat digunakan sebagai bukti
                                pembelian</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>