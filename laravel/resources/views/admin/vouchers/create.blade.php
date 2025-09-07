<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-pink-100 rounded-lg mr-3">
                    <i class="bi bi-ticket-perforated text-pink-600 text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Buat Voucher</h1>
                    <p class="text-sm text-gray-500 mt-1">Tambah voucher diskon baru</p>
                </div>
            </div>
            <a href="{{ route('admin.vouchers.index') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                <i class="bi bi-arrow-left mr-2"></i>
                <span>Kembali</span>
            </a>
        </div>
    </x-slot>
    <div class="max-w-2xl mx-auto py-8">
        @if ($errors->any())
            <div class="mb-6">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Terjadi kesalahan:</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Voucher</label>
                        <input type="text" name="code" class="form-input w-full" required value="{{ old('code') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <input type="text" name="description" class="form-input w-full" required
                            value="{{ old('description') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Voucher</label>
                        <select name="type" class="form-select w-full" required onchange="toggleVoucherFields(this)">
                            <option value="">Pilih Tipe</option>
                            <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Diskon Persentase
                            </option>
                            <option value="nominal" {{ old('type') == 'nominal' ? 'selected' : '' }}>Diskon Nominal
                            </option>
                            <option value="cashback" {{ old('type') == 'cashback' ? 'selected' : '' }}>Cashback</option>
                            <option value="shipping" {{ old('type') == 'shipping' ? 'selected' : '' }}>Potongan Ongkir
                            </option>
                            <option value="seasonal" {{ old('type') == 'seasonal' ? 'selected' : '' }}>Voucher
                                Musiman/Event</option>
                            <option value="first_purchase" {{ old('type') == 'first_purchase' ? 'selected' : '' }}>Voucher
                                Pembelian Pertama</option>
                            <option value="loyalty" {{ old('type') == 'loyalty' ? 'selected' : '' }}>Voucher Member/Loyal
                                Customer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Voucher</label>
                        <input type="number" step="0.01" name="value" class="form-input w-full" required
                            value="{{ old('value') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Belanja</label>
                        <input type="number" step="0.01" name="minimum_spend" class="form-input w-full" required
                            value="{{ old('minimum_spend') }}">
                    </div>
                    <!-- Field Maksimum Potongan (untuk persentase) dihapus sesuai permintaan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Batas Penggunaan</label>
                        <input type="number" name="usage_limit" class="form-input w-full"
                            value="{{ old('usage_limit') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-input w-full" required
                            value="{{ old('start_date') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berakhir</label>
                        <input type="date" name="end_date" class="form-input w-full" required
                            value="{{ old('end_date') }}">
                    </div>
                    <div class="flex items-center mt-4">
                        <input type="checkbox" name="active" class="form-checkbox" value="1" {{ old('active', '1') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Aktif</span>
                    </div>
                </div>
                <!-- Field khusus tipe voucher -->
                <div id="seasonalFields" style="display:none;" class="mt-4">
                    <!-- (Field Nama Event & Tipe Event dihapus sesuai permintaan) -->
                </div>


                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Syarat & Ketentuan</label>
                    <textarea name="terms_and_conditions" class="form-textarea w-full"
                        rows="3">{{ old('terms_and_conditions') }}</textarea>
                </div>
                <div class="mt-6 flex justify-end">
                    <a href="{{ route('admin.vouchers.index') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded mr-2">Batal</a>
                    <button type="submit"
                        class="px-6 py-2 bg-pink-600 text-white rounded hover:bg-pink-700 font-semibold">Buat
                        Voucher</button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            function toggleVoucherFields(select) {
                const type = select.value;
                // Field Maksimum Potongan (untuk persentase) dihapus, tidak perlu toggle display
                document.getElementById('seasonalFields').style.display = (type === 'seasonal') ? 'block' : 'none';
                document.getElementById('firstPurchaseFields').style.display = (type === 'first_purchase') ? 'block' : 'none';
                document.getElementById('loyaltyFields').style.display = (type === 'loyalty') ? 'block' : 'none';

                // Atur required pada event_name dan event_type jika seasonal
                // (Field Nama Event & Tipe Event dihapus, tidak perlu toggle display/required)
            }
            document.addEventListener('DOMContentLoaded', function () {
                const select = document.querySelector('select[name="type"]');
                if (select) toggleVoucherFields(select);
                select.addEventListener('change', function () { toggleVoucherFields(this); });
            });
        </script>
    @endpush
</x-app-layout>