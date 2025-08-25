// Script khusus untuk cart (keranjang belanja)
function formatPrice(price) {
    // Ensure price is a number, remove any existing separators
    const numPrice = parseFloat(String(price).replace(/[,.]/g, '')) || 0;
    return Math.round(numPrice).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Initialize cart when page loads
document.addEventListener('DOMContentLoaded', function() {
    updateCart();
});

function toggleCart() {
    const cart = document.getElementById('sideCart');
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
            cartItemsContainer.innerHTML = data.items.map(item => `
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
                        <p class="text-sm text-gray-500">${item.quantity} x Rp ${formatPrice(item.price)}</p>
                        <div class="flex items-center space-x-2 mt-2">
                            <button onclick="updateQuantity('${item.id}', -1)" class="w-6 h-6 bg-gray-100 hover:bg-rose-100 text-gray-600 hover:text-rose-600 rounded-full flex items-center justify-center transition-colors duration-200">-</button>
                            <span class="text-sm font-medium min-w-[20px] text-center">${item.quantity}</span>
                            <button onclick="updateQuantity('${item.id}', 1)" class="w-6 h-6 bg-gray-100 hover:bg-rose-100 text-gray-600 hover:text-rose-600 rounded-full flex items-center justify-center transition-colors duration-200">+</button>
                        </div>
                    </div>
                    <button onclick="removeFromCart('${item.id}')" class="text-gray-400 hover:text-red-500 p-1">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `).join('');
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
    // Disable buttons sementara untuk mencegah multiple clicks
    const buttons = document.querySelectorAll(`button[onclick*="updateQuantity('${cartKey}'"]`);
    buttons.forEach(btn => btn.disabled = true);
    
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
        } else {
            console.error('Update quantity failed:', data.message);
            alert('Gagal mengupdate jumlah: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error updating quantity:', error);
        alert('Terjadi kesalahan saat mengupdate jumlah produk');
    })
    .finally(() => {
        // Re-enable buttons
        buttons.forEach(btn => btn.disabled = false);
    });
}

function removeFromCart(cartKey) {
    if (!confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
        return;
    }
    
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
        } else {
            console.error('Remove from cart failed:', data.message);
            alert('Gagal menghapus produk: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error removing from cart:', error);
        alert('Terjadi kesalahan saat menghapus produk dari keranjang');
    });
}
