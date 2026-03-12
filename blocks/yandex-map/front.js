(function () {
    'use strict';

    function initMapInstance(ymaps3, config) {
        if (!config || !config.address || !config.container) {
            console.warn('swsYandexMap: invalid config', config);
            return;
        }

        var container = config.container;
        if (!container) {
            console.warn('swsYandexMap: container not found', config);
            return;
        }

        var zoom = parseInt(config.zoom, 10);
        if (isNaN(zoom)) {
            zoom = 16;
        }

        var location = {
            center: [37.623082, 55.75254], // запасной центр (Москва)
            zoom: zoom,
        };

        function createMap() {
            var YMap = ymaps3.YMap;
            var YMapDefaultSchemeLayer = ymaps3.YMapDefaultSchemeLayer;
            var YMapDefaultFeaturesLayer = ymaps3.YMapDefaultFeaturesLayer;
            var YMapMarker = ymaps3.YMapMarker;

            if (!YMap || !YMapDefaultSchemeLayer || !YMapDefaultFeaturesLayer || !YMapMarker) {
                console.error('swsYandexMap: required ymaps3 classes missing', ymaps3);
                return;
            }

            console.log('swsYandexMap: creating map', location, config);

            var map = new YMap(
                container,
                {
                    location: location,
                },
                [new YMapDefaultSchemeLayer({}), new YMapDefaultFeaturesLayer({})]
            );

            var markerElement = document.createElement('div');
            markerElement.style.width = '36px';
            markerElement.style.height = '36px';
            markerElement.style.display = 'flex';
            markerElement.style.alignItems = 'center';
            markerElement.style.justifyContent = 'center';
            markerElement.style.borderRadius = '50%';

            if (config.markerUrl) {
                var img = document.createElement('img');
                img.src = config.markerUrl;
                img.alt = '';
                img.style.maxWidth = '100%';
                img.style.maxHeight = '100%';
                img.style.objectFit = 'contain';
                markerElement.appendChild(img);
            } else {
                // Стандартный красный маркер с «хвостиком»
                markerElement.style.background = 'transparent';
                markerElement.style.color = 'transparent';
                markerElement.style.fontSize = '0';
                markerElement.style.overflow = 'visible';

                var svgNS = 'http://www.w3.org/2000/svg';
                var svg = document.createElementNS(svgNS, 'svg');
                svg.setAttribute('width', '32');
                svg.setAttribute('height', '48');
                svg.setAttribute('viewBox', '0 0 32 48');

                var path = document.createElementNS(svgNS, 'path');
                path.setAttribute(
                    'd',
                    'M16 0C9.372 0 4 5.372 4 12c0 8.25 9.333 18.917 11.44 21.134a1 1 0 0 0 1.12 0C18.667 30.917 28 20.25 28 12 28 5.372 22.628 0 16 0z'
                );
                path.setAttribute('fill', '#ff0000');

                var circle = document.createElementNS(svgNS, 'circle');
                circle.setAttribute('cx', '16');
                circle.setAttribute('cy', '12');
                circle.setAttribute('r', '5');
                circle.setAttribute('fill', '#ffffff');

                svg.appendChild(path);
                svg.appendChild(circle);
                markerElement.appendChild(svg);
            }

            var marker = new YMapMarker(
                {
                    coordinates: location.center,
                },
                markerElement
            );

            map.addChild(marker);
        }

        // Если есть геокодер v3 — пробуем им, иначе просто показываем карту по умолчанию
        if (typeof ymaps3.search === 'function') {
            var searchParams = {
                text: config.address,
            };

            ymaps3
                .search(searchParams)
                .then(function (results) {
                    if (
                        Array.isArray(results) &&
                        results.length &&
                        results[0].geometry &&
                        results[0].geometry.coordinates
                    ) {
                        location.center = results[0].geometry.coordinates;
                    }
                })
                .catch(function (e) {
                    console.warn('swsYandexMap: search error', e);
                })
                .finally(createMap);
        } else {
            console.warn('swsYandexMap: ymaps3.search is not available, using default center');
            createMap();
        }
    }

    function initAllMaps(ymaps3) {
        var nodes = document.querySelectorAll('.swsYandexMap');
        if (!nodes.length) {
            console.log('swsYandexMap: no map containers found');
            return;
        }

        nodes.forEach(function (node) {
            var zoomAttr = node.getAttribute('data-zoom');
            var markerUrlAttr = node.getAttribute('data-marker-url') || '';
            var cfg = {
                container: node,
                address: node.getAttribute('data-address') || '',
                zoom: zoomAttr ? parseInt(zoomAttr, 10) : 16,
                markerUrl: markerUrlAttr,
            };
            if (cfg.address) {
                initMapInstance(ymaps3, cfg);
            } else {
                console.warn('swsYandexMap: container has empty address', node);
            }
        });
    }

    function whenYandexReady() {
        if (typeof window.ymaps3 === 'undefined') {
            // API ещё не загрузился — пробуем снова чуть позже
            setTimeout(whenYandexReady, 300);
            return;
        }
        window.ymaps3.ready.then(function () {
            console.log('swsYandexMap: ymaps3.ready, init maps');
            initAllMaps(window.ymaps3);
        });
    }

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(whenYandexReady, 0);
    } else {
        document.addEventListener('DOMContentLoaded', whenYandexReady);
    }
})();

