document.addEventListener('DOMContentLoaded', () => {
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = lightbox.querySelector('.lightbox-img');
    const closeBtn = lightbox.querySelector('.lightbox-close');

    document.querySelectorAll('.post-clickable-img').forEach(img => {
        img.addEventListener('click', () => {
            lightbox.classList.remove('d-none');
            lightboxImg.src = img.src;
        });
    });

    closeBtn.addEventListener('click', () => {
        lightbox.classList.add('d-none');
        lightboxImg.src = '';
    });

    // Optional: click outside image to close
    lightbox.addEventListener('click', e => {
        if (e.target === lightbox) {
            lightbox.classList.add('d-none');
            lightboxImg.src = '';
        }
    });
});
