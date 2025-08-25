<!-- Push notifications have been removed -->
<div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
<span>Pembayaran diterima</span>
</div>
</div>
{{-- <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg mt-4">
    <p class="text-blue-800 text-sm">
        <span class="font-semibold">Tetap responsif</span> - Notifikasi akan muncul meskipun Anda
        sedang bekerja di tab lain
    </p>
</div> --}}
</div>
</div>

<!-- Modal Footer -->
<div class="p-6 pt-0 flex space-x-3">
    <button onclick="enablePushNotifications()"
        class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-xl active:scale-95 shadow-lg flex items-center justify-center space-x-2">
        <span>✨</span>
        <span>Aktifkan Sekarang</span>
    </button>
    <button onclick="dismissNotificationBanner()"
        class="px-4 py-3 text-gray-500 hover:text-gray-700 transition-all duration-200 rounded-xl hover:bg-gray-100 active:scale-95">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"></path>
        </svg>
    </button>
</div>
</div>
</div>

<!-- Notification Status Indicator -->
<div id="notification-status" class="fixed bottom-4 right-4 z-40">
    <div id="notification-enabled"
        class="hidden bg-green-500 text-white px-4 py-2 rounded-full shadow-lg flex items-center space-x-2">
        <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
        <span class="text-sm font-medium">🔔 Notifikasi Aktif</span>
    </div>
    <div id="notification-disabled"
        class="hidden bg-gray-500 text-white px-4 py-2 rounded-full shadow-lg flex items-center space-x-2">
        <div class="w-2 h-2 bg-white rounded-full"></div>
        <span class="text-sm font-medium">🔕 Notifikasi Nonaktif</span>
    </div>
</div>

<!-- Test Notification Button (only for admin) -->
@auth
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'owner')
        <div class="fixed bottom-20 right-4 z-40">
            <button onclick="testPushNotification()"
                class="bg-purple-600 hover:bg-purple-700 text-white p-3 rounded-full shadow-lg transition-colors"
                title="Test Push Notification">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                </svg>
            </button>
        </div>
    @endif
@endauth
</div>

<!-- Push Notification Scripts -->
<script src="{{ asset('js/push-notifications.js') }}"></script>
<script>
    // Initialize push notifications when page loads
    document.addEventListener('DOMContentLoaded', function () {
        try {
            initializePushNotifications();
        } catch (error) {
            console.error('Error initializing push notifications:', error);
        }
    });

    function initializePushNotifications() {
        // Show notification manager
        const manager = document.getElementById('push-notification-manager');
        if (manager) {
            manager.classList.remove('hidden');
        }

        // Check current notification permission
        if ('Notification' in window) {
            const permission = Notification.permission;
            updateNotificationStatus(permission);

            // Initialize push manager if supported
            try {
                if (typeof PushNotificationManager !== 'undefined') {
                    window.pushManager = new PushNotificationManager();
                }
            } catch (error) {
                console.warn('Could not initialize PushNotificationManager:', error);
            }

            // Check if banner was dismissed before
            const dismissed = localStorage.getItem('notification-banner-dismissed');

            // Show banner if permission not granted and not dismissed
            if (permission === 'default' && !dismissed) {
                const banner = document.getElementById('notification-permission-banner');
                if (banner) {
                    setTimeout(() => {
                        banner.classList.remove('hidden');
                        // Add smooth entrance animation
                        setTimeout(() => {
                            banner.classList.remove('opacity-0');
                            banner.querySelector('.bg-white').classList.remove('scale-95');
                        }, 50);
                    }, 500);
                }
            }
        } else {
            // Browser doesn't support notifications
            const disabledIndicator = document.getElementById('notification-disabled');
            if (disabledIndicator) {
                disabledIndicator.classList.remove('hidden');
            }
        }
    }

    async function enablePushNotifications() {
        try {
            // Check if notifications are supported
            if (!('Notification' in window)) {
                showNotificationToast('❌ Browser Anda tidak mendukung notifikasi.', 'error');
                return;
            }

            // Check if pushManager exists
            if (!window.pushManager) {
                console.warn('PushManager not initialized, creating simple notification');
                // Fallback to simple notification request
                const permission = await Notification.requestPermission();
                if (permission === 'granted') {
                    // Hide banner with animation
                    const banner = document.getElementById('notification-permission-banner');
                    if (banner) {
                        banner.querySelector('.bg-white').classList.add('scale-95', 'opacity-75');
                        banner.classList.add('opacity-0');

                        setTimeout(() => {
                            banner.classList.add('hidden');
                            // Reset classes for next time
                            banner.querySelector('.bg-white').classList.remove('scale-95', 'opacity-75');
                            banner.classList.remove('opacity-0');
                        }, 300);
                    }

                    // Update status
                    updateNotificationStatus('granted');

                    // Simple test notification
                    new Notification('🌸 Seikat Bungo', {
                        body: 'Notifikasi berhasil diaktifkan!',
                        icon: '/logo-seikat-bungo.png'
                    });

                    showNotificationToast('✅ Notifikasi berhasil diaktifkan!', 'success');
                } else {
                    showNotificationToast('❌ Gagal mengaktifkan notifikasi.', 'error');
                }
                return;
            }

            const granted = await window.pushManager.requestPermission();

            if (granted) {
                // Hide banner with animation
                const banner = document.getElementById('notification-permission-banner');
                if (banner) {
                    banner.querySelector('.bg-white').classList.add('scale-95', 'opacity-75');
                    banner.classList.add('opacity-0');

                    setTimeout(() => {
                        banner.classList.add('hidden');
                        // Reset classes for next time
                        banner.querySelector('.bg-white').classList.remove('scale-95', 'opacity-75');
                        banner.classList.remove('opacity-0');
                    }, 300);
                }

                // Update status
                updateNotificationStatus('granted');

                // Test notification
                if (window.pushManager && typeof window.pushManager.testNotification === 'function') {
                    await window.pushManager.testNotification();
                }

                // Show success message
                showNotificationToast('✅ Notifikasi berhasil diaktifkan! Anda akan mendapat alert real-time.', 'success');
            } else {
                showNotificationToast('❌ Gagal mengaktifkan notifikasi. Silakan aktifkan melalui pengaturan browser.', 'error');
            }
        } catch (error) {
            console.error('Error enabling notifications:', error);
            showNotificationToast('❌ Terjadi kesalahan: ' + error.message, 'error');
        }
    }

    function dismissNotificationBanner() {
        const modal = document.getElementById('notification-permission-banner');
        if (modal) {
            // Add fade out animation
            modal.querySelector('.bg-white').classList.add('scale-95', 'opacity-75');
            modal.classList.add('opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                // Reset classes for next time
                modal.querySelector('.bg-white').classList.remove('scale-95', 'opacity-75');
                modal.classList.remove('opacity-0');
            }, 300);
        }

        // Store dismissal in localStorage
        localStorage.setItem('notification-banner-dismissed', 'true');
    }

    function updateNotificationStatus(permission) {
        const enabledIndicator = document.getElementById('notification-enabled');
        const disabledIndicator = document.getElementById('notification-disabled');

        if (permission === 'granted') {
            enabledIndicator.classList.remove('hidden');
            disabledIndicator.classList.add('hidden');
        } else {
            enabledIndicator.classList.add('hidden');
            disabledIndicator.classList.remove('hidden');
        }
    }

    async function testPushNotification() {
        try {
            const response = await fetch('/api/admin/notifications/test', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                showNotificationToast('🧪 Test notification sent!', 'success');
            } else {
                showNotificationToast('❌ Failed to send test notification', 'error');
            }
        } catch (error) {
            console.error('Error sending test notification:', error);
            showNotificationToast('❌ Error sending test notification', 'error');
        }
    }

    function showNotificationToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;

        if (type === 'success') {
            toast.className += ' bg-green-500 text-white';
        } else if (type === 'error') {
            toast.className += ' bg-red-500 text-white';
        } else {
            toast.className += ' bg-blue-500 text-white';
        }

        toast.innerHTML = `
            <div class="flex items-center">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }, 5000);
    }

    // Check if banner was dismissed before
    document.addEventListener('DOMContentLoaded', function () {
        const dismissed = localStorage.getItem('notification-banner-dismissed');
        if (dismissed && Notification.permission === 'default') {
            // Don't show banner if user previously dismissed it
            setTimeout(() => {
                document.getElementById('notification-permission-banner').classList.add('hidden');
            }, 100);
        }
    });
</script>