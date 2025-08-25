<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('online-customers.show', $customerData->wa_number) }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="bi bi-pencil text-pink-500 mr-2"></i>
                {{ __('Edit Pelanggan Online') }} - {{ $customerData->customer_name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Customer Info -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Informasi Pelanggan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Nama:</span>
                    <span class="ml-2 font-medium">{{ $customerData->customer_name }}</span>
                </div>
                <div>
                    <span class="text-sm text-gray-500">WhatsApp:</span>
                    <span class="ml-2 font-medium">{{ $customerData->wa_number }}</span>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <form action="{{ route('online-customers.update', $customerData->wa_number) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Reseller Settings -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="bi bi-star text-yellow-500 mr-2"></i>
                        Pengaturan Reseller
                    </h4>
                    
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_reseller" 
                                   id="is_reseller_checkbox"
                                   value="1" 
                                   {{ old('is_reseller', optional($customerData->customer)->is_reseller ?? false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Tetapkan sebagai reseller</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">Pelanggan dengan status reseller dapat menggunakan kode reseller untuk mendapatkan harga khusus.</p>
                    </div>
                </div>
                
                <!-- Promo Settings -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="bi bi-gift text-red-500 mr-2"></i>
                        Pengaturan Promo
                    </h4>
                    
                    <div class="mb-4">
                        <label for="promo_discount" class="block text-sm font-medium text-gray-700 mb-2">
                            Diskon Promo (%)
                        </label>
                        <input type="number" 
                               name="promo_discount" 
                               id="promo_discount"
                               value="{{ old('promo_discount', optional($customerData->customer)->promo_discount ?? '') }}"
                               min="0" 
                               max="100" 
                               step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500"
                               placeholder="Contoh: 15">
                        @error('promo_discount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Reseller Code Management Section (only show if customer is reseller) -->
            @if($customerData->customer && $customerData->customer->is_reseller)
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="bi bi-key text-purple-500 mr-2"></i>
                        Kelola Kode Reseller
                        <span class="text-sm text-gray-500 ml-2">({{ $activeResellerCodes->count() }} aktif)</span>
                    </h4>

                    <!-- Generate New Code Form -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <h5 class="font-medium text-gray-800 mb-3">Generate Kode Baru</h5>
                        
                        <div class="flex gap-3 items-end">
                            <div class="flex-1">
                                <label class="block text-sm text-gray-600 mb-1">Kode Reseller</label>
                                <div class="flex">
                                    <input type="text" id="resellerCodeEdit" 
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:ring-purple-500 focus:border-purple-500 text-sm"
                                        placeholder="Masukkan kode reseller" maxlength="20">
                                    <button type="button" onclick="generateRandomCodeEdit()" 
                                        class="px-3 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-200 text-sm">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm text-gray-600 mb-1">Berlaku Selama (Jam)</label>
                                <select id="expiryHoursEdit" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500 text-sm">
                                    <option value="24">24 Jam (1 Hari)</option>
                                    <option value="48">48 Jam (2 Hari)</option>
                                    <option value="72" selected>72 Jam (3 Hari)</option>
                                    <option value="168">168 Jam (1 Minggu)</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm text-gray-600 mb-1">Catatan</label>
                                <input type="text" id="notesEdit" placeholder="Catatan kode..." 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500 text-sm">
                            </div>
                            <button type="button" onclick="generateResellerCodeEdit()" 
                                class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600 transition text-sm">
                                <i class="bi bi-plus-circle mr-1"></i>
                                Generate
                            </button>
                        </div>
                    </div>

                    <!-- Active Codes -->
                    @if($activeResellerCodes->count() > 0)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 mb-4">
                            <h5 class="font-medium text-gray-800 mb-3 flex items-center">
                                <i class="bi bi-check-circle text-green-500 mr-2"></i>
                                Kode Aktif
                            </h5>

                            <div class="space-y-3">
                                @foreach($activeResellerCodes as $code)
                                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                        <div>
                                            <div class="font-mono text-lg font-bold text-green-700">{{ $code->code }}</div>
                                            <div class="text-sm text-gray-600">
                                                Berlaku hingga: {{ $code->expires_at->format('d M Y H:i') }}
                                                ({{ $code->expires_at->diffForHumans() }})
                                            </div>
                                            @if($code->notes)
                                                <div class="text-xs text-gray-500 mt-1">{{ $code->notes }}</div>
                                            @endif
                                        </div>
                                        <form action="{{ route('online-customers.revoke-code', [$customerData->wa_number, $code->id]) }}" 
                                            method="POST" onsubmit="return confirm('Yakin ingin membatalkan kode ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600 transition">
                                                <i class="bi bi-x-circle mr-1"></i>
                                                Batalkan
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Code History -->
                    @if($resellerCodeHistory->count() > 0)
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h5 class="font-medium text-gray-800 mb-3 flex items-center">
                                <i class="bi bi-clock-history text-gray-500 mr-2"></i>
                                Riwayat Kode (10 Terakhir)
                            </h5>

                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @foreach($resellerCodeHistory as $code)
                                    <div class="flex items-center justify-between p-2 border-b border-gray-100 last:border-b-0">
                                        <div>
                                            <div class="font-mono text-sm {{ $code->is_used ? 'text-gray-500' : ($code->isValid() ? 'text-green-600' : 'text-red-500') }}">
                                                {{ $code->code }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Dibuat: {{ $code->created_at->format('d M Y H:i') }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            @if($code->is_used)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600">
                                                    <i class="bi bi-check-circle mr-1"></i>
                                                    Digunakan
                                                </span>
                                            @elseif($code->isValid())
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-600">
                                                    <i class="bi bi-circle mr-1"></i>
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-100 text-red-600">
                                                    <i class="bi bi-x-circle mr-1"></i>
                                                    Expired
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            
            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="bi bi-journal-text mr-1"></i>
                    Catatan
                </label>
                <textarea name="notes" 
                          id="notes" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500"
                          placeholder="Catatan khusus untuk pelanggan ini...">{{ old('notes', optional($customerData->customer)->notes ?? '') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-8 flex gap-3">
                <button type="submit" class="px-6 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                    <i class="bi bi-check-circle mr-2"></i>
                    Simpan Perubahan
                </button>
                
                <a href="{{ route('online-customers.show', $customerData->wa_number) }}" 
                   class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    <i class="bi bi-x-circle mr-2"></i>
                    Batal
                </a>
            </div>
            
        </form>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <i class="bi bi-exclamation-triangle mr-2"></i>
        Terdapat kesalahan dalam form
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.fixed.bottom-4').remove();
        }, 5000);
    </script>
    @endif

    <script>
        function generateRandomCodeEdit() {
            const prefix = 'RES';
            const randomPart = Math.random().toString(36).substring(2, 8).toUpperCase();
            const code = prefix + randomPart;
            document.getElementById('resellerCodeEdit').value = code;
        }

        function generateResellerCodeEdit() {
            const code = document.getElementById('resellerCodeEdit').value.trim();
            const expiryHours = document.getElementById('expiryHoursEdit').value;
            const notes = document.getElementById('notesEdit').value.trim();

            if (!code) {
                alert('Kode reseller tidak boleh kosong!');
                return;
            }

            if (!expiryHours || expiryHours < 1 || expiryHours > 168) {
                alert('Jam berlaku harus antara 1-168 jam!');
                return;
            }

            // Show loading state
            const generateBtn = event.target;
            const originalText = generateBtn.innerHTML;
            generateBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin mr-1"></i> Generating...';
            generateBtn.disabled = true;

            // Send AJAX request
            fetch(`/online-customers/{{ $customerData->wa_number }}/generate-code`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    code: code,
                    expiry_hours: expiryHours,
                    notes: notes || null
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Kode reseller berhasil di-generate!\n\nKode: ' + data.code + '\nBerlaku hingga: ' + data.expires_at);
                    // Reset form
                    document.getElementById('resellerCodeEdit').value = '';
                    document.getElementById('expiryHoursEdit').value = '72';
                    document.getElementById('notesEdit').value = '';
                    // Refresh halaman untuk update data
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Gagal generate kode reseller'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat generate kode reseller');
            })
            .finally(() => {
                // Reset button state
                generateBtn.innerHTML = originalText;
                generateBtn.disabled = false;
            });
        }

        // Auto generate code when page loads
        document.addEventListener('DOMContentLoaded', function() {
            @if($customerData->customer && $customerData->customer->is_reseller)
                generateRandomCodeEdit();
            @endif
        });
    </script>
</x-app-layout>
