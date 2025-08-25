<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="update_password_current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="bi bi-lock mr-1 text-pink-600"></i>
                    Kata Sandi Saat Ini
                </label>
                <input id="update_password_current_password" name="current_password" type="password"
                    class="input-field w-full" autocomplete="current-password"
                    placeholder="Masukkan kata sandi saat ini">
                @error('current_password', 'updatePassword')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="update_password_password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-key mr-1 text-pink-600"></i>
                        Kata Sandi Baru
                    </label>
                    <input id="update_password_password" name="password" type="password" class="input-field w-full"
                        autocomplete="new-password" placeholder="Masukkan kata sandi baru">
                    @error('password', 'updatePassword')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="update_password_password_confirmation"
                        class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-check-square mr-1 text-pink-600"></i>
                        Konfirmasi Kata Sandi
                    </label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                        class="input-field w-full" autocomplete="new-password" placeholder="Ulangi kata sandi baru">
                    @error('password_confirmation', 'updatePassword')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <i class="bi bi-info-circle text-blue-600 text-lg mr-2 mt-0.5"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-medium">Tips Kata Sandi yang Aman:</p>
                    <ul class="mt-1 list-disc list-inside space-y-1">
                        <li>Gunakan minimal 8 karakter</li>
                        <li>Kombinasikan huruf besar, huruf kecil, angka, dan simbol</li>
                        <li>Hindari informasi pribadi seperti nama atau tanggal lahir</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-4">
            <button type="submit" class="save-button flex items-center">
                <i class="bi bi-shield-check mr-2"></i>
                Perbarui Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 font-medium flex items-center">
                    <i class="bi bi-check-circle-fill mr-1"></i>
                    Kata sandi berhasil diperbarui!
                </p>
            @endif
        </div>
    </form>
</section>