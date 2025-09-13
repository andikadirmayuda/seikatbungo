<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg mr-3">
                    <i class="bi bi-receipt-cutoff text-blue-600 text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Detail Transaksi</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $sale->order_number }}</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('sales.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="bi bi-arrow-left mr-2"></i>
                    <span class="hidden sm:inline">Kembali</span>
                </a>

                @if($sale->wa_number)
                    <button onclick="shareToWhatsApp()" id="shareBtn"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 relative">
                        <i class="bi bi-whatsapp mr-2"></i>
                        <span class="hidden sm:inline">Share ke WhatsApp</span>
                        <span class="sm:hidden">Share WA</span>
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full animate-pulse"></span>
                    </button>
                @endif

                <a href="{{ route('sales.show', $sale->id) }}?print=1"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="bi bi-printer mr-2"></i>
                    <span class="hidden sm:inline">Print Struk</span>
                    <span class="sm:hidden">Print</span>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <div class="py-6 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Transaction Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="bi bi-info-circle mr-2 text-blue-600"></i>Informasi Transaksi
                    </h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="bi bi-hash text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">No. Transaksi
                                    </p>
                                    <p class="text-sm font-semibold text-blue-700">{{ $sale->order_number }}</p>
                                </div>
                            </div>

                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="bi bi-whatsapp text-green-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">No. WhatsApp
                                    </p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $sale->wa_number ?? '-' }}</p>
                                    @if($sale->wa_number)
                                        <p class="text-xs text-green-600 mt-1">
                                            <i class="bi bi-check-circle mr-1"></i>Dapat mengirim link struk
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="bi bi-calendar3 text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal &
                                        Waktu</p>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($sale->order_time)->format('d/m/Y') }}
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        {{ \Carbon\Carbon::parse($sale->order_time)->format('H:i') }} WIB
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="bi bi-credit-card text-orange-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Metode
                                        Pembayaran</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $sale->payment_method === 'transfer' ? 'bg-blue-100 text-blue-800' :
    ($sale->payment_method === 'cash' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($sale->payment_method) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div
                                class="flex items-center p-3 bg-gradient-to-r from-pink-50 to-rose-50 rounded-lg border border-pink-100">
                                <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="bi bi-cash-stack text-pink-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                                        Pembayaran</p>
                                    <p class="text-lg font-bold text-pink-600">
                                        Rp {{ number_format($sale->total, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            @if($sale->payment_method === 'cash' && $sale->cash_given)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="bi bi-arrow-return-left text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Kembalian</p>
                                        <p class="text-sm font-semibold text-green-600">
                                            Rp {{ number_format($sale->cash_given - $sale->total, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="bi bi-cart-check mr-2 text-green-600"></i>Item Penjualan
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Daftar produk yang dibeli</p>
                </div>

                <div class="overflow-hidden">
                    <!-- Desktop Table -->
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 via-gray-25 to-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Nama Produk</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Tipe Harga</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Harga</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($sale->items as $i => $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $i + 1 }}</td>
                                        <td class="px-6 py-4">
                                            @if($item->product)
                                                <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $item->product->code ?? '' }}</div>
                                            @else
                                                <div class="text-sm font-medium text-gray-900 italic text-red-600">Produk
                                                    Dihapus</div>
                                                <div class="text-xs text-gray-500">ID: {{ $item->product_id }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ method_exists($item, 'getPriceTypeDisplayAttribute') ? $item->price_type_display : ucfirst(str_replace('_', ' ', $item->price_type)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900">
                                            {{ $item->quantity }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900">
                                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden">
                        @foreach($sale->items as $i => $item)
                            <div class="border-b border-gray-200 last:border-b-0 p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-1">
                                            <span
                                                class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-xs font-medium text-gray-600 mr-2">
                                                {{ $i + 1 }}
                                            </span>
                                            @if($item->product)
                                                <h4 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h4>
                                            @else
                                                <h4 class="text-sm font-medium text-red-600 italic">Produk Dihapus</h4>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst(str_replace('_', ' ', $item->price_type)) }}
                                            </span>
                                            <span class="text-xs text-gray-500">Qty: {{ $item->quantity }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-900">
                                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @ Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Total Footer -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-pink-50 to-rose-50">
                    <div class="flex justify-between items-center">
                        <div class="text-sm font-medium text-gray-700">
                            Total Item: {{ $sale->items->count() }} produk
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Total Pembayaran</div>
                            <div class="text-xl font-bold text-pink-600">
                                Rp {{ number_format($sale->total, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($sale->wa_number)
        <script>

            function shareToWhatsApp() {
                // Ubah tampilan button sementara
                const shareBtn = document.getElementById('shareBtn');
                const originalContent = shareBtn.innerHTML;
                shareBtn.innerHTML = '<i class="bi bi-hourglass-split mr-2 animate-spin"></i><span class="hidden sm:inline">Membuka WhatsApp...</span><span class="sm:hidden">Loading...</span>';
                shareBtn.disabled = true;

                // Data transaksi
                const orderNumber = '{{ $sale->order_number }}';
                const total = 'Rp {{ number_format($sale->total, 0, ",", ".") }}';
                const orderDate = '{{ \Carbon\Carbon::parse($sale->order_time)->format("d/m/Y H:i") }}';
                const waNumber = '{{ $sale->wa_number }}';

                // Link public receipt menggunakan public_code
                const publicReceiptUrl = '{{ route("sales.public_receipt", $sale->public_code) }}';

                // Pesan WhatsApp
                const message = `Halo! Berikut adalah struk pembelian Anda:\n\n*No. Transaksi:* ${orderNumber}\n*Tanggal:* ${orderDate}\n*Total:* ${total}\n\n*Link Struk Digital:*\n\n${publicReceiptUrl}\n\nKlik link di atas untuk melihat detail struk pembelian Anda.\n\nTerima kasih telah berbelanja di Seikat Bungo! 🌸`;

                // Format nomor WhatsApp (hilangkan semua karakter non-digit kecuali angka)
                let cleanWaNumber = waNumber.replace(/[^\d]/g, '');
                if (cleanWaNumber.startsWith('0')) {
                    cleanWaNumber = '62' + cleanWaNumber.substring(1);
                } else if (!cleanWaNumber.startsWith('62')) {
                    // Jika user input tanpa 0 atau 62, anggap salah
                    showNotification('Format nomor WhatsApp harus diawali 08 atau 62', 'error');
                    shareBtn.innerHTML = originalContent;
                    shareBtn.disabled = false;
                    return;
                }
                // Validasi panjang nomor minimal 10 digit setelah 62
                if (!/^62\d{9,}$/.test(cleanWaNumber)) {
                    showNotification('Nomor WhatsApp tidak valid! Pastikan minimal 10 digit setelah 62.', 'error');
                    shareBtn.innerHTML = originalContent;
                    shareBtn.disabled = false;
                    return;
                }

                // Buka WhatsApp dengan pesan
                const whatsappUrl = `https://wa.me/${cleanWaNumber}?text=${encodeURIComponent(message)}`;
                window.open(whatsappUrl, '_blank');

                // Kembalikan button ke kondisi normal setelah 2 detik
                setTimeout(() => {
                    shareBtn.innerHTML = originalContent;
                    shareBtn.disabled = false;
                }, 2000);

                // Tampilkan notifikasi sukses
                showNotification('Link struk berhasil dibagikan ke WhatsApp!', 'success');
            }

            // Fungsi untuk menampilkan notifikasi
            function showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 ${type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'
                    }`;
                notification.innerHTML = `
                                                                            <div class="flex items-center">
                                                                                <i class="bi bi-check-circle-fill mr-2"></i>
                                                                                <span class="text-sm font-medium">${message}</span>
                                                                            </div>
                                                                        `;

                document.body.appendChild(notification);

                // Animasi masuk
                setTimeout(() => {
                    notification.style.transform = 'translateX(0)';
                }, 100);

                // Hapus notifikasi setelah 3 detik
                setTimeout(() => {
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 3000);
            }
        </script>
    @endif
</x-app-layout>