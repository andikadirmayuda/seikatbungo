<!-- Full Image Modal -->
<div id="fullImageModal" class="hidden fixed inset-0 z-[1000] bg-black/90 justify-center items-center">
    <div class="relative max-w-[90%] max-h-[90vh]">
        <button onclick="closeFullImage()" class="absolute -top-10 right-0 text-white text-3xl hover:scale-110 transition-transform">&times;</button>
        <img id="fullImage" src="" alt="" class="max-w-full max-h-[90vh] object-contain">
        <div id="imageTitle" class="absolute -bottom-10 left-0 right-0 text-center text-white text-xl font-medium"></div>
    </div>
</div>

<script>
    function showFullImage(imageUrl, title) {
        const modal = document.getElementById('fullImageModal');
        const img = document.getElementById('fullImage');
        const titleEl = document.getElementById('imageTitle');
        
        img.src = imageUrl;
        titleEl.textContent = title;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Prevent body scrolling when modal is open
        document.body.style.overflow = 'hidden';
    }

    function closeFullImage() {
        const modal = document.getElementById('fullImageModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        
        // Restore body scrolling
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside the image
    document.getElementById('fullImageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeFullImage();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFullImage();
        }
    });
</script>
