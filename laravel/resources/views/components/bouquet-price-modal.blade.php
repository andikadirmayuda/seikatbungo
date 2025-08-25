<!-- Bouquet Price Selection Modal -->
<div id="bouquetPriceModal" class="fixed inset-0     function selectBouquetPrice(selectedPrice) {
        // Close the price selection modal first
        closeBouquetPriceModal();

        // Pastikan data size tersedia dengan benar
        const sizeId = selectedPrice.size_id || (selectedPrice.size ? selectedPrice.size.id : 'standard');
        const sizeName = selectedPrice.size ? selectedPrice.size.name : 'Standard';

        // Show greeting card modal with selected price data
        showGreetingCardModal(
            selectedBouquetData.id,
            selectedBouquetData.name,
            sizeId,
            sizeName,
            selectedPrice.price
        );
    }pacity-50 hidden z-50 transition-opacity duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95"
            id="bouquetModalContent">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800" id="bouquetModalTitle">Pilih Ukuran Bouquet</h3>
                    <button onclick="closeBouquetPriceModal()"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>
                <p class="text-gray-600 text-sm mt-2">Pilih ukuran yang sesuai dengan kebutuhan Anda</p>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div id="bouquetPriceOptions" class="space-y-3">
                    <!-- Price options will be populated by JavaScript -->
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                <div class="text-center text-xs text-gray-500">
                    <i class="bi bi-info-circle mr-1"></i>
                    Harga sudah termasuk biaya arrangement dan kemasan
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedBouquetData = {};

    // Function yang dipanggil dari halaman bouquet
    function showBouquetPriceModalComponent(bouquetId, bouquetName, prices) {
        selectedBouquetData = { id: bouquetId, name: bouquetName, prices: prices };

        // Update modal title
        document.getElementById('bouquetModalTitle').textContent = `Pilih Ukuran ${bouquetName}`;

        // Clear and populate price options
        const optionsContainer = document.getElementById('bouquetPriceOptions');
        optionsContainer.innerHTML = '';

        prices.forEach(price => {
            const option = document.createElement('div');
            option.className = 'group cursor-pointer border-2 border-gray-200 rounded-xl p-4 hover:border-rose-300 hover:bg-rose-50 transition-all duration-200';
            option.onclick = () => selectBouquetPrice(price);

            option.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="font-semibold text-gray-800">${price.size ? price.size.name : 'Standard'}</div>
                        <div class="text-sm text-gray-600">Ukuran ${price.size ? price.size.name.toLowerCase() : 'standard'}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-rose-600">Rp ${new Intl.NumberFormat('id-ID').format(price.price)}</div>
                        <div class="text-xs text-gray-500">per bouquet</div>
                    </div>
                </div>
                <div class="mt-3 flex items-center justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-xs text-rose-600 font-medium">
                        <i class="bi bi-cart-plus mr-1"></i>Pilih ukuran ini
                    </span>
                </div>
            `;

            optionsContainer.appendChild(option);
        });

        // Show modal
        const modal = document.getElementById('bouquetPriceModal');
        const modalContent = document.getElementById('bouquetModalContent');

        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    // Legacy function name untuk backward compatibility
    function showBouquetPriceModal(bouquetId, bouquetName, prices) {
        showBouquetPriceModalComponent(bouquetId, bouquetName, prices);
    } function selectBouquetPrice(selectedPrice) {
        // Close the price selection modal first
        closeBouquetPriceModal();

        // Show greeting card modal with selected price data
        showGreetingCardModal(
            selectedBouquetData.id,
            selectedBouquetData.name,
            selectedPrice.size_id || selectedPrice.size?.id,
            selectedPrice.size?.name || 'Standard',
            selectedPrice.price
        );
    }

    function closeBouquetPriceModal() {
        const modal = document.getElementById('bouquetPriceModal');
        const modalContent = document.getElementById('bouquetModalContent');

        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function showSuccessMessage(message) {
        // Create success notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="bi bi-check-circle mr-2"></i>
                ${message}
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Close modal when clicking outside
    document.getElementById('bouquetPriceModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeBouquetPriceModal();
        }
    });
</script>