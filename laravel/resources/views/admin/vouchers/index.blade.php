<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-pink-100 rounded-lg mr-3">
                    <i class="bi bi-ticket-perforated text-pink-600 text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Kelola Voucher</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola semua voucher diskon</p>
                </div>
            </div>
            <a href="{{ route('admin.vouchers.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                <i class="bi bi-plus-circle mr-2"></i>
                <span>Tambah Voucher</span>
            </a>
        </div>
    </x-slot>




    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-end mb-4">
            {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#createVoucherModal"
                class="inline-flex items-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold rounded-lg shadow transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Voucher
            </button> --}}
        </div>


        @if(session('success'))
            <div class="max-w-2xl mx-auto mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex items-center justify-between" role="alert">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="ml-4 text-green-700 hover:text-green-900">&times;</button>
                </div>
            </div>
        @endif


        <!-- Filter & Search Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <form action="{{ route('admin.vouchers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Voucher</label>
                    <select name="type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                        <option value="">Semua Tipe</option>
                        <option value="percent" {{ request('type') == 'percent' ? 'selected' : '' }}>Diskon Persentase</option>
                        <option value="nominal" {{ request('type') == 'nominal' ? 'selected' : '' }}>Diskon Nominal</option>
                        <option value="cashback" {{ request('type') == 'cashback' ? 'selected' : '' }}>Cashback</option>
                        <option value="shipping" {{ request('type') == 'shipping' ? 'selected' : '' }}>Potongan Ongkir</option>
                        <option value="seasonal" {{ request('type') == 'seasonal' ? 'selected' : '' }}>Voucher Musiman/Event</option>
                        <option value="first_purchase" {{ request('type') == 'first_purchase' ? 'selected' : '' }}>Voucher Pembelian Pertama</option>
                        <option value="loyalty" {{ request('type') == 'loyalty' ? 'selected' : '' }}>Voucher Member/Loyal Customer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50" placeholder="Cari kode atau deskripsi..." value="{{ request('search') }}">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white font-semibold rounded-lg shadow transition-all duration-200 w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>


        <!-- Vouchers List -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min. Belanja</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penggunaan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($vouchers as $voucher)
                            <tr>
                                <td class="px-4 py-3 align-middle">
                                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-pink-100 text-pink-700 rounded">{{ $voucher->code }}</span>
                                </td>
                                <td class="px-4 py-3 align-middle">{{ $voucher->description }}</td>
                                <td class="px-4 py-3 align-middle">{{ $voucher->getTypeDescription() }}</td>
                                <td class="px-4 py-3 align-middle">{{ $voucher->getFormattedValue() }}</td>
                                <td class="px-4 py-3 align-middle">Rp {{ number_format($voucher->minimum_spend, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 align-middle">
                                    <div class="text-xs text-gray-600">Mulai: {{ $voucher->start_date->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-600">Sampai: {{ $voucher->end_date->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    @php
                                        $status = $voucher->getStatus();
                                        $statusClass = [
                                            'active' => 'bg-green-100 text-green-800',
                                            'inactive' => 'bg-gray-200 text-gray-700',
                                            'expired' => 'bg-red-100 text-red-800',
                                            'exhausted' => 'bg-yellow-100 text-yellow-800',
                                            'pending' => 'bg-blue-100 text-blue-800',
                                        ][$status];
                                    @endphp
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $statusClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    @if($voucher->usage_limit)
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                                @php
                                                    $percentage = ($voucher->usage_count / $voucher->usage_limit) * 100;
                                                @endphp
                                                <div class="h-2.5 rounded-full {{ $percentage >= 80 ? 'bg-red-500' : 'bg-pink-500' }}" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-700">{{ $voucher->usage_count }}/{{ $voucher->usage_limit }}</span>
                                        </div>
                                    @else
                                        <span class="inline-block px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded">
                                            {{ $voucher->usage_count }} kali
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    <div class="flex gap-2">
                                        
                                                                                <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="inline-flex items-center justify-center w-8 h-8 text-pink-600 hover:bg-pink-100 rounded-full focus:outline-none" title="Edit Voucher">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 1 1 2.97 2.97L7.5 19.788l-4 1 1-4 12.362-12.3z" />
                                                                                        </svg>
                                                                                </a>
                                                                                                                        <a href="{{ route('admin.vouchers.show', $voucher) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-100 rounded-full focus:outline-none" title="Detail Voucher">
                                                                                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                                                                                </svg>
                                                                                                                        </a>
                                        <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus voucher ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-100 rounded-full focus:outline-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mb-2 text-gray-300">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m-4.5 4.5a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
                                        </svg>
                                        Belum ada data voucher
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="flex justify-end mt-4">
                {{ $vouchers->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script>
        // Handle Edit Modal
        const editVoucherModal = document.getElementById('editVoucherModal');
        if (editVoucherModal) {
            editVoucherModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const voucher = JSON.parse(button.getAttribute('data-voucher'));

                // Populate form fields
                const form = editVoucherModal.querySelector('form');
                form.querySelector('[name="code"]').value = voucher.code;
                form.querySelector('[name="description"]').value = voucher.description;
                form.querySelector('[name="type"]').value = voucher.type;
                form.querySelector('[name="value"]').value = voucher.value;
                form.querySelector('[name="minimum_spend"]').value = voucher.minimum_spend;
                form.querySelector('[name="maximum_discount"]').value = voucher.maximum_discount;
                form.querySelector('[name="usage_limit"]').value = voucher.usage_limit;
                form.querySelector('[name="start_date"]').value = voucher.start_date.split(' ')[0];
                form.querySelector('[name="end_date"]').value = voucher.end_date.split(' ')[0];
                form.querySelector('[name="is_active"]').checked = voucher.is_active;
            });
        }

        // Dynamic fields based on voucher type
        function toggleVoucherFields(selectElement) {
            const maxDiscountGroup = document.getElementById('maxDiscountGroup');
            const type = selectElement.value;

            if (type === 'percent') {
                maxDiscountGroup.style.display = 'block';
            } else {
                maxDiscountGroup.style.display = 'none';
            }
        }
    </script>
@endpush