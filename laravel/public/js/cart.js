// Script khusus untuk cart (keranjang belanja)
function formatPrice(price) {
    return Math.round(price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function getRibbonColorClass(color) {
    const classes = {
        'pink': 'bg-pink-400',
        'red': 'bg-red-500',
        'purple': 'bg-purple-500',
        'gold': 'bg-yellow-500',
        'silver': 'bg-gray-400',
        'white': 'bg-white border border-gray-300'
    };
    return classes[color] || 'bg-pink-400';
}

function getCustomBouquetDetails(item) {
    let details = `
        <div class="text-xs text-gray-600 mt-1">
            ${item.components_summary}
        </div>`;

    if (item.ribbon_color) {
        details += `
        <div class="flex items-center gap-2 mt-2">
            <span class="text-xs text-purple-700">Pita:</span>
            <div class="w-3 h-3 rounded-full ${getRibbonColorClass(item.ribbon_color)}"></div>
            <span class="text-xs text-purple-800 capitalize">${item.ribbon_color}</span>
        </div>`;
    }

    return details;
}

function getCartHTML(items) {
    let html = getCartInfoPanel();

    items.forEach(item => {
        html += `
        <div class="mb-4 p-3 bg-white rounded-lg border border-gray-200 hover:border-rose-200 transition-colors">
            <div class="flex justify-between">
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-800 text-sm">${item.name}</h4>
                    ${item.type === 'custom_bouquet' ? getCustomBouquetDetails(item) : ''}
                </div>
                <div class="text-right ml-4">
                    <div class="text-sm font-semibold text-gray-800">
                        Rp ${formatPrice(item.price)}
                    </div>
                    <div class="text-xs text-gray-500">x ${item.qty}</div>
                </div>
            </div>
        </div>`;
    });

    return html;
}

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
        updateCart();
    }
}

function updateCart(silentMode = false) {
    const cartItemsContainer = document.getElementById('cartItems');
    const cartBadge = document.getElementById('cartBadge');
    const cartTotal = document.getElementById('cartTotal');
    const checkoutButton = document.querySelector('#sideCart a[href*="checkout"]');

    // Skip loading indicator in silent mode
    if (cartItemsContainer && !silentMode) {
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
                cartItemsContainer.innerHTML = getEmptyCartHTML();
                if (cartBadge) cartBadge.classList.add('hidden');
                if (cartTotal) cartTotal.textContent = 'Rp 0';
                if (checkoutButton) checkoutButton.style.display = 'none';
                return;
            }

            updateCartBadgeAndButton(cartBadge, checkoutButton, data.items.length);

            if (cartItemsContainer) {
                cartItemsContainer.innerHTML = getCartHTML(data.items);
            }

            if (cartTotal) {
                cartTotal.textContent = `Rp ${formatPrice(data.total)}`;
            }
        })
        .catch(error => {
            console.error('Error updating cart:', error);
            if (cartItemsContainer) {
                cartItemsContainer.innerHTML = getErrorHTML();
            }
        });
}

function getEmptyCartHTML() {
    return `
        ${getCartInfoPanel()}
        <div class="flex flex-col items-center justify-center h-full text-gray-500">
            <i class="bi bi-bag-x text-5xl mb-2"></i>
            <p>Keranjang belanja kosong</p>
        </div>
    `;
}

function getCartInfoPanel() {
    return `
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
                    <span>Rangkaian bunga siap jadi dengan berbagai ukuran</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs px-2 py-0.5 rounded-full mr-2">Custom</span>
                    <span>Bouquet custom sesuai keinginan Anda</span>
                </div>
                <p class="text-rose-600 mt-2 text-xs italic">
                    💡 Anda dapat menambahkan bunga, bouquet, dan custom bouquet dalam satu keranjang!
                </p>
            </div>
        </div>
    `;
}

function getCartHTML(items) {
    return `
        ${getCartInfoPanel()}
        ${items.map(item => getCartItemHTML(item)).join('')}
    `;
}

function getCartItemHTML(item) {
    return `
        <div class="flex items-start space-x-4 mb-4 pb-4 border-b border-gray-100" data-cart-key="${item.id}">
            ${getItemImageHTML(item)}
            <div class="flex-1">
                ${getItemDetailsHTML(item)}
                ${getItemQuantityControlsHTML(item)}
            </div>
            <button onclick="removeFromCart('${item.id}', '${item.name.replace(/'/g, "\\'")}')" 
                    class="text-gray-400 hover:text-red-500 p-1 transition-colors duration-200 hover:bg-red-50 rounded-lg">
                <i class="bi bi-trash text-lg"></i>
            </button>
        </div>
    `;
}

function getItemImageHTML(item) {
    return `
        <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
            ${item.image ?
            `<img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover" />` :
            `<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z" />
                </svg>`
        }
        </div>
    `;
}

function getItemDetailsHTML(item) {
    return `
        <h4 class="font-semibold text-gray-800 text-sm">${item.name}</h4>
        ${getItemTypeHTML(item)}
        <p class="text-sm text-gray-500">${item.quantity} x Rp ${formatPrice(item.price)}</p>
        ${item.price_type ? `<p class="text-xs text-gray-400">${item.price_type}</p>` : ''}
        ${getGreetingCardHTML(item)}
        ${getComponentsSummaryHTML(item)}
    `;
}

function getItemTypeHTML(item) {
    if (item.type === 'bouquet') {
        return '<span class="inline-block bg-gradient-to-r from-rose-500 to-pink-500 text-white text-xs px-2 py-1 rounded-full mb-1">Bouquet</span>';
    } else if (item.type === 'custom_bouquet') {
        return '<span class="inline-block bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs px-2 py-1 rounded-full mb-1">Custom Bouquet</span>';
    }
    return '<span class="inline-block bg-gradient-to-r from-green-500 to-teal-500 text-white text-xs px-2 py-1 rounded-full mb-1">Bunga</span>';
}

function getGreetingCardHTML(item) {
    if (!item.greeting_card || !item.greeting_card.trim()) {
        return '';
    }

    return `
        <div class="mt-2 p-2 bg-pink-50 border border-pink-200 rounded-lg">
            <div class="flex items-center mb-1">
                <i class="bi bi-card-text text-pink-600 mr-1"></i>
                <span class="text-xs font-medium text-pink-700">Kartu Ucapan:</span>
            </div>
            <p class="text-xs text-pink-800 italic whitespace-pre-wrap">"${item.greeting_card}"</p>
        </div>
    `;
}

function getComponentsSummaryHTML(item) {
    if (!item.components_summary || item.type !== 'custom_bouquet') {
        return '';
    }

    return `
        <div class="mt-2 p-2 bg-purple-50 border border-purple-200 rounded-lg">
            <div class="flex items-center mb-1">
                <i class="bi bi-palette text-purple-600 mr-1"></i>
                <span class="text-xs font-medium text-purple-700">Komponen:</span>
            </div>
            <p class="text-xs text-purple-800">${Array.isArray(item.components_summary) ?
            item.components_summary.slice(0, 2).join(', ') +
            (item.components_summary.length > 2 ? ', +' + (item.components_summary.length - 2) + ' lainnya' : '') :
            item.components_summary}</p>
        </div>
    `;
}

function getItemQuantityControlsHTML(item) {
    return `
        <div class="flex items-center space-x-2 mt-2">
            <button onclick="updateQuantity('${item.id}', -1)" 
                    class="w-6 h-6 bg-gray-100 hover:bg-rose-100 text-gray-600 hover:text-rose-600 rounded-full flex items-center justify-center transition-colors duration-200">
                <span class="transform translate-y-[-1px]">-</span>
            </button>
            <span class="text-sm font-medium min-w-[20px] text-center">${item.quantity}</span>
            <button onclick="updateQuantity('${item.id}', 1)" 
                    class="w-6 h-6 bg-gray-100 hover:bg-rose-100 text-gray-600 hover:text-rose-600 rounded-full flex items-center justify-center transition-colors duration-200">
                <span class="transform translate-y-[-1px]">+</span>
            </button>
        </div>
    `;
}

function getErrorHTML() {
    return `
        <div class="flex flex-col items-center justify-center h-full text-red-500">
            <i class="bi bi-exclamation-triangle text-3xl mb-2"></i>
            <p class="text-sm">Gagal memuat keranjang</p>
            <button onclick="updateCart()" class="mt-2 text-xs text-rose-600 hover:underline">Coba lagi</button>
        </div>
    `;
}

function updateCartBadgeAndButton(cartBadge, checkoutButton, itemCount) {
    if (cartBadge) {
        cartBadge.classList.remove('hidden');
        cartBadge.textContent = itemCount;
    }
    if (checkoutButton) {
        checkoutButton.style.display = 'block';
    }
}

function updateQuantity(cartKey, change) {
    const itemElement = document.querySelector(`[data-cart-key="${cartKey}"]`);
    if (!itemElement) {
        showToast('Item tidak ditemukan', 'error');
        return;
    }

    const quantityElement = itemElement.querySelector('.text-sm.font-medium');
    const currentQuantity = parseInt(quantityElement.textContent);
    const newQuantity = currentQuantity + change;

    if (newQuantity < 1) {
        showToast('Jumlah minimum adalah 1', 'warning');
        return;
    }

    const buttons = itemElement.querySelectorAll('button');
    buttons.forEach(btn => btn.disabled = true);

    // Optimistic update: update jumlah di UI langsung
    quantityElement.textContent = newQuantity;

    // Update harga total item di UI (jika ada)
    const priceText = itemElement.querySelector('p.text-sm.text-gray-500');
    if (priceText) {
        // Ambil harga satuan dari text, misal: "2 x Rp 10.000"
        const priceMatch = priceText.textContent.match(/Rp ([\d.]+)/);
        if (priceMatch) {
            const unitPrice = parseInt(priceMatch[1].replace(/\./g, ''));
            priceText.textContent = `${newQuantity} x Rp ${formatPrice(unitPrice)}`;
        }
    }

    // Update total cart secara async (tidak perlu notifikasi sukses)
    fetch(`/cart/update/${cartKey}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity_change: change })
    })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Hanya update cart panel (total, badge, dsb) tanpa notifikasi sukses
                updateCart(true);
            } else {
                // Rollback jumlah jika gagal
                quantityElement.textContent = currentQuantity;
                if (priceText) {
                    const priceMatch = priceText.textContent.match(/Rp ([\d.]+)/);
                    if (priceMatch) {
                        const unitPrice = parseInt(priceMatch[1].replace(/\./g, ''));
                        priceText.textContent = `${currentQuantity} x Rp ${formatPrice(unitPrice)}`;
                    }
                }
                showToast(data.message || 'Gagal mengupdate jumlah', 'error');
            }
        })
        .catch(error => {
            console.error('Error updating quantity:', error);
            // Rollback jumlah jika gagal
            quantityElement.textContent = currentQuantity;
            if (priceText) {
                const priceMatch = priceText.textContent.match(/Rp ([\d.]+)/);
                if (priceMatch) {
                    const unitPrice = parseInt(priceMatch[1].replace(/\./g, ''));
                    priceText.textContent = `${currentQuantity} x Rp ${formatPrice(unitPrice)}`;
                }
            }
            showToast('Terjadi kesalahan saat mengupdate jumlah', 'error');
        })
        .finally(() => {
            buttons.forEach(btn => btn.disabled = false);
        });
}

function removeFromCart(cartKey, itemName = 'produk ini') {
    // Create backdrop
    const backdrop = document.createElement('div');
    backdrop.className = 'fixed inset-0 bg-black bg-opacity-40 z-50 backdrop-blur-sm transition-opacity duration-200';
    backdrop.style.opacity = '0';

    // Create modal
    const modal = document.createElement('div');
    modal.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-xl z-[60] transition-all duration-200 scale-95 opacity-0 w-[90%] max-w-sm p-6';
    modal.innerHTML = `
        <div class="text-center">
            <div class="w-12 h-12 rounded-full bg-red-50 mx-auto mb-4 flex items-center justify-center">
                <i class="bi bi-trash text-red-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">Hapus dari Keranjang?</h3>
            <p class="text-gray-500 text-sm mb-6">Apakah Anda yakin ingin menghapus ${itemName} dari keranjang belanja?</p>
            <div class="flex space-x-3">
                <button class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors cancel-button">
                    Batal
                </button>
                <button class="flex-1 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition-colors confirm-button">
                    Ya, Hapus
                </button>
            </div>
        </div>
    `;

    // Add to DOM
    document.body.appendChild(backdrop);
    document.body.appendChild(modal);

    // Animate in
    requestAnimationFrame(() => {
        backdrop.style.opacity = '1';
        modal.style.opacity = '1';
        modal.style.transform = 'translate(-50%, -50%) scale(1)';
    });

    // Handle confirmation
    return new Promise((resolve) => {
        function removeModal() {
            modal.style.opacity = '0';
            modal.style.transform = 'translate(-50%, -50%) scale(0.95)';
            backdrop.style.opacity = '0';
            setTimeout(() => {
                backdrop.remove();
                modal.remove();
            }, 200);
        }

        // Cancel button
        modal.querySelector('.cancel-button').addEventListener('click', () => {
            removeModal();
            resolve(false);
        });

        // Confirm button
        modal.querySelector('.confirm-button').addEventListener('click', () => {
            removeModal();
            resolve(true);

            // Hapus item dari DOM
            const itemElement = document.querySelector(`[data-cart-key="${cartKey}"]`);
            if (itemElement) {
                itemElement.remove();
            }

            // Kirim request ke server
            fetch(`/cart/remove/${cartKey}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCart(true);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        // Click backdrop to cancel
        backdrop.addEventListener('click', () => {
            removeModal();
            resolve(false);
        });
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
    backdrop.id = 'toastOverlay';
    backdrop.className = 'fixed inset-0 bg-black bg-opacity-40 z-50 backdrop-blur-sm transition-opacity duration-300';
    backdrop.style.opacity = '0';

    // Create notification element
    const notification = document.createElement('div');
    notification.id = 'cartToast';
    notification.className = `fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 py-3 px-6 rounded-lg shadow-xl z-[60] ${getNotificationColor(type)} transition-all duration-300 scale-95 opacity-0`;

    // Add content to notification
    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0 text-xl">${getNotificationIcon(type)}</div>
            <p class="text-sm font-medium text-white">${message}</p>
        </div>
    `;

    // Add elements to DOM
    document.body.appendChild(backdrop);
    document.body.appendChild(notification);

    // Animate in
    requestAnimationFrame(() => {
        backdrop.style.opacity = '1';
        notification.style.opacity = '1';
        notification.style.transform = 'translate(-50%, -50%) scale(1)';
    });

    // Auto remove after 2 seconds (except for loading)
    if (type !== 'loading') {
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translate(-50%, -50%) scale(0.95)';
            backdrop.style.opacity = '0';
            setTimeout(() => {
                backdrop.remove();
                notification.remove();
            }, 300);
        }, 2000);
    }

    // Click backdrop to close
    backdrop.addEventListener('click', () => {
        notification.style.opacity = '0';
        notification.style.transform = 'translate(-50%, -50%) scale(0.95)';
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
        case 'success': return '<i class="bi bi-check-circle-fill"></i>';
        case 'error': return '<i class="bi bi-x-circle-fill"></i>';
        case 'warning': return '<i class="bi bi-exclamation-triangle-fill"></i>';
        case 'info': return '<i class="bi bi-info-circle-fill"></i>';
        default: return '<i class="bi bi-info-circle-fill"></i>';
    }
}

function getToastColorClass(type) {
    switch (type) {
        case 'success': return 'bg-green-500 text-white';
        case 'error': return 'bg-red-500 text-white';
        case 'warning': return 'bg-yellow-500 text-white';
        case 'info': return 'bg-blue-500 text-white';
        default: return 'bg-gray-500 text-white';
    }
}

function getToastIcon(type) {
    switch (type) {
        case 'success': return '<i class="bi bi-check-circle text-white"></i>';
        case 'error': return '<i class="bi bi-x-circle text-white"></i>';
        case 'warning': return '<i class="bi bi-exclamation-circle text-white"></i>';
        case 'info': return '<i class="bi bi-info-circle text-white"></i>';
        default: return '<i class="bi bi-bell text-white"></i>';
    }
}