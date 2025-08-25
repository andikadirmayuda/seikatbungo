import "./bootstrap";

// Alpine.js sudah diload oleh Livewire
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.Alpine === 'undefined') {
        console.error('Alpine.js tidak tersedia - pastikan Livewire terload dengan benar');
        return;
    }

    // Fungsi untuk mengecek notifikasi
    window.checkNotifications = async function() {
        try {
            const response = await fetch('/api/notifications/pending', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success && data.data.length > 0) {
                // Update badge count
                const badge = document.querySelector('#notification-badge');
                if (badge) {
                    badge.textContent = data.data.length;
                    badge.style.display = 'inline-block';
                }
                
                // Play notification sound if enabled
                const audio = document.querySelector('#notification-sound');
                if (audio) {
                    audio.play().catch(e => console.log('Audio play failed:', e));
                }
            }

            return data;
        } catch (error) {
            console.error('Error checking notifications:', error);
            return { success: false, data: [] };
        }
    };

    // Check setiap 30 detik
    setInterval(checkNotifications, 30000);
    
    // Check pertama kali saat load
    checkNotifications();
});
