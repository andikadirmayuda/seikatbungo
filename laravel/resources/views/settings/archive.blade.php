<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Arsip Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('settings.archive.update') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Periode Arsip Pesanan
                            </label>
                            <div class="mt-2 space-y-4">
                                <div class="flex items-center">
                                    <input type="radio" name="period" value="daily" id="daily"
                                        {{ $currentPeriod === 'daily' ? 'checked' : '' }}
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="daily" class="ml-3 block text-sm font-medium text-gray-700">
                                        Per Hari (Arsipkan pesanan yang lebih dari 1 hari)
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="period" value="weekly" id="weekly"
                                        {{ $currentPeriod === 'weekly' ? 'checked' : '' }}
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="weekly" class="ml-3 block text-sm font-medium text-gray-700">
                                        Per Minggu (Arsipkan pesanan yang lebih dari 1 minggu)
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="period" value="monthly" id="monthly"
                                        {{ $currentPeriod === 'monthly' ? 'checked' : '' }}
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="monthly" class="ml-3 block text-sm font-medium text-gray-700">
                                        Per Bulan (Arsipkan pesanan yang lebih dari 1 bulan)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-start space-x-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Simpan & Terapkan
                            </button>
                            <p class="text-sm text-gray-500">
                                * Mengubah periode akan langsung mengarsipkan pesanan sesuai periode yang dipilih
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
