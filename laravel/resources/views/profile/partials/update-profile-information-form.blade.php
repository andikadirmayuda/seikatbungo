<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="bi bi-person mr-1 text-pink-600"></i>
                    Nama Lengkap
                </label>
                <input id="name" name="name" type="text" class="input-field w-full"
                    value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="bi bi-envelope mr-1 text-pink-600"></i>
                    Alamat Email
                </label>
                <input id="email" name="email" type="email" class="input-field w-full"
                    value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="bi bi-exclamation-triangle text-yellow-600 text-lg mr-2 mt-0.5"></i>
                    <div>
                        <p class="text-sm text-yellow-800">
                            Email Anda belum diverifikasi.
                            <button form="send-verification"
                                class="underline text-yellow-600 hover:text-yellow-800 font-medium transition-colors">
                                Klik di sini untuk mengirim ulang email verifikasi.
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm text-green-600 font-medium">
                                Link verifikasi baru telah dikirim ke alamat email Anda.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="flex items-center justify-between pt-4">
            <button type="submit" class="save-button flex items-center">
                <i class="bi bi-check-circle mr-2"></i>
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 font-medium flex items-center">
                    <i class="bi bi-check-circle-fill mr-1"></i>
                    Profil berhasil diperbarui!
                </p>
            @endif
        </div>
    </form>
</section>