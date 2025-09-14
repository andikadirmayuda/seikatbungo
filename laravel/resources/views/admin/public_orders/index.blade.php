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
                        <!-- Container untuk tombol hapus & filter pencarian sejajar di desktop/tablet -->
                        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
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
                            <!-- Filter Bar Pencarian Client-side (dipindah ke sini) -->
                            <div class="relative w-full sm:w-80">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                                    </svg>
                                </div>
                                <input type="text" id="searchInput" placeholder="Cari pesanan publik..."
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" id="clearSearch" class="text-gray-400 hover:text-gray-600 hidden">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
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
                    <!-- Filter Bar Pencarian dipindah ke atas, sejajar tombol hapus -->
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
    <!-- Script filter pencarian client-side -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const clearButton = document.getElementById('clearSearch');
            const tableBody = document.getElementById('orders-table-body');
            let originalRows = [];

            // Simpan baris asli saat load
            if (tableBody) {
                originalRows = Array.from(tableBody.children);
            }

            function performSearch(query) {
                const searchTerm = query.toLowerCase().trim();
                if (searchTerm === '') {
                    // Tampilkan semua baris asli
                    if (tableBody) {
                        tableBody.innerHTML = '';
                        originalRows.forEach(row => tableBody.appendChild(row));
                    }
                    clearButton.classList.add('hidden');
                    if (typeof window.updateDeleteBtn === 'function') window.updateDeleteBtn();
                    return;
                }
                clearButton.classList.remove('hidden');
                // Filter baris
                if (tableBody) {
                    const filteredRows = originalRows.filter(row => {
                        const text = row.textContent.toLowerCase();
                        return text.includes(searchTerm);
                    });
                    tableBody.innerHTML = '';
                    if (filteredRows.length > 0) {
                        filteredRows.forEach(row => tableBody.appendChild(row.cloneNode(true)));
                    } else {
                        tableBody.innerHTML = `<tr><td colspan="8" class="py-8 text-center text-gray-500">Tidak ada hasil pencarian</td></tr>`;
                    }
                }
                if (typeof window.updateDeleteBtn === 'function') window.updateDeleteBtn();
            }

            searchInput.addEventListener('input', function (e) {
                performSearch(e.target.value);
            });
            clearButton.addEventListener('click', function () {
                searchInput.value = '';
                performSearch('');
                searchInput.focus();
            });
            document.addEventListener('keydown', function (e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    searchInput.focus();
                }
                if (e.key === 'Escape' && document.activeElement === searchInput) {
                    clearButton.click();
                }
            });
            searchInput.addEventListener('focus', function () {
                this.placeholder = 'Ketik untuk mencari... (Ctrl+K)';
            });
            searchInput.addEventListener('blur', function () {
                this.placeholder = 'Cari pesanan publik...';
            });
        });
    </script>
</x-app-layout>