<x-app-layout>
    <x-slot name="header">
        Daftar Pesanan Publik
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @php $user = auth()->user(); @endphp
                    @if($user && $user->hasRole(['owner', 'admin']))
                        <div class="mb-4 flex flex-col sm:flex-row sm:items-center gap-2">

                            <!-- Bulk Delete Checklist Button -->
                            <button type="button" id="delete-selected-btn" disabled
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow opacity-50 cursor-not-allowed flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Hapus Terpilih
                                <span id="selected-count"
                                    class="ml-2 bg-white text-red-600 rounded-full px-2 text-xs hidden">0</span>
                            </button>
                        </div>
                        <!-- mass-delete-panel dihapus, hanya fitur checklist yang tersisa -->
                        <!-- Modal Konfirmasi Hapus Checklist -->
                        <div id="delete-modal"
                            class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
                            <div class="bg-white rounded-lg p-8 max-w-md mx-auto">
                                <div class="text-center">
                                    <svg class="mx-auto mb-4 w-12 h-12 text-red-500" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
                                    </svg>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                                    <p class="text-gray-500 mb-6">Anda yakin ingin menghapus <span id="delete-modal-count"
                                            class="font-semibold"></span> pesanan publik yang dipilih? Tindakan ini tidak
                                        dapat dibatalkan.</p>
                                    <div class="flex justify-center space-x-4">
                                        <button type="button" onclick="closeDeleteModal()"
                                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">Batal</button>
                                        <button type="button" onclick="executeBulkDelete()"
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Ya,
                                            Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form id="bulk-delete-checklist-form" method="POST"
                            action="{{ route('admin.public-orders.bulk-delete') }}" style="display:none;">
                            @csrf
                        </form>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                // Checklist Bulk Delete
                                const deleteBtn = document.getElementById('delete-selected-btn');
                                const selectedCount = document.getElementById('selected-count');
                                const deleteModal = document.getElementById('delete-modal');
                                let checkedIds = [];

                                // Jadikan global agar bisa dipanggil dari script lain
                                window.updateDeleteBtn = function () {
                                    checkedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
                                    if (checkedIds.length > 0) {
                                        deleteBtn.disabled = false;
                                        deleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                                        selectedCount.textContent = checkedIds.length;
                                        selectedCount.classList.remove('hidden');
                                    } else {
                                        deleteBtn.disabled = true;
                                        deleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
                                        selectedCount.classList.add('hidden');
                                    }
                                }
                                document.addEventListener('change', function (e) {
                                    if (e.target.classList.contains('row-checkbox')) {
                                        window.updateDeleteBtn();
                                    }
                                });
                                deleteBtn.addEventListener('click', function () {
                                    document.getElementById('delete-modal-count').textContent = checkedIds.length;
                                    deleteModal.classList.remove('hidden');
                                    deleteModal.classList.add('flex');
                                });
                                window.closeDeleteModal = function () {
                                    deleteModal.classList.add('hidden');
                                    deleteModal.classList.remove('flex');
                                }
                                window.executeBulkDelete = function () {
                                    const form = document.getElementById('bulk-delete-checklist-form');
                                    form.innerHTML = '';
                                    const csrf = document.createElement('input');
                                    csrf.type = 'hidden';
                                    csrf.name = '_token';
                                    csrf.value = '{{ csrf_token() }}';
                                    form.appendChild(csrf);
                                    checkedIds.forEach(id => {
                                        const input = document.createElement('input');
                                        input.type = 'hidden';
                                        input.name = 'ids[]';
                                        input.value = id;
                                        form.appendChild(input);
                                    });
                                    form.style.display = 'block';
                                    form.submit();
                                }
                            });
                        </script>
                    @endif
                    <!-- Filter Bar Responsive -->
                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                            <div>
                                <label for="filter-nama" class="block text-xs font-semibold text-gray-600 mb-1">Nama
                                    Pelanggan</label>
                                <input type="text" id="filter-nama" placeholder="Cari nama..."
                                    class="w-full border border-gray-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 rounded-lg px-3 py-2 text-sm transition" />
                            </div>
                            <div>
                                <label for="filter-tanggal"
                                    class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Kirim/Ambil</label>
                                <input type="date" id="filter-tanggal"
                                    class="w-full border border-gray-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 rounded-lg px-3 py-2 text-sm transition" />
                            </div>
                            <div>
                                <label for="filter-metode" class="block text-xs font-semibold text-gray-600 mb-1">Metode
                                    Pengiriman</label>
                                <select id="filter-metode"
                                    class="w-full border border-gray-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 rounded-lg px-3 py-2 text-sm transition">
                                    <option value="">Semua</option>
                                    <option value="Ambil Langsung Ke Toko">Ambil Langsung Ke Toko</option>
                                    <option value="Gosend (Dipesan Pribadi)">Gosend (Dipesan Pribadi)</option>
                                    <option value="Gocar (Dipesan Pribadi)">Gocar (Dipesan Pribadi)</option>
                                    <option value="Gosend (Pesan Dari Toko)">Gosend (Pesan Dari Toko)</option>
                                    <option value="Gocar (Pesan Dari Toko)">Gocar (Pesan Dari Toko)</option>
                                    <option value="Travel (Di Pesan Sendiri)">Travel (Di Pesan Sendiri)</option>
                                    <!-- Legacy options -->
                                    <option value="Ambil Langsung">Ambil Langsung (Legacy)</option>
                                    <option value="GoSend">GoSend (Legacy)</option>
                                    <option value="Kurir Toko">Kurir Toko (Legacy)</option>
                                </select>
                            </div>
                            <div>
                                <label for="filter-status" class="block text-xs font-semibold text-gray-600 mb-1">Status
                                    Pesanan</label>
                                <select id="filter-status"
                                    class="w-full border border-gray-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 rounded-lg px-3 py-2 text-sm transition">
                                    <option value="">Semua</option>
                                    <option value="pending">Menunggu Diproses</option>
                                    <option value="processed">Diproses</option>
                                    <option value="packing">Dikemas</option>
                                    <option value="shipped">Dikirim</option>
                                    <option value="completed">Selesai</option>
                                    <option value="done">Selesai</option>
                                    <option value="cancelled">Dibatalkan</option>
                                </select>
                            </div>
                            <div>
                                <label for="filter-bayar" class="block text-xs font-semibold text-gray-600 mb-1">Status
                                    Pembayaran</label>
                                <select id="filter-bayar"
                                    class="w-full border border-gray-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 rounded-lg px-3 py-2 text-sm transition">
                                    <option value="">Semua</option>
                                    <option value="waiting_confirmation">Menunggu Konfirmasi</option>
                                    <option value="ready_to_pay">Siap Dibayar</option>
                                    <option value="waiting_payment">Menunggu Pembayaran</option>
                                    <option value="waiting_verification">Menunggu Verifikasi</option>
                                    <option value="dp_paid">Dp</option>
                                    <option value="partial_paid">Sebagian Bayar</option>
                                    <option value="paid">Lunas</option>
                                    <option value="rejected">Ditolak</option>
                                    <option value="cancelled">Dibatalkan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm bg-white">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                                <tr>
                                    @if($user && $user->hasRole(['owner', 'admin']))
                                        <th class="px-2 py-2 border font-semibold text-gray-700" style="width:40px;">
                                            <div
                                                style="display:flex;align-items:center;justify-content:center;height:100%;">
                                                <input type="checkbox" id="select-all-checkbox"
                                                    class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                            </div>
                                        </th>
                                    @endif
                                    <th class="px-4 py-2 border font-semibold text-gray-700">ID</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Nama Pelanggan</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Tanggal Ambil/Kirim</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Metode Pengiriman</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Status Pesanan</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Status Bayar</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="orders-table-body" class="bg-white divide-y divide-gray-200 transition-all">
                                {{-- Data rows will be loaded here via AJAX --}}
                                @foreach($orders as $order)
                                    <tr>
                                        @if($user && $user->hasRole(['owner', 'admin']))
                                            <td class="border px-2 py-2" style="width:40px;">
                                                <div
                                                    style="display:flex;align-items:center;justify-content:center;height:100%;">
                                                    <input type="checkbox"
                                                        class="row-checkbox rounded border-gray-300 text-red-600 focus:ring-red-500"
                                                        value="{{ $order->id }}">
                                                </div>
                                            </td>
                                        @endif
                                        @include('admin.public_orders._order_row', ['order' => $order])
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <script>
                            // Event delegation: select all & sync status
                            document.addEventListener('change', function (e) {
                                // Select all
                                if (e.target && e.target.id === 'select-all-checkbox') {
                                    const checkboxes = document.querySelectorAll('.row-checkbox');
                                    checkboxes.forEach(cb => {
                                        cb.checked = e.target.checked;
                                        cb.dispatchEvent(new Event('change'));
                                    });
                                    // Panggil updateDeleteBtn setelah select all
                                    if (typeof window.updateDeleteBtn === 'function') window.updateDeleteBtn();
                                }
                                // Sync select all status
                                if (e.target && e.target.classList.contains('row-checkbox')) {
                                    const checkboxes = document.querySelectorAll('.row-checkbox');
                                    const checked = document.querySelectorAll('.row-checkbox:checked');
                                    const selectAll = document.getElementById('select-all-checkbox');
                                    if (selectAll) {
                                        selectAll.checked = checkboxes.length > 0 && checked.length === checkboxes.length;
                                        selectAll.indeterminate = checked.length > 0 && checked.length < checkboxes.length;
                                    }
                                }
                            });
                        </script>
                        <!-- Pagination removed as requested -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const loading = document.createElement('tr');
        loading.innerHTML = `<td colspan="7" class="py-8 text-center text-blue-500 animate-pulse">Memuat data...</td>`;

        // Debounce util
        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        function fetchOrders() {
            const params = {
                nama: document.getElementById('filter-nama').value,
                tanggal: document.getElementById('filter-tanggal').value,
                metode: document.getElementById('filter-metode').value,
                status: document.getElementById('filter-status').value,
                bayar: document.getElementById('filter-bayar').value,
            };
            const query = new URLSearchParams(params).toString();
            const tbody = document.getElementById('orders-table-body');
            tbody.innerHTML = '';
            tbody.appendChild(loading);
            fetch(`{{ route('admin.public-orders.filter') }}?${query}`)
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = data.rows;
                    document.getElementById('pagination-links').innerHTML = data.pagination;
                    // Panggil ulang updateDeleteBtn agar tombol hapus & select all tetap sinkron
                    if (typeof updateDeleteBtn === 'function') updateDeleteBtn();
                });
        }

        // Debounce hanya untuk input nama (300ms), yang lain langsung
        document.getElementById('filter-nama').addEventListener('input', debounce(fetchOrders, 300));
        document.getElementById('filter-tanggal').addEventListener('change', fetchOrders);
        document.getElementById('filter-metode').addEventListener('change', fetchOrders);
        document.getElementById('filter-status').addEventListener('change', fetchOrders);
        document.getElementById('filter-bayar').addEventListener('change', fetchOrders);
    </script>
</x-app-layout>