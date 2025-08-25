<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-pink-700">
                Edit Buket - {{ $bouquet->name }}
            </h2>
            <a href="{{ route('bouquets.index') }}" 
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('bouquets.update', $bouquet) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Nama Buket -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Buket</label>
                            <input type="text" name="name" id="name" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                value="{{ old('name', $bouquet->name) }}" required>
                        </div>

                        <!-- Kategori -->
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category_id" id="category_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id', $bouquet->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $bouquet->description) }}</textarea>
                        </div>

                        <!-- Gambar -->
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Gambar</label>
                            @if($bouquet->image)
                                <div class="mt-2 mb-2">
                                    <!-- Debug info -->
                                    <p class="text-sm text-gray-500 mb-2">Image path: {{ $bouquet->image }}</p>
                                    <p class="text-sm text-gray-500 mb-2">Full URL: {{ asset('storage/' . $bouquet->image) }}</p>
                                    <img src="{{ asset('storage/' . $bouquet->image) }}" alt="Current Image" class="h-32 w-auto object-cover rounded">
                                </div>
                            @else
                                <p class="text-sm text-gray-500 mb-2">No image available</p>
                            @endif
                            <input type="file" name="image" id="image" 
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-sm text-gray-500">Biarkan kosong jika tidak ingin mengubah gambar</p>
                        </div>

                        <!-- Harga per Ukuran -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Ukuran</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($sizes as $size)
                                    <div class="border rounded-md p-4">
                                        <input type="hidden" name="sizes[]" value="{{ $size->id }}">
                                        <label class="block text-sm font-medium text-gray-700">{{ $size->name }}</label>
                                        <input type="text" 
                                            name="prices[]" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 autonumeric" 
                                            placeholder="Harga untuk ukuran {{ $size->name }}" 
                                            value="{{ old('prices.' . $loop->index, $bouquet->prices->where('size_id', $size->id)->first()?->price) }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('bouquets.show', $bouquet) }}" 
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AutoNumeric.multiple('.autonumeric', {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            decimalPlaces: 0,
            unformatOnSubmit: true
        });
    });
</script>
</x-app-layout>
