<section class="space-y-6">
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <div class="flex items-start">
            <i class="bi bi-exclamation-triangle text-red-600 text-2xl mr-3 mt-1"></i>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-red-800 mb-2">Zona Berbahaya</h3>
                <p class="text-sm text-red-700 mb-4">
                    Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen.
                    Sebelum menghapus akun, silakan unduh data atau informasi yang ingin Anda simpan.
                </p>

                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    class="delete-button flex items-center">
                    <i class="bi bi-trash mr-2"></i>
                    Hapus Akun Permanen
                </button>
            </div>
        </div>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="text-center mb-6">
                <div class="bg-red-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">
                    Konfirmasi Penghapusan Akun
                </h2>
                <p class="text-sm text-gray-600">
                    Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-red-800">
                    <i class="bi bi-info-circle mr-1"></i>
                    Setelah akun dihapus, semua data, pesanan, dan informasi terkait akan hilang secara permanen.
                    Masukkan kata sandi Anda untuk mengkonfirmasi penghapusan.
                </p>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="bi bi-lock mr-1 text-red-600"></i>
                    Konfirmasi dengan Kata Sandi
                </label>
                <input id="password" name="password" type="password" class="input-field w-full"
                    placeholder="Masukkan kata sandi Anda" required>
                @error('password', 'userDeletion')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                    <i class="bi bi-x-circle mr-1"></i>
                    Batal
                </button>
                <button type="submit" class="delete-button flex items-center">
                    <i class="bi bi-trash mr-2"></i>
                    Hapus Akun Sekarang
                </button>
            </div>
        </form>
    </x-modal>
</section>