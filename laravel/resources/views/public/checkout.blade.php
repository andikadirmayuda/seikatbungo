<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Seikat Bungo</title>
    <link rel="icon" href="{{ asset(config('app.logo')) }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Figtree Font -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Figtree', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body,
        .font-sans {
            font-family: 'Figtree', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #fdf2f8 0%, #ffffff 50%, #f0fdf4 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            ring: 2px;
            ring-color: rgba(244, 63, 94, 0.5);
            border-color: rgb(244, 63, 94);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Animation untuk form */
        .form-enter {
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="min-h-screen gradient-bg font-sans">
    <!-- Header -->
    <header class="w-full glass-effect border-b border-gray-100 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Section -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('public.flowers') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-rose-500 rounded-full flex items-center justify-center">
                            <img src="{{ asset('logo-seikat-bungo.png') }}" alt="Logo" class="rounded-full w-9 h-9">
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-gray-800">Seikat Bungo</h1>
                            <p class="text-xs text-gray-500">Since 2025</p>
                        </div>
                    </a>
                </div>

                <!-- Back Button -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('public.flowers') }}"
                        class="text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                        title="Kembali Berbelanja">
                        <i class="bi bi-arrow-left text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Page Header -->
        <div class="text-center mb-8 form-enter">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-rose-500 to-pink-600 rounded-full mb-4 shadow-lg"
                style="background: #247A72;">
                <i class="bi bi-cart-check text-2xl text-white"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                Checkout <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-pink-600"
                    style="color: #247A72;">Pesanan</span>
            </h1>
            <p class="text-gray-600">Lihat Ringkasan Keranjang dan Lengkapi data pesanan Anda</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6 form-enter">
                <div class="flex items-center">
                    <i class="bi bi-exclamation-triangle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if(empty($cartData))
            <div class="bg-white rounded-2xl shadow-lg border border-rose-100 p-8 text-center form-enter">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                    <i class="bi bi-cart-x text-3xl text-yellow-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Keranjang Belanja Kosong</h3>
                <p class="text-gray-600 mb-6">Silakan tambahkan produk ke keranjang terlebih dahulu</p>
                <a href="{{ route('public.flowers') }}"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-semibold rounded-xl hover:from-rose-600 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="bi bi-shop mr-2"></i>
                    Mulai Berbelanja
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-12">
                <!-- Order Summary Section (MOBILE ONLY) -->
                <div class="block md:hidden mb-6">
                    @include('public.partials.checkout-cart-summary')
                </div>
                <!-- Form Section -->
                <div class="md:col-span-1 lg:col-span-3">
                    <form method="POST" action="{{ route('public.checkout.process') }}"
                        class="bg-white rounded-2xl shadow-lg border border-rose-100 p-6 form-enter">
                        @csrf

                        @if(session('debug'))
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-3 rounded-xl mb-6">
                                <i class="bi bi-info-circle mr-2"></i>
                                Debug: {{ json_encode(session('debug')) }}
                            </div>
                        @endif

                        <!-- Form Header -->
                        <div class="mb-6 pb-4 border-b border-gray-100">
                            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                <i class="bi bi-person-lines-fill mr-2 text-rose-500"></i>
                                Data Pemesanan
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Isi data dengan lengkap dan benar</p>
                        </div>

                        <!-- Form Fields -->
                        <div class="space-y-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-person mr-1 text-rose-500"></i>
                                    Nama Lengkap Pemesan
                                </label>
                                <input type="text" name="customer_name"
                                    class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                    placeholder="Masukkan nama lengkap Anda" required>
                            </div>

                            <!-- No. WhatsApp -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-whatsapp mr-1 text-rose-500"></i>
                                    No. WhatsApp Pemesan
                                </label>
                                <input type="text" name="wa_number"
                                    class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                    placeholder="Contoh: 08123456789" required>
                            </div>

                            <!-- Nama Penerima (Opsional) -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-person-check mr-1 text-rose-500"></i>
                                    Nama Penerima <span class="text-gray-400 font-normal">(Opsional/Jika Ada)</span>
                                </label>
                                <input type="text" name="receiver_name"
                                    class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                    placeholder="Masukkan nama penerima jika berbeda dengan pemesan">
                            </div>

                            <!-- No. WhatsApp Penerima (Opsional) -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-whatsapp mr-1 text-rose-500"></i>
                                    No. WhatsApp Penerima <span class="text-gray-400 font-normal">(Opsional/Jika Ada)</span>
                                </label>
                                <input type="text" name="receiver_wa"
                                    class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                    placeholder="Masukkan nomor WA penerima jika berbeda dengan pemesan">
                            </div>

                            <!-- Tanggal & Waktu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="bi bi-calendar-event mr-1 text-rose-500"></i>
                                        Tanggal Ambil/Kirim
                                    </label>
                                    <input type="date" name="pickup_date" id="pickup_date"
                                        class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                        required>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        Hari: <span id="day_name" class="font-medium text-rose-600">-</span>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="bi bi-clock mr-1 text-rose-500"></i>
                                        Waktu Ambil/Pengiriman
                                    </label>
                                    <input type="time" name="pickup_time"
                                        class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                        required>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        Waktu: <span id="pickup_time_display" class="font-medium text-rose-600">-</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Metode Pengiriman -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-truck mr-1 text-rose-500"></i>
                                    Metode Pengiriman
                                </label>
                                <select name="delivery_method" id="delivery_method"
                                    class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                    required>
                                    <option value="">Pilih metode pengiriman</option>
                                    <option value="Ambil Langsung Ke Toko">🏪 (1) Ambil Langsung di Toko</option>
                                    <option value="Gosend (Dipesan Pribadi)">🛵 (2) Gosend (Pesan Sendiri)</option>
                                    <option value="Gocar (Dipesan Pribadi)">🚕 (3) Gocar (Pesan Sendiri)</option>
                                    <option value="Gosend (Pesan Dari Toko)">🛵 (4) Gosend (Pesan Via Toko, + Ongkir)
                                    </option>
                                    <option value="Gocar (Pesan Dari Toko)">🚕 (5) Gocar (Pesan Via Toko, + Ongkir)</option>
                                    <option value="Travel (Di Pesan Sendiri)">🚌 (6) Travel (Luar Kota, Pesan Sendiri)
                                    </option>
                                </select>
                            </div>

                            <!-- Tujuan Pengiriman -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-geo-alt mr-1 text-rose-500"></i>
                                    Tujuan Pengiriman
                                </label>
                                <textarea name="destination"
                                    class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                    rows="3" placeholder="Masukkan alamat lengkap pengiriman"></textarea>
                            </div>

                            <!-- Catatan Pesanan -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-chat-left-text mr-1 text-rose-500"></i>
                                    Catatan untuk Pesanan <span class="text-gray-400 font-normal">(Opsional)</span>
                                </label>
                                <textarea name="notes"
                                    class="w-full px-4 py-3 border border-rose-200 rounded-xl input-focus focus:outline-none"
                                    rows="4"
                                    placeholder="Contoh: Bunga untuk acara ulang tahun, warna dominan pink, jangan terlalu besar, dll."></textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="bi bi-info-circle mr-1"></i>
                                    Berikan detail khusus yang Anda inginkan untuk pesanan ini
                                </p>
                            </div>

                            @php
                                $hasCustomBouquet = false;
                                $cartRaw = session('cart', []);
                                foreach ($cartRaw as $key => $item) {
                                    if (isset($item['type']) && $item['type'] === 'custom_bouquet') {
                                        $hasCustomBouquet = true;
                                        break;
                                    }
                                }
                            @endphp

                            @if($hasCustomBouquet)
                                <!-- Hanya Ucapan Kartu (Greeting Card) untuk Custom Bouquet -->
                                @foreach($cartRaw as $cartKey => $item)
                                    @if(isset($item['type']) && $item['type'] === 'custom_bouquet')
                                        <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 mb-4">
                                            <label class="block text-xs font-semibold text-pink-700 mb-1">
                                                <i class="bi bi-card-text mr-1 text-pink-500"></i>
                                                Ucapan Kartu (Greeting Card) <span class="text-pink-400 font-normal">(Opsional)</span>
                                            </label>
                                            <textarea name="greeting_card[{{ $cartKey }}]"
                                                class="w-full px-4 py-2 border border-pink-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 focus:outline-none"
                                                rows="2" placeholder="Contoh: Selamat ulang tahun, semoga bahagia!"></textarea>
                                            <p class="text-xs text-purple-600 mt-1">
                                                <i class="bi bi-lightbulb mr-1"></i>
                                                Berikan ucapan yang ingin dicantumkan pada kartu custom bouquet Anda.
                                            </p>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <!-- Info Note -->
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <div class="flex items-start">
                                <i class="bi bi-info-circle text-blue-600 mr-2 mt-0.5"></i>
                                <div class="text-sm text-blue-700">
                                    <p class="font-semibold mb-1">Informasi Penting:</p>
                                    <p>Setelah mengirim pesanan, Anda akan diarahkan ke halaman detail pesanan untuk
                                        memantau status dan proses pembayaran.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full mt-6 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl card-hover">
                            <i class="bi bi-send mr-2"></i>
                            Kirim Pesanan & Lihat Detail
                        </button>
                    </form>
                </div>

                <!-- Order Summary Section (DESKTOP ONLY) -->
                <div class="hidden md:block md:col-span-1 lg:col-span-2 w-full">
                    @include('public.partials.checkout-cart-summary')
                </div>
        @endif
        </div>

        <!-- Modal Notifikasi Waktu Tidak Valid -->
        <div id="modal-time-warning"
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-40 hidden">
            <div
                class="bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full text-center border border-rose-200 animate-fade-in relative z-[10000]">
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 flex items-center justify-center bg-rose-100 rounded-full mb-4">
                        <i class="bi bi-exclamation-triangle text-3xl text-rose-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-rose-700 mb-2">Mohon Bersabar</h3>
                    <p id="modal-time-warning-text" class="text-gray-700 mb-6"></p>
                    <button id="close-modal-time-warning" type="button"
                        class="px-6 py-2 bg-rose-600 text-white rounded-xl font-semibold shadow hover:bg-rose-700 transition">Tutup</button>
                </div>
            </div>
        </div>
        <style>
            /* Pastikan modal dan konten modal selalu di atas elemen lain (termasuk native select/time picker di browser modern) */
            #modal-time-warning {
                z-index: 9999 !important;
            }

            #modal-time-warning>div {
                z-index: 10000 !important;
                position: relative;
            }

            @keyframes fade-in {
                from {
                    opacity: 0;
                    transform: scale(0.95);
                }

                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            .animate-fade-in {
                animation: fade-in 0.2s ease;
            }
        </style>
        <script>
            // Fungsi untuk mendapatkan nama hari dalam Bahasa Indonesia
            function getDayName(date) {
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                return days[date.getDay()];
            }

            // Event listener untuk input tanggal
            document.getElementById('pickup_date').addEventListener('change', function () {
                const dateValue = this.value;
                if (dateValue) {
                    const selectedDate = new Date(dateValue);
                    const dayName = getDayName(selectedDate);
                    document.getElementById('day_name').textContent = dayName;
                } else {
                    document.getElementById('day_name').textContent = '-';
                }
            });

            // Script untuk mengisi input hidden custom_instructions per item sebelum submit
            document.querySelector('form').addEventListener('submit', function (e) {
                document.querySelectorAll('.custom-instructions-textarea').forEach(function (textarea) {
                    var idx = textarea.getAttribute('data-item-index');
                    var hidden = document.querySelector('input[name="custom_instructions[' + idx + ']"]');
                    if (hidden) hidden.value = textarea.value;
                });
            });
        </script>

        <script>
            const pickupTimeInput = document.querySelector('input[name="pickup_time"]');
            const pickupTimeDisplay = document.getElementById('pickup_time_display');
            const modal = document.getElementById('modal-time-warning');
            const closeModalBtn = document.getElementById('close-modal-time-warning');
            const modalText = document.getElementById('modal-time-warning-text');

            // Deteksi apakah ada bouquet/custom_bouquet di keranjang (dari PHP -> JS)
            let minMinutes = 5;
            @php
                $cartRaw = session('cart', []);
                $hasBouquet = false;
                foreach ($cartRaw as $item) {
                    if ((isset($item['type']) && ($item['type'] === 'bouquet' || $item['type'] === 'custom_bouquet'))) {
                        $hasBouquet = true;
                        break;
                    }
                }
            @endphp
            @if($hasBouquet)
                minMinutes = 30;
            @endif

            // Set minimal waktu ambil sesuai jenis pesanan
            function setMinPickupTime() {
                const now = new Date();
                now.setMinutes(now.getMinutes() + minMinutes);
                const minHours = String(now.getHours()).padStart(2, '0');
                const minMins = String(now.getMinutes()).padStart(2, '0');
                const minTime = `${minHours}:${minMins}`;
                pickupTimeInput.min = minTime;
            }
            setMinPickupTime();

            function showModal(text) {
                modalText.innerHTML = text;
                modal.classList.remove('hidden');
            }
            function hideModal() {
                modal.classList.add('hidden');
            }
            closeModalBtn.addEventListener('click', hideModal);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') hideModal();
            });

            pickupTimeInput.addEventListener('input', () => {
                const timeValue = pickupTimeInput.value;
                if (!timeValue) {
                    pickupTimeDisplay.textContent = "-";
                    return;
                }

                // Validasi: waktu harus >= min
                const min = pickupTimeInput.min;
                if (timeValue < min) {
                    // let msg = 'Waktu ambil/pengiriman minimal <span class="font-semibold">' + minMinutes + ' menit</span> dari sekarang. Serta membutuhkan waktu Dalam perakitan & pengemasan pesanan';
                    // let msg = 'Pengambilan/pengiriman dapat dilakukan minimal <span class="font-semibold">' + minMinutes + ' menit</span> dari sekarang, karena pesanan memerlukan waktu untuk perakitan dan pengemasan.<br><br><i>*Waktu dapat berubah sesuai ukuran dan jumlah pesanan.</i>';
                    let msg = 'Pesanan Anda membutuhkan waktu untuk dirangkai atau dikemas. Pengambilan dan Pengiriman bisa dilakukan minimal <span class="font-semibold">' + minMinutes + ' menit</span> dari sekarang.<br><br><i>*Estimasi waktu dapat Berubah tergantung ukuran dan jumlah pesanan.</i>';

                    showModal(msg);
                    pickupTimeInput.value = '';
                    pickupTimeDisplay.textContent = "-";
                    return;
                }

                // Pisahkan jam & menit
                const [hours, minutes] = timeValue.split(":").map(Number);
                let period = "";

                if (hours >= 4 && hours < 11) {
                    period = "Pagi";       // 04:00 - 10:59
                } else if (hours >= 11 && hours < 15) {
                    period = "Siang";      // 11:00 - 14:59
                } else if (hours >= 15 && hours < 18) {
                    period = "Sore";       // 15:00 - 17:59
                } else {
                    period = "Malam";      // 18:00 - 03:59
                }

                // Format jam supaya ada leading zero (contoh 08:05)
                const formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;

                pickupTimeDisplay.textContent = `${formattedTime} (${period})`;
            });
        </script>
</body>

</html>