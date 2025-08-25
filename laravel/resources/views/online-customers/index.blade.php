<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col space-y-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="bi bi-people-fill text-pink-500 mr-2"></i>
                {{ __('Daftar Pelanggan Online') }}
            </h2>
            {{-- <p class="text-sm text-gray-600">
                <i class="bi bi-info-circle mr-1"></i>
                Menampilkan semua data pelanggan (tidak ada filter tanggal). Data dikelompokkan berdasarkan nomor
                WhatsApp.
            </p> --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search Form -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('online-customers.index') }}" class="flex gap-3">
                            <div class="flex-1">
                                <input type="text" name="search" value="{{ $search }}"
                                    placeholder="Cari nama pelanggan atau nomor WhatsApp..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <button type="submit"
                                class="px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                                <i class="bi bi-search mr-1"></i> Cari
                            </button>
                            @if($search)
                                <a href="{{ route('online-customers.index') }}"
                                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                                    <i class="bi bi-x-circle mr-1"></i> Reset
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Tambah Pelanggan Button -->
                    <div class="mb-6">
                        <button onclick="openAddCustomerModal()"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                            <i class="bi bi-person-plus-fill mr-2"></i>
                            Tambah Pelanggan Baru
                        </button>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <p class="text-blue-100">Total Pelanggan</p>
                                    <p class="text-2xl font-bold">{{ $onlineCustomers->count() }}</p>
                                </div>
                                <i class="bi bi-people text-3xl text-blue-200"></i>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <p class="text-green-100">Total Pesanan</p>
                                    <p class="text-2xl font-bold">{{ $onlineCustomers->sum('total_orders') }}</p>
                                </div>
                                <i class="bi bi-bag-check text-3xl text-green-200"></i>
                            </div>
                        </div>
                    </div>
                    @if(auth()->user()->hasRole(['owner']))
                        <div
                            class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-4 rounded-lg form-enter mb-6 w-full">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <p class="text-purple-100">Total Penjualan</p>
                                    <p class="text-2xl font-bold">Rp
                                        {{ number_format($onlineCustomers->sum('total_spent'), 0, ',', '.') }}
                                    </p>
                                </div>
                                <i class="bi bi-currency-dollar text-3xl text-purple-200"></i>
                            </div>
                        </div>
                    @endif

                    <!-- Customers Table -->
                    @if($onlineCustomers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pelanggan
                                            <div class="flex items-center text-xs text-gray-400 mt-1 normal-case">
                                                <i class="bi bi-info-circle mr-1"></i>
                                                <span>Berdasarkan No. WA</span>
                                            </div>
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No. WhatsApp
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Pesanan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Belanja
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Terakhir Pesan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($onlineCustomers as $customer)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0 h-10 w-10 mt-0.5">
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center">
                                                            <i class="bi bi-person-fill text-pink-500"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4 flex-1">
                                                        <div class="text-sm font-medium text-gray-900 mb-1">
                                                            {{ $customer->customer_name }}
                                                        </div>
                                                        @if($customer->names_count > 1)
                                                            <div class="space-y-1">
                                                                <div class="text-xs text-gray-500">
                                                                    <i class="bi bi-info-circle mr-1"></i>
                                                                    {{ $customer->names_count }} variasi nama:
                                                                </div>
                                                                <div class="flex flex-wrap gap-1">
                                                                    @foreach($customer->all_names as $name)
                                                                        <span
                                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            {{ $name === $customer->customer_name ? 'bg-pink-100 text-pink-700' : 'bg-gray-100 text-gray-600' }}">
                                                                            {{ $name }}
                                                                            @if($name === $customer->customer_name)
                                                                                <i class="bi bi-star-fill ml-1 text-xs"></i>
                                                                            @endif
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="text-sm text-gray-500 mt-2">
                                                            Bergabung:
                                                            {{ \Carbon\Carbon::parse($customer->first_order_date)->format('d M Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <div class="text-sm text-gray-900 mb-1">
                                                        <i class="bi bi-whatsapp text-green-500 mr-1"></i>
                                                        {{ $customer->wa_number }}
                                                    </div>
                                                    @if($customer->names_count > 1)
                                                        <span class="text-xs text-gray-500">
                                                            <i class="bi bi-people-fill mr-1"></i>
                                                            {{ $customer->names_count }} nama berbeda
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col gap-1">
                                                    @if($customer->is_reseller)
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="bi bi-star-fill mr-1"></i>
                                                            Reseller
                                                        </span>
                                                    @endif
                                                    @if($customer->promo_discount && $customer->promo_discount > 0)
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <i class="bi bi-gift-fill mr-1"></i>
                                                            Promo {{ $customer->promo_discount }}%
                                                        </span>
                                                    @endif
                                                    @if(!$customer->is_reseller && (!$customer->promo_discount || $customer->promo_discount == 0))
                                                        <span class="text-xs text-gray-400">Regular</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $customer->total_orders }} pesanan
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($customer->total_spent, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($customer->last_order_date)->diffForHumans() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="{{ route('online-customers.show', $customer->wa_number) }}"
                                                        class="text-blue-600 hover:text-blue-900 transition"
                                                        title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                        <p>lihat</p>
                                                    </a>
                                                    @if(!auth()->user()->hasRole(['customers service', 'karyawan']))
                                                        <a href="{{ route('online-customers.edit', $customer->wa_number) }}"
                                                            class="text-green-600 hover:text-green-900 transition"
                                                            title="Edit Pelanggan">
                                                            <i class="bi bi-pencil"></i>
                                                            <p>Edit</p>
                                                        </a>
                                                    @endif
                                                    @if($customer->is_reseller)
                                                        <button type="button"
                                                            class="text-purple-600 hover:text-purple-900 transition"
                                                            title="Generate Kode Reseller"
                                                            onclick="openGenerateCodeModal('{{ $customer->wa_number }}', '{{ $customer->customer_name }}')">
                                                            <i class="bi bi-key"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- No Pagination -->
                    @else
                        <div class="text-center py-12">
                            <i class="bi bi-people text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pelanggan online</h3>
                            <p class="text-gray-500">
                                @if($search)
                                    Tidak ditemukan pelanggan dengan kata kunci "{{ $search }}"
                                @else
                                    Belum ada pelanggan yang melakukan pemesanan online
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Reseller Code Modal -->
    <div id="generateCodeModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">
                        <i class="bi bi-key text-purple-500 mr-2"></i>
                        Generate Kode Reseller
                    </h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeGenerateCodeModal()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Pelanggan: <span id="customerName" class="font-medium"></span>
                    </p>
                    <p class="text-sm text-gray-600 mb-4">Nomor WA: <span id="customerWA" class="font-medium"></span>
                    </p>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Reseller</label>
                            <div class="flex">
                                <input type="text" id="resellerCode"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="Masukkan kode reseller" maxlength="20">
                                <button type="button" onclick="generateRandomCode()"
                                    class="px-3 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-200 text-sm">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Kode harus unik dan maksimal 20 karakter</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Berlaku Selama (Jam)</label>
                            <select id="expiryHours"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                                <option value="24">24 Jam (1 Hari)</option>
                                <option value="48">48 Jam (2 Hari)</option>
                                <option value="72" selected>72 Jam (3 Hari)</option>
                                <option value="168">168 Jam (1 Minggu)</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Waktu berlaku kode reseller</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                            <textarea id="notes" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Tambahkan catatan untuk kode reseller ini..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeGenerateCodeModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="button" onclick="generateResellerCode()"
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                        <i class="bi bi-key mr-1"></i>
                        Generate Kode
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentCustomerWA = '';

        function openGenerateCodeModal(waNumber, customerName) {
            currentCustomerWA = waNumber;
            document.getElementById('customerName').textContent = customerName;
            document.getElementById('customerWA').textContent = waNumber;
            document.getElementById('generateCodeModal').classList.remove('hidden');

            // Generate random code by default
            generateRandomCode();

            // Set default values
            document.getElementById('expiryHours').value = '72';
            document.getElementById('notes').value = '';
        }

        function closeGenerateCodeModal() {
            document.getElementById('generateCodeModal').classList.add('hidden');
            // Reset form
            document.getElementById('resellerCode').value = '';
            document.getElementById('expiryHours').value = '72';
            document.getElementById('notes').value = '';
            currentCustomerWA = '';
        }

        function generateRandomCode() {
            const prefix = 'RES';
            const randomPart = Math.random().toString(36).substring(2, 8).toUpperCase();
            const code = prefix + randomPart;
            document.getElementById('resellerCode').value = code;
        }

        function generateResellerCode() {
            const code = document.getElementById('resellerCode').value.trim();
            const expiryHours = document.getElementById('expiryHours').value;
            const notes = document.getElementById('notes').value.trim();

            if (!code) {
                alert('Kode reseller tidak boleh kosong!');
                return;
            }

            if (!expiryHours || expiryHours < 1 || expiryHours > 168) {
                alert('Jam berlaku harus antara 1-168 jam!');
                return;
            }

            // Show loading state
            const generateBtn = event.target;
            const originalText = generateBtn.innerHTML;
            generateBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin mr-1"></i> Generating...';
            generateBtn.disabled = true;

            // Send AJAX request
            fetch(`/online-customers/${currentCustomerWA}/generate-code`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    code: code,
                    expiry_hours: expiryHours,
                    notes: notes || null
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Kode reseller berhasil di-generate!\n\nKode: ' + data.code + '\nBerlaku hingga: ' + data.expires_at);
                        closeGenerateCodeModal();
                        // Refresh halaman untuk update data
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Gagal generate kode reseller'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat generate kode reseller');
                })
                .finally(() => {
                    // Reset button state
                    generateBtn.innerHTML = originalText;
                    generateBtn.disabled = false;
                });
        }

        // Close modal when clicking outside
        document.getElementById('generateCodeModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeGenerateCodeModal();
            }
        });
    </script>

    <!-- Modal Tambah Pelanggan -->
    <div id="addCustomerModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="bi bi-person-plus-fill text-green-500 mr-2"></i>
                        Tambah Pelanggan Baru
                    </h3>

                    <form id="addCustomerForm" action="{{ route('online-customers.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                                <input type="text" name="wa_number" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                    placeholder="Contoh: 08123456789">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                                <input type="text" name="name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                    placeholder="Nama lengkap pelanggan">
                            </div>

                            <div class="flex items-center space-x-2">
                                <!-- Tambahkan hidden input untuk nilai default -->
                                <input type="hidden" name="is_reseller" value="0">
                                <input type="checkbox" name="is_reseller" id="is_reseller" value="1"
                                    class="rounded text-green-500 focus:ring-green-500">
                                <label for="is_reseller" class="text-sm font-medium text-gray-700">Set sebagai
                                    Reseller</label>
                            </div>

                            <div class="pt-4 flex justify-end space-x-3">
                                <button type="button" onclick="closeAddCustomerModal()"
                                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                    Batal
                                </button>
                                <button type="submit" id="submitAddCustomer"
                                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                    <i class="bi bi-save mr-1"></i>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openAddCustomerModal() {
            document.getElementById('addCustomerModal').classList.remove('hidden');
        }

        function closeAddCustomerModal() {
            document.getElementById('addCustomerModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('addCustomerModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeAddCustomerModal();
            }
        });

        // Handle form submission
        document.getElementById('addCustomerForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // Disable submit button and show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Menyimpan...';

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Success handling
                        alert('Berhasil: ' + data.message);
                        closeAddCustomerModal();
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.reload();
                        }
                    } else {
                        // Error with message
                        throw new Error(data.message || 'Terjadi kesalahan saat menyimpan data');
                    }
                })
                .catch(error => {
                    // Error handling
                    alert('Error: ' + error.message);

                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
        });
    </script>
</x-app-layout>