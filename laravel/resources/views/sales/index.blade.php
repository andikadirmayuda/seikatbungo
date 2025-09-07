<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-pink-100 rounded-lg mr        -3">
                    <i class="bi bi-cash-coin text-pink-600 text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Daftar Transaksi Penjualan</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola semua transaksi penjualan</p>
                </div>
            </div>
            <a href="{{ route('sales.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                <i class="bi bi-plus-circle mr-2"></i>
                <span class="hidden sm:inline">Transaksi Baru</span>
                <span class="sm:hidden">Baru</span>
            </a>
        </div>
    </x-slot>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm"
                    role="alert">
                    <div class="flex items-center">
                        <i class="bi bi-check-circle-fill mr-2 text-green-600"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Stats Cards (optional) -->
            <div class="mb-6 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg mb-6 shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="bi bi-receipt text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="justify-center items-center ml-3">
                            <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                            <div class="flex items-center ml-3">
                                <p class="text-lg font-semibold text-gray-900">{{ $sales->total() ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Table Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Table Header -->
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Data Transaksi</h3>
                                <p class="mt-1 text-sm text-gray-500">Daftar semua transaksi penjualan</p>
                            </div>

                            <div class="flex items-center gap-2">
                                <!-- Delete Selected Button -->
                                <button id="deleteSelected" disabled
                                    class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white rounded-lg opacity-50 cursor-not-allowed transition-all duration-200"
                                    onclick="confirmBulkDelete()">
                                    <i class="bi bi-trash mr-2"></i>
                                    Hapus Terpilih
                                    <span id="selectedCount"
                                        class="ml-2 bg-red-500 px-2 py-0.5 rounded-full text-xs hidden">0</span>
                                </button>

                                <!-- Search Box -->
                                <div class="relative w-full sm:w-80">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-search text-gray-400"></i>
                                    </div>
                                    <input type="text" id="searchInput"
                                        placeholder="Cari berdasarkan nomor transaksi, metode bayar, atau total..."
                                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition-all duration-200">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <button type="button" id="clearSearch"
                                            class="text-gray-400 hover:text-gray-600 hidden">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table Container -->
                        <div class="overflow-hidden">
                            <!-- Desktop Table -->
                            <div class="hidden lg:block">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gradient-to-r from-pink-50 via-pink-25 to-rose-50">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-pink-100">
                                                <input type="checkbox" id="selectAll"
                                                    class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-pink-100">
                                                No
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-pink-100">
                                                <i class="bi bi-receipt mr-2"></i>No. Penjualan
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-pink-100">
                                                <i class="bi bi-clock-history mr-2"></i>Waktu
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-pink-100">
                                                <i class="bi bi-cash-stack mr-2"></i>Total
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-pink-100">
                                                <i class="bi bi-credit-card mr-2"></i>Metode Pembayaran
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-pink-100">
                                                <i class="bi bi-gear mr-2"></i>Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @forelse($sales as $sale)
                                            <tr class="hover:bg-pink-25 transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="checkbox" name="selected[]" value="{{ $sale->id }}"
                                                        class="sale-checkbox rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ ($sales->currentPage() - 1) * $sales->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-semibold text-gray-900">
                                                        {{ $sale->order_number }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ \Carbon\Carbon::parse($sale->order_time)->format('d/m/Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-semibold text-gray-900">
                                                        Rp {{ number_format($sale->total, 0, ',', '.') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                                                            {{ $sale->payment_method === 'transfer' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                        {{ ucfirst($sale->payment_method) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex items-center space-x-3">
                                                        <a href="{{ route('sales.show', $sale->id) }}"
                                                            class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-md transition-colors duration-150"
                                                            title="Detail">
                                                            <i class="bi bi-eye mr-1"></i>
                                                            <span class="hidden xl:inline">Detail</span>
                                                        </a>
                                                        {{-- <a href="{{ route('sales.show', $sale->id) }}?print=1"
                                                            class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 hover:bg-green-100 rounded-md transition-colors duration-150"
                                                            title="Print">
                                                            <i class="bi bi-printer mr-1"></i>
                                                            <span class="hidden xl:inline">Print</span>
                                                        </a> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <i class="bi bi-inbox text-gray-300 text-4xl mb-4"></i>
                                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada
                                                            transaksi
                                                        </h3>
                                                        <p class="text-gray-500 mb-4">Mulai buat transaksi penjualan pertama
                                                            Anda
                                                        </p>
                                                        <a href="{{ route('sales.create') }}"
                                                            class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">
                                                            <i class="bi bi-plus-circle mr-2"></i>
                                                            Buat Transaksi
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Cards -->
                            <div class="lg:hidden">
                                @forelse($sales as $sale)
                                    <div class="border-b border-gray-200 last:border-b-0">
                                        <div class="p-4 hover:bg-pink-25 transition-colors duration-150">
                                            <!-- Header Row -->
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                                        <i class="bi bi-receipt text-pink-600 text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900">
                                                            {{ $sale->order_number }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ \Carbon\Carbon::parse($sale->order_time)->format('d/m/Y H:i') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                                                                        {{ $sale->payment_method === 'transfer' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ ucfirst($sale->payment_method) }}
                                                </span>
                                            </div>

                                            <!-- Content Row -->
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-lg font-bold text-gray-900">
                                                        Rp {{ number_format($sale->total, 0, ',', '.') }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">Total Transaksi</p>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('sales.show', $sale->id) }}"
                                                        class="inline-flex items-center justify-center w-9 h-9 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-full transition-colors duration-150"
                                                        title="Detail">
                                                        <i class="bi bi-eye text-sm"></i>
                                                    </a>
                                                    <a href="{{ route('sales.show', $sale->id) }}?print=1"
                                                        class="inline-flex items-center justify-center w-9 h-9 bg-green-50 text-green-700 hover:bg-green-100 rounded-full transition-colors duration-150"
                                                        title="Print">
                                                        <i class="bi bi-printer text-sm"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-8 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bi bi-inbox text-gray-300 text-4xl mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada transaksi</h3>
                                            <p class="text-gray-500 mb-4 text-center">Mulai buat transaksi penjualan pertama
                                                Anda
                                            </p>
                                            <a href="{{ route('sales.create') }}"
                                                class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">
                                                <i class="bi bi-plus-circle mr-2"></i>
                                                Buat Transaksi
                                            </a>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Pagination -->
                        @if($sales->hasPages())
                            <div class="px-4 py-4 sm:px-6 border-t border-gray-200 bg-gray-50">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div class="text-sm text-gray-700">
                                        Menampilkan {{ $sales->firstItem() ?? 0 }} sampai {{ $sales->lastItem() ?? 0 }}
                                        dari {{ $sales->total() }} transaksi
                                    </div>
                                    <div class="flex justify-center sm:justify-end">
                                        {{ $sales->links('pagination::tailwind') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bulk Delete Modal -->
            <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
                <div class="bg-white rounded-lg p-8 max-w-md mx-auto">
                    <div class="text-center">
                        <i class="bi bi-exclamation-triangle text-red-500 text-5xl mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                        <p class="text-gray-500 mb-6">Anda yakin ingin menghapus <span id="deleteCount"
                                class="font-semibold"></span> transaksi yang dipilih? Tindakan ini tidak dapat
                            dibatalkan.</p>
                        <div class="flex justify-center space-x-4">
                            <button onclick="closeDeleteModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                                Batal
                            </button>
                            <button onclick="executeBulkDelete()"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Ya, Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Live Search JavaScript -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Bulk Delete functionality
                    const selectAll = document.getElementById('selectAll');
                    const deleteSelected = document.getElementById('deleteSelected');
                    const selectedCount = document.getElementById('selectedCount');
                    const checkboxes = document.getElementsByClassName('sale-checkbox');

                    // Select All functionality
                    selectAll?.addEventListener('change', function () {
                        const isChecked = this.checked;
                        Array.from(checkboxes).forEach(checkbox => {
                            checkbox.checked = isChecked;
                        });
                        updateDeleteButton();
                    });

                    // Individual checkbox functionality
                    Array.from(checkboxes).forEach(checkbox => {
                        checkbox.addEventListener('change', updateDeleteButton);
                    });

                    // Update delete button state
                    function updateDeleteButton() {
                        const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
                        const count = checkedBoxes.length;

                        if (count > 0) {
                            deleteSelected.disabled = false;
                            deleteSelected.classList.remove('opacity-50', 'cursor-not-allowed');
                            selectedCount.textContent = count;
                            selectedCount.classList.remove('hidden');
                        } else {
                            deleteSelected.disabled = true;
                            deleteSelected.classList.add('opacity-50', 'cursor-not-allowed');
                            selectedCount.classList.add('hidden');
                        }

                        // Update select all checkbox
                        if (checkboxes.length > 0) {
                            selectAll.checked = count === checkboxes.length;
                            selectAll.indeterminate = count > 0 && count < checkboxes.length;
                        }
                    }

                    window.confirmBulkDelete = function () {
                        const count = Array.from(checkboxes).filter(cb => cb.checked).length;
                        document.getElementById('deleteCount').textContent = count;
                        document.getElementById('deleteModal').classList.remove('hidden');
                        document.getElementById('deleteModal').classList.add('flex');
                    }

                    window.closeDeleteModal = function () {
                        document.getElementById('deleteModal').classList.add('hidden');
                        document.getElementById('deleteModal').classList.remove('flex');
                    }

                    window.executeBulkDelete = function () {
                        const selectedIds = Array.from(checkboxes)
                            .filter(cb => cb.checked)
                            .map(cb => cb.value);

                        // Create form and submit
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('sales.bulk-delete') }}';

                        // Add CSRF token
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        // Add selected IDs
                        selectedIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = id;
                            form.appendChild(input);
                        });

                        document.body.appendChild(form);
                        form.submit();
                    }
                    const searchInput = document.getElementById('searchInput');
                    const clearButton = document.getElementById('clearSearch');
                    const desktopTableBody = document.querySelector('.hidden.lg\\:block tbody');
                    const mobileCards = document.querySelector('.lg\\:hidden');
                    let originalDesktopRows = [];
                    let originalMobileCards = [];

                    // Store original content
                    if (desktopTableBody) {
                        originalDesktopRows = Array.from(desktopTableBody.children);
                    }
                    if (mobileCards) {
                        originalMobileCards = Array.from(mobileCards.children);
                    }

                    // Search functionality
                    function performSearch(query) {
                        const searchTerm = query.toLowerCase().trim();

                        if (searchTerm === '') {
                            // Show all original content
                            if (desktopTableBody) {
                                desktopTableBody.innerHTML = '';
                                originalDesktopRows.forEach(row => desktopTableBody.appendChild(row));
                            }
                            if (mobileCards) {
                                mobileCards.innerHTML = '';
                                originalMobileCards.forEach(card => mobileCards.appendChild(card));
                            }
                            clearButton.classList.add('hidden');
                            return;
                        }

                        clearButton.classList.remove('hidden');

                        // Filter desktop table rows
                        if (desktopTableBody) {
                            const filteredDesktopRows = originalDesktopRows.filter(row => {
                                const text = row.textContent.toLowerCase();
                                return text.includes(searchTerm);
                            });

                            desktopTableBody.innerHTML = '';
                            if (filteredDesktopRows.length > 0) {
                                filteredDesktopRows.forEach(row => desktopTableBody.appendChild(row.cloneNode(true)));
                            } else {
                                desktopTableBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="bi bi-search text-gray-300 text-4xl mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada hasil pencarian</h3>
                                        <p class="text-gray-500 mb-4">Coba gunakan kata kunci yang berbeda</p>
                                        <button onclick="document.getElementById('searchInput').value=''; document.getElementById('searchInput').dispatchEvent(new Event('input'))" 
                                                class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">
                                            <i class="bi bi-arrow-clockwise mr-2"></i>
                                            Reset Pencarian
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                            }
                        }

                        // Filter mobile cards
                        if (mobileCards) {
                            const filteredMobileCards = originalMobileCards.filter(card => {
                                const text = card.textContent.toLowerCase();
                                return text.includes(searchTerm);
                            });

                            mobileCards.innerHTML = '';
                            if (filteredMobileCards.length > 0) {
                                filteredMobileCards.forEach(card => mobileCards.appendChild(card.cloneNode(true)));
                            } else {
                                mobileCards.innerHTML = `
                            <div class="p-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="bi bi-search text-gray-300 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada hasil pencarian</h3>
                                    <p class="text-gray-500 mb-4 text-center">Coba gunakan kata kunci yang berbeda</p>
                                    <button onclick="document.getElementById('searchInput').value=''; document.getElementById('searchInput').dispatchEvent(new Event('input'))" 
                                            class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">
                                        <i class="bi bi-arrow-clockwise mr-2"></i>
                                        Reset Pencarian
                                    </button>
                                </div>
                            </div>
                        `;
                            }
                        }
                    }

                    // Event listeners
                    searchInput.addEventListener('input', function (e) {
                        performSearch(e.target.value);
                    });

                    clearButton.addEventListener('click', function () {
                        searchInput.value = '';
                        performSearch('');
                        searchInput.focus();
                    });

                    // Keyboard shortcuts
                    document.addEventListener('keydown', function (e) {
                        // Ctrl/Cmd + K to focus search
                        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                            e.preventDefault();
                            searchInput.focus();
                        }

                        // Escape to clear search
                        if (e.key === 'Escape' && document.activeElement === searchInput) {
                            clearButton.click();
                        }
                    });

                    // Add search hints
                    searchInput.addEventListener('focus', function () {
                        this.placeholder = 'Ketik untuk mencari... (Ctrl+K)';
                    });

                    searchInput.addEventListener('blur', function () {
                        this.placeholder = 'Cari berdasarkan nomor transaksi, metode bayar, atau total...';
                    });
                });
            </script>
</x-app-layout>