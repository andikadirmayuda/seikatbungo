@props(['vouchers'])

<div class="bg-white rounded-lg shadow">
    {{-- Filter dan Search --}}
    <div class="p-4 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row gap-4">
            {{-- Search --}}
            <div class="flex-1">
                <form method="GET" class="flex gap-2">
                    <input type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari kode atau deskripsi..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500">
                    <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                        <i class="bi bi-search mr-2"></i>
                        Cari
                    </button>
                </form>
            </div>

            {{-- Filter Status --}}
            <div class="sm:w-48">
                <select name="status" 
                    onchange="this.form.submit()"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                        Non-aktif
                    </option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                        Kadaluarsa
                    </option>
                </select>
            </div>

            {{-- Filter Tipe --}}
            <div class="sm:w-48">
                <select name="type" 
                    onchange="this.form.submit()"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500">
                    <option value="">Semua Tipe</option>
                    <option value="percent" {{ request('type') == 'percent' ? 'selected' : '' }}>
                        Diskon Persentase
                    </option>
                    <option value="nominal" {{ request('type') == 'nominal' ? 'selected' : '' }}>
                        Diskon Nominal
                    </option>
                    <option value="shipping" {{ request('type') == 'shipping' ? 'selected' : '' }}>
                        Potongan Ongkir
                    </option>
                    <option value="cashback" {{ request('type') == 'cashback' ? 'selected' : '' }}>
                        Cashback
                    </option>
                    <option value="seasonal" {{ request('type') == 'seasonal' ? 'selected' : '' }}>
                        Musiman/Event
                    </option>
                    <option value="first_purchase" {{ request('type') == 'first_purchase' ? 'selected' : '' }}>
                        Pembelian Pertama
                    </option>
                    <option value="loyalty" {{ request('type') == 'loyalty' ? 'selected' : '' }}>
                        Member
                    </option>
                </select>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kode
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tipe & Nilai
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Min. Belanja
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Penggunaan
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Periode
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($vouchers as $voucher)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $voucher->code }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ Str::limit($voucher->description, 30) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $voucher->getTypeDescription() }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $voucher->getFormattedValue() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $voucher->getFormattedMinimumSpend() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $voucher->getUsageInfo() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @switch($voucher->getStatus())
                                    @case('active')
                                        bg-green-100 text-green-800
                                        @break
                                    @case('pending')
                                        bg-yellow-100 text-yellow-800
                                        @break
                                    @case('expired')
                                        bg-red-100 text-red-800
                                        @break
                                    @case('exhausted')
                                        bg-gray-100 text-gray-800
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800
                                @endswitch
                            ">
                                {{ ucfirst($voucher->getStatus()) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $voucher->start_date->format('d M Y H:i') }}</div>
                            <div>{{ $voucher->end_date->format('d M Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.vouchers.show', $voucher) }}" 
                                class="text-rose-600 hover:text-rose-900">
                                Detail
                            </a>
                            <a href="{{ route('admin.vouchers.edit', $voucher) }}" 
                                class="text-indigo-600 hover:text-indigo-900">
                                Edit
                            </a>
                            @if($voucher->usage_count === 0)
                                <form action="{{ route('admin.vouchers.destroy', $voucher) }}" 
                                    method="POST" 
                                    class="inline-block"
                                    onsubmit="return confirm('Yakin ingin menghapus voucher ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="text-red-600 hover:text-red-900">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada voucher yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="p-4 border-t border-gray-200">
        {{ $vouchers->links() }}
    </div>
</div>
