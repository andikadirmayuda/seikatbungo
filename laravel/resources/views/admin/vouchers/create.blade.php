<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-pink-100 rounded-lg mr-3">
                    <i class="bi bi-ticket-perforated text-pink-600 text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Tambah Voucher Baru</h1>
                    <p class="text-sm text-gray-500 mt-1">Buat voucher diskon baru untuk pelanggan</p>
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
            <h2 class="text-2xl font-bold mb-6 text-pink-700 flex items-center gap-2">
                <i class="bi bi-ticket-perforated"></i> Tambah Voucher Baru
            </h2>
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
                            <option value="percent">Diskon Persentase</option>
                            <option value="nominal">Diskon Nominal</option>
                            <option value="cashback">Cashback</option>
                            <option value="shipping">Potongan Ongkir</option>
                            <option value="seasonal">Voucher Musiman/Event</option>
                            <option value="first_purchase">Voucher Pembelian Pertama</option>
                            <option value="loyalty">Voucher Member/Loyal Customer</option>
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
                    <div id="maxDiscountGroup" style="display:none;">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maksimum Potongan (untuk
                            persentase)</label>
                        <input type="number" step="0.01" name="maximum_discount" class="form-input w-full"
                            value="{{ old('maximum_discount') }}">
                    </div>
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
                        <input type="checkbox" name="is_active" class="form-checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Aktif</span>
                    </div>
                </div>

                <!-- Field khusus tipe voucher -->
                <div id="seasonalFields" style="display:none;" class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Event</label>
                    <input type="text" name="event_name" class="form-input w-full" value="{{ old('event_name') }}">
                    <label class="block text-sm font-medium text-gray-700 mb-1 mt-2">Tipe Event</label>
                    <input type="text" name="event_type" class="form-input w-full" value="{{ old('event_type') }}">
                </div>
                <div id="firstPurchaseFields" style="display:none;" class="mt-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="first_purchase_only" class="form-checkbox" value="1" {{ old('first_purchase_only') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Hanya untuk pembelian pertama</span>
                    </label>
                </div>
                <div id="loyaltyFields" style="display:none;" class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Poin Member</label>
                    <input type="number" name="minimum_points" class="form-input w-full"
                        value="{{ old('minimum_points') }}">
                    <label class="block text-sm font-medium text-gray-700 mb-1 mt-2">Level Member</label>
                    <input type="text" name="member_level" class="form-input w-full" value="{{ old('member_level') }}">
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
                        class="px-6 py-2 bg-pink-600 text-white rounded hover:bg-pink-700 font-semibold">Simpan</button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>

@push('scripts')
    <script>
        function toggleVoucherFields(select) {
            const type = select.value;
            document.getElementById('maxDiscountGroup').style.display = (type === 'percent') ? 'block' : 'none';
            document.getElementById('seasonalFields').style.display = (type === 'seasonal') ? 'block' : 'none';
            document.getElementById('firstPurchaseFields').style.display = (type === 'first_purchase') ? 'block' : 'none';
            document.getElementById('loyaltyFields').style.display = (type === 'loyalty') ? 'block' : 'none';
        }
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.querySelector('select[name="type"]');
            if (select) toggleVoucherFields(select);
            select.addEventListener('change', function () { toggleVoucherFields(this); });
        });
    </script>
@endpush