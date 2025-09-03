<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-pink-100 rounded-lg mr-3">
                    <i class="bi bi-ticket-perforated text-pink-600 text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Detail Voucher</h1>
                    <p class="text-sm text-gray-500 mt-1">Informasi lengkap voucher</p>
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
        <div class="bg-white shadow rounded-lg p-6">
            <dl class="divide-y divide-gray-200">
                <div class="py-3 flex justify-between">
                    <dt class="font-medium text-gray-700">Kode Voucher</dt>
                    <dd class="text-gray-900 font-mono">{{ $voucher->code }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="font-medium text-gray-700">Deskripsi</dt>
                    <dd class="text-gray-900">{{ $voucher->description }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="font-medium text-gray-700">Tipe</dt>
                    <dd class="text-gray-900">{{ $voucher->getTypeDescription() }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="font-medium text-gray-700">Nilai</dt>
                    <dd class="text-gray-900">{{ $voucher->getFormattedValue() }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="font-medium text-gray-700">Minimum Belanja</dt>
                    <dd class="text-gray-900">Rp {{ number_format($voucher->minimum_spend, 0, ',', '.') }}</dd>
                </div>
                @if($voucher->maximum_discount)
                    <div class="py-3 flex justify-between">
                        <dt class="font-medium text-gray-700">Maksimum Potongan</dt>
                        <dd class="text-gray-900">Rp {{ number_format($voucher->maximum_discount, 0, ',', '.') }}</dd>
                    </div>
                @endif
                <div class="py-3 flex justify-between">
                    <dt class="font-medium text-gray-700">Periode</dt>
                    <dd class="text-gray-900">{{ $voucher->start_date->format('d/m/Y') }} -
                        {{ $voucher->end_date->format('d/m/Y') }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="font-medium text-gray-700">Status</dt>
                    <dd class="text-gray-900">{{ ucfirst($voucher->getStatus()) }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="font-medium text-gray-700">Penggunaan</dt>
                    <dd class="text-gray-900">
                        {{ $voucher->usage_count }}{{ $voucher->usage_limit ? ' / ' . $voucher->usage_limit : '' }}</dd>
                </div>
                @if($voucher->type === 'seasonal')
                    <div class="py-3 flex justify-between">
                        <dt class="font-medium text-gray-700">Nama Event</dt>
                        <dd class="text-gray-900">{{ $voucher->event_name }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="font-medium text-gray-700">Tipe Event</dt>
                        <dd class="text-gray-900">{{ $voucher->event_type }}</dd>
                    </div>
                @endif
                @if($voucher->type === 'loyalty')
                    <div class="py-3 flex justify-between">
                        <dt class="font-medium text-gray-700">Minimum Poin Member</dt>
                        <dd class="text-gray-900">{{ $voucher->minimum_points }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="font-medium text-gray-700">Level Member</dt>
                        <dd class="text-gray-900">{{ $voucher->member_level }}</dd>
                    </div>
                @endif
                @if($voucher->type === 'first_purchase')
                    <div class="py-3 flex justify-between">
                        <dt class="font-medium text-gray-700">Hanya untuk pembelian pertama</dt>
                        <dd class="text-gray-900">{{ $voucher->first_purchase_only ? 'Ya' : 'Tidak' }}</dd>
                    </div>
                @endif
                @if($voucher->terms_and_conditions)
                    <div class="py-3">
                        <dt class="font-medium text-gray-700 mb-1">Syarat & Ketentuan</dt>
                        <dd class="text-gray-900 whitespace-pre-line text-sm">
                            {{ is_string($voucher->terms_and_conditions) ? $voucher->terms_and_conditions : (is_array($voucher->terms_and_conditions) ? implode("\n", $voucher->terms_and_conditions) : '') }}
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>
</x-app-layout>