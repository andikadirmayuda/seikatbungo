<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-700">Tambah Buket</h1>
            <a href="{{ route('bouquets.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('bouquets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama Buket</label>
                            <input type="text" name="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required
                                value="{{ old('name') }}">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Gambar</label>
                            <input type="file" name="image"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Harga Buket (per Ukuran)</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($sizes as $size)
                                    <div>
                                        <label class="block text-xs font-semibold mb-1">{{ $size->name }}</label>
                                        <input type="text" name="prices[{{ $size->id }}]"
                                            class="block w-full rounded-md border-gray-300 shadow-sm autonumeric"
                                            placeholder="Harga untuk ukuran {{ $size->name }}"
                                            value="{{ old('prices.' . $size->id) }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('bouquets.index') }}"
                                class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded mr-2">Batal</a>
                            <button type="submit"
                                class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            AutoNumeric.multiple('.autonumeric', {
                digitGroupSeparator: '.',
                decimalCharacter: ',',
                decimalPlaces: 0,
                unformatOnSubmit: true
            });
        });
    </script>
</x-app-layout>