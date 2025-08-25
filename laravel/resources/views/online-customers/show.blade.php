<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('online-customers.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                    <i class="bi bi-arrow-left text-xl"></i>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <i class="bi bi-person-fill text-pink-500 mr-2"></i>
                    {{ __('Detail Pelanggan Online') }} - {{ $customerData->customer_name }}
                </h2>
            </div>
            @if(!auth()->user()->hasRole(['customer service', 'karyawan']))
            <a href="{{ route('online-customers.edit', $customerData->wa_number) }}"
                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                <i class="bi bi-pencil mr-1"></i> Edit Pelanggan
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Customer Info Card -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Customer Details -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pelanggan</h3>

                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <i class="bi bi-person-fill text-gray-400 w-5 mr-3"></i>
                                        <div>
                                            <span class="text-sm text-gray-500">Nama:</span>
                                            <span class="ml-2 font-medium">{{ $customerData->customer_name }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <i class="bi bi-whatsapp text-green-500 w-5 mr-3"></i>
                                        <div>
                                            <span class="text-sm text-gray-500">WhatsApp:</span>
                                            <span class="ml-2 font-medium">{{ $customerData->wa_number }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <i class="bi bi-calendar text-gray-400 w-5 mr-3"></i>
                                        <div>
                                            <span class="text-sm text-gray-500">Bergabung:</span>
                                            <span
                                                class="ml-2 font-medium">{{ \Carbon\Carbon::parse($customerData->first_order_date)->format('d M Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <i class="bi bi-clock text-gray-400 w-5 mr-3"></i>
                                        <div>
                                            <span class="text-sm text-gray-500">Terakhir Pesan:</span>
                                            <span
                                                class="ml-2 font-medium">{{ \Carbon\Carbon::parse($customerData->last_order_date)->format('d M Y') }}</span>
                                        </div>
                                    </div>

                                    @if($customerData->customer)
                                        @if($customerData->customer->is_reseller)
                                            <div class="flex items-center">
                                                <i class="bi bi-star-fill text-yellow-500 w-5 mr-3"></i>
                                                <div>
                                                    <span class="text-sm text-gray-500">Status:</span>
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="bi bi-star-fill mr-1"></i>
                                                        Reseller
                                                    </span>
                                                </div>
                                            </div>
                                        @endif

                                        @if($customerData->customer->promo_discount)
                                            <div class="flex items-center">
                                                <i class="bi bi-gift-fill text-red-500 w-5 mr-3"></i>
                                                <div>
                                                    <span class="text-sm text-gray-500">Promo:</span>
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="bi bi-gift-fill mr-1"></i>
                                                        {{ $customerData->customer->promo_discount }}% diskon
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <!-- Statistics -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik</h3>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-blue-50 p-4 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-blue-600">{{ $customerData->total_orders }}
                                        </div>
                                        <div class="text-sm text-blue-500">Total Pesanan</div>
                                    </div>

                                    <div class="bg-green-50 p-4 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-green-600">Rp
                                            {{ number_format($customerData->total_spent, 0, ',', '.') }}</div>
                                        <div class="text-sm text-green-500">Total Belanja</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    @if(!auth()->user()->hasRole(['customer service', 'karyawan']))
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Set as Reseller -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-medium text-gray-800 mb-2">
                                    <i class="bi bi-star text-yellow-500 mr-2"></i>
                                    Tetapkan sebagai Reseller
                                </h4>
                                <p class="text-sm text-gray-600 mb-3">Berikan status reseller untuk pelanggan ini. Reseller dapat menggunakan kode reseller khusus.</p>

                                @if($customerData->customer && $customerData->customer->is_reseller)
                                    <div class="flex items-center justify-center py-2">
                                        <i class="bi bi-check-circle-fill text-green-500 mr-2"></i>
                                        <span class="text-sm text-green-600 font-medium">Sudah menjadi reseller</span>
                                    </div>
                                @else
                                    <form action="{{ route('online-customers.set-reseller', $customerData->wa_number) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm">
                                            Set sebagai Reseller
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Set Promo Discount -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-medium text-gray-800 mb-2">
                                    <i class="bi bi-gift text-red-500 mr-2"></i>
                                    Berikan Diskon Promo
                                </h4>
                                <p class="text-sm text-gray-600 mb-3">Berikan diskon khusus untuk pelanggan ini</p>

                                <form action="{{ route('online-customers.set-promo', $customerData->wa_number) }}"
                                    method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="number" name="promo_discount" placeholder="Diskon %" min="0" max="100"
                                        step="0.01"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 text-sm"
                                        required>
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
                                        Set Promo
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Reseller Code Management -->
                    @if($customerData->customer && $customerData->customer->is_reseller)
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="bi bi-key text-purple-500 mr-2"></i>
                                Kelola Kode Reseller
                                <span
                                    class="text-sm text-gray-500 ml-2">({{ isset($activeResellerCodes) ? $activeResellerCodes->count() : 0 }}
                                    aktif)</span>
                            </h3>

                            <!-- Generate New Code Form -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4 mb-4">
                                <h4 class="font-medium text-gray-800 mb-3">Generate Kode Baru</h4>

                                @if ($errors->any())
                                    <div class="mb-3 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                                        <ul class="text-sm">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('online-customers.generate-code', $customerData->wa_number) }}"
                                    method="POST" class="flex gap-3 items-end">
                                    @csrf
                                    <div class="flex-1">
                                        <label class="block text-sm text-gray-600 mb-1">Masa Berlaku (Jam)</label>
                                        <input type="number" name="expiry_hours" value="24" min="1" max="168"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm"
                                            required>
                                    </div>
                                    <div class="flex-2">
                                        <label class="block text-sm text-gray-600 mb-1">Catatan (Opsional)</label>
                                        <input type="text" name="notes" placeholder="Catatan untuk kode ini..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm">
                                    </div>
                                    <button type="submit"
                                        class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition text-sm">
                                        <i class="bi bi-plus-circle mr-1"></i>
                                        Generate
                                    </button>
                                </form>
                            </div>

                            <!-- Active Codes -->
                            @if(isset($activeResellerCodes) && $activeResellerCodes->count() > 0)
                                <div class="bg-white border border-gray-200 rounded-lg p-4 mb-4">
                                    <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                                        <i class="bi bi-check-circle text-green-500 mr-2"></i>
                                        Kode Aktif
                                    </h4>

                                    <div class="space-y-3">
                                        @foreach($activeResellerCodes as $code)
                                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                                <div>
                                                    <div class="font-mono text-lg font-bold text-green-700">{{ $code->code }}</div>
                                                    <div class="text-sm text-gray-600">
                                                        Berlaku hingga: {{ $code->expires_at->format('d M Y H:i') }}
                                                        ({{ $code->expires_at->diffForHumans() }})
                                                    </div>
                                                    @if($code->notes)
                                                        <div class="text-xs text-gray-500 mt-1">{{ $code->notes }}</div>
                                                    @endif
                                                </div>
                                                <form
                                                    action="{{ route('online-customers.revoke-code', [$customerData->wa_number, $code->id]) }}"
                                                    method="POST" onsubmit="return confirm('Yakin ingin membatalkan kode ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="flex space-x-2">
                                                        <!-- Share to WhatsApp Button -->
                                                        <a href="#" 
                                                           onclick="shareToWhatsApp('{{ $customerData->wa_number }}', '{{ $code->code }}', '{{ $code->expires_at->format('d M Y H:i') }}')"
                                                           class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700 transition flex items-center">
                                                            <i class="bi bi-whatsapp mr-1"></i>
                                                            Bagikan
                                                        </a>

                                                        <!-- Revoke Button -->
                                                        <button type="submit"
                                                            class="px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600 transition">
                                                            <i class="bi bi-x-circle mr-1"></i>
                                                        Batalkan
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Code History -->
                            @if(isset($resellerCodeHistory) && $resellerCodeHistory->count() > 0)
                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                                        <i class="bi bi-clock-history text-gray-500 mr-2"></i>
                                        Riwayat Kode
                                    </h4>

                                    <div class="space-y-2 max-h-64 overflow-y-auto">
                                        @foreach($resellerCodeHistory as $code)
                                            <div
                                                class="flex items-center justify-between p-2 border-b border-gray-100 last:border-b-0">
                                                <div>
                                                    <div
                                                        class="font-mono text-sm {{ $code->is_used ? 'text-gray-500' : ($code->isValid() ? 'text-green-600' : 'text-red-500') }}">
                                                        {{ $code->code }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Dibuat: {{ $code->created_at->format('d M Y H:i') }}
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    @if($code->is_used)
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600">
                                                            <i class="bi bi-check-circle mr-1"></i>
                                                            Digunakan
                                                        </span>
                                                        @if($code->used_at)
                                                            <div class="text-xs text-gray-400 mt-1">
                                                                {{ $code->used_at->format('d M Y H:i') }}</div>
                                                        @endif
                                                    @elseif($code->isValid())
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-600">
                                                            <i class="bi bi-circle mr-1"></i>
                                                            Aktif
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-100 text-red-600">
                                                            <i class="bi bi-x-circle mr-1"></i>
                                                            Expired
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Order History -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="bi bi-clock-history text-gray-600 mr-2"></i>
                            Riwayat Pesanan
                        </h3>

                        @if($orders->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kode Pesanan
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tanggal
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Metode Pengiriman
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Total
                                            </th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($orders as $order)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $order->public_code }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ ucfirst($order->delivery_method) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
        $statusColors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    Rp {{ number_format($order->amount_paid, 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <a href="{{ route('admin.public-orders.show', $order->id) }}"
                                                        class="text-blue-600 hover:text-blue-900 transition">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $orders->links() }}
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="bi bi-inbox text-4xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500">Belum ada riwayat pesanan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <i class="bi bi-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.fixed.bottom-4').remove();
            }, 5000);
        </script>
    @endif

    @if(session('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <i class="bi bi-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
        <script>
            setTimeout(() => {
                document.querySelectorAll('.fixed.bottom-4').forEach(el => el.remove());
            }, 5000);
        </script>
    @endif

    @if(session('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <i class="bi bi-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.fixed.bottom-4').remove();
            }, 5000);
        </script>
    @endif

    <script>
        function shareToWhatsApp(waNumber, code, expiryDate) {
            // Format nomor WhatsApp
            // Hapus karakter non-digit
            let formattedNumber = waNumber.replace(/\D/g, '');
            // Jika dimulai dengan '0', ganti dengan '62'
            if (formattedNumber.startsWith('0')) {
                formattedNumber = '62' + formattedNumber.substring(1);
            }
            // Jika belum ada kode negara, tambahkan '62'
            else if (!formattedNumber.startsWith('62')) {
                formattedNumber = '62' + formattedNumber;
            }

            // Format pesan
            const message = `*Kode Reseller - Seikat Bungo*\n\n` +
                          `Hallo! Berikut adalah kode reseller Anda:\n\n` +
                          `📱 No. WhatsApp: ${waNumber}\n` +
                          `🔑 Kode: ${code}\n` +
                          `⏰ Masa Aktif Kode: ${expiryDate}\n\n` +
                          `- Silakan gunakan kode ini saat melakukan pemesanan untuk mendapatkan harga khusus reseller.\n` +
                          `- Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.\n\n` +
                          `Terima kasih telah berbelanja di Seikat Bungo! 🌸😊🙏`;




            // Encode pesan untuk URL
            const encodedMessage = encodeURIComponent(message);
            
            // Buka WhatsApp dengan pesan yang sudah disiapkan
            window.open(`https://wa.me/${formattedNumber}?text=${encodedMessage}`, '_blank');
        }
    </script>
</x-app-layout>