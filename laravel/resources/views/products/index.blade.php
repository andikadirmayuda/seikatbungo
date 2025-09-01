<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-700">Daftar Produk</h1>
            @if(!auth()->user()->hasRole(['kasir', 'karyawan']))
                <a href="{{ route('products.create') }}"
                    class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Tambah Produk</a>
            @endif
        </div>
    </x-slot>



    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-sm">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Live Search & Filter Input -->
                    <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 w-full">
                        <div class="w-full sm:w-1/2">
                            <label for="searchInput" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="bi bi-search mr-1 text-pink-500"></i>
                                Pencarian Produk
                            </label>
                            <input type="text" id="searchInput" placeholder="Cari kode, nama produk, atau deskripsi..."
                                class="w-full px-4 py-3 border border-pink-400 rounded-sm input-focus focus:outline-none" />
                        </div>
                        <div class="w-full sm:w-1/3">
                            <label for="categoryFilter" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="bi bi-tags mr-1 text-pink-500"></i>
                                Filter Kategori
                            </label>
                            <select id="categoryFilter"
                                class="w-full px-4 py-3 border-0 rounded-sm bg-gray-50 focus:outline-none focus:bg-white focus:shadow-md transition-all duration-200">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ strtolower($name) }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:w-1/4">
                            <label for="statusFilter" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="bi bi-toggle-on mr-1 text-pink-500"></i>
                                Filter Status
                            </label>
                            <select id="statusFilter"
                                class="w-full px-4 py-3 border-0 rounded-sm bg-gray-50 focus:outline-none focus:bg-white focus:shadow-md transition-all duration-200">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak aktif">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Results Counter -->
                    <div class="mb-4 flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <span id="resultsCounter">{{ $products->count() }}</span> produk ditemukan
                        </div>
                        <button id="clearFilters" class="text-pink-600 hover:text-pink-800 text-sm font-medium hidden">
                            <i class="bi bi-x-circle mr-1"></i>
                            Bersihkan Filter
                        </button>
                    </div>

                    <div class="overflow-x-auto rounded-sm border border-gray-200 shadow-sm bg-white">
                        <table class="min-w-full divide-y divide-gray-200 text-sm" id="productsTable">
                            <thead class="bg-gradient-to-r from-pink-50 to-pink-100">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                        <i class="bi bi-list-ol mr-1"></i>
                                        No
                                    </th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                        <i class="bi bi-hash mr-1"></i>
                                        Kode
                                    </th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                        <i class="bi bi-box mr-1"></i>
                                        Nama
                                    </th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                        <i class="bi bi-tags mr-1"></i>
                                        Kategori
                                    </th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                        <i class="bi bi-archive mr-1"></i>
                                        Stok
                                    </th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                        <i class="bi bi-toggle-on mr-1"></i>
                                        Status
                                    </th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                        <i class="bi bi-gear mr-1"></i>
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 transition-all">
                                @forelse($products as $loop_index => $product)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-center font-medium text-gray-500">{{ $loop_index + 1 }}
                                        </td>
                                        <td class="px-4 py-3 font-mono text-sm">{{ $product->code }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                                        <td class="px-4 py-3">{{ $product->category->name }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center space-x-2">
                                                <span class="font-medium">{{ $product->formatted_stock }}</span>
                                                @if($product->needs_restock)
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        <i class="bi bi-exclamation-triangle mr-1"></i>
                                                        Stok Rendah
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                <i
                                                    class="bi bi-{{ $product->is_active ? 'check-circle' : 'x-circle' }} mr-1"></i>
                                                {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('products.show', $product) }}"
                                                    class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded-sm hover:bg-blue-200 transition-colors">
                                                    <i class="bi bi-eye mr-1"></i>
                                                    Detail
                                                </a>
                                                @if(!auth()->user()->hasRole(['kasir', 'karyawan']))
                                                    <a href="{{ route('products.edit', $product) }}"
                                                        class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 rounded-sm hover:bg-green-200 transition-colors">
                                                        <i class="bi bi-pencil mr-1"></i>
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 rounded-sm hover:bg-red-200 transition-colors delete-confirm">
                                                            <i class="bi bi-trash mr-1"></i>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="noDataRow">
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <i class="bi bi-inbox text-4xl text-gray-300 mb-2"></i>
                                                <p class="font-medium">Tidak ada data produk</p>
                                                <p class="text-sm">Silakan tambahkan produk baru</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                <!-- No Results Row (hidden by default) -->
                                <tr id="noResultsRow" style="display: none;">
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="bi bi-search text-4xl text-gray-300 mb-2"></i>
                                            <p class="font-medium">Tidak ada produk yang sesuai</p>
                                            <p class="text-sm">Coba ubah kata kunci atau filter pencarian</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Search & Filter Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const statusFilter = document.getElementById('statusFilter');
            const table = document.getElementById('productsTable');
            const rows = table.querySelectorAll('tbody tr:not(#noResultsRow):not(#noDataRow)');
            const noResultsRow = document.getElementById('noResultsRow');
            const noDataRow = document.getElementById('noDataRow');
            const resultsCounter = document.getElementById('resultsCounter');
            const clearFilters = document.getElementById('clearFilters');

            let totalProducts = {{ $products->count() }};

            function updateResultsCounter(visibleCount) {
                resultsCounter.textContent = visibleCount;
            }

            function toggleClearButton() {
                const hasFilters = searchInput.value || categoryFilter.value || statusFilter.value;
                clearFilters.classList.toggle('hidden', !hasFilters);
            }

            function filterRows() {
                const query = searchInput.value.toLowerCase();
                const selectedCategory = categoryFilter.value.toLowerCase();
                const selectedStatus = statusFilter.value.toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    // Kolom: 0=No, 1=Kode, 2=Nama, 3=Kategori, 4=Stok, 5=Status, 6=Aksi
                    const categoryCell = row.children[3]?.textContent.toLowerCase() || '';
                    const statusCell = row.children[5]?.textContent.toLowerCase() || '';

                    const matchText = !query || text.includes(query);
                    const matchCategory = !selectedCategory || categoryCell.includes(selectedCategory);
                    const matchStatus = !selectedStatus || statusCell.includes(selectedStatus);

                    const isVisible = matchText && matchCategory && matchStatus;
                    row.style.display = isVisible ? '' : 'none';

                    if (isVisible) visibleCount++;
                });

                // Handle no results display
                if (noDataRow) noDataRow.style.display = 'none';

                if (visibleCount === 0 && rows.length > 0) {
                    noResultsRow.style.display = '';
                } else {
                    noResultsRow.style.display = 'none';
                }

                updateResultsCounter(visibleCount);
                toggleClearButton();
            }

            // Event listeners
            searchInput.addEventListener('input', filterRows);
            categoryFilter.addEventListener('change', filterRows);
            statusFilter.addEventListener('change', filterRows);

            clearFilters.addEventListener('click', function () {
                searchInput.value = '';
                categoryFilter.selectedIndex = 0;
                statusFilter.selectedIndex = 0;
                filterRows();
            });

            // Initialize
            toggleClearButton();

            // Enhanced delete confirmation
            document.querySelectorAll('.delete-confirm').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const productName = this.closest('tr').children[1].textContent.trim();

                    if (confirm(`Apakah Anda yakin ingin menghapus produk "${productName}"?\n\nTindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait produk ini.`)) {
                        this.closest('form').submit();
                    }
                });
            });

            // Auto-focus search when pressing Ctrl/Cmd + F
            document.addEventListener('keydown', function (e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    searchInput.focus();
                    searchInput.select();
                }
            });

            // Search functionality is handled by CSS focus states
        });
    </script>
</x-app-layout>