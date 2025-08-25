<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-700">
                <i class="bi bi-person-circle mr-2"></i>
                Profil Pengguna
            </h1>
        </div>
    </x-slot>

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #fdf2f8 0%, #ffffff 50%, #f0fdf4 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .section-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(244, 63, 94, 0.1);
            transition: all 0.3s ease;
        }

        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
            border-radius: 1rem 1rem 0 0;
            color: white;
        }

        .form-enter {
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon-wrapper {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 0.75rem;
            padding: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .save-button {
            background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
            border: none;
            border-radius: 0.75rem;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(236, 72, 153, 0.3);
        }

        .save-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 15px -3px rgba(236, 72, 153, 0.4);
        }

        .delete-button {
            background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
            border: none;
            border-radius: 0.75rem;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.3);
        }

        .delete-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 15px -3px rgba(239, 68, 68, 0.4);
        }

        .input-field {
            border: 2px solid #f1f5f9;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .input-field:focus {
            border-color: #ec4899;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
            background: white;
        }
    </style>

    <div class="gradient-bg min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Profile Information Card -->
            <div class="section-card form-enter">
                <div class="profile-header p-6">
                    <div class="flex items-center space-x-4">
                        <div class="icon-wrapper">
                            <i class="bi bi-person-fill text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">Informasi Profil</h2>
                            <p class="text-pink-100 text-sm">Perbarui informasi profil dan alamat email Anda</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Update Card -->
            <div class="section-card form-enter" style="animation-delay: 0.1s">
                <div class="profile-header p-6">
                    <div class="flex items-center space-x-4">
                        <div class="icon-wrapper">
                            <i class="bi bi-shield-lock-fill text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">Perbarui Kata Sandi</h2>
                            <p class="text-pink-100 text-sm">Pastikan akun Anda menggunakan kata sandi yang aman</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="section-card form-enter" style="animation-delay: 0.2s">
                <div class="p-6"
                    style="background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); border-radius: 1rem 1rem 0 0; color: white;">
                    <div class="flex items-center space-x-4">
                        <div class="icon-wrapper" style="background: rgba(255, 255, 255, 0.2);">
                            <i class="bi bi-exclamation-triangle-fill text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">Hapus Akun</h2>
                            <p class="text-red-100 text-sm">Hapus akun Anda secara permanen</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>