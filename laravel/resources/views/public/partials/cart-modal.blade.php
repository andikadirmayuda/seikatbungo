<!-- Modal Pilih Harga Produk -->
<div id="cartPriceModal" class="fixed inset-0 z-[1000] flex items-center justify-center bg-black bg-opacity-40 hidden"
    style="z-index: 1000;">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 relative">
        <button onclick="closeCartPriceModal()" class="absolute top-3 right-3 text-gray-400 hover:text-rose-500">
            <i class="bi bi-x-lg"></i>
        </button>
        <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
            <i class="bi bi-tag mr-2"></i> Pilih Harga Produk
        </h3>
        <div id="modalPriceOptions">
            <!-- Daftar harga akan diisi via JS -->
        </div>

        <!-- Reseller Option Section -->
        <div id="resellerSection" class="mt-4 p-3 bg-amber-50 rounded-lg border border-amber-200"
            style="display: none;">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <i class="bi bi-crown text-amber-600"></i>
                    <span class="text-sm font-medium text-amber-800">Harga Reseller</span>
                </div>
                <button id="enterResellerCodeBtn" onclick="openResellerCodeModal()"
                    class="text-xs px-2 py-1 bg-amber-500 text-white rounded hover:bg-amber-600" style="display: none;">
                    <i class="bi bi-key mr-1"></i>Masukkan Kode
                </button>
            </div>

            <!-- Status Info -->
            <div id="resellerStatus" class="text-xs">
                <span id="resellerNotActive" class="text-amber-600">
                    <i class="bi bi-info-circle mr-1"></i>Masukkan kode reseller untuk mendapatkan harga khusus
                </span>
                <span id="resellerActive" class="text-green-600" style="display: none;">
                    <i class="bi bi-check-circle mr-1"></i>Anda sudah terdaftar sebagai reseller aktif
                </span>
            </div>
        </div>
        <div class="mt-6 flex justify-end gap-2">
            <button onclick="closeCartPriceModal()"
                class="px-4 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">Batal</button>
            <button id="modalAddToCartBtn"
                class="px-4 py-2 rounded-lg bg-gradient-to-r from-rose-500 to-pink-500 text-white font-semibold hover:from-rose-600 hover:to-pink-600"
                disabled>Tambah ke Keranjang</button>
        </div>
    </div>
</div>

<!-- Modal Input Kode Reseller -->
<div id="resellerCodeModal"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm hidden"
    style="z-index: 99999;">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 relative border-2 border-amber-300">
        <button onclick="closeResellerCodeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-rose-500">
            <i class="bi bi-x-lg"></i>
        </button>
        <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
            <i class="bi bi-key mr-2 text-amber-500"></i> Masukkan Kode Reseller
        </h3>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                <input type="text" id="resellerWaNumber"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    placeholder="08123456789" maxlength="15">
                <p class="text-xs text-gray-500 mt-1">Nomor WA yang terdaftar sebagai reseller</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Reseller</label>
                <input type="text" id="resellerCodeInput"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    placeholder="Masukkan kode reseller" maxlength="20">
                <p class="text-xs text-gray-500 mt-1">Kode yang diberikan oleh admin</p>
            </div>

            <!-- Status Message -->
            <div id="resellerCodeStatus" class="text-sm" style="display: none;"></div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
            <button onclick="closeResellerCodeModal()"
                class="px-4 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">Batal</button>
            <button id="validateResellerCodeBtn" onclick="validateResellerCode()"
                class="px-4 py-2 rounded-lg bg-gradient-to-r from-amber-500 to-yellow-500 text-white font-semibold hover:from-amber-600 hover:to-yellow-600">
                <i class="bi bi-shield-check mr-1"></i>Validasi Kode
            </button>
        </div>
    </div>
</div>
<script>
    let selectedPriceId = null;
    let currentFlowerId = null;
    let availablePrices = [];

    function formatPrice(price) {
        // Ensure price is a number, remove any existing separators
        const numPrice = parseFloat(String(price).replace(/[,.]/g, '')) || 0;
        return Math.round(numPrice).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function openCartPriceModal(flowerId, prices) {
        const modal = document.getElementById('cartPriceModal');
        const optionsDiv = document.getElementById('modalPriceOptions');
        const addBtn = document.getElementById('modalAddToCartBtn');
        const resellerSection = document.getElementById('resellerSection');

        currentFlowerId = flowerId;
        availablePrices = prices.filter(price => !['custom_ikat', 'custom_tangkai', 'custom_khusus'].includes(price.type));
        selectedPriceId = null;
        addBtn.disabled = true;

        // Check if reseller prices available
        const hasResellerPrice = prices.some(price => price.type === 'reseller');
        const isResellerActive = checkResellerStatus();

        // Render price options
        optionsDiv.innerHTML = prices.map(price => {
            // Only show reseller price if code is already validated
            if (price.type === 'reseller' && !isResellerActive) {
                return ''; // Don't show reseller option until validated
            }

            return `
            <label class="flex items-center gap-3 mb-2 cursor-pointer">
                <input type="radio" name="priceOption" value="${price.type}" onchange="selectPriceOption('${price.type}')">
                <span class="font-semibold text-gray-700">${price.label}</span>
                <span class="ml-auto text-rose-600 font-bold">Rp ${formatPrice(price.price)}</span>
            </label>
        `}).join('');

        // Show/hide reseller section
        if (hasResellerPrice) {
            resellerSection.style.display = 'block';
            updateResellerStatus();
        } else {
            resellerSection.style.display = 'none';
        }

        // Show modal
        modal.classList.remove('hidden');

        // Set add to cart action
        addBtn.onclick = function () {
            if (selectedPriceId) addToCartWithPrice(flowerId, selectedPriceId);
        };
    }

    function closeCartPriceModal() {
        document.getElementById('cartPriceModal').classList.add('hidden');
    }

    function refreshPriceOptions() {
        // Re-render price options with current reseller status
        if (currentFlowerId && availablePrices.length > 0) {
            const optionsDiv = document.getElementById('modalPriceOptions');
            const isResellerActive = checkResellerStatus();

            optionsDiv.innerHTML = availablePrices.map(price => {
                // Only show reseller price if code is already validated
                if (price.type === 'reseller' && !isResellerActive) {
                    return ''; // Don't show reseller option until validated
                }

                return `
                <label class="flex items-center gap-3 mb-2 cursor-pointer">
                    <input type="radio" name="priceOption" value="${price.type}" onchange="selectPriceOption('${price.type}')">
                    <span class="font-semibold text-gray-700">${price.label}</span>
                    <span class="ml-auto text-rose-600 font-bold">Rp ${formatPrice(price.price)}</span>
                </label>
            `}).join('');
        }
    }

    function selectPriceOption(priceId) {
        // Check if trying to select reseller price without validation
        if (priceId === 'reseller' && !checkResellerStatus()) {
            // Don't allow selection, show modal instead
            openResellerCodeModal();
            // Reset radio selection
            document.querySelector('input[name="priceOption"]:checked').checked = false;
            selectedPriceId = null;
            document.getElementById('modalAddToCartBtn').disabled = true;
            return;
        }

        selectedPriceId = priceId;
        document.getElementById('modalAddToCartBtn').disabled = false;
    }

    function handleResellerPriceSelection() {
        const isResellerActive = checkResellerStatus();

        if (!isResellerActive) {
            // Show reseller code modal
            openResellerCodeModal();
        }
    }

    function checkResellerStatus() {
        // Check session for active reseller status
        const isSessionActive = sessionStorage.getItem('resellerActive') === 'true' &&
            sessionStorage.getItem('resellerExpiry') &&
            new Date() < new Date(sessionStorage.getItem('resellerExpiry'));

        // If session shows active, validate with server
        if (isSessionActive) {
            validateResellerStatusWithServer();
        }

        return isSessionActive;
    }

    async function validateResellerStatusWithServer() {
        const code = sessionStorage.getItem('resellerCode');
        const waNumber = sessionStorage.getItem('resellerWaNumber');

        if (!code || !waNumber) {
            clearResellerSession();
            return;
        }

        try {
            console.log('Validating reseller code:', code, 'for WA:', waNumber);
            const response = await fetch('/api/validate-reseller-code', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    code: code,
                    wa_number: waNumber
                })
            });

            console.log('Response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Response data:', data);

            if (!data.valid) {
                // Code is no longer valid, clear session and refresh UI
                clearResellerSession();
                refreshPriceOptions();
                updateResellerStatus();

                // Show notification that code has expired/been cancelled
                showResellerNotification('Kode reseller Anda sudah tidak valid atau telah dibatalkan.', 'warning');
            }
        } catch (error) {
            console.error('Error validating reseller status:', error);
            showResellerNotification('Terjadi kesalahan saat memvalidasi kode reseller. Silakan coba lagi.', 'error');
        }
    }

    function clearResellerSession() {
        sessionStorage.removeItem('resellerActive');
        sessionStorage.removeItem('resellerWaNumber');
        sessionStorage.removeItem('resellerCode');
        sessionStorage.removeItem('resellerExpiry');
    }

    function updateResellerStatus() {
        const isActive = checkResellerStatus();
        const notActiveSpan = document.getElementById('resellerNotActive');
        const activeSpan = document.getElementById('resellerActive');
        const enterCodeBtn = document.getElementById('enterResellerCodeBtn');

        if (isActive) {
            notActiveSpan.style.display = 'none';
            activeSpan.style.display = 'inline';
            if (enterCodeBtn) enterCodeBtn.style.display = 'none';
        } else {
            notActiveSpan.style.display = 'inline';
            activeSpan.style.display = 'none';
            if (enterCodeBtn) enterCodeBtn.style.display = 'inline-block';
        }
    }

    function openResellerCodeModal() {
        const resellerModal = document.getElementById('resellerCodeModal');
        const priceModal = document.getElementById('cartPriceModal');

        // Ensure reseller modal is on top
        resellerModal.style.zIndex = '9999';
        resellerModal.classList.remove('hidden');

        // Clear previous input
        document.getElementById('resellerWaNumber').value = '';
        document.getElementById('resellerCodeInput').value = '';
        document.getElementById('resellerCodeStatus').style.display = 'none';

        // Reset button
        const validateBtn = document.getElementById('validateResellerCodeBtn');
        validateBtn.disabled = false;
        validateBtn.innerHTML = '<i class="bi bi-shield-check mr-1"></i>Validasi Kode';
    }

    function closeResellerCodeModal() {
        document.getElementById('resellerCodeModal').classList.add('hidden');
        // Reset selection if code not validated
        if (!checkResellerStatus()) {
            selectedPriceId = null;
            document.getElementById('modalAddToCartBtn').disabled = true;
            // Uncheck any radio buttons
            const checkedRadio = document.querySelector('input[name="priceOption"]:checked');
            if (checkedRadio) checkedRadio.checked = false;
        }
    }

    function validateResellerCode() {
        const waNumber = document.getElementById('resellerWaNumber').value.trim();
        const code = document.getElementById('resellerCodeInput').value.trim();
        const statusDiv = document.getElementById('resellerCodeStatus');
        const validateBtn = document.getElementById('validateResellerCodeBtn');

        if (!waNumber || !code) {
            showResellerStatus('error', 'Nomor WA dan kode reseller harus diisi!');
            return;
        }

        // Show loading
        validateBtn.disabled = true;
        validateBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin mr-1"></i>Memvalidasi...';
        showResellerStatus('info', 'Memvalidasi kode reseller...');

        // Send AJAX request
        fetch('/api/validate-reseller-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                code: code,
                wa_number: waNumber
            })
        })
            .then(response => {
                console.log('Validation response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Validation response data:', data);
                if (data.valid) {
                    // Save to session
                    sessionStorage.setItem('resellerActive', 'true');
                    sessionStorage.setItem('resellerWaNumber', waNumber);
                    sessionStorage.setItem('resellerCode', code);
                    sessionStorage.setItem('resellerExpiry', new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString()); // 24 hours default

                    showResellerStatus('success', '✓ Kode valid! Anda dapat menggunakan harga reseller.');

                    setTimeout(() => {
                        closeResellerCodeModal();
                        updateResellerStatus();
                        // Refresh the price options to show reseller prices
                        refreshPriceOptions();
                        // Auto-select reseller price if available
                        setTimeout(() => {
                            const resellerRadio = document.querySelector('input[value="reseller"]');
                            if (resellerRadio) {
                                resellerRadio.checked = true;
                                selectPriceOption('reseller');
                            }
                        }, 100);
                    }, 1500);
                } else {
                    showResellerStatus('error', '✗ ' + (data.message || 'Kode tidak valid atau sudah expired'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showResellerStatus('error', '✗ Terjadi kesalahan saat validasi kode');
            })
            .finally(() => {
                validateBtn.disabled = false;
                validateBtn.innerHTML = '<i class="bi bi-shield-check mr-1"></i>Validasi Kode';
            });
    }

    function showResellerStatus(type, message) {
        const statusDiv = document.getElementById('resellerCodeStatus');
        const colors = {
            success: 'text-green-600 bg-green-50 border-green-200',
            error: 'text-red-600 bg-red-50 border-red-200',
            info: 'text-blue-600 bg-blue-50 border-blue-200'
        };

        statusDiv.className = `text-sm p-2 rounded border ${colors[type]}`;
        statusDiv.textContent = message;
        statusDiv.style.display = 'block';
    }

    // Event listener for reseller checkbox
    document.addEventListener('DOMContentLoaded', function () {
        const useResellerCheckbox = document.getElementById('useResellerPrice');
        if (useResellerCheckbox) {
            useResellerCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    handleResellerPriceSelection();
                } else {
                    // If unchecked, revert to previous selection
                    selectedPriceId = null;
                    document.getElementById('modalAddToCartBtn').disabled = true;
                }
            });
        }
    });

    // Tidak perlu fungsi addToCartWithPrice lokal, gunakan yang global dari flowers.blade.php
</script>