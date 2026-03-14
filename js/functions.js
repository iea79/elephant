document.addEventListener('DOMContentLoaded', function (e) {
    toggleNav();
    toggleSearch();
    stikedHeader();
    objectHomeVideo();
    initSearchPageLoadMore();
    initBlockPostsReorder();
    initObjectPlacementGallery();
    initWorksFilterToggle();
    initFirstScreenReorder();
    initSecondScreenReorder();
    initCompositionReorder();
    initModalToggles();
    initSendRequestModal();
    initWpformsPhoneFix();
    initYandexMapScrollLock();
    window.addEventListener('resize', handleLayoutResize);
});

function toggleNav() {
    // const nav = document.querySelector('.nav');
    const fullNav = document.querySelector('.header__fullnav');
    const toggles = document.querySelectorAll('.nav__toggle');
    const header = document.querySelector('#masthead');
    if (!toggles.length) return;

    // Create or get overlay
    let overlay = document.querySelector('.nav-backdrop');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'nav-backdrop';
        document.body.appendChild(overlay);
    }

    // Close overlay on click
    overlay.addEventListener('click', function (e) {
        e.preventDefault();
        // nav.classList.remove('open');
        fullNav.classList.remove('open');
        toggles.forEach((t) => t.classList.remove('active'));
        overlay.classList.remove('open');
        document.body.style.overflow = '';
    });

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            const isOpening = !fullNav.classList.contains('open');
            if (isOpening) {
                // Закрываем поиск, если он открыт
                closeAllMenus();
            }

            // nav.classList.toggle('open');
            fullNav.classList.toggle('open');
            toggles.forEach((t) => t.classList.toggle('active'));

            if (isOpening) {
                header.classList.add('open');
                overlay.classList.add('open');
                // if (window.screen.width < 768) {
                //     document.body.style.overflow = 'hidden';
                // }
            } else {
                overlay.classList.remove('open');
                header.classList.remove('open');
                // document.body.style.overflow = '';
            }
        });
    });
}

// Перенос блока .firstScreen__img в зависимости от ширины экрана
function getFirstScreenElements() {
    const firstScreen = document.querySelector('.firstScreen');
    if (!firstScreen) return null;

    const firstScreenImg = firstScreen.querySelector('.firstScreen__img');
    if (!firstScreenImg) return null;

    const firstScreenSearch = firstScreen.querySelector('.firstScreen__search');

    return {
        firstScreen,
        firstScreenImg,
        firstScreenSearch,
    };
}

function applyFirstScreenLayout() {
    const els = getFirstScreenElements();
    if (!els) return;

    const { firstScreen, firstScreenImg, firstScreenSearch } = els;

    if (window.innerWidth >= 768) {
        // Вставляем картинку в начало блока firstScreen
        if (firstScreen.firstElementChild !== firstScreenImg) {
            firstScreen.insertBefore(firstScreenImg, firstScreen.firstElementChild);
        }
    } else if (firstScreenSearch) {
        // Вставляем картинку в конец блока поиска
        if (firstScreenSearch.lastElementChild !== firstScreenImg) {
            firstScreenSearch.appendChild(firstScreenImg);
        }
    }
}

function handleFirstScreenResize() {
    applyFirstScreenLayout();
}

function initFirstScreenReorder() {
    applyFirstScreenLayout();
}

// Перенос блока .secondScreen__img в зависимости от ширины экрана
function getSecondScreenElements() {
    const secondScreen = document.querySelector('.secondScreen');
    if (!secondScreen) return null;

    const secondScreenImg = secondScreen.querySelector('.secondScreen__img');
    if (!secondScreenImg) return null;

    const secondScreenTop = secondScreen.querySelector('.secondScreen__top');
    const secondScreenContent = secondScreen.querySelector('.secondScreen__content');

    return {
        secondScreen,
        secondScreenImg,
        secondScreenTop,
        secondScreenContent,
    };
}

function applySecondScreenLayout() {
    const els = getSecondScreenElements();
    if (!els) return;

    const { secondScreen, secondScreenImg, secondScreenTop } = els;

    if (window.innerWidth < 480) {
        // Вставляем secondScreen__img сразу после secondScreen__top
        if (secondScreenTop && secondScreenTop.nextElementSibling !== secondScreenImg) {
            secondScreenTop.parentNode.insertBefore(secondScreenImg, secondScreenTop.nextElementSibling);
        }
    } else {
        // Вставляем secondScreen__img в конец блока secondScreen
        if (secondScreen && secondScreen.lastElementChild !== secondScreenImg) {
            secondScreen.appendChild(secondScreenImg);
        }
    }
}

function initSecondScreenReorder() {
    applySecondScreenLayout();
}

function getCompositionElements() {
    const composition = document.querySelector('.composition');
    if (!composition) return null;

    const compositionHead = composition.querySelector('.composition__head');
    const compositionBody = composition.querySelector('.composition__body');

    if (!compositionHead || !compositionBody) return null;

    return {
        composition,
        compositionHead,
        compositionBody,
    };
}

function applyCompositionLayout() {
    const els = getCompositionElements();
    if (!els) return;

    const { composition, compositionHead, compositionBody } = els;

    if (window.innerWidth >= 768) {
        // >=768: переносим в начало composition__body
        if (compositionHead.parentElement !== compositionBody) {
            compositionBody.insertBefore(compositionHead, compositionBody.firstElementChild);
        }
    } else {
        // <768: переносим в начало composition
        if (compositionHead.parentElement !== composition) {
            composition.insertBefore(compositionHead, composition.firstElementChild);
        }
    }
}

function initCompositionReorder() {
    applyCompositionLayout();
}

// Общий обработчик resize для адаптивных блоков
function handleLayoutResize() {
    applyFirstScreenLayout();
    applySecondScreenLayout();
    applyCompositionLayout();
    updateWorksFilterLayout();
    applyBlockPostsLayout();
    updateObjectPlacementGallery();
}

function initSearchPageLoadMore() {
    const searchPage = document.querySelector('.searchPage');
    const moreBtn = document.querySelector('.searchPage__more');
    if (!searchPage || !moreBtn) return;

    const items = Array.from(searchPage.querySelectorAll('.worksSearch__item'));
    const sections = Array.from(searchPage.querySelectorAll('.searchPage__section'));
    const STEP = 7;

    if (items.length <= STEP) {
        moreBtn.style.display = 'none';
        return;
    }

    let visibleCount = STEP;

    items.forEach((item, index) => {
        if (index >= STEP) {
            item.classList.add('searchPage__item--hidden');
        }
    });

    const updateSectionVisibility = () => {
        sections.forEach((section) => {
            const hasVisible = section.querySelector('.worksSearch__item:not(.searchPage__item--hidden)');
            section.style.display = hasVisible ? '' : 'none';
        });
    };

    updateSectionVisibility();

    moreBtn.addEventListener('click', function (e) {
        e.preventDefault();

        const nextVisible = visibleCount + STEP;

        items.forEach((item, index) => {
            if (index < nextVisible) {
                item.classList.remove('searchPage__item--hidden');
            }
        });

        visibleCount = nextVisible;

        if (visibleCount >= items.length) {
            moreBtn.style.display = 'none';
        }

        updateSectionVisibility();
    });
}

// Группа картинок в .objectPlacement:
// <= 768: оборачиваем все .wp-block-image в .objectPlacement__gallery
// >  768: разворачиваем обратно (убираем обертку)
function updateObjectPlacementGallery() {
    const groups = document.querySelectorAll('.objectPlacement > .wp-block-group');
    if (!groups.length) return;

    const isMobile = window.innerWidth <= 768;

    groups.forEach((group) => {
        const images = group.querySelectorAll('.wp-block-image');
        if (!images.length) return;

        let gallery = group.querySelector('.objectPlacement__gallery');

        if (isMobile) {
            if (!gallery) {
                gallery = document.createElement('div');
                gallery.className = 'objectPlacement__gallery';
                group.insertBefore(gallery, images[0]);
            }

            images.forEach((img) => {
                if (img.parentElement !== gallery) {
                    gallery.appendChild(img);
                }
            });
        } else if (gallery) {
            // Переносим все элементы обратно в группу и удаляем обертку
            while (gallery.firstChild) {
                group.insertBefore(gallery.firstChild, gallery);
            }
            gallery.remove();
        }
    });
}

function initObjectPlacementGallery() {
    updateObjectPlacementGallery();
}

function initYandexMapScrollLock() {
    const maps = document.querySelectorAll('.swsYandexMap');
    if (!maps.length) return;

    const overlays = [];

    maps.forEach((map) => {
        // Обеспечиваем, что контейнер может содержать абсолютный оверлей
        if (getComputedStyle(map).position === 'static') {
            map.style.position = 'relative';
        }

        const overlay = document.createElement('div');
        overlay.className = 'swsYandexMap-scrollShield';
        overlay.style.position = 'absolute';
        overlay.style.left = '0';
        overlay.style.top = '0';
        overlay.style.right = '0';
        overlay.style.bottom = '0';
        overlay.style.zIndex = '2';
        overlay.style.background = 'transparent';
        // По умолчанию блокируем скролл/тач по карте
        overlay.style.pointerEvents = 'auto';

        // Клик по оверлею «разблокирует» карту: убираем перехват событий
        overlay.addEventListener('click', function (e) {
            e.stopPropagation();
            overlay.style.pointerEvents = 'none';
            overlay.classList.add('swsYandexMap-scrollShield--unlocked');
        });

        map.appendChild(overlay);
        overlays.push({ map, overlay });
    });

    // Клик вне карты снова включает блокировку для всех карт
    document.addEventListener('click', function (e) {
        const target = e.target;
        const isInsideAnyMap = overlays.some(function (item) {
            return item.map.contains(target);
        });

        if (isInsideAnyMap) {
            return;
        }

        overlays.forEach(function (item) {
            item.overlay.style.pointerEvents = 'auto';
            item.overlay.classList.remove('swsYandexMap-scrollShield--unlocked');
        });
    });
}

// Перенос кнопки .wp-block-button в блоке .blockPosts
// >= 768: внутрь .blockPosts .wp-block-buttons
// <  768: сразу после .blockPosts__list
function applyBlockPostsLayout() {
    const blocks = document.querySelectorAll('.blockPosts');
    if (!blocks.length) return;

    const isDesktop = window.innerWidth >= 768;

    blocks.forEach((block) => {
        const button = block.querySelector('.wp-block-button');
        if (!button) return;

        const buttonsWrapper = block.querySelector('.wp-block-buttons');
        const list = block.querySelector('.blockPosts__list');

        if (isDesktop) {
            if (buttonsWrapper && button.parentElement !== buttonsWrapper) {
                buttonsWrapper.appendChild(button);
            }
        } else {
            if (list && list.nextElementSibling !== button) {
                list.parentNode.insertBefore(button, list.nextElementSibling);
            }
        }
    });
}

function initBlockPostsReorder() {
    applyBlockPostsLayout();
}

function updateWorksFilterLayout() {
    const container = document.querySelector('.worksFilter__container');
    if (!container) return;

    const isMobile = window.innerWidth < 768;

    if (!isMobile) {
        // Десктоп: фильтр всегда открыт, убираем инлайн-стили
        container.style.removeProperty('overflow');
        container.style.removeProperty('transition');
        container.style.removeProperty('height');
        container.classList.remove('is-open');
        return;
    }

    // Мобилка: плавное скрытие/показ по высоте (только для кликов, не для первой инициализации из inline-скрипта)
    container.style.overflow = 'hidden';
    container.style.transition = 'height 0.3s ease';

    if (container.classList.contains('is-open')) {
        container.style.height = container.scrollHeight + 'px';
    } else {
        container.style.height = '0px';
    }
}

function initWorksFilterToggle() {
    const toggle = document.querySelector('.worksFilter__toggle');
    const container = document.querySelector('.worksFilter__container');

    if (!toggle || !container) return;

    updateWorksFilterLayout();

    toggle.addEventListener('click', function (e) {
        e.preventDefault();

        // На десктопе ничего не делаем
        if (window.innerWidth >= 768) return;

        const willOpen = !container.classList.contains('is-open');

        if (willOpen) {
            container.classList.add('is-open');
        } else {
            container.classList.remove('is-open');
        }

        updateWorksFilterLayout();

        // Сохраняем состояние фильтра на мобильных
        if (typeof window.localStorage !== 'undefined') {
            try {
                localStorage.setItem('worksFilterIsOpen', container.classList.contains('is-open') ? '1' : '0');
            } catch (e) {}
        }
    });
}

function objectHomeVideo() {
    const objectHome = document.querySelector('.objectHome');
    if (!objectHome) return;

    const videoBlock = objectHome.querySelector('.objectHome__video');
    if (!videoBlock) return;

    const video = videoBlock.querySelector('video');
    const buttonsWrapper = videoBlock.querySelector('.wp-block-buttons');

    if (!video || !buttonsWrapper) return;

    const hideButtons = () => {
        buttonsWrapper.style.display = 'none';
    };

    const showButtons = () => {
        buttonsWrapper.style.display = '';
    };

    // Клик по любой кнопке внутри .wp-block-buttons
    buttonsWrapper.addEventListener('click', function (e) {
        e.preventDefault();
        if (video.paused || video.ended) {
            video.play();
        }
        hideButtons();
    });

    video.addEventListener('click', function () {
        if (!video.paused && !video.ended) {
            video.pause();
            showButtons();
        } else {
            video.play();
            hideButtons();
        }
    });

    video.addEventListener('ended', function () {
        showButtons();
    });
}

function toggleSearch() {
    const box = document.querySelector('.header__navsearch');
    const toggles = document.querySelectorAll('.search__toggle');
    const header = document.querySelector('#masthead');
    if (!box || !toggles.length) return;

    // Create or get overlay
    let overlay = document.querySelector('.search-backdrop');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'search-backdrop';
        document.body.appendChild(overlay);
    }

    // Close overlay on click
    overlay.addEventListener('click', function (e) {
        e.preventDefault();
        box.classList.remove('open');
        overlay.classList.remove('open');
    });

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            const isOpening = !box.classList.contains('open');
            if (isOpening) {
                // Закрываем меню навигации, если оно открыто
                closeAllMenus();
            }
            box.classList.toggle('open');
            if (isOpening) {
                header.classList.add('open');
                overlay.classList.add('open');
            } else {
                overlay.classList.remove('open');
                header.classList.remove('open');
            }
        });
    });
}

function stikedHeader() {
    const header = document.querySelector('.header_sticked');
    if (!header) return;
    const sticky = header.offsetTop;

    toggle();

    window.addEventListener('scroll', function (e) {
        toggle();
    });

    function toggle() {
        if (window.pageYOffset > sticky) {
            header.classList.add('sticky');
        } else {
            header.classList.remove('sticky');
        }
    }
}

// Close all open menus and search when modal opens
function closeAllMenus() {
    const fullNav = document.querySelector('.header__fullnav');
    const search = document.querySelector('.header__navsearch');
    const navToggles = document.querySelectorAll('.nav__toggle');
    const searchToggles = document.querySelectorAll('.search__toggle');
    const navOverlay = document.querySelector('.nav-backdrop');
    const searchOverlay = document.querySelector('.search-backdrop');
    const header = document.querySelector('#masthead');

    // Закрываем навигацию, если открыта
    if (fullNav && fullNav.classList.contains('open')) {
        fullNav.classList.remove('open');
        navToggles.forEach((t) => t.classList.remove('active'));
        if (navOverlay) navOverlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    // Закрываем поиск, если открыт
    if (search && search.classList.contains('open')) {
        search.classList.remove('open');
        if (searchOverlay) searchOverlay.classList.remove('open');
    }

    // Всегда убираем класс open с хедера,
    // конкретное состояние при открытии ставится в toggleNav/toggleSearch
    if (header) {
        header.classList.remove('open');
    }
}

// Close menus when clicking modal toggle buttons
function initModalToggles() {
    const modalToggles = document.querySelectorAll('[data-toggle="modal"]');
    modalToggles.forEach((toggle) => {
        toggle.addEventListener('click', function (e) {
            const targetSelector = this.getAttribute('data-target') || '';
            const targetId = targetSelector.replace('#', '');

            // Закрываем меню/поиск перед открытием модалки
            closeAllMenus();

            // Для модалки getConsult подменяем заголовок на текст кнопки
            if (targetId === 'getConsult' && typeof jQuery !== 'undefined') {
                const $btn = jQuery(this);
                const title = jQuery.trim($btn.text()) || $btn.attr('data-title') || '';
                if (title) {
                    jQuery('#getConsult').find('.modal-title').text(title);
                }
            }
        });
    });
}

// Открытие модалки getConsult по клику на .js-send-request
function initSendRequestModal() {
    if (typeof jQuery === 'undefined') return;

    jQuery(document).on('click', '.js-send-request', function (e) {
        e.preventDefault();
        var $btn = jQuery(this);
        var title = jQuery.trim($btn.text()) || $btn.attr('data-title') || '';
        var $modal = jQuery('#getConsult');
        if ($modal.length && typeof $modal.modal === 'function') {
            if (title) {
                $modal.find('.modal-title').text(title);
            }
            $modal.modal('show');
        }
    });
}

// WPForms + Inputmask: фикс ошибок при автозаполнении телефона
function initWpformsPhoneFix() {
    if (typeof jQuery === 'undefined') return;

    // Селектор для телефонных полей WPForms (при необходимости можно уточнить)
    var PHONE_SELECTOR = '.wpforms-field-phone input, .wpforms-field-phone-alt input, input[type="tel"]';

    function detachInputmask($inputs) {
        if (!window.Inputmask && !(jQuery.fn && jQuery.fn.inputmask)) {
            return;
        }

        $inputs.each(function () {
            var $el = jQuery(this);
            try {
                // Снимаем маску, чтобы избежать падений keyEvent внутри jquery.inputmask
                if (typeof $el.inputmask === 'function') {
                    $el.inputmask('remove');
                }
                // На всякий случай убираем навешанные обработчики inputmask
                $el.off('.inputmask');
            } catch (e) {}
        });
    }

    function applyPhoneFix(context) {
        var $ctx = context ? jQuery(context) : jQuery(document);
        var $phones = $ctx.find(PHONE_SELECTOR);
        if (!$phones.length) return;

        // 1) Снимаем inputmask сразу после инициализации формы
        detachInputmask($phones);

        // 2) Подчищаем значение после автозаполнения/вставки, но без маски
        $phones.off('.wpformsPhoneFix').on('change.wpformsPhoneFix input.wpformsPhoneFix', function () {
            var val = this.value;
            if (typeof val !== 'string') return;
            // Оставляем только цифры и символы +()-
            var cleaned = val.replace(/[^0-9+()\-\s]/g, '');
            if (cleaned !== val) {
                this.value = cleaned;
            }
        });
    }

    // Инициализация при первой загрузке документа
    jQuery(function () {
        applyPhoneFix(document);
    });

    // Повторная инициализация при готовности/перерисовке WPForms (AJAX, мульти-формы и т.п.)
    jQuery(document).on('wpformsReady wpformsAjaxSubmitSuccess wpformsAjaxSubmit', function (event, form) {
        applyPhoneFix(form || document);
    });
}

// Маска ввода для элементов с data-inputmask-mask
jQuery(document).ready(function () {
    jQuery('[data-inputmask-mask]').each(function () {
        const mask = jQuery(this).attr('data-inputmask-mask');
        if (mask) {
            jQuery(this).mask(mask);
        }
    });
});
