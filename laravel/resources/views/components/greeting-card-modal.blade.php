<!-- Greeting Card Modal Component -->
<div id="greetingCardModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl transform transition-all">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 bg-gradient-to-r from-rose-500 to-pink-500 rounded-full flex items-center justify-center">
                    <i class="bi bi-card-text text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Kartu Ucapan</h3>
                    <p class="text-sm text-gray-500">Tambahkan pesan spesial untuk bouquet Anda</p>
                </div>
            </div>
            <button onclick="closeGreetingCardModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="bi bi-flower1 mr-1 text-rose-500"></i>
                    Bouquet: <span id="modalBouquetName" class="font-semibold text-rose-600"></span>
                </label>
                <div class="text-xs text-gray-500 bg-rose-50 p-2 rounded-lg">
                    <span id="modalBouquetSize" class="font-medium"></span> -
                    Rp <span id="modalBouquetPrice" class="font-medium"></span>
                </div>
            </div>

            <div class="mb-4">
                <label for="greetingCardMessage" class="block text-sm font-medium text-gray-700 mb-2">
                    💌 Pesan Kartu Ucapan <span class="text-gray-400">(Opsional)</span>
                </label>
                <textarea id="greetingCardMessage" rows="4" maxlength="200"
                    class="w-full border-2 border-gray-200 rounded-xl p-3 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 transition-all resize-none"
                    placeholder="Tulis pesan khusus Anda di sini... &#10;Contoh: &#10;• Happy Anniversary! ❤️&#10;• Selamat Ulang Tahun!&#10;• Semoga cepat sembuh 🌸"></textarea>
                <div class="flex justify-between items-center mt-2">
                    <div class="text-xs text-gray-500">
                        <i class="bi bi-info-circle mr-1"></i>
                        Pesan akan ditulis pada kartu cantik
                    </div>
                    <div class="text-xs text-gray-400">
                        <span id="characterCount">0</span>/200 karakter
                    </div>
                </div>
            </div>

            <!-- Quick Templates -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    ✨ Template Cepat
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button"
                        onclick="setGreetingTemplate('Happy Anniversary! Wishing you both all the happiness in the world. ❤️')"
                        class="text-xs bg-rose-50 hover:bg-rose-100 text-rose-700 px-3 py-2 rounded-lg transition-colors">
                        💕 Anniversary
                    </button>
                    <button type="button"
                        onclick="setGreetingTemplate('Selamat Ulang Tahun! Semoga semua impian Anda terwujud. 🎉')"
                        class="text-xs bg-pink-50 hover:bg-pink-100 text-pink-700 px-3 py-2 rounded-lg transition-colors">
                        🎂 Ulang Tahun
                    </button>
                    <button type="button"
                        onclick="setGreetingTemplate('Semoga lekas sembuh dan sehat selalu. Get well soon! 🌸')"
                        class="text-xs bg-green-50 hover:bg-green-100 text-green-700 px-3 py-2 rounded-lg transition-colors">
                        🌸 Get Well
                    </button>
                    <button type="button"
                        onclick="setGreetingTemplate('Congratulations! Wishing you success and happiness ahead. ✨')"
                        class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-lg transition-colors">
                        🎉 Congratulations
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex gap-3 p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
            <button onclick="closeGreetingCardModal()"
                class="flex-1 px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                <i class="bi bi-x-circle mr-2"></i>Batal
            </button>
            <button onclick="addBouquetWithGreeting()"
                class="flex-1 px-4 py-2 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white rounded-xl transition-all shadow-md hover:shadow-lg font-medium">
                <i class="bi bi-cart-plus mr-2"></i>Tambah ke Keranjang
            </button>
        </div>
    </div>
</div>

<script>
    let currentGreetingData = null;

    // Show greeting card modal
    function showGreetingCardModal(bouquetId, bouquetName, sizeId, sizeName, price) {
        currentGreetingData = {
            bouquetId,
            bouquetName,
            sizeId,
            sizeName,
            price
        };

        // Update modal content
        document.getElementById('modalBouquetName').textContent = bouquetName;
        document.getElementById('modalBouquetSize').textContent = sizeName;
        document.getElementById('modalBouquetPrice').textContent = new Intl.NumberFormat('id-ID').format(price);

        // Clear previous message
        document.getElementById('greetingCardMessage').value = '';
        updateCharacterCount();

        // Show modal
        document.getElementById('greetingCardModal').classList.remove('hidden');

        // Focus on textarea
        setTimeout(() => {
            document.getElementById('greetingCardMessage').focus();
        }, 100);
    }

    // Close greeting card modal
    function closeGreetingCardModal() {
        document.getElementById('greetingCardModal').classList.add('hidden');
        currentGreetingData = null;
    }

    // Set greeting template
    function setGreetingTemplate(template) {
        document.getElementById('greetingCardMessage').value = template;
        updateCharacterCount();
    }

    // Update character count
    function updateCharacterCount() {
        const textarea = document.getElementById('greetingCardMessage');
        const count = textarea.value.length;
        document.getElementById('characterCount').textContent = count;

        // Change color based on length
        const countElement = document.getElementById('characterCount');
        if (count > 180) {
            countElement.className = 'text-red-500 font-medium';
        } else if (count > 150) {
            countElement.className = 'text-orange-500';
        } else {
            countElement.className = 'text-gray-400';
        }
    }

    // Add bouquet with greeting to cart
    function addBouquetWithGreeting() {
        if (!currentGreetingData) return;

        const greetingMessage = document.getElementById('greetingCardMessage').value.trim();

        // Add to cart via AJAX
        fetch('/cart/add-bouquet', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json; charset=utf-8',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                bouquet_id: currentGreetingData.bouquetId,
                size_id: currentGreetingData.sizeId,
                quantity: 1,
                greeting_card: greetingMessage
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    let message = `${currentGreetingData.bouquetName} (${currentGreetingData.sizeName}) berhasil ditambahkan ke keranjang!`;
                    if (greetingMessage) {
                        message += ` Dengan kartu ucapan: "${greetingMessage.substring(0, 30)}${greetingMessage.length > 30 ? '...' : ''}"`;
                    }

                    // Use existing toast notification system
                    if (typeof showToast === 'function') {
                        showToast(message, 'success');
                    } else {
                        // Fallback to simple alert if showToast is not available
                        alert(message);
                    }

                    // Update cart badge
                    if (typeof updateCart === 'function') {
                        updateCart();
                    }

                    // Close modal
                    closeGreetingCardModal();
                } else {
                    // Use toast for error message
                    if (typeof showToast === 'function') {
                        showToast('Gagal menambahkan ke keranjang: ' + (data.message || 'Unknown error'), 'error');
                    } else {
                        alert('Gagal menambahkan ke keranjang: ' + (data.message || 'Unknown error'));
                    }
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                // Use toast for error message
                if (typeof showToast === 'function') {
                    showToast('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
                } else {
                    alert('Terjadi kesalahan saat menambahkan ke keranjang');
                }
            });
    }

    // Character count event listener
    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('greetingCardMessage');
        if (textarea) {
            textarea.addEventListener('input', updateCharacterCount);
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeGreetingCardModal();
        }
    });

    // Close modal on outside click
    document.getElementById('greetingCardModal')?.addEventListener('click', function (e) {
        if (e.target === this) {
            closeGreetingCardModal();
        }
    });
</script>