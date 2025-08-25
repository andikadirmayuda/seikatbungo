// Script khusus untuk cart (keranjang belanja)
function formatPrice(price) {
    return Math.round(price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Initialize cart when page loads
document.addEventListener('DOMContentLoaded', function() {
    updateCart();
});

function toggleCart() {
    const cart = document.getElementById('sideCart');
    if (!cart) {
        console.error('Element sideCart tidak ditemukan');
        return;
    }
    cart.classList.toggle('translate-x-full');
    let overlay = document.getElementById('cartOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'cartOverlay';
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300';
        overlay.onclick = toggleCart;
        document.body.appendChild(overlay);
    }
    if (cart.classList.contains('translate-x-full')) {
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.remove(), 300);
    } else {
        overlay.classList.remove('opacity-0');
        // Update cart when opening
        updateCart();
    }
}

function updateCart() {
    const cartItemsContainer = document.getElementById('cartItems');
    const cartBadge = document.getElementById('cartBadge');
    const cartTotal = document.getElementById('cartTotal');
    const checkoutButton = document.querySelector('#sideCart a[href*="checkout"]');
    
    // Show loading state
    if (cartItemsContainer) {
        cartItemsContainer.innerHTML = `
            <div class="flex items-center justify-center h-20">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-rose-500"></div>
                <span class="ml-2 text-gray-500">Memuat...</span>
            </div>
        `;
    }
    
    fetch('/cart/get')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (!data.success) {
            console.error('Cart update failed:', data.message);
            if (cartItemsContainer) {
                cartItemsContainer.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-red-500">
                        <i class="bi bi-exclamation-triangle text-3xl mb-2"></i>
                        <p>Gagal memuat keranjang</p>
                    </div>
                `;
            }
            return;
        }
        
        if (data.items.length === 0) {
            cartItemsContainer.innerHTML = `
                <!-- Cart Information Panel -->
                <div class="bg-gradient-to-r from-rose-50 to-pink-50 border border-rose-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-info-circle text-rose-600 mr-2"></i>
                        <h4 class="font-semibold text-rose-800 text-sm">Keranjang Terpadu</h4>
                    </div>
                    <div class="text-xs text-rose-700 space-y-1">
                        <div class="flex items-center">
                            <span class="inline-block bg-gradient-to-r from-green-500 to-teal-500 text-white text-xs px-2 py-0.5 rounded-full mr-2">Bunga</span>
                            <span>Produk bunga satuan dengan berbagai pilihan harga</span>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-block bg-gradient-to-r from-rose-500 to-pink-500 text-white text-xs px-2 py-0.5 rounded-full mr-2">Bouquet</span>
                            <span>Rangkaian bunga siap jadi dengan berbagai ukuran
                        </div>
                        <div class="flex items-center">
                            <span class="inline-block bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs px-2 py-0.5 rounded-full mr-2">Custom</span>
                            <span>Bouquet custom sesuai keinginan Anda</span></span>
                        </div>
                        <p class="text-rose-600 mt-2 text-xs italic">
                            💡 Anda dapat menambahkan bunga, bouquet, dan custom bouquet dalam satu keranjang!
                        </p>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center h-full text-gray-500">
                    <i class="bi bi-bag-x text-5xl mb-2"></i>
                    <p>Keranjang belanja kosong</p>
                </div>
            `;
            if (cartBadge) cartBadge.classList.add('hidden');
            if (cartTotal) cartTotal.textContent = 'Rp 0';
            if (checkoutButton) checkoutButton.style.display = 'none';
            return;
        }
        
        if (cartBadge) {
            cartBadge.classList.remove('hidden');
            cartBadge.textContent = data.items.length;
        }
        if (checkoutButton) checkoutButton.style.display = 'block';
        
        if (cartItemsContainer) {
            cartItemsContainer.innerHTML = `
                <!-- Cart Information Panel -->
                <div class="bg-gradient-to-r from-rose-50 to-pink-50 border border-rose-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-info-circle text-rose-600 mr-2"></i>
                        <h4 class="font-semibold text-rose-800 text-sm">Keranjang Terpadu</h4>
                    </div>
                    <div class="text-xs text-rose-700 space-y-1">
                        <div class="flex items-center">
                            <span class="inline-block bg-gradient-to-r from-green-500 to-teal-500 text-white text-xs px-2 py-0.5 rounded-full mr-2">Bunga</span>
                            <span>Produk bunga satuan dengan berbagai pilihan harga</span>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-block bg-gradient-to-r from-rose-500 to-pink-500 text-white text-xs px-2 py-0.5 rounded-full mr-2">Bouquet</span>
                            <span>Rangkaian bunga siap jadi dengan berbagai ukuran
                        </div>
                        <div class="flex items-center">
                            <span class="inline-block bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs px-2 py-0.5 rounded-full mr-2">Custom</span>
                            <span>Bouquet custom sesuai keinginan Anda</span></span>
                        </div>
                        <p class="text-rose-600 mt-2 text-xs italic">
                            💡 Anda dapat menambahkan bunga, bouquet, dan custom bouquet dalam satu keranjang!
                        </p>
                    </div>
                </div>
                ${data.items.map(item => `
                <div class="flex items-start space-x-4 mb-4 pb-4 border-b border-gray-100">
                    <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                        ${item.image ? 
                            `<img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">` : 
                            `<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"></path>
                            </svg>`
                        }
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 text-sm">${item.name}</h4>
                        ${item.type === 'bouquet' ? 
                            `<span class="inline-block bg-gradient-to-r from-rose-500 to-pink-500 text-white text-xs px-2 py-1 rounded-full mb-1">Bouquet</span>` : 
                            item.type === 'custom_bouquet' ?
                            `<span class="inline-block bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs px-2 py-1 rounded-full mb-1">Custom Bouquet</span>` :
                            `<span class="inline-block bg-gradient-to-r from-green-500 to-teal-500 text-white text-xs px-2 py-1 rounded-full mb-1">Bunga</span>`
                        }
                        <p class="text-sm text-gray-500">${item.quantity} x Rp ${formatPrice(item.price)}</p>
                        ${item.price_type ? `<p class="text-xs text-gray-400">${item.price_type}</p>` : ''}
                        ${item.greeting_card && item.greeting_card.trim() ? 
                            `<div class="mt-2 p-2 bg-pink-50 border border-pink-200 rounded-lg">
                                <div class="flex items-center mb-1">
                                    <i class="bi bi-card-text text-pink-600 mr-1"></i>
                                    <span class="text-xs font-medium text-pink-700">Kartu Ucapan:</span>
                                </div>
                                <p class="text-xs text-pink-800 italic">"${item.greeting_card.length > 50 ? item.greeting_card.substring(0, 50) + '...' : item.greeting_card}"</p>
                            </div>` : ''
                        }
                        ${item.components_summary && item.type === 'custom_bouquet' ? 
                            `<div class="mt-2 p-2 bg-purple-50 border border-purple-200 rounded-lg">
                                <div class="flex items-center mb-1">
                                    <i class="bi bi-palette text-purple-600 mr-1"></i>
                                    <span class="text-xs font-medium text-purple-700">Komponen:</span>
                                </div>
                                <p class="text-xs text-purple-800">${Array.isArray(item.components_summary) ? item.components_summary.slice(0, 2).join(', ') + (item.components_summary.length > 2 ? ', +' + (item.components_summary.length - 2) + ' lainnya' : '') : item.components_summary}</p>
                            </div>` : ''
                        }
                        <div class="flex items-center space-x-2 mt-2">
                            <button onclick="updateQuantity('${item.id}', -1)" class="w-6 h-6 bg-gray-100 hover:bg-rose-100 text-gray-600 hover:text-rose-600 rounded-full flex items-center justify-center transition-colors duration-200">-</button>
                            <span class="text-sm font-medium min-w-[20px] text-center">${item.quantity}</span>
                            <button onclick="updateQuantity('${item.id}', 1)" class="w-6 h-6 bg-gray-100 hover:bg-rose-100 text-gray-600 hover:text-rose-600 rounded-full flex items-center justify-center transition-colors duration-200">+</button>
                        </div>
                    </div>
                    <button onclick="removeFromCart('${item.id}', '${item.name.replace(/'/g, "\\\'")}')" class="text-gray-400 hover:text-red-500 p-1 transition-colors duration-200 hover:bg-red-50 rounded-lg">
                        <i class="bi bi-trash text-lg"></i>
                    </button>
                </div>
            `).join('')}
            `;
        }
        
        if (cartTotal) {
            cartTotal.textContent = `Rp ${formatPrice(data.total)}`;
        }
    })
    .catch(error => {
        console.error('Error updating cart:', error);
        if (cartItemsContainer) {
            cartItemsContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-red-500">
                    <i class="bi bi-exclamation-triangle text-3xl mb-2"></i>
                    <p class="text-sm">Gagal memuat keranjang</p>
                    <button onclick="updateCart()" class="mt-2 text-xs text-rose-600 hover:underline">Coba lagi</button>
                </div>
            `;
        }
    });
}

function updateQuantity(cartKey, change) {
    // Cek apakah item ada
    const itemElement = document.querySelector(`[data-cart-key="${cartKey}"]`);
    if (!itemElement) {
        showToast('Item tidak ditemukan', 'error');
        return;
    }

    // Ambil elemen quantity
    const quantityElement = itemElement.querySelector('.text-sm.font-medium');
    const currentQuantity = parseInt(quantityElement.textContent);
    
    // Validasi quantity baru
    const newQuantity = currentQuantity + change;
    if (newQuantity < 1) {
        showToast('Jumlah minimum adalah 1', 'warning');
        return;
    }

    // Disable buttons dan update UI secara optimistic
    const buttons = itemElement.querySelectorAll('button');
    buttons.forEach(btn => btn.disabled = true);
    
    // Update tampilan quantity secara optimistic
    quantityElement.textContent = newQuantity;
    
    fetch(`/cart/update/${cartKey}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity_change: change })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            updateCart();
            showToast('Jumlah berhasil diperbarui', 'success');
        } else {
            // Kembalikan quantity jika gagal
            quantityElement.textContent = currentQuantity;
            showToast(data.message || 'Gagal mengupdate jumlah', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating quantity:', error);
        // Kembalikan quantity jika error
        quantityElement.textContent = currentQuantity;
        showToast('Terjadi kesalahan saat mengupdate jumlah', 'error');
    })
    .finally(() => {
        // Re-enable buttons
        buttons.forEach(btn => btn.disabled = false);
    });
}

function removeFromCart(cartKey, itemName = 'produk ini') {
    // Optimistic update - hapus item dari tampilan terlebih dahulu
    const itemElement = document.querySelector(`[data-cart-key="${cartKey}"]`);
    if (itemElement) {
        itemElement.style.height = itemElement.offsetHeight + 'px';
        itemElement.style.overflow = 'hidden';
        itemElement.style.transition = 'all 0.2s ease-out';
        
        requestAnimationFrame(() => {
            itemElement.style.height = '0';
            itemElement.style.opacity = '0';
            itemElement.style.padding = '0';
            itemElement.style.margin = '0';
        });
    }

    // Kirim request ke server
    fetch(`/cart/remove/${cartKey}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Remove element completely after animation
            setTimeout(() => {
                if (itemElement) itemElement.remove();
                updateCart(); // Update cart totals
            }, 200);
            
            showToast('Item berhasil dihapus', 'success');
        } else {
            // Jika gagal, kembalikan tampilan item
            if (itemElement) {
                itemElement.style.height = '';
                itemElement.style.opacity = '';
                itemElement.style.padding = '';
                itemElement.style.margin = '';
            }
            showToast(data.message || 'Gagal menghapus item', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Jika error, kembalikan tampilan item
        if (itemElement) {
            itemElement.style.height = '';
            itemElement.style.opacity = '';
            itemElement.style.padding = '';
            itemElement.style.margin = '';
        }
        showToast('Terjadi kesalahan saat menghapus item', 'error');
    });
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteConfirmModal');
    if (modal) {
        modal.style.opacity = '0';
        document.getElementById('modalContent').style.transform = 'scale(0.95)';
        setTimeout(() => {
            modal.remove();
        }, 300);
    }
}

function confirmRemoveFromCart(cartKey) {
    closeDeleteModal();
    
    // Show loading toast
    showToast('Menghapus produk...', 'loading');
    
    fetch(`/cart/remove/${cartKey}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            updateCart();
            showToast('Produk berhasil dihapus dari keranjang!', 'success');
        } else {
            console.error('Remove from cart failed:', data.message);
            showToast('Gagal menghapus produk: ' + (data.message || 'Terjadi kesalahan'), 'error');
        }
    })
    .catch(error => {
        console.error('Error removing from cart:', error);
        showToast('Terjadi kesalahan saat menghapus produk dari keranjang', 'error');
    });
}

function showToast(message, type = 'info') {
    // Remove existing toast and overlay
    const existingToast = document.getElementById('cartToast');
    const existingOverlay = document.getElementById('toastOverlay');
    if (existingToast) existingToast.remove();
    if (existingOverlay) existingOverlay.remove();
    
    // Create backdrop overlay
    const backdrop = document.createElement('div');
    backdrop.className = 'fixed inset-0 bg-black bg-opacity-40 z-40 backdrop-blur-sm';

    // Create notification element - much smaller for mobile
    const notification = document.createElement('div');
    notification.className = `fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 p-2 sm:p-3 md:p-4 rounded-lg shadow-2xl z-50 ${getNotificationColor(type)} w-10/12 max-w-64 sm:max-w-xs md:max-w-sm text-center border`;

    // Add icon based on type - much smaller icons for mobile
    const icon = getNotificationIcon(type);
    notification.innerHTML = `
        <div class="flex flex-col items-center space-y-1 sm:space-y-2">
            <div class="text-sm sm:text-lg md:text-xl">${icon}</div>
            <div class="text-xs sm:text-sm md:text-base font-semibold leading-tight px-1 sm:px-2">${message}</div>
            <div class="w-full bg-white/20 rounded-full h-0.5 mt-1 sm:mt-2">
                <div class="bg-white h-0.5 rounded-full transition-all duration-3000 notification-progress"></div>
            </div>
        </div>
    `;

    // Add both backdrop and notification to body
    document.body.appendChild(backdrop);
    document.body.appendChild(notification);

    // Add progress animation
    const progressBar = notification.querySelector('.notification-progress');
    setTimeout(() => {
        progressBar.style.width = '100%';
    }, 100);

    // Auto remove after 3 seconds (except for loading)
    if (type !== 'loading') {
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translate(-50%, -50%) scale(0.9)';
            backdrop.style.opacity = '0';
            setTimeout(() => {
                backdrop.remove();
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Click backdrop to close
    backdrop.addEventListener('click', () => {
        notification.style.opacity = '0';
        notification.style.transform = 'translate(-50%, -50%) scale(0.9)';
        backdrop.style.opacity = '0';
        setTimeout(() => {
            backdrop.remove();
            notification.remove();
        }, 300);
    });
}

function getNotificationColor(type) {
    switch (type) {
        case 'success': return 'bg-gradient-to-br from-green-500 to-emerald-600 text-white';
        case 'error': return 'bg-gradient-to-br from-red-500 to-rose-600 text-white';
        case 'warning': return 'bg-gradient-to-br from-yellow-500 to-orange-600 text-white';
        case 'loading': return 'bg-gradient-to-br from-blue-500 to-indigo-600 text-white';
        default: return 'bg-gradient-to-br from-blue-500 to-indigo-600 text-white';
    }
}

function getNotificationIcon(type) {
    switch (type) {
        case 'success': return '✅';
        case 'error': return '❌';
        case 'warning': return '⚠️';
        case 'loading': return '⏳';
        default: return 'ℹ️';
    }
}
