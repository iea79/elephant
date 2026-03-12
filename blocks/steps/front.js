(function ($) {
    'use strict';

    $(document).ready(function () {
        var $container = $('.steps__wrapper');
        if (!$container.length) return;

        var $items = $container.find('.steps__item');
        var itemsCount = $items.length;
        if (itemsCount <= 1) return;

        // Ensure GSAP and ScrollTrigger are available
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
            console.warn('GSAP or ScrollTrigger not loaded');
            return;
        }

        gsap.registerPlugin(ScrollTrigger);

        // Для мобильных (<768) отключаем фиксацию (pin),
        // оставляем только переключение активного класса.
        var isMobile = window.innerWidth < 768;

        // Clear any existing active classes
        $items.removeClass('active');

        // Create a timeline that will be controlled by scroll
        var tl = gsap.timeline({
            scrollTrigger: {
                trigger: $container[0],
                start: isMobile ? 'top center' : 'center center',
                end: isMobile ? 'center' : '+300% center',
                scrub: !isMobile,
                pin: !isMobile,
                // markers: true,
                onUpdate: function (self) {
                    // progress from 0 to 1
                    var progress = self.progress;
                    // calculate active index based on progress
                    var activeIndex = Math.floor(progress * itemsCount);
                    // clamp to valid range
                    if (activeIndex >= itemsCount) activeIndex = itemsCount - 1;
                    if (activeIndex < 0) activeIndex = 0;

                    // Update active class
                    $items.removeClass('active');
                    $items.eq(activeIndex).addClass('active');
                },
                onEnter: function () {
                    $items.removeClass('active');
                    $items.first().addClass('active');
                },
                onLeave: function () {
                    $items.removeClass('active');
                    $items.last().addClass('active');
                },
                onEnterBack: function () {
                    $items.removeClass('active');
                    $items.first().addClass('active');
                },
                onLeaveBack: function () {
                    $items.removeClass('active');
                    $items.first().addClass('active');
                },
            },
        });

        // Add dummy animation to timeline (just to have a duration)
        tl.to($container, { duration: 1.5 });

        // Refresh ScrollTrigger after DOM changes
        ScrollTrigger.refresh();
    });
})(jQuery);
