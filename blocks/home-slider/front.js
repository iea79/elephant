(function ($) {
    'use strict';

    /**
     * Инициализация слайдера
     */
    function initHomeSlider() {
        $('.homeSlider').each(function () {
            var $slider = $(this);
            var autoplay = $slider.data('autoplay') !== false;
            var autoplaySpeed = parseInt($slider.data('autoplay-speed')) || 3000;
            var dots = $slider.data('dots') !== false;
            var arrows = $slider.data('arrows') === true;
            var fade = $slider.data('fade') === true;
            var speed = parseInt($slider.data('speed')) || 500;

            // Если slick уже инициализирован, уничтожаем
            if ($slider.hasClass('slick-initialized')) {
                $slider.slick('unslick');
            }

            $slider.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: autoplay,
                autoplaySpeed: autoplaySpeed,
                dots: dots,
                arrows: arrows,
                fade: fade,
                speed: speed,
                infinite: true,
                adaptiveHeight: true,
                pauseOnHover: true,
                pauseOnFocus: true,
                cssEase: 'ease',
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            arrows: false,
                            dots: true,
                        },
                    },
                ],
            });
        });
    }

    // Инициализация при загрузке документа
    $(document).ready(function () {
        if (typeof $.fn.slick !== 'undefined') {
            initHomeSlider();
        } else {
            console.warn('Slick slider library not loaded.');
        }
    });

    // Инициализация после AJAX-загрузки (например, в блоках, загруженных через REST)
    $(document).on('blocksLoaded', initHomeSlider);
})(jQuery);