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

    // Touch swipe navigation
    let touchStartX = 0;
    let touchStartY = 0;
    let isTouching = false;
    const SWIPE_THRESHOLD = 40;

    lightbox.addEventListener('touchstart', function (e) {
        if (!lightbox.classList.contains('active')) return;
        if (!e.touches || e.touches.length !== 1) return;
        const touch = e.touches[0];
        touchStartX = touch.clientX;
        touchStartY = touch.clientY;
        isTouching = true;
    }, { passive: true });

    lightbox.addEventListener('touchmove', function (e) {
        if (!isTouching) return;
        // Не мешаем вертикальному скроллу, пока нет явного горизонтального свайпа
    }, { passive: true });

    lightbox.addEventListener('touchend', function (e) {
        if (!isTouching) return;
        isTouching = false;
        if (!e.changedTouches || e.changedTouches.length === 0) return;

        const touch = e.changedTouches[0];
        const deltaX = touch.clientX - touchStartX;
        const deltaY = touch.clientY - touchStartY;

        // Игнорируем почти вертикальные движения
        if (Math.abs(deltaY) > Math.abs(deltaX)) {
            return;
        }

        if (Math.abs(deltaX) < SWIPE_THRESHOLD) {
            return;
        }

        if (deltaX < 0) {
            // свайп влево → следующая картинка
            showNextImage();
        } else {
            // свайп вправо → предыдущая картинка
            showPrevImage();
        }
    }, { passive: true });

    // Show image in lightbox (slider-style)
    function showImage(index) {
        if (!images || !images.length) {
            return;
        }

        // Защита от выхода за пределы
        currentImageIndex = (index + images.length) % images.length;

        // При первом открытии (оверлей ещё не активен) рендерим все слайды пачкой
        if (!lightbox.classList.contains('active')) {
            const slidesHtml = images
                .map(function (src, i) {
                    const activeClass = i === currentImageIndex ? ' is-active' : '';
                    return (
                        '<div class="sws-lightbox__slide' +
                        activeClass +
                        '"><img src="' +
                        src +
                        '" alt=""></div>'
                    );
                })
                .join('');

            lightbox.innerHTML =
                '<div class="sws-lightbox__content">' +
                '<div class="sws-lightbox__track" style="transform: translateX(' +
                -currentImageIndex * 100 +
                '%);">' +
                slidesHtml +
                '</div>' +
                '<div class="sws-lightbox__close"></div>' +
                (images.length > 1
                    ? '<div class="sws-lightbox__nav prev"></div><div class="sws-lightbox__nav next"></div>'
                    : '') +
                '</div>';
            return;
        }

        // При последующих переключениях просто двигаем трек
        const track = lightbox.querySelector('.sws-lightbox__track');
        if (track) {
            track.style.transform = 'translateX(' + -currentImageIndex * 100 + '%)';
        }

        const slides = lightbox.querySelectorAll('.sws-lightbox__slide');
        if (slides.length) {
            Array.prototype.forEach.call(slides, function (slide, i) {
                if (i === currentImageIndex) {
                    slide.classList.add('is-active');
                } else {
                    slide.classList.remove('is-active');
                }
            });
        }
    }

    // Initialize lightbox
    function initLightbox() {
        // Галерея на одиночной странице объекта works
        const workZooms = document.querySelectorAll('.workSingle__zoom');
        const workImages = document.querySelectorAll('.workSingle__slide img');
        if (workZooms.length > 0 && workImages.length === workZooms.length) {
            const workImagesArray = Array.from(workImages);

            workZooms.forEach((zoom, index) => {
                zoom.style.cursor = 'pointer';
                zoom.addEventListener('click', function (e) {
                    e.preventDefault();
                    images = workImagesArray.map((img) => img.src);
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
