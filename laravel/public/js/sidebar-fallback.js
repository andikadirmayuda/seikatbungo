// Fallback JavaScript untuk sidebar jika Alpine.js tidak bekerja
document.addEventListener('DOMContentLoaded', function() {
    // Tunggu sebentar untuk Alpine.js load
    setTimeout(function() {
        if (typeof window.Alpine === 'undefined') {
            console.log('Alpine.js tidak tersedia, menggunakan fallback JavaScript');
            initFallbackSidebar();
        }
    }, 1000);
});

function initFallbackSidebar() {
    let isSidebarOpen = localStorage.getItem('sidebarOpen') === 'true';
    
    const sidebar = document.querySelector('[x-show="isSidebarOpen"]');
    const overlay = document.querySelector('[x-show="isSidebarOpen"][class*="bg-black bg-opacity-50"]');
    const mainContent = document.querySelector('.flex-1');
    const toggleButton = document.querySelector('button[title="Toggle Sidebar"]');
    
    if (!sidebar || !toggleButton) {
        console.error('Fallback: Element sidebar atau tombol toggle tidak ditemukan');
        return;
    }
    
    function updateSidebarState() {
        if (isSidebarOpen) {
            sidebar.style.display = 'block';
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            if (overlay) {
                overlay.style.display = 'block';
            }
            if (mainContent) {
                mainContent.classList.add('lg:ml-64');
            }
        } else {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
            setTimeout(() => {
                sidebar.style.display = 'none';
            }, 300);
            if (overlay) {
                overlay.style.display = 'none';
            }
            if (mainContent) {
                mainContent.classList.remove('lg:ml-64');
            }
        }
        localStorage.setItem('sidebarOpen', isSidebarOpen.toString());
    }
    
    function toggleSidebar() {
        console.log('Fallback toggle sidebar clicked');
        isSidebarOpen = !isSidebarOpen;
        updateSidebarState();
    }
    
    // Set initial state
    updateSidebarState();
    
    // Add event listener
    toggleButton.addEventListener('click', toggleSidebar);
    
    // Close sidebar when clicking overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            if (isSidebarOpen) {
                toggleSidebar();
            }
        });
    }
    
    // Close sidebar when clicking outside (click away)
    document.addEventListener('click', function(event) {
        if (isSidebarOpen && sidebar && !sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
            toggleSidebar();
        }
    });
    
    console.log('Fallback sidebar initialized with state:', isSidebarOpen);
}
