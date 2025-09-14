<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="bg-pink-100 p-2 rounded-full">
                <svg class="w-6 h-6 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan</h1>
                <p class="text-sm text-gray-600">Kelola informasi pesanan pelanggan</p>
            </div>
        </div>
    </x-slot>
    @php
// Base steps untuk flow normal pesanan
$baseSteps = [
    'pending' => 'Pesanan Diterima',
    'processed' => 'Diproses',
    'packing' => 'Dikemas',
    'ready' => 'Pesanan Sudah Siap',
    'shipped' => 'Dikirim',
    'completed' => 'Selesai',
];

// Tambahkan status dibatalkan hanya jika pesanan dibatalkan
$steps = $baseSteps;
if (in_array(strtolower($order->status), ['cancelled', 'canceled'])) {
    $steps['cancelled'] = 'Dibatalkan';
}

$statusMap = [
    'pending' => 'pending',
    'processed' => 'processed',
    'processing' => 'processed', // alias lama ke baru
    'packing' => 'packing',
    'ready' => 'ready',
    'shipped' => 'shipped',
    'completed' => 'completed',
    'done' => 'completed', // alias lama ke baru
    'cancelled' => 'cancelled',
    'canceled' => 'cancelled',
];
$currentStatus = strtolower($order->status);
$currentStatus = $statusMap[$currentStatus] ?? $currentStatus;
$stepKeys = array_keys($steps);
$currentIndex = array_search($currentStatus, $stepKeys);

// Handle untuk status dibatalkan
if (in_array(strtolower($order->status), ['cancelled', 'canceled'])) {
    $currentIndex = count($baseSteps); // Set ke indeks terakhir setelah semua status normal
}
    @endphp
    <div class="max-w-full mx-auto py-4 px-3 sm:py-6 sm:px-6 lg:max-w-6xl lg:px-8">
        <!-- Navigation Back -->
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('admin.public-orders.index') }}"
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200 w-full sm:w-auto justify-center sm:justify-start">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Pesanan
            </a>
        </div>

        <!-- Customer Information Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
            <div class="flex items-center mb-4">
                <div class="bg-pink-100 p-2 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Informasi Pelanggan</h2>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <div class="space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-sm font-medium text-gray-500 sm:w-32 mb-1 sm:mb-0">Nama Pemesan:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $order->customer_name }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-sm font-medium text-gray-500 sm:w-32 mb-1 sm:mb-0">WA Pemesan:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $order->wa_number }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-sm font-medium text-gray-500 sm:w-32 mb-1 sm:mb-0">Nama Penerima:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $order->receiver_name ?: '-' }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-sm font-medium text-gray-500 sm:w-32 mb-1 sm:mb-0">WA Penerima:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $order->receiver_wa ?: '-' }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-sm font-medium text-gray-500 sm:w-32 mb-1 sm:mb-0">Tanggal:</span>
                        <div>
                            <span class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($order->pickup_date)->format('d-m-Y') }}</span>
                            <span class="text-sm text-rose-600 ml-2">({{ \Carbon\Carbon::parse($order->pickup_date)->locale('id')->dayName }})</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-sm font-medium text-gray-500 sm:w-32 mb-1 sm:mb-0">Waktu:</span>
                        <div>
                            <span class="text-sm font-semibold text-gray-900">{{ $order->pickup_time }}</span>
                            @php
$hour = (int) substr($order->pickup_time, 0, 2);
$timeOfDay = match (true) {
    $hour >= 5 && $hour < 11 => 'Pagi',
    $hour >= 11 && $hour < 15 => 'Siang',
    $hour >= 15 && $hour < 18 => 'Sore',
    default => 'Malam'
};
                            @endphp
                            <span class="text-sm text-blue-600 ml-2">({{ $timeOfDay }})</span>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-sm font-medium text-gray-500 sm:w-32 mb-1 sm:mb-0">Metode:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $order->delivery_method }}</span>
                    </div>
                    <!-- Update Metode Pembayaran (Admin Only) -->
                    <div class="flex flex-col sm:flex-row sm:items-center mt-2">
                        <span class="text-sm font-medium text-gray-500 sm:w-32 mb-1 sm:mb-0">Metode Pembayaran:</span>
                        <form method="POST" action="{{ route('admin.public-orders.update-payment-method', $order->id) }}" class="flex items-center gap-2">
                            @csrf
                            <div class="relative">
                                <select name="payment_method" class="text-base px-4 py-2 border-2 border-pink-400 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200 bg-white appearance-none w-48 font-semibold text-gray-700 hover:border-pink-500 cursor-pointer custom-select-arrow">
                                    <option value="" disabled {{ !$order->payment_method ? 'selected' : '' }} class="text-gray-400">Pilih metode</option>
                                    <option value="cash" {{ $order->payment_method === 'cash' ? 'selected' : '' }}>💵 Cash</option>
                                    <option value="transfer" {{ $order->payment_method === 'transfer' ? 'selected' : '' }}>🏦 Transfer</option>
                                    <option value="debit" {{ $order->payment_method === 'debit' ? 'selected' : '' }}>💳 Debit</option>
                                    <option value="e-wallet" {{ $order->payment_method === 'e-wallet' ? 'selected' : '' }}>📱 E-Wallet</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-pink-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                            <button type="submit" class="ml-2 bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-pink-400">Update</button>
                        </form>
                        @if($order->payment_method)
                            <span class="ml-2 text-xs text-gray-600">Terakhir: <b>{{ ucfirst($order->payment_method) }}</b></span>
                        @endif
                    </div>
                    @if($order->shipping_fee > 0)
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <span class="text-sm font-medium text-gray-500 sm:w-32 mb-1 sm:mb-0">Ongkir:</span>
                            <span class="text-sm font-semibold text-red-600">Rp{{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <style>
                        /* Custom select arrow for better appearance */
                        .custom-select-arrow {
                            background-image: none;
                        }
                        .custom-select-arrow::-ms-expand {
                            display: none;
                        }
                        .custom-select-arrow option {
                            font-size: 1rem;
                            padding: 0.5rem 1rem;
                        }
                    </style>
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-sm font-medium text-gray-500 sm:w-32 mb-1 sm:mb-0">Tujuan:</span>
                        <span class="text-sm font-semibold text-gray-900 break-words">{{ $order->destination }}</span>
                    </div>
                </div>
            </div>
            @if(!empty($order->notes))
                <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-lg">
                    <div class="flex items-start">
                        <div class="bg-amber-100 p-2 rounded-lg mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-amber-800 mb-2">Catatan Pesanan</h4>
                            <p class="text-amber-700 text-sm whitespace-pre-wrap break-words">{{ $order->notes }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Status Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
            <div class="flex items-center mb-4 sm:mb-6">
                <div class="bg-pink-100 p-2 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Status Pesanan</h3>
            </div>

            @if (in_array(strtolower($order->status), ['cancelled', 'canceled']))
                <!-- Layout khusus untuk pesanan dibatalkan -->
                <div class="flex justify-center mb-4 sm:mb-6">
                    <div class="bg-red-100 border-2 border-red-300 rounded-lg p-3 sm:p-4 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <i class="bi bi-x-circle-fill text-red-500 text-xl sm:text-2xl"></i>
                            <span class="text-red-700 font-semibold text-base sm:text-lg">Pesanan Dibatalkan</span>
                        </div>
                    </div>
                </div>
            @else
                <!-- Progress bar untuk pesanan normal -->
                <!-- Desktop: Layout horizontal -->
                <div class="hidden sm:flex w-full justify-between mb-4 sm:mb-6 overflow-x-auto pb-2">
                    @foreach($baseSteps as $key => $label)
                        @php
        $index = array_search($key, array_keys($baseSteps));
        $isCompleted = $index <= $currentIndex;
        $isCurrent = $index == $currentIndex;
                        @endphp
                        <div class="flex-1 flex flex-col items-center min-w-0 px-1">
                            <div
                                class="rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center mb-2 transition-all duration-300 {{ $isCompleted ? 'bg-pink-600 text-white shadow-lg' : 'bg-gray-200 text-gray-500' }} flex-shrink-0">
                                @if($isCompleted)
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <span class="text-xs sm:text-sm">{{ $index + 1 }}</span>
                                @endif
                            </div>
                            <div
                                class="text-xs text-center px-1 leading-tight {{ $isCompleted ? 'text-pink-600 font-semibold' : 'text-gray-500' }}">
                                {{ $label }}
                            </div>
                        </div>
                        @if(!$loop->last)
                            <div class="flex-1 h-1 bg-gray-200 mx-1 sm:mx-2 mt-3 sm:mt-4 rounded-full overflow-hidden">
                                @if($index < $currentIndex)
                                    <div class="h-full bg-pink-600 rounded-full"></div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Mobile: Layout grid 2 baris (1-2-3 di atas, 4-5-6 di bawah) -->
                <div class="sm:hidden mb-4">
                    @php
    $stepsArray = array_keys($baseSteps);
    $labelsArray = array_values($baseSteps);
    $firstRow = array_slice($stepsArray, 0, 3);
    $secondRow = array_slice($stepsArray, 3, 3);
    $firstRowLabels = array_slice($labelsArray, 0, 3);
    $secondRowLabels = array_slice($labelsArray, 3, 3);
                    @endphp
                    
                    <!-- Baris pertama: Step 1, 2, 3 -->
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        @foreach($firstRow as $rowIndex => $key)
                            @php
        $index = array_search($key, $stepsArray);
        $isCompleted = $index <= $currentIndex;
        $isCurrent = $index == $currentIndex;
        $label = $firstRowLabels[$rowIndex];
                            @endphp
                            <div class="flex flex-col items-center">
                                <div
                                    class="rounded-full w-10 h-10 flex items-center justify-center mb-2 transition-all duration-300 {{ $isCompleted ? 'bg-pink-600 text-white shadow-lg' : 'bg-gray-200 text-gray-500' }}">
                                    @if($isCompleted)
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <span class="text-sm font-medium">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <div
                                    class="text-xs text-center leading-tight {{ $isCompleted ? 'text-pink-600 font-semibold' : 'text-gray-500' }}">
                                    {{ $label }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Baris kedua: Step 4, 5, 6 (jika ada) -->
                    @if(count($secondRow) > 0)
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($secondRow as $rowIndex => $key)
                                @php
            $index = array_search($key, $stepsArray);
            $isCompleted = $index <= $currentIndex;
            $isCurrent = $index == $currentIndex;
            $label = $secondRowLabels[$rowIndex];
                                @endphp
                                <div class="flex flex-col items-center">
                                    <div
                                        class="rounded-full w-10 h-10 flex items-center justify-center mb-2 transition-all duration-300 {{ $isCompleted ? 'bg-pink-600 text-white shadow-lg' : 'bg-gray-200 text-gray-500' }}">
                                        @if($isCompleted)
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <span class="text-sm font-medium">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                    <div
                                        class="text-xs text-center leading-tight {{ $isCompleted ? 'text-pink-600 font-semibold' : 'text-gray-500' }}">
                                        {{ $label }}
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Fill empty cells if less than 3 items in second row -->
                            @for($i = count($secondRow); $i < 3; $i++)
                                <div class="flex flex-col items-center">
                                    <!-- Empty cell -->
                                </div>
                            @endfor
                        </div>
                    @endif
                </div>
            @endif

            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Status Saat Ini:</span>
                    <span
                        class="px-3 py-1 rounded-full text-sm font-semibold {{ $currentStatus === 'completed' ? 'bg-green-100 text-green-800' : ($currentStatus === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-pink-100 text-pink-800') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            @if(!in_array($order->status, ['completed', 'done', 'cancelled', 'canceled']))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4">
                    <h4 class="font-semibold text-blue-800 mb-3">Update Status Pesanan</h4>
                    <form method="POST" action="{{ route('admin.public-orders.update-status', $order->id) }}"
                        class="space-y-4" enctype="multipart/form-data" id="statusForm">
                        @csrf
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                            <select name="status"
                                class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-pink-500 focus:border-pink-500"
                                id="statusSelect">
                                @foreach($baseSteps as $key => $label)
                                    <option value="{{ $key }}" @if($currentStatus == $key) selected @endif>{{ $label }}</option>
                                @endforeach
                                <!-- Opsi untuk membatalkan pesanan -->
                                <option value="cancelled">Batalkan Pesanan</option>
                            </select>
                            <button type="submit"
                                class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                Update Status
                            </button>
                        </div>

                        <!-- File Upload Section for Packing -->
                        <div id="packingFilesSection" class="hidden space-y-3">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                                <div class="text-center">
                                    <div class="mt-2">
                                        <label for="packingFilesInput" class="cursor-pointer">
                                            <span class="mt-2 block text-sm font-medium text-gray-900">
                                                📸 Upload Foto & Video Packing
                                            </span>
                                            <span class="block text-xs text-gray-500 mt-1">
                                                Pilih gambar atau video. Bisa lebih dari 1 file.
                                            </span>
                                        </label>
                                        <input type="file" name="packing_files[]" id="packingFilesInput" multiple
                                            accept="image/*,video/*"
                                            class="mt-2 border border-gray-300 rounded-lg px-3 py-2 w-full" />
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">
                                        Maks 10MB per file, maksimal 10 file
                                    </p>
                                </div>
                            </div>

                            <!-- Simple File Counter -->
                            <div id="fileCounter" class="hidden">
                                <p class="text-sm text-gray-600">
                                    <span id="fileCount">0</span> file dipilih
                                </p>
                            </div>
                        </div>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const statusSelect = document.getElementById('statusSelect');
                            const packingFilesSection = document.getElementById('packingFilesSection');
                            const packingFilesInput = document.getElementById('packingFilesInput');
                            const fileCounter = document.getElementById('fileCounter');
                            const fileCount = document.getElementById('fileCount');

                            function togglePackingFiles() {
                                if (statusSelect.value === 'packing') {
                                    packingFilesSection.classList.remove('hidden');
                                } else {
                                    packingFilesSection.classList.add('hidden');
                                    if (packingFilesInput) {
                                        packingFilesInput.value = '';
                                    }
                                    if (fileCounter) {
                                        fileCounter.classList.add('hidden');
                                    }
                                }
                            }

                            // File input change handler
                            if (packingFilesInput) {
                                packingFilesInput.addEventListener('change', function (e) {
                                    const files = e.target.files;

                                    if (files.length > 0) {
                                        // Validasi jumlah file
                                        if (files.length > 10) {
                                            alert('Maksimal 10 file yang dapat diupload');
                                            e.target.value = '';
                                            return;
                                        }

                                        // Validasi ukuran file
                                        const maxSize = 10 * 1024 * 1024; // 10MB
                                        let hasOversizedFile = false;

                                        for (let i = 0; i < files.length; i++) {
                                            if (files[i].size > maxSize) {
                                                alert(`File "${files[i].name}" terlalu besar. Maksimal 10MB per file.`);
                                                hasOversizedFile = true;
                                                break;
                                            }
                                        }

                                        if (hasOversizedFile) {
                                            e.target.value = '';
                                            return;
                                        }

                                        // Update counter
                                        if (fileCount) {
                                            fileCount.textContent = files.length;
                                        }
                                        if (fileCounter) {
                                            fileCounter.classList.remove('hidden');
                                        }
                                    } else {
                                        if (fileCounter) {
                                            fileCounter.classList.add('hidden');
                                        }
                                    }
                                });
                            }

                            // Status select change handler
                            if (statusSelect) {
                                statusSelect.addEventListener('change', togglePackingFiles);
                                togglePackingFiles(); // Initial call
                            }
                        });
                    </script>
                </div>
            @endif
        </div>

        <!-- Payment Status Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center mb-6">
                <div class="bg-green-100 p-2 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Status Pembayaran</h3>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                @php
$paymentStatusMap = [
    'waiting_confirmation' => 'Menunggu Konfirmasi Stok',
    'ready_to_pay' => 'Siap Dibayar',
    'waiting_payment' => 'Menunggu Pembayaran',
    'waiting_verification' => 'Menunggu Verifikasi Pembayaran',
    'dp_paid' => 'DP (Uang Muka)',
    'partial_paid' => 'Sebagian Terbayar',
    'paid' => 'Lunas',
    'rejected' => 'Pembayaran Ditolak',
    'cancelled' => 'Dibatalkan',
];
$paymentBg = match ($order->payment_status) {
    'paid' => 'bg-green-100 text-green-800 border-green-200',
    'ready_to_pay' => 'bg-orange-100 text-orange-800 border-orange-200',
    'waiting_confirmation' => 'bg-gray-100 text-gray-800 border-gray-200',
    'waiting_payment' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'waiting_verification' => 'bg-blue-100 text-blue-800 border-blue-200',
    'dp_paid' => 'bg-purple-100 text-purple-800 border-purple-200',
    'partial_paid' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
    'rejected' => 'bg-red-100 text-red-800 border-red-200',
    'cancelled' => 'bg-gray-100 text-gray-800 border-gray-200',
    default => 'bg-gray-100 text-gray-800 border-gray-200',
};
                @endphp
                <span
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border {{ $paymentBg }}">
                    @if($order->payment_status === 'paid')
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    @endif
                    {{ $paymentStatusMap[$order->payment_status] ?? ucfirst($order->payment_status) }}
                </span>
                @if(!in_array($order->payment_status, ['paid', 'rejected', 'cancelled']) && !in_array($order->status, ['cancelled', 'completed', 'done']))
                    <div class="w-full mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-800 mb-3">Update Status Pembayaran</h4>
                        <form method="POST" action="{{ route('admin.public-orders.update-payment-status', $order->id) }}"
                            class="space-y-3" enctype="multipart/form-data" id="paymentStatusForm">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                <select name="payment_status"
                                    class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-pink-500 focus:border-pink-500"
                                    id="paymentStatusSelect">
                                    @foreach($paymentStatusMap as $key => $label)
                                        <option value="{{ $key }}" @if($order->payment_status == $key) selected @endif>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                <input type="number" name="amount_paid" id="amountPaidInput"
                                    class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-pink-500 focus:border-pink-500"
                                    style="display:none;" placeholder="Nominal bayar" min="0" step="1000">
                                <input type="file" name="payment_proof" id="paymentProofInput"
                                    accept="image/*,application/pdf"
                                    class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-pink-500 focus:border-pink-500"
                                    style="display:none;">
                            </div>
                            <small id="totalHelper" class="text-gray-600 text-sm block" style="display:none;">
                                @php 
                                    $totalForHelper = $order->items->sum(function ($item) {
        return ($item->price ?? 0) * ($item->quantity ?? 0);
    });
    $totalWithShipping = $totalForHelper + ($order->shipping_fee ?? 0);
                                @endphp
                                Total Produk: Rp{{ number_format($totalForHelper, 0, ',', '.') }}
                                @if($order->shipping_fee > 0)
                                    <br>Ongkir: Rp{{ number_format($order->shipping_fee, 0, ',', '.') }}
                                    <br><strong>Total Keseluruhan: Rp{{ number_format($totalWithShipping, 0, ',', '.') }}</strong>
                                @endif
                            </small>
                            <button type="submit"
                                class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                Update Status Pembayaran
                            </button>
                        </form>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const paymentStatusSelect = document.getElementById('paymentStatusSelect');
                                const paymentProofInput = document.getElementById('paymentProofInput');
                                const amountPaidInput = document.getElementById('amountPaidInput');
                                const totalHelper = document.getElementById('totalHelper');
                                const paymentStatusForm = document.getElementById('paymentStatusForm');
                                function togglePaymentInputs() {
                                    const val = paymentStatusSelect.value;
                                    if (["paid", "dp_paid", "partial_paid"].includes(val)) {
                                        paymentProofInput.style.display = '';
                                        paymentProofInput.required = true;
                                        totalHelper.style.display = '';

                                        if (val === 'paid') {
                                            amountPaidInput.style.display = '';
                                            amountPaidInput.required = false;
                                            amountPaidInput.placeholder = 'Otomatis = total pesanan';
                                        } else {
                                            amountPaidInput.style.display = '';
                                            amountPaidInput.required = true;
                                            if (val === 'dp_paid') {
                                                amountPaidInput.placeholder = 'Nominal DP';
                                            } else {
                                                amountPaidInput.placeholder = 'Nominal bayar';
                                            }
                                        }
                                    } else {
                                        paymentProofInput.style.display = 'none';
                                        paymentProofInput.required = false;
                                        amountPaidInput.style.display = 'none';
                                        amountPaidInput.required = false;
                                        totalHelper.style.display = 'none';
                                    }
                                }
                                paymentStatusSelect.addEventListener('change', togglePaymentInputs);
                                togglePaymentInputs();
                                paymentStatusForm.addEventListener('submit', function (e) {
                                    const val = paymentStatusSelect.value;
                                    if (["paid", "dp_paid", "partial_paid"].includes(val)) {
                                        if (!paymentProofInput.value) {
                                            alert('Silakan pilih file bukti pembayaran sebelum submit!');
                                            paymentProofInput.focus();
                                            e.preventDefault();
                                            return;
                                        }

                                        if (val !== 'paid' && (!amountPaidInput.value || amountPaidInput.value <= 0)) {
                                            alert('Silakan masukkan nominal pembayaran yang valid!');
                                            amountPaidInput.focus();
                                            e.preventDefault();
                                            return;
                                        }
                                    }
                                });
                            });
                        </script>
                    </div>
                @endif
            </div>
        </div>

        <!-- Update Ongkir Card -->
        @php
$needsShippingFee = in_array($order->delivery_method, [
    'Gosend (Pesan Dari Toko)',
    'Gocar (Pesan Dari Toko)'
]);
        @endphp
        @if($needsShippingFee)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center mb-6">
                    <div class="bg-orange-100 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Update Ongkir</h3>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-blue-800 text-sm font-medium">
                            Metode pengiriman "{{ $order->delivery_method }}" memerlukan ongkir yang bisa diatur admin.
                        </span>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.public-orders.update-shipping-fee', $order->id) }}" class="space-y-4">
                    @csrf
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <label class="text-sm font-medium text-gray-700 sm:w-24">Ongkir:</label>
                        <input type="number" name="shipping_fee" min="0" step="1000" 
                               value="{{ $order->shipping_fee ?? 0 }}"
                               class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 w-full sm:w-48"
                               placeholder="Masukkan biaya ongkir">
                        <button type="submit"
                                class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 w-full sm:w-auto">
                            Update Ongkir
                        </button>
                    </div>
                    <small class="text-gray-600 text-sm block">
                        Ongkir akan ditambahkan ke total pesanan. Current ongkir: Rp{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }}
                    </small>
                </form>
            </div>
        @endif

        <!-- Products Ordered Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center mb-6">
                <div class="bg-purple-100 p-2 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM9 9a1 1 0 012 0v4a1 1 0 11-2 0V9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900">Produk Dipesan</h2>
            </div>

            <div class="overflow-hidden border border-gray-200 rounded-lg mb-6 hidden sm:block">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipe Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga Satuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Satuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    @php
    // Clean product name dari duplikasi komponen
    $cleanName = preg_replace('/\s*\(Komponen:.*?\)\s*/', '', $item->product_name);
    $cleanName = trim($cleanName) ?: $item->product_name;
                                    @endphp
                                    {{ $cleanName }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->price_type ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->unit_equivalent ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    Rp{{ number_format(($item->price ?? 0) * ($item->quantity ?? 0), 0, ',', '.') }}
                                </td>
                            </tr>
                            @if(!empty($item->greeting_card))
                            <tr>
                                <td colspan="6" class="px-6 pb-4 pt-0 align-top">
                                    <div class="mt-2 p-3 bg-pink-50 border border-pink-200 rounded-lg flex items-start gap-2">
                                        <i class="bi bi-card-text text-pink-600 text-lg mt-0.5"></i>
                                        <div>
                                            <span class="font-semibold text-pink-700">Kartu Ucapan:</span>
                                            <span class="text-pink-800 italic whitespace-pre-wrap" style="word-break: break-all;">{{ $item->greeting_card }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile: Card Layout -->
            <div class="sm:hidden space-y-3 mb-6">
                @foreach($order->items as $item)
                    @php 
                        $subtotal = ($item->price ?? 0) * ($item->quantity ?? 0);
    $cleanName = preg_replace('/\s*\(Komponen:.*?\)\s*/', '', $item->product_name);
    $cleanName = trim($cleanName) ?: $item->product_name;
                    @endphp
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <!-- Product Header -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-3 py-3 border-b border-gray-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0 pr-2">
                                    <h3 class="font-semibold text-gray-800 text-sm leading-relaxed break-words whitespace-normal">
                                        {{ $cleanName }}
                                    </h3>
                                    @if($item->price_type && $item->price_type !== '-')
                                        <span class="inline-block mt-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-md font-medium">
                                            {{ $item->price_type }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if(!empty($item->greeting_card))
                            <div class="p-3 pt-2 pb-0">
                                <div class="p-3 bg-pink-50 border border-pink-200 rounded-lg flex items-start gap-2">
                                    <i class="bi bi-card-text text-pink-600 text-lg mt-0.5"></i>
                                    <div>
                                        <span class="font-semibold text-pink-700">Kartu Ucapan:</span>
                                        <span class="text-pink-800 italic whitespace-pre-wrap" style="word-break: break-all;">{{ $item->greeting_card }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Product Details -->
                        <div class="p-3">
                            <!-- Harga dan Satuan -->
                            <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                                <div class="flex-1">
                                    <span class="text-gray-500 text-xs">Harga Satuan</span>
                                    <p class="text-gray-800 text-sm font-semibold">Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex-1 text-right">
                                    <span class="text-gray-500 text-xs">Satuan</span>
                                    <p class="text-gray-800 text-sm font-semibold">{{ $item->unit_equivalent ?? '-' }}</p>
                                </div>
                            </div>
                            <!-- Jumlah dan Subtotal -->
                            <div class="flex justify-between items-center py-1.5">
                                <div class="flex-1">
                                    <span class="text-gray-500 text-xs">Jumlah</span>
                                    <p class="text-gray-800 text-sm font-semibold">{{ $item->quantity }}</p>
                                </div>
                                <div class="flex-1 text-right">
                                    <span class="text-gray-500 text-xs">Subtotal</span>
                                    <p class="text-green-600 text-sm font-bold">Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Custom Bouquet Detail Section -->
            @foreach($order->items as $item)
                @if($item->custom_bouquet_id)
                    <x-custom-bouquet-order-detail :item="$item" />
                @endif
            @endforeach

            @php
$totalOrder = $order->items->sum(function ($item) {
    return ($item->price ?? 0) * ($item->quantity ?? 0);
});
$shippingFee = $order->shipping_fee ?? 0;
$voucherAmount = $order->voucher_amount ?? 0;
$grandTotal = $totalOrder + $shippingFee - $voucherAmount;
$totalPaid = $order->amount_paid ?? 0;
$sisaPembayaran = $order->payment_status === 'paid' ? 0 : max($grandTotal - $totalPaid, 0);
            @endphp

            <!-- Mobile Payment Summary Card -->
            <div class="sm:hidden bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-pink-600 to-pink-700 px-3 py-2.5">
                    <h3 class="text-white font-semibold text-sm flex items-center">
                        <i class="bi bi-calculator mr-2"></i>
                        Ringkasan Pembayaran
                    </h3>
                </div>
                <div class="p-3 space-y-2">
                    <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">Total Produk</span>
                        <span class="text-green-600 text-sm font-bold">Rp{{ number_format($totalOrder, 0, ',', '.') }}</span>
                    </div>
                    @if($shippingFee > 0)
                        <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                            <span class="text-gray-600 text-sm">Ongkir</span>
                            <span class="text-orange-600 text-sm font-bold">Rp{{ number_format($shippingFee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($voucherAmount > 0)
                        <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                            <span class="text-gray-600 text-sm">Voucher</span>
                            <span class="text-purple-600 text-sm font-bold">-Rp{{ number_format($voucherAmount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center py-1.5 border-b border-gray-100 bg-gray-50 -mx-3 px-3">
                        <span class="text-gray-700 text-sm font-semibold">Total Keseluruhan</span>
                        <span class="text-purple-600 text-sm font-bold">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">Sudah Dibayar</span>
                        <span class="text-blue-600 text-sm font-bold">Rp{{ number_format($totalPaid, 0, ',', '.') }}</span>
                    </div>
                    @if($sisaPembayaran > 0)
                        <div class="flex justify-between items-center py-1.5">
                            <span class="text-gray-600 text-sm">Sisa Pembayaran</span>
                            <span class="text-red-600 text-sm font-bold">Rp{{ number_format($sisaPembayaran, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <div class="flex justify-between items-center py-1.5">
                            <span class="text-gray-600 text-sm">Status</span>
                            <span class="text-green-600 text-sm font-bold flex items-center">
                                <i class="bi bi-check-circle-fill mr-1 text-xs"></i>
                                Lunas
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Desktop Payment Summary -->
            <div class="hidden sm:block mt-6 bg-gradient-to-r from-pink-50 to-red-50 border border-pink-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                            <span class="block text-sm font-medium text-gray-600 mb-1">Total Produk</span>
                            <span
                                class="block text-2xl font-bold text-green-600">Rp{{ number_format($totalOrder, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @if($shippingFee > 0)
                        <div class="text-center">
                            <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                                <span class="block text-sm font-medium text-gray-600 mb-1">Ongkir</span>
                                <span
                                    class="block text-2xl font-bold text-orange-600">Rp{{ number_format($shippingFee, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif
                    @if($voucherAmount > 0)
                        <div class="text-center">
                            <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                                <span class="block text-sm font-medium text-gray-600 mb-1">Voucher</span>
                                <span
                                    class="block text-2xl font-bold text-purple-600">-Rp{{ number_format($voucherAmount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif
                    <div class="text-center">
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-purple-200 bg-purple-50">
                            <span class="block text-sm font-medium text-purple-600 mb-1">Total Keseluruhan</span>
                            <span
                                class="block text-2xl font-bold text-purple-600">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                            <span class="block text-sm font-medium text-gray-600 mb-1">Sudah Dibayar</span>
                            <span
                                class="block text-2xl font-bold text-blue-600">Rp{{ number_format($totalPaid, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                            <span class="block text-sm font-medium text-gray-600 mb-1">Sisa Pembayaran</span>
                            <span
                                class="block text-2xl font-bold {{ $sisaPembayaran > 0 ? 'text-red-600' : 'text-green-600' }}">
                                Rp{{ number_format($sisaPembayaran, 0, ',', '.') }}
                            </span>
                            @if($sisaPembayaran == 0 && $order->payment_status === 'paid')
                                <div
                                    class="inline-flex items-center mt-2 px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    LUNAS
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Bouquet Information Card -->
        @php
$customBouquetItems = $order->items->filter(function ($item) {
    return $item->item_type === 'custom_bouquet' && (!empty($item->reference_image) || !empty($item->custom_instructions));
});
        @endphp
        
        @if($customBouquetItems->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center mb-6">
                    <div class="bg-purple-100 p-3 rounded-lg mr-4">
                        <i class="bi bi-palette text-purple-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Custom Bouquet Information</h3>
                        <p class="text-gray-600">Detail referensi dan instruksi khusus dari customer</p>
                    </div>
                </div>

                @foreach($customBouquetItems as $item)
                                <div class="border border-purple-200 rounded-lg p-6 mb-4 bg-purple-50">
                                    <h4 class="font-semibold text-purple-800 mb-4 text-lg">
                                        @php
        // Extract simple product name without components info
        $simpleName = preg_replace('/\s*\(.*?\)\s*/', '', $item->product_name);
        $simpleName = trim($simpleName) ?: 'Custom Bouquet';
                                        @endphp
                                        {{ $simpleName }}
                                    </h4>

                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <!-- Reference Image Section -->
                                        @if(!empty($item->reference_image))
                                            <div class="bg-white rounded-lg border border-purple-200 p-4">
                                                <div class="flex items-center mb-3">
                                                    <i class="bi bi-image text-purple-600 text-lg mr-2"></i>
                                                    <h5 class="font-semibold text-purple-800">Upload Referensi</h5>
                                                </div>

                                                <div class="space-y-3">
                                                    <div class="relative group cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $item->reference_image) }}', 'Referensi - {{ $item->product_name }}')">
                                                        <img src="{{ asset('storage/' . $item->reference_image) }}" 
                                                             alt="Referensi Custom Bouquet" 
                                                             class="w-full h-48 object-cover rounded-lg border border-gray-200 transition-transform hover:scale-105">
                                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                                <i class="bi bi-zoom-in text-white text-2xl"></i>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex gap-2">
                                                        <button onclick="openImageModal('{{ asset('storage/' . $item->reference_image) }}', 'Referensi - {{ $item->product_name }}')"
                                                                class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors">
                                                            <i class="bi bi-zoom-in mr-1"></i>Lihat
                                                        </button>
                                                        <a href="{{ asset('storage/' . $item->reference_image) }}" 
                                                           download="referensi-{{ Str::slug($item->product_name) }}.jpg"
                                                           class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors text-center">
                                                            <i class="bi bi-download mr-1"></i>Download
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Custom Instructions Section -->
                                        @if(!empty($item->custom_instructions))
                                            <div class="bg-white rounded-lg border border-purple-200 p-4">
                                                <div class="flex items-center mb-3">
                                                    <i class="bi bi-chat-left-text text-purple-600 text-lg mr-2"></i>
                                                    <h5 class="font-semibold text-purple-800">Instruksi Khusus</h5>
                                                </div>

                                                <div class="bg-purple-50 border border-purple-200 rounded p-4">
                                                    <p class="text-purple-800 leading-relaxed">"{{ $item->custom_instructions }}"</p>
                                                </div>

                                                <div class="mt-3 text-sm text-purple-600">
                                                    <i class="bi bi-info-circle mr-1"></i>
                                                    Instruksi dari customer untuk pembuatan bouquet
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Custom Bouquet Components Section -->
                                    @if($item->custom_bouquet_id)
                                                        @php
            $customBouquet = \App\Models\CustomBouquet::with(['items.product'])->find($item->custom_bouquet_id);
                                                        @endphp

                                                        @if($customBouquet && $customBouquet->items->count() > 0)
                                                            <div class="bg-white rounded-lg border border-purple-200 p-4 mt-6">
                                                                <div class="flex items-center mb-4">
                                                                    <i class="bi bi-flower1 text-purple-600 text-lg mr-2"></i>
                                                                    <h5 class="font-semibold text-purple-800">Komponen Custom Bouquet</h5>
                                                                </div>

                                                                <!-- Ribbon Color Display -->
                                                                        <div class="flex items-center gap-2 mb-4 pb-4 border-b border-purple-100">
                                                                            <span class="text-sm font-medium text-purple-700">Warna Pita:</span>
                                                                            <div class="flex items-center gap-2">
                                                                                <div class="w-4 h-4 rounded-full"
                                                                                    style="background-color: {{ App\Enums\RibbonColor::getColorCode($customBouquet->ribbon_color) }}">
                                                                                </div>
                                                                                <span class="text-sm text-purple-800">
                                                                                    {{ App\Enums\RibbonColor::getColorName($customBouquet->ribbon_color) }}
                                                                                </span>
                                                                            </div>
                                                                        </div>

                                                                <div class="space-y-3">
                                                                    @foreach($customBouquet->items as $component)
                                                                        <div class="flex items-center justify-between bg-purple-50 border border-purple-100 rounded-lg p-3">
                                                                            <div class="flex items-center">
                                                                                @if($component->product->image)
                                                                                    <img src="{{ asset('storage/' . $component->product->image) }}" 
                                                                                         alt="{{ $component->product->name }}" 
                                                                                         class="w-10 h-10 object-cover rounded mr-3">
                                                                                @else
                                                                                    <div class="w-10 h-10 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                                                                        <i class="bi bi-flower1 text-gray-400"></i>
                                                                                    </div>
                                                                                @endif
                                                                                <div>
                                                                                    <span class="font-medium text-purple-800">{{ $component->product->name }}</span>
                                                                                    <span class="text-sm text-purple-600 block">{{ $component->price_type_display }} - {{ $component->formatted_quantity }}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="text-right">
                                                                                <span class="font-bold text-purple-800">{{ $component->quantity }}</span>
                                                                                <span class="text-sm text-purple-600 ml-1">{{ $component->product->base_unit ?? 'pcs' }}</span>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>

                                                                <div class="mt-4 p-3 bg-purple-100 rounded-lg">
                                                                    <div class="flex justify-between items-center">
                                                                        <span class="font-semibold text-purple-800">Total Harga Custom Bouquet:</span>
                                                                        <span class="font-bold text-lg text-purple-800">Rp {{ number_format((float) ($customBouquet->total_price ?? 0), 0, ',', '.') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                    @endif
                                </div>
                @endforeach
            </div>
        @endif

        <!-- Payment Proof Card -->
        @if(!empty($order->payment_proof))
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                                <div class="flex items-center mb-4">
                                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-900">Bukti Pembayaran</h3>
                                </div>
                                @php
            $ext = pathinfo($order->payment_proof, PATHINFO_EXTENSION);
                                @endphp
                                <div class="my-8 text-center">
                                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran"
                                            class="mx-auto rounded shadow max-h-64 border mb-2 cursor-pointer hover:opacity-90 transition-opacity"
                                            style="max-width:300px;"
                                            onclick="openImageModal('{{ asset('storage/' . $order->payment_proof) }}', 'Bukti Pembayaran')"
                                            onerror="this.style.display='none'; document.getElementById('payment-proof-error').style.display='block';" />
                                            <div class="flex flex-col items-center justify-center text-xs text-gray-600 gap-1 mb-2">
                            <a href="{{ asset('storage/' . $order->payment_proof) }}" download class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="bi bi-download mr-1"></i>Download Bukti Pembayaran
                            </a>
                        </div>
                                    @elseif(strtolower($ext) == 'pdf')

                                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Lihat Bukti Pembayaran (PDF)
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Download Bukti Pembayaran
                                        </a>
                                    @endif
                                </div>
                                <div id="payment-proof-error" style="display:none;"
                                    class="text-center p-4 text-red-600 bg-red-50 rounded-lg border border-red-200">
                                    Bukti pembayaran tidak ditemukan di server.
                                </div>
                            </div>
        @endif

        <!-- Packing Files Card -->
        @if(!empty($order->packing_photo) || !empty($order->packing_files))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center mb-4">
                    <div class="bg-orange-100 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Foto & Video Packing</h3>
                </div>

                @php
    $packingFiles = [];

    // Prioritize new multiple files format
    if (!empty($order->packing_files)) {
        $files = is_string($order->packing_files) ? json_decode($order->packing_files, true) : $order->packing_files;
        if (is_array($files)) {
            $packingFiles = $files;
        }
    }
    // Fallback to old single photo format only if no packing_files
    elseif (!empty($order->packing_photo)) {
        $packingFiles[] = $order->packing_photo;
    }
                @endphp

                @if(count($packingFiles) > 0)
                    <div class="@if(count($packingFiles) === 1) flex justify-center @else grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 @endif">
                        @foreach($packingFiles as $index => $file)
                            @php
            $filePath = asset('storage/' . $file);
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm']);
            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                            @endphp

                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                @if($isVideo)
                                    <div class="space-y-2">
                                        <video controls class="w-full h-48 rounded-lg object-cover bg-black"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <source src="{{ $filePath }}" type="video/{{ $ext }}">
                                            Browser Anda tidak mendukung video.
                                        </video>
                                        <div style="display:none;"
                                            class="text-center p-4 text-red-600 bg-red-50 rounded-lg border border-red-200">
                                            <i class="bi bi-exclamation-triangle mr-2"></i>Video tidak ditemukan.
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h6l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                                <path
                                                    d="M8 10a1 1 0 011.707 0l2 2a1 1 0 01-1.414 1.414L9 12.121V15a1 1 0 11-2 0v-2.879l-1.293 1.293a1 1 0 01-1.414-1.414l2-2z" />
                                            </svg>
                                            Video {{ $index + 1 }}
                                        </div>
                                        <a href="{{ $filePath }}" target="_blank"
                                            class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                @elseif($isImage)
                                    <div class="space-y-2">
                                        <img src="{{ $filePath }}" alt="Foto Packing {{ $index + 1 }}"
                                            class="w-full h-48 object-cover rounded-lg border border-gray-200 cursor-pointer"
                                            onclick="openImageModal('{{ $filePath }}', 'Foto Packing {{ $index + 1 }}')"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
                                        <div style="display:none;"
                                            class="text-center p-4 text-red-600 bg-red-50 rounded-lg border border-red-200">
                                            <i class="bi bi-exclamation-triangle mr-2"></i>Foto tidak ditemukan.
                                        </div>
                                        <div class="flex items-center justify-between text-sm text-gray-600">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Foto {{ $index + 1 }}
                                            </div>
                                            <a href="{{ $filePath }}" target="_blank"
                                                class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="space-y-2">
                                        <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <div class="text-center">
                                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <p class="text-sm text-gray-500 mt-2">File {{ $index + 1 }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ $filePath }}" target="_blank"
                                            class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Download File
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-4 text-gray-500 bg-gray-50 rounded-lg">
                        <i class="bi bi-camera text-2xl mb-2"></i>
                        <p>Belum ada foto atau video packing yang diupload.</p>
                    </div>
                @endif
            </div>

            <!-- Image Modal -->
            <div id="imageModal"
                class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
                <div class="relative max-w-4xl max-h-full">
                    <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
                    <button onclick="closeImageModal()"
                        class="absolute top-4 right-4 text-white bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div id="modalTitle"
                        class="absolute bottom-4 left-4 text-white bg-black bg-opacity-50 px-3 py-1 rounded-lg text-sm">
                    </div>
                </div>
            </div>

            <script>
                function openImageModal(src, title) {
                    document.getElementById('modalImage').src = src;
                    document.getElementById('modalTitle').textContent = title;
                    document.getElementById('imageModal').classList.remove('hidden');
                }

                function closeImageModal() {
                    document.getElementById('imageModal').classList.add('hidden');
                }

                // Close modal when clicking outside the image
                document.getElementById('imageModal').addEventListener('click', function (e) {
                    if (e.target === this) {
                        closeImageModal();
                    }
                });

                // Close modal with Escape key
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        closeImageModal();
                    }
                });
            </script>
        @endif

        <!-- Action Buttons Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Aksi Pesanan</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                @if(!empty($order->public_code))
                    <a href="{{ route('public.order.invoice', ['public_code' => $order->public_code]) }}" target="_blank"
                        class="inline-flex items-center justify-center px-4 py-3 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                clip-rule="evenodd" />
                        </svg>
                        Lihat Invoice
                    </a>

                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $order->wa_number)) }}?text={{ urlencode('Terima kasih telah memesan di Seikat Bungo! Berikut link invoice pesanan Anda: ' . route('public.order.invoice', ['public_code' => $order->public_code])) }}"
                        target="_blank"
                        class="inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                        </svg>
                        Kirim Invoice ke Customer
                    </a>

                    <button onclick="shareToEmployeeGroup({{ $order->id }})"
                        class="inline-flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 12A4 4 0 1 1 12 8a4 4 0 0 1 4 4Z"/>
                            <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94 1 1 0 1 0-.16-2A8 8 0 1 1 20 10.76V13a1 1 0 0 1-2 0V8a1 1 0 0 1 1-1 1 1 0 0 1 1 1v.28A10 10 0 0 0 12 2a10 10 0 0 0-8 16 1 1 0 0 0 1.6 1.2A8 8 0 0 1 12 4a8 8 0 0 1 3.2.64A6 6 0 0 0 12 8a6 6 0 0 0 4 5.66Z"/>
                        </svg>
                        Share Pesanan ke Grup Karyawan
                    </button>

                    <button onclick="shareOrderLinkToCustomer({{ $order->id }})"
                        class="inline-flex items-center justify-center px-4 py-3 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        Share Link Pesanan ke Customer
                    </button>

                    <button onclick="copyMessageToClipboard({{ $order->id }})"
                        class="inline-flex items-center justify-center px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                            <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                        </svg>
                        Copy Pesan
                    </button>
                    @if(!auth()->user()->hasRole(['customer service', 'karyawan']))
                    @if(config('public_order.enable_public_order_edit') && $order->status === 'pending')
                        <a href="{{ route('admin.public-orders.edit', $order->id) }}"
                            class="inline-flex items-center justify-center px-4 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit Pesanan
                        </a>
                    @endif
                    @endif
                @else
                    <div class="col-span-full">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                            <svg class="w-12 h-12 text-red-400 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-red-800 font-semibold">Kode invoice publik belum tersedia</p>
                            <p class="text-red-600 text-sm mt-1">Silakan edit/migrasi data order ini untuk mengaktifkan
                                fitur publik.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function copyMessageToClipboard(orderId) {
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating...';
            button.disabled = true;

            // Generate WhatsApp message
            fetch(`{{ url('/admin/public-orders') }}/${orderId}/whatsapp-message`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Copy to clipboard
                        navigator.clipboard.writeText(data.message).then(() => {
                            showNotification('Pesan berhasil disalin ke clipboard!', 'success');
                        }).catch(() => {
                            // Fallback for older browsers
                            const textArea = document.createElement('textarea');
                            textArea.value = data.message;
                            document.body.appendChild(textArea);
                            textArea.select();
                            document.execCommand('copy');
                            document.body.removeChild(textArea);
                            showNotification('Pesan berhasil disalin ke clipboard!', 'success');
                        });
                    } else {
                        showNotification(data.error || 'Terjadi kesalahan saat generate pesan WhatsApp', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat generate pesan WhatsApp', 'error');
                })
                .finally(() => {
                    // Restore button state
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }

        function shareToEmployeeGroup(orderId) {
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating...';
            button.disabled = true;

            // Generate WhatsApp message
            fetch(`{{ url('/admin/public-orders') }}/${orderId}/whatsapp-message`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const targetInfo = data.target_info;
                        
                        if (targetInfo.type === 'group') {
                            // Untuk grup WhatsApp: copy pesan ke clipboard lalu buka grup
                            navigator.clipboard.writeText(data.message).then(() => {
                                // Buka grup WhatsApp
                                window.open(data.whatsapp_url, '_blank');
                                
                                // Show instruction notification
                                showNotification(`✅ Pesan disalin ke clipboard!<br>📱 Grup "${targetInfo.name}" terbuka - silakan paste pesan di grup.`, 'success');
                            }).catch(() => {
                                // Fallback untuk browser lama
                                const textArea = document.createElement('textarea');
                                textArea.value = data.message;
                                document.body.appendChild(textArea);
                                textArea.select();
                                document.execCommand('copy');
                                document.body.removeChild(textArea);
                                
                                window.open(data.whatsapp_url, '_blank');
                                showNotification(`✅ Pesan disalin ke clipboard!<br>📱 Grup "${targetInfo.name}" terbuka - silakan paste pesan di grup.`, 'success');
                            });
                        } else {
                            // Untuk individual: langsung buka WhatsApp dengan pesan
                            window.open(data.whatsapp_url, '_blank');
                            showNotification(`📱 WhatsApp terbuka - pesan siap dikirim ke ${targetInfo.name}!`, 'success');
                        }
                    } else {
                        showNotification(data.error || 'Terjadi kesalahan saat generate pesan WhatsApp', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat generate pesan WhatsApp', 'error');
                })
                .finally(() => {
                    // Restore button state
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }

        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
            
            if (type === 'success') {
                notification.className += ' bg-green-500 text-white';
            } else if (type === 'error') {
                notification.className += ' bg-red-500 text-white';
            } else {
                notification.className += ' bg-blue-500 text-white';
            }
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        function shareOrderLinkToCustomer(orderId) {
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating...';
            button.disabled = true;

            // Generate customer link message
            fetch(`{{ url('/admin/public-orders') }}/${orderId}/customer-link-message`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Langsung buka WhatsApp dengan pesan untuk customer
                        window.open(data.whatsapp_url, '_blank');
                        showNotification(`✅ WhatsApp terbuka dengan link detail pesanan!<br>📱 Pesan siap dikirim ke ${data.customer_name} (${data.customer_whatsapp})`, 'success');
                    } else {
                        showNotification(data.error || 'Terjadi kesalahan saat generate pesan untuk customer', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat generate pesan untuk customer', 'error');
                })
                .finally(() => {
                    // Restore button state
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }

        // Image Modal Functions for Custom Bouquet
        function openImageModal(imageSrc, title = 'Gambar Referensi') {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalDownloadBtn = document.getElementById('modalDownloadBtn');
            
            modalImage.src = imageSrc;
            modalTitle.textContent = title;
            modalDownloadBtn.href = imageSrc;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Initialize modal events
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('imageModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeImageModal();
                    }
                });
            }
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeImageModal();
                }
            });
        });
    </script>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center p-4" style="display: none;">
        <div class="relative max-w-4xl max-h-full bg-white rounded-lg overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-purple-600 text-white p-4 flex items-center justify-between">
                <h3 id="modalTitle" class="font-bold text-lg">Gambar Referensi</h3>
                <button onclick="closeImageModal()" class="text-white hover:text-gray-300 text-2xl">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4 max-h-96 overflow-auto">
                <img id="modalImage" src="" alt="Gambar Referensi" class="w-full h-auto rounded-lg">
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 p-4 flex justify-end space-x-3">
                <button onclick="closeImageModal()" 
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200">
                    <i class="bi bi-x-circle mr-2"></i>Tutup
                </button>
                <a id="modalDownloadBtn" href="" download="referensi-custom-bouquet.jpg"
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200">
                    <i class="bi bi-download mr-2"></i>Download
                </a>
            </div>
        </div>
    </div>
    </script>
</x-app-layout>