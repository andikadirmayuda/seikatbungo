<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-700">Kategori Buket</h1>
            @if(!auth()->user()->hasRole('kasir'))
                <a href="{{ route('bouquet-categories.create') }}"
                    class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Tambah Kategori</a>
            @endif
        </div>
    </x-slot>
    <div class="py-8 text-center">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm bg-white">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gradient-to-r from-pink-50 to-pink-100">
                                <tr>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Kode</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Nama Kategori</th>
                                    @if(!auth()->user()->hasRole('kasir'))
                                        <th class="px-4 py-2 border font-semibold text-gray-700">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 transition-all">
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="px-4 py-2 border">{{ $category->code }}</td>
                                        <td class="px-4 py-2 border">{{ $category->name }}</td>
                                        @if(!auth()->user()->hasRole('kasir'))
                                            <td class="px-4 py-2 border text-sm font-medium space-x-2">
                                                <a href="{{ route('bouquet-categories.edit', $category) }}"
                                                    class="text-green-600 hover:text-green-900">Ubah</a>
                                                <form action="{{ route('bouquet-categories.destroy', $category) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 delete-confirm">Hapus</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 border text-center text-gray-500">Tidak ada
                                            kategori ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4 flex justify-center">
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>