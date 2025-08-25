<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Riwayat Pemesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.history.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="cleanup_period" class="block text-sm font-medium text-gray-700">Periode Pembersihan</label>
                            <select id="cleanup_period" name="cleanup_period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="biweekly" {{ $cleanupPeriod === 'biweekly' ? 'selected' : '' }}>2 Minggu Sekali</option>
                                <option value="monthly" {{ $cleanupPeriod === 'monthly' ? 'selected' : '' }}>1 Bulan Sekali</option>
                            </select>
                            @error('cleanup_period')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="retention_days" class="block text-sm font-medium text-gray-700">Lama Penyimpanan Data</label>
                            <select id="retention_days" name="retention_days" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="14" {{ $retentionDays == 14 ? 'selected' : '' }}>2 Minggu</option>
                                <option value="30" {{ $retentionDays == 30 ? 'selected' : '' }}>1 Bulan</option>
                            </select>
                            @error('retention_days')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
