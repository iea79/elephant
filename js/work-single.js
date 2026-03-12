/* global jQuery */
(function ($) {
    'use strict';

    function initWorkSingleSlider() {
        if (typeof $.fn.slick === 'undefined') {
            return;
        }

        var $main = $('.js-workSingle-main');
        if (!$main.length || $main.hasClass('slick-initialized')) {
            return;
        }

        $main.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            dots: false,
            infinite: true,
            adaptiveHeight: true,
            pauseOnHover: true,
            pauseOnFocus: true,
            draggable: false,
            swipe: false,
            touchMove: false,
        });

        var $thumbs = $('.js-workSingle-thumbs');
        if ($thumbs.length) {
            $thumbs.on('click', '.workSingle__thumb', function (e) {
                e.preventDefault();
                var index = parseInt($(this).data('slide'), 10) || 0;
                $main.slick('slickGoTo', index);
            });

            $main.on('afterChange', function (_event, _slick, currentSlide) {
                $thumbs
                    .find('.workSingle__thumb')
                    .removeClass('is-active')
                    .filter('[data-slide="' + currentSlide + '"]')
                    .addClass('is-active');
            });

            $thumbs.find('.workSingle__thumb').first().addClass('is-active');
        }
    }

    function loadFavorites() {
        if (typeof window.localStorage === 'undefined') {
            return [];
        }
        try {
            var raw = localStorage.getItem('worksFavorites');
            if (!raw) return [];
            var parsed = JSON.parse(raw);
            if (!Array.isArray(parsed)) return [];
            return parsed.map(function (v) {
                return String(v);
            });
        } catch (e) {
            return [];
        }
    }

    function saveFavorites(favorites) {
        if (typeof window.localStorage === 'undefined') {
            return;
        }
        try {
            localStorage.setItem('worksFavorites', JSON.stringify(favorites));
        } catch (e) {
            // ignore
        }
    }

    function updateFavoriteIcons(favorites) {
        favorites = favorites || [];

        $('.workSingle')
            .find('.works__itemFavorite')
            .each(function () {
                var $btn = $(this);
                var id = String($btn.data('work-id'));
                var isActive = favorites.indexOf(id) !== -1;
                var $icon = $btn.find('span');

                if (isActive) {
                    $btn.addClass('works__itemFavorite--active');
                    $btn.attr('aria-pressed', 'true');
                    $btn.attr('aria-label', 'Убрать из избранного');
                    $icon.removeClass('ie-icon_heart').addClass('ie-icon_heart_half');
                } else {
                    $btn.removeClass('works__itemFavorite--active');
                    $btn.attr('aria-pressed', 'false');
                    $btn.attr('aria-label', 'Добавить в избранное');
                    $icon.removeClass('ie-icon_heart_half').addClass('ie-icon_heart');
                }
            });

        var $headerIcon = $('.header__favorLink i');
        if ($headerIcon.length) {
            if (favorites.length > 0) {
                $headerIcon.removeClass('ie-icon_heart').addClass('ie-icon_heart_half');
            } else {
                $headerIcon.removeClass('ie-icon_heart_half').addClass('ie-icon_heart');
            }
        }
    }

    function isDesktop() {
        var ua = navigator.userAgent || navigator.vendor || window.opera || '';
        var isMobileOS = /android|iphone|ipad|ipod/i.test(ua);
        return !isMobileOS && window.innerWidth >= 1024;
    }

    function initShareAndFavorites() {
        var favorites = loadFavorites();
        updateFavoriteIcons(favorites);

        $(document).on('click', '.workSingle .works__itemShare', function (e) {
            e.preventDefault();
            var $btn = $(this);
            var url = $btn.data('share-url') || window.location.href;
            var title = $btn.data('share-title') || document.title;
            var workId = $btn.data('work-id') || '';

            // Мобильные / планшеты — поведение как раньше
            if (!isDesktop() && navigator.share) {
                navigator
                    .share({
                        title: title,
                        url: url,
                    })
                    .catch(function () {});
                return;
            }

            // Десктоп — копируем ссылку и показываем модальное окно
            var copyPromise;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                copyPromise = navigator.clipboard.writeText(url);
            } else {
                copyPromise = new Promise(function (resolve, reject) {
                    try {
                        var input = document.createElement('input');
                        input.value = url;
                        input.setAttribute('readonly', '');
                        input.style.position = 'absolute';
                        input.style.left = '-9999px';
                        document.body.appendChild(input);
                        input.select();
                        document.execCommand('copy');
                        document.body.removeChild(input);
                        resolve();
                    } catch (err) {
                        reject(err);
                    }
                });
            }

            copyPromise
                .then(function () {
                    var $modal = jQuery('#shareSuccess');
                    if ($modal.length) {
                        var text = 'Ссылка на объект';
                        if (workId) {
                            text += ' ' + workId;
                        }
                        text += ' скопирована';
                        $modal.find('.modal-title').text(text);
                        $modal.modal('show');
                    }
                })
                .catch(function () {
                    // fallback: ничего не делаем, чтобы не ломать UX
                });
        });

        $(document).on('click', '.workSingle .works__itemFavorite', function (e) {
            e.preventDefault();
            var $btn = $(this);
            var id = String($btn.data('work-id'));
            if (!id) return;

            var index = favorites.indexOf(id);
            if (index === -1) {
                favorites.push(id);
            } else {
                favorites.splice(index, 1);
            }

            saveFavorites(favorites);
            updateFavoriteIcons(favorites);
        });
    }

    $(document).ready(function () {
        initWorkSingleSlider();
        initShareAndFavorites();
    });
})(jQuery);
