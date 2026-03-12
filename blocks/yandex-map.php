<?php

/**
 * Блок "Яндекс карта" для Гутенберга.
 *
 * @package sws
 */

/**
 * Регистрация скриптов для блока.
 */
function sws_yandex_map_assets()
{
    wp_register_script(
        'sws-yandex-map-editor',
        get_template_directory_uri() . '/blocks/yandex-map/edit.js',
        array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components', 'wp-data'),
        _S_VERSION,
        true
    );

    wp_register_script(
        'sws-yandex-map-front',
        get_template_directory_uri() . '/blocks/yandex-map/front.js',
        array(),
        _S_VERSION,
        true
    );
}
add_action('init', 'sws_yandex_map_assets');

/**
 * Регистрация блока.
 */
function sws_register_yandex_map_block()
{
    register_block_type(
        __DIR__ . '/yandex-map',
        array(
            'render_callback' => 'sws_render_yandex_map_block',
            'editor_script'   => 'sws-yandex-map-editor',
            'script'          => 'sws-yandex-map-front',
        )
    );
}
add_action('init', 'sws_register_yandex_map_block');

/**
 * Рендер блока на фронтенде.
 *
 * @param array $attributes Атрибуты блока.
 * @return string HTML.
 */
function sws_render_yandex_map_block($attributes)
{
    $address      = isset($attributes['address']) ? trim((string) $attributes['address']) : '';
    $marker_id    = isset($attributes['markerId']) ? (int) $attributes['markerId'] : 0;
    $marker_url   = '';
    $zoom         = isset($attributes['zoom']) ? (int) $attributes['zoom'] : 16;
    $map_height   = isset($attributes['height']) ? (int) $attributes['height'] : 360;
    $api_key_attr = isset($attributes['apiKey']) ? trim((string) $attributes['apiKey']) : '';
    $api_key      = $api_key_attr !== '' ? $api_key_attr : apply_filters('sws_yandex_map_api_key', '');

    if ($marker_id) {
        $marker_url = wp_get_attachment_image_url($marker_id, 'full');
    }

    if ($address === '') {
        return '';
    }

    // Уникальный ID контейнера для карты
    $map_id = 'sws-yandex-map-' . uniqid();

    // Подключаем скрипт API Яндекс-Карт v3 только один раз
    if (!wp_script_is('sws-yandex-maps-api', 'enqueued') && !wp_script_is('sws-yandex-maps-api', 'registered')) {
        $api_url = 'https://api-maps.yandex.ru/v3/?lang=ru_RU';
        if (!empty($api_key)) {
            $api_url .= '&apikey=' . rawurlencode($api_key);
        }

        // Важно: версия null, чтобы WP не добавлял ?ver=...
        wp_register_script(
            'sws-yandex-maps-api',
            $api_url,
            array(),
            null,
            true
        );
        wp_enqueue_script('sws-yandex-maps-api');
    } else {
        wp_enqueue_script('sws-yandex-maps-api');
    }

    ob_start();
?>
    <div
        id="<?php echo esc_attr($map_id); ?>"
        class="swsYandexMap"
        data-address="<?php echo esc_attr($address); ?>"
        data-zoom="<?php echo esc_attr($zoom); ?>"
        data-marker-url="<?php echo esc_attr($marker_url); ?>"
        style="min-height: <?php echo esc_attr($map_height); ?>px; background: #f2f2f2;"></div>
<?php

    return ob_get_clean();
}
