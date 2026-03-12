(function () {
    'use strict';

    // Конфигурация
    const REST_API_BASE = window.wpApiSettings ? window.wpApiSettings.root : '/wp-json/';
    const POST_TYPE = 'works';
    const DEBOUNCE_DELAY = 300;

    // Инициализация всех селекторов на странице
    document.addEventListener('DOMContentLoaded', function () {
        const selectors = document.querySelectorAll('.objectSelector');
        selectors.forEach(initSelector);
    });

    function initSelector(container) {
        const input = container.querySelector('.objectSelector__input');
        const results = container.querySelector('.objectSelector__results');
        const button = container.querySelector('.objectSelector__button');
        const hiddenInput = container.querySelector('.objectSelector__hidden');

        if (!input) return;

        let debounceTimer;
        let selectedPostId = hiddenInput ? parseInt(hiddenInput.value, 10) : 0;

        // Обработчик ввода
        input.addEventListener('input', function (e) {
            clearTimeout(debounceTimer);
            const query = e.target.value.trim();
            // if (query.length < 2) {
            //     clearResults();
            //     hideResults();
            //     return;
            // }
            debounceTimer = setTimeout(() => searchPosts(query), DEBOUNCE_DELAY);
        });

        // Показывать результаты при фокусе, если есть текст
        input.addEventListener('focus', function (e) {
            const query = e.target.value.trim();
            // if (results.innerHTML.trim() !== '') {
            debounceTimer = setTimeout(() => searchPosts(query), DEBOUNCE_DELAY);
            showResults();
            // }
        });

        // Скрывать результаты при клике вне
        document.addEventListener('click', function (e) {
            if (!container.contains(e.target)) {
                hideResults();
            }
        });

        // Обработчик клика по результату
        if (results) {
            results.addEventListener('click', function (e) {
                const item = e.target.closest('.objectSelector__result');
                if (!item) return;
                const postId = item.dataset.id;
                const title = item.dataset.title;
                const link = item.dataset.link;
                selectPost(postId, title, link);
            });
        }

        // Обработчик кнопки
        if (button) {
            button.addEventListener('click', function () {
                if (selectedPostId) {
                    const link = button.getAttribute('href');
                    if (link) {
                        window.location.href = link;
                    }
                } else {
                    // alert('Выберите пост');
                }
            });
        }

        function searchPosts(query = '') {
            fetch(REST_API_BASE + `wp/v2/${POST_TYPE}?per_page=10&search=${encodeURIComponent(query)}`)
                .then((response) => response.json())
                .then((posts) => displayResults(posts))
                .catch((error) => console.error('Ошибка поиска:', error));
        }

        function displayResults(posts) {
            clearResults();
            if (!posts.length) {
                const noResults = document.createElement('div');
                noResults.className = 'objectSelector__no-results';
                noResults.textContent = 'Ничего не найдено';
                results.appendChild(noResults);
            } else {
                posts.forEach((post) => {
                    const item = document.createElement('div');
                    item.className = 'objectSelector__result';
                    item.dataset.id = post.id;
                    item.dataset.title = post.title.rendered;
                    item.dataset.link = post.link;
                    item.innerHTML = `<span>${post.title.rendered}</span>`;
                    results.appendChild(item);
                });
            }
            showResults();
        }

        function clearResults() {
            if (results) {
                results.innerHTML = '';
            }
        }

        function showResults() {
            if (results) {
                results.style.display = 'block';
            }
        }

        function hideResults() {
            if (results) {
                results.style.display = 'none';
            }
        }

        function selectPost(postId, title, link) {
            selectedPostId = postId;
            input.value = title;
            if (hiddenInput) hiddenInput.value = postId;
            clearResults();
            hideResults();
            // Обновляем ссылку кнопки
            if (button) {
                button.setAttribute('href', link);
            }
        }
    }
})();
