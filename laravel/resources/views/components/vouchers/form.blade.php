@props(['voucher' => null])

<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ $voucher ? route('admin.vouchers.update', $voucher) : route('admin.vouchers.store') }}" class="space-y-6">
        @csrf
        @if($voucher) @method('PUT') @endif

        {{-- Kode Voucher --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Kode Voucher
            </label>
            <input type="text" name="code" 
                value="{{ old('code', $voucher?->code) }}"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
                {{ $voucher ? 'readonly' : '' }}
                placeholder="Contoh: DISC50">
            @error('code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tipe Voucher --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Tipe Voucher
            </label>
            <select name="type" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
                x-on:change="voucherType = $event.target.value">
                <option value="">Pilih Tipe Voucher</option>
                <option value="percent" {{ old('type', $voucher?->type) == 'percent' ? 'selected' : '' }}>
                    Diskon Persentase
                </option>
                <option value="nominal" {{ old('type', $voucher?->type) == 'nominal' ? 'selected' : '' }}>
                    Diskon Nominal
                </option>
                <option value="shipping" {{ old('type', $voucher?->type) == 'shipping' ? 'selected' : '' }}>
                    Potongan Ongkir
                </option>
                <option value="cashback" {{ old('type', $voucher?->type) == 'cashback' ? 'selected' : '' }}>
                    Cashback
                </option>
                <option value="seasonal" {{ old('type', $voucher?->type) == 'seasonal' ? 'selected' : '' }}>
                    Voucher Musiman/Event
                </option>
                <option value="first_purchase" {{ old('type', $voucher?->type) == 'first_purchase' ? 'selected' : '' }}>
                    Voucher Pembelian Pertama
                </option>
                <option value="loyalty" {{ old('type', $voucher?->type) == 'loyalty' ? 'selected' : '' }}>
                    Voucher Member
                </option>
            </select>
            @error('type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nilai Voucher --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nilai Voucher
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span x-show="voucherType !== 'percent'" class="text-gray-500 sm:text-sm">Rp</span>
                    <span x-show="voucherType === 'percent'" class="text-gray-500 sm:text-sm hidden">%</span>
                </div>
                <input type="number" name="value" 
                    value="{{ old('value', $voucher?->value) }}"
                    class="w-full rounded-md border-gray-300 pl-12 focus:border-rose-500 focus:ring-rose-500"
                    x-bind:max="voucherType === 'percent' ? 100 : null"
                    placeholder="Masukkan nilai">
            </div>
            @error('value')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Minimum Pembelanjaan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Minimum Pembelanjaan
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">Rp</span>
                </div>
                <input type="number" name="minimum_spend" 
                    value="{{ old('minimum_spend', $voucher?->minimum_spend) }}"
                    class="w-full rounded-md border-gray-300 pl-12 focus:border-rose-500 focus:ring-rose-500"
                    placeholder="Minimum pembelanjaan">
            </div>
            @error('minimum_spend')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Maximum Discount (untuk tipe percent) --}}
        <div x-show="voucherType === 'percent'">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Maksimum Diskon
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">Rp</span>
                </div>
                <input type="number" name="maximum_discount" 
                    value="{{ old('maximum_discount', $voucher?->maximum_discount) }}"
                    class="w-full rounded-md border-gray-300 pl-12 focus:border-rose-500 focus:ring-rose-500"
                    placeholder="Maksimum nilai diskon">
            </div>
            @error('maximum_discount')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Batas Penggunaan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Batas Penggunaan
            </label>
            <input type="number" name="usage_limit" 
                value="{{ old('usage_limit', $voucher?->usage_limit) }}"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
                placeholder="Kosongkan jika tidak ada batas">
            @error('usage_limit')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Periode Berlaku --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal Mulai
                </label>
                <input type="datetime-local" name="start_date" 
                    value="{{ old('start_date', $voucher?->start_date?->format('Y-m-d\TH:i')) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500">
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal Berakhir
                </label>
                <input type="datetime-local" name="end_date" 
                    value="{{ old('end_date', $voucher?->end_date?->format('Y-m-d\TH:i')) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500">
                @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Fields untuk Seasonal --}}
        <div x-show="voucherType === 'seasonal'" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Event
                </label>
                <input type="text" name="event_name" 
                    value="{{ old('event_name', $voucher?->event_name) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
                    placeholder="Contoh: Valentine, Wisuda">
                @error('event_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tipe Event
                </label>
                <input type="text" name="event_type" 
                    value="{{ old('event_type', $voucher?->event_type) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
                    placeholder="Contoh: Holiday, Special Day">
                @error('event_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Fields untuk Loyalty/Member --}}
        <div x-show="voucherType === 'loyalty'" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Minimum Poin
                </label>
                <input type="number" name="minimum_points" 
                    value="{{ old('minimum_points', $voucher?->minimum_points) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
                    placeholder="Minimum poin yang dibutuhkan">
                @error('minimum_points')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Level Member
                </label>
                <select name="member_level" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500">
                    <option value="">Pilih Level Member</option>
                    <option value="silver" {{ old('member_level', $voucher?->member_level) == 'silver' ? 'selected' : '' }}>Silver</option>
                    <option value="gold" {{ old('member_level', $voucher?->member_level) == 'gold' ? 'selected' : '' }}>Gold</option>
                    <option value="platinum" {{ old('member_level', $voucher?->member_level) == 'platinum' ? 'selected' : '' }}>Platinum</option>
                </select>
                @error('member_level')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Status Aktif --}}
        <div class="flex items-center">
            <input type="checkbox" name="is_active" id="is_active"
                class="rounded border-gray-300 text-rose-600 shadow-sm focus:border-rose-500 focus:ring-rose-500"
                {{ old('is_active', $voucher?->is_active) ? 'checked' : '' }}>
            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                Voucher Aktif
            </label>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.vouchers.index') }}" 
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Batal
            </a>
            <button type="submit" 
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                {{ $voucher ? 'Update Voucher' : 'Buat Voucher' }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('voucherForm', () => ({
            voucherType: '{{ old('type', $voucher?->type ?? '') }}',
        }))
    })
</script>
@endpush
