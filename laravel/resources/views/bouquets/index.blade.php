<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-700">Master Buket</h1>
            @if(!auth()->user()->hasRole('kasir'))
                <a href="{{ route('bouquets.create') }}"
                    class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Tambah Buket</a>
            @endif
        </div>
    </x-slot>
    <div class="py-8 text-center">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    <!-- Search & Filter Input -->
                    <div class="mb-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 w-full">
                        <div class="w-full sm:w-1/2">
                            <input type="text" id="searchInput" placeholder="Cari buket..."
                                class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-400" />
                        </div>
                        <div class="w-full sm:w-1/3 flex sm:justify-end">
                            <select id="categoryFilter"
                                class="border border-gray-300 rounded px-3 py-2 w-full sm:w-48 focus:outline-none focus:ring-2 focus:ring-pink-400">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ strtolower($cat->name) }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm bg-white">
                        <table class="min-w-full divide-y divide-gray-200 text-sm" id="bouquetTable">
                            <thead class="bg-gradient-to-r from-pink-50 to-pink-100">
                                <tr>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Nama Buket</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Kategori</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Deskripsi</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Gambar</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 transition-all">
                                @forelse($bouquets as $bouquet)
                                    <tr>
                                        <td class="px-4 py-2 border">{{ $bouquet->name }}</td>
                                        <td class="px-4 py-2 border">{{ $bouquet->category->name ?? '-' }}</td>
                                        <td class="px-4 py-2 border">{{ $bouquet->description }}</td>
                                        <td class="px-4 py-2 border">
                                            @if($bouquet->image)
                                                <img src="{{ asset('storage/' . $bouquet->image) }}" alt="Gambar Buket"
                                                    class="h-12 rounded shadow items-center mx-auto">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 border text-sm font-medium space-x-2">
                                            <a href="{{ route('bouquets.show', $bouquet) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Lihat</a>

                                            @if(!auth()->user()->hasRole('kasir'))
                                                <a href="{{ route('bouquets.edit', $bouquet) }}"
                                                    class="text-green-600 hover:text-green-900">Ubah</a>
                                                <form action="{{ route('bouquets.destroy', $bouquet) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 delete-confirm">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-2 border text-center text-gray-500">Tidak ada buket
                                            ditemukan</td>
                                    </tr>
                                @endforelse
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
            const table = document.getElementById('bouquetTable');
            const rows = table.querySelectorAll('tbody tr');

            function filterRows() {
                const query = searchInput.value.toLowerCase();
                const selectedCategory = categoryFilter.value;
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const catCell = row.children[1]?.textContent.toLowerCase() || '';
                    const matchCategory = !selectedCategory || catCell === selectedCategory;
                    const matchText = text.includes(query);
                    row.style.display = (matchCategory && matchText) ? '' : 'none';
                });
            }
            searchInput.addEventListener('input', filterRows);
            categoryFilter.addEventListener('change', filterRows);
        });
    </script>
</x-app-layout>