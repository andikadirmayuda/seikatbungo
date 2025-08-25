<x-app-layout>
    <x-slot name="header">
        @if(!auth()->user()->hasRole('kasir'))
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-pink-700">Daftar Kategori</h1>
                <a href="{{ route('categories.create') }}"
                    class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Tambah Kategori</a>
            </div>
        @endif
    </x-slot>

    <style>
        /* Custom Pagination Styling */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .pagination .page-item {
            display: inline-block;
        }

        .pagination .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            padding: 0.5rem;
            margin: 0 0.125rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: rgb(107, 114, 128);
            background: white;
            border: 1px solid rgba(244, 63, 94, 0.1);
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .pagination .page-link:hover {
            color: rgb(244, 63, 94);
            background: rgba(244, 63, 94, 0.05);
            border-color: rgba(244, 63, 94, 0.2);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .pagination .page-item.active .page-link {
            color: white;
            background: linear-gradient(135deg, rgb(244, 63, 94), rgb(225, 29, 72));
            border-color: rgb(244, 63, 94);
            box-shadow: 0 4px 6px -1px rgba(244, 63, 94, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            color: rgb(156, 163, 175);
            background: rgb(249, 250, 251);
            border-color: rgb(229, 231, 235);
            cursor: not-allowed;
        }

        .pagination .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Previous/Next pagination styling */
        .pagination .page-link[rel="prev"],
        .pagination .page-link[rel="next"] {
            width: auto;
            padding: 0.5rem 0.75rem;
            font-weight: 600;
        }
    </style>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm bg-white">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gradient-to-r from-pink-50 to-pink-100">
                                <tr>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Kode</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Nama</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 transition-all">
                                @forelse ($categories as $category)
                                    <tr>
                                        <td class="px-4 py-2 border">{{ $category->code }}</td>
                                        <td class="px-4 py-2 border">{{ $category->name }}</td>
                                        <td class="px-4 py-2 border text-sm font-medium space-x-2">
                                            <a href="{{ route('categories.edit', $category) }}"
                                                class="text-green-600 hover:text-green-900">Ubah</a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 delete-confirm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 border text-center text-gray-500">
                                            Tidak ada kategori yang ditemukan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-6 flex justify-center">
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                                {{ $categories->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>