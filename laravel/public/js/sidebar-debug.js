// Debug script untuk sidebar
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sidebar debug script loaded');
    
    // Cek apakah Alpine.js tersedia
    if (typeof window.Alpine === 'undefined') {
        console.error('Alpine.js tidak tersedia!');
        return;
    }
    
    console.log('Alpine.js tersedia');
    
    // Cek apakah elemen sidebar ada
    const sidebarElement = document.querySelector('[x-show="isSidebarOpen"]');
    if (sidebarElement) {
        console.log('Element sidebar ditemukan');
    } else {
        console.error('Element sidebar tidak ditemukan');
    }
    
    // Cek tombol toggle
    const toggleButton = document.querySelector('[\\@click*="toggleSidebar"]');
    if (toggleButton) {
        console.log('Tombol toggle ditemukan');
        
        // Tambahkan event listener manual sebagai backup
        toggleButton.addEventListener('click', function() {
            console.log('Manual event listener: tombol toggle diklik');
        });
    } else {
        console.error('Tombol toggle tidak ditemukan');
    }
    
    // Monitor localStorage
    const currentState = localStorage.getItem('sidebarOpen');
    console.log('Current localStorage sidebarOpen:', currentState);
});
