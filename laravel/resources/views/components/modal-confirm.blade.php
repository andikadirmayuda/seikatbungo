<div x-data="{ open: @entangle($attributes->wire('model')).defer ?? false }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40" style="display: none;">
    <div class="bg-white rounded-sm shadow-lg p-8 max-w-md w-full text-center border">
        <div class="flex flex-col items-center justify-center mb-4">
            <i class="bi bi-exclamation-circle text-6xl text-orange-300 mb-2"></i>
            <span class="text-black text-xl font-bold mb-2">Apakah Anda yakin?</span>
            <span class="text-gray-600 text-sm mb-2">Data yang dihapus tidak dapat dikembalikan!</span>
        </div>
        <div class="flex justify-center gap-4 mt-6">
            <button @click="$dispatch('confirm-delete')" class="inline-flex items-center gap-2 bg-black text-white rounded-sm px-5 py-2 hover:bg-gray-900 font-semibold text-sm">
                <i class="bi bi-trash3"></i> Ya, hapus!
            </button>
            <button @click="open = false" class="inline-flex items-center gap-2 bg-gray-200 text-black rounded-sm px-5 py-2 hover:bg-gray-300 font-semibold text-sm">
                <i class="bi bi-x-circle"></i> Batal
            </button>
        </div>
    </div>
</div>
