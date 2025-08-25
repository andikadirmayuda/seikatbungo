<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight mb-2">
            Kelola Komponen: {{ $bouquet->name }} (Ukuran: {{ $size->name }})
        </h2>
        <a href="{{ route('bouquet-components.index') }}" class="text-pink-600 hover:underline">&larr; Kembali ke Daftar</a>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto bg-white shadow-xl rounded-lg p-8">
            <h3 class="font-semibold text-lg mb-4">Daftar Komponen</h3>
            <table class="w-full mb-6">
                <thead>
                    <tr class="text-left text-xs text-gray-500">
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($components as $comp)
                        <tr class="border-b last:border-b-0">
                            <td class="py-2">{{ $comp->product->name }}</td>
                            <td class="py-2">{{ $comp->quantity }} {{ $comp->product->base_unit ?? 'pcs' }}</td>
                            <td class="py-2">
                                <a href="{{ route('bouquet-components.edit', $comp->id) }}" class="text-indigo-600 hover:underline mr-2">Edit</a>
                                <form action="{{ route('bouquet-components.destroy', $comp->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Hapus komponen ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-gray-400 py-4">Belum ada komponen.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <h3 class="font-semibold text-lg mb-2">Tambah Komponen Baru</h3>
            <form action="{{ route('bouquet-components.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="bouquet_id" value="{{ $bouquet->id }}">
                <input type="hidden" name="size_id" value="{{ $size->id }}">
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Produk</label>
                        <select name="components[0][product_id]" class="w-full border-gray-300 rounded-lg">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-32">
                        <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                        <input type="number" name="components[0][quantity]" min="1" class="w-full border-gray-300 rounded-lg" />
                    </div>
                </div>
                <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded-lg">Tambah Komponen</button>
            </form>
        </div>
    </div>
</x-app-layout>
