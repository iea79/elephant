(function ($) {
    'use strict';

    var WorksSearch = function (container) {
        this.$container = $(container);
        this.$input = this.$container.find('.worksSearch__input');
        this.$button = this.$container.find('.worksSearch__button');
        this.$results = this.$container.find('.worksSearch__results');
        this.init();
    };

    WorksSearch.prototype = {
        init: function () {
            this.bindEvents();
        },

        bindEvents: function () {
            var self = this;

            // При нажатии на кнопку переходим на страницу поиска
            this.$button.on('click', function () {
                var searchTerm = self.$input.val().trim();
                if (!searchTerm.length) {
                    return;
                }

                var base = (typeof swsWorksSearch !== 'undefined' && swsWorksSearch.searchUrl)
                    ? swsWorksSearch.searchUrl
                    : window.location.origin + '/';

                var url = base;
                url += (base.indexOf('?') === -1 ? '?' : '&') + 's=' + encodeURIComponent(searchTerm);

                window.location.href = url;
            });

            // Поиск при вводе с задержкой
            var typingTimer;
            this.$input.on('keyup', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function () {
                    self.performSearch();
                }, 300);
            });

            // Закрытие результатов при клике вне
            // Работает только для выпадающего списка в шапке,
            // а не для блочного списка на странице поиска (worksSearch--page)
            if (!this.$container.hasClass('worksSearch--page')) {
                $(document).on('click', function (e) {
                    if (!$(e.target).closest(self.$container).length) {
                        self.$results.hide();
                    }
                });
            }
        },

        performSearch: function () {
            var searchTerm = this.$input.val().trim();
            if (searchTerm.length < 2) {
                this.$results.hide().empty();
                return;
            }

            var self = this;
            $.ajax({
                url: swsWorksSearch.ajaxurl,
                type: 'POST',
                data: {
                    action: 'works_search',
                    nonce: swsWorksSearch.nonce,
                    search: searchTerm,
                },
                beforeSend: function () {
                    self.$results.html('<div class="worksSearch__loading">' + swsWorksSearch.loadingText + '</div>').show();
                },
                success: function (response) {
                    if (response.success) {
                        self.$results.html(response.data.html);
                        self.$results.show();
                    } else {
                        self.$results.html('<div class="worksSearch__error">' + swsWorksSearch.errorText + '</div>').show();
                    }
                },
                error: function () {
                    self.$results.html('<div class="worksSearch__error">' + swsWorksSearch.errorText + '</div>').show();
                },
            });
        },
    };

    $(document).ready(function () {
        $('.worksSearch').each(function () {
            new WorksSearch(this);
        });
    });
})(jQuery);