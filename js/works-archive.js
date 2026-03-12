(function ($) {
    'use strict';

    var WorksArchive = function () {
        this.$container = $('#works');
        this.$list = $('#works-list');
        this.$loadMore = $('.works__loadMore');
        this.$form = $('.worksFilter');
        this.$rangeSliders = this.$form.find('.worksFilter__rangeSlider');
        // Избранное хранится только в localStorage (не в базе)
        this.favorites = this.loadFavorites();
        this.bindEvents();
        this.initRangeSliders();
        this.updateFavoriteIcons();
        if (typeof swsWorksArchive !== 'undefined' && swsWorksArchive.favoritesPage) {
            this.loadFavoritesContent();
        }
    };

    WorksArchive.prototype.bindEvents = function () {
        var self = this;

        if (this.$loadMore.length) {
            this.$loadMore.on('click', function (e) {
                e.preventDefault();
                self.loadMore($(this));
            });
        }

        this.$container.on('click', '.worksFilter__reset', function (e) {
            e.preventDefault();
            var resetUrl = self.$form.data('reset-url');
            if (resetUrl) {
                window.location.href = resetUrl;
            } else {
                self.$form[0].reset();
            }
        });

        this.$container.on('click', '.works__itemShare', function (e) {
            e.preventDefault();
            var $btn = $(this);
            var url = $btn.data('share-url') || window.location.href;
            var title = $btn.data('share-title') || document.title;
            var workId = $btn.data('work-id') || '';

            // Мобильные / планшеты — поведение как раньше
            if (window.innerWidth < 1024 && navigator.share) {
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

        this.$container.on('click', '.works__itemFavorite', function (e) {
            e.preventDefault();
            var $btn = $(this);
            var id = String($btn.data('work-id'));
            if (!id) return;

            var index = self.favorites.indexOf(id);
            if (index === -1) {
                self.favorites.push(id);
            } else {
                self.favorites.splice(index, 1);
                if (typeof swsWorksArchive !== 'undefined' && swsWorksArchive.favoritesPage) {
                    $btn.closest('.works__item').remove();
                    if (!self.$list.find('.works__item').length) {
                        $('#works-favorites-empty').show();
                    }
                }
            }
            self.saveFavorites();
            self.updateFavoriteIcons();
        });
    };

    WorksArchive.prototype.loadFavoritesContent = function () {
        var self = this;
        var $empty = $('#works-favorites-empty');

        if (!window.swsWorksArchive || !swsWorksArchive.ajaxurl) {
            $empty.show();
            return;
        }

        if (this.favorites.length === 0) {
            $empty.show();
            return;
        }
        $empty.hide();

        $.post(
            swsWorksArchive.ajaxurl,
            {
                action: 'works_favorites_cards',
                nonce: swsWorksArchive.nonce,
                post_ids: JSON.stringify(this.favorites),
            },
            function (response) {
                if (response.success && response.data && response.data.html) {
                    self.$list.append(response.data.html);
                }
                self.updateFavoriteIcons();
                self.initHomeSliders();
                $(document).trigger('blocksLoaded');
            }
        ).fail(function () {
            $empty.show();
        });
    };

    WorksArchive.prototype.loadFavorites = function () {
        // Только localStorage, без обращения к серверу/БД
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
    };

    WorksArchive.prototype.saveFavorites = function () {
        if (typeof window.localStorage === 'undefined') {
            return;
        }
        try {
            localStorage.setItem('worksFavorites', JSON.stringify(this.favorites));
        } catch (e) {
            // ignore
        }
    };

    WorksArchive.prototype.updateFavoriteIcons = function () {
        var favorites = this.favorites;
        this.$container.find('.works__itemFavorite').each(function () {
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

        // Обновляем иконку в шапке
        var $headerIcon = $('.header__favorLink i');
        if ($headerIcon.length) {
            if (favorites.length > 0) {
                $headerIcon.removeClass('ie-icon_heart').addClass('ie-icon_heart_half');
            } else {
                $headerIcon.removeClass('ie-icon_heart_half').addClass('ie-icon_heart');
            }
        }
    };

    WorksArchive.prototype.initRangeSliders = function () {
        if (!this.$rangeSliders.length || typeof $.fn.slider === 'undefined') {
            return;
        }

        this.$rangeSliders.each(function () {
            var $wrapper = $(this);
            var min = parseInt($wrapper.data('min'), 10) || 0;
            var max = parseInt($wrapper.data('max'), 10) || 100;
            var currentMin = parseInt($wrapper.data('current-min'), 10);
            var currentMax = parseInt($wrapper.data('current-max'), 10);

            if (isNaN(currentMin)) currentMin = min;
            if (isNaN(currentMax)) currentMax = max;

            var minInputName = $wrapper.data('min-input');
            var maxInputName = $wrapper.data('max-input');

            var $minInput = $('input[name="' + minInputName + '"]');
            var $maxInput = $('input[name="' + maxInputName + '"]');

            var $track = $wrapper.find('.worksFilter__rangeSliderTrack');
            var $minValue = $wrapper.find('.worksFilter__rangeValue--min > span');
            var $maxValue = $wrapper.find('.worksFilter__rangeValue--max > span');

            $track.slider({
                range: true,
                min: min,
                max: max,
                values: [currentMin, currentMax],
                slide: function (_event, ui) {
                    $minInput.val(ui.values[0]);
                    $maxInput.val(ui.values[1]);
                    $minValue.text(ui.values[0]);
                    $maxValue.text(ui.values[1]);
                },
                change: function (_event, ui) {
                    $minInput.val(ui.values[0]);
                    $maxInput.val(ui.values[1]);
                    $minValue.text(ui.values[0]);
                    $maxValue.text(ui.values[1]);
                },
            });
        });
    };

    WorksArchive.prototype.initHomeSliders = function () {
        if (typeof $.fn.slick === 'undefined') {
            return;
        }

        this.$list.find('.homeSlider').each(function () {
            var $slider = $(this);

            if ($slider.hasClass('slick-initialized')) {
                return;
            }

            var autoplay = $slider.data('autoplay') !== false;
            var autoplaySpeed = parseInt($slider.data('autoplay-speed'), 10) || 3000;
            var dots = $slider.data('dots') !== false;
            var arrows = $slider.data('arrows') === true;
            var fade = $slider.data('fade') === true;
            var speed = parseInt($slider.data('speed'), 10) || 500;

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
    };

    WorksArchive.prototype.loadMore = function ($button) {
        var self = this;
        if ($button.prop('disabled')) {
            return;
        }

        var currentPage = parseInt($button.data('current-page'), 10) || 1;
        var maxPages = parseInt($button.data('max-pages'), 10) || 1;

        if (currentPage >= maxPages) {
            $button.remove();
            return;
        }

        var formData = this.$form.serializeArray();
        var data = {
            action: 'works_archive_load_more',
            nonce: swsWorksArchive.nonce,
            page: currentPage + 1,
        };

        $.each(formData, function (_, field) {
            if (field.name in data) {
                if (!Array.isArray(data[field.name])) {
                    data[field.name] = [data[field.name]];
                }
                data[field.name].push(field.value);
            } else {
                data[field.name] = field.value;
            }
        });

        $button.prop('disabled', true);

        $.ajax({
            url: swsWorksArchive.ajaxurl,
            type: 'POST',
            data: data,
            success: function (response) {
                if (response.success && response.data && response.data.html) {
                    $('#works-list').append(response.data.html);
                    self.updateFavoriteIcons();
                    self.initHomeSliders();

                    $button.data('current-page', currentPage + 1);

                    if (currentPage + 1 >= maxPages) {
                        $button.remove();
                    } else {
                        $button.prop('disabled', false);
                    }
                } else {
                    $button.remove();
                }
            },
            error: function () {
                $button.prop('disabled', false);
            },
        });
    };

    $(document).ready(function () {
        if ($('#works-list').length && typeof swsWorksArchive !== 'undefined') {
            new WorksArchive();
        }

        // Иконка избранного в шапке по данным из localStorage
        if (typeof window.localStorage !== 'undefined') {
            try {
                var raw = localStorage.getItem('worksFavorites');
                var hasFavorites = false;
                if (raw) {
                    var parsed = JSON.parse(raw);
                    hasFavorites = Array.isArray(parsed) && parsed.length > 0;
                }

                if (hasFavorites) {
                    var $headerIcon = $('.header__favorLink i');
                    if ($headerIcon.length) {
                        $headerIcon.removeClass('ie-icon_heart').addClass('ie-icon_heart_half');
                    }
                }
            } catch (e) {}
        }
    });
})(jQuery);
