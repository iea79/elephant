document.addEventListener('DOMContentLoaded', function () {
    // Lightbox functionality
    const lightbox = document.createElement('div');
    lightbox.className = 'sws-lightbox__overlay';
    document.body.appendChild(lightbox);

    let currentImageIndex = 0;
    let images = [];

    // Close lightbox
    lightbox.addEventListener('click', function (e) {
        if (e.target === lightbox || e.target.className === 'sws-lightbox__close') {
            lightbox.classList.remove('active');
        }
    });

    // Navigation
    lightbox.addEventListener('click', function (e) {
        if (e.target.className === 'sws-lightbox__nav prev') {
            showPrevImage();
        } else if (e.target.className === 'sws-lightbox__nav next') {
            showNextImage();
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function (e) {
        if (lightbox.classList.contains('active')) {
            if (e.key === 'Escape') {
                lightbox.classList.remove('active');
            } else if (e.key === 'ArrowLeft') {
                showPrevImage();
            } else if (e.key === 'ArrowRight') {
                showNextImage();
            }
        }
    });

    // Show previous image
    function showPrevImage() {
        currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
        showImage(currentImageIndex);
    }

    // Show next image
    function showNextImage() {
        currentImageIndex = (currentImageIndex + 1) % images.length;
        showImage(currentImageIndex);
    }

    // Show image in lightbox
    function showImage(index) {
        const imgSrc = images[index];
        lightbox.innerHTML = `
      <div class="sws-lightbox__content">
        <img src="${imgSrc}" alt="">
        <div class="sws-lightbox__close"></div>
        ${images.length > 1 ? '<div class="sws-lightbox__nav prev"></div><div class="sws-lightbox__nav next"></div>' : ''}
      </div>
    `;
    }

    // Initialize lightbox
    function initLightbox() {
        // Галерея на одиночной странице объекта works
        const workLinks = document.querySelectorAll('.workSingle__slideLink');
        if (workLinks.length > 0) {
            const workImages = Array.from(workLinks).map((link) => link.getAttribute('href'));

            workLinks.forEach((link, index) => {
                const img = link.querySelector('img');
                if (img) {
                    img.style.cursor = 'pointer';
                }
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    images = workImages;
                    currentImageIndex = index;
                    showImage(currentImageIndex);
                    lightbox.classList.add('active');
                });
            });
        }

        // Галерея объекта .objectDetail__gallery
        const objectDetailImages = document.querySelectorAll('.objectDetail__gallery img');
        if (objectDetailImages.length > 0) {
            const galleryImages = Array.from(objectDetailImages);
            galleryImages.forEach((img) => {
                img.style.cursor = 'pointer';
            });

            galleryImages.forEach((img, index) => {
                img.addEventListener('click', function () {
                    images = galleryImages.map((node) => node.src);
                    currentImageIndex = index;
                    showImage(currentImageIndex);
                    lightbox.classList.add('active');
                });
            });
        }

        // Стандартная галерея Gutenberg
        const lightboxImages = document.querySelectorAll('.wp-block-gallery img');
        if (lightboxImages.length > 0) {
            const galleryImages = Array.from(lightboxImages);
            galleryImages.forEach((img) => {
                img.style.cursor = 'pointer';
            });

            galleryImages.forEach((img, index) => {
                img.addEventListener('click', function () {
                    images = galleryImages.map((node) => node.src);
                    currentImageIndex = index;
                    showImage(currentImageIndex);
                    lightbox.classList.add('active');
                });
            });
        }
    }
    initLightbox();
});
