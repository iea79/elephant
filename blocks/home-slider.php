<?php

/**
 * Блок "Слайдер для главной" для Гутенберга.
 *
 * @package sws
 */

/**
 * Регистрация скриптов и стилей для блока.
 */
function sws_home_slider_assets()
{
    // Регистрируем slick, если ещё не зарегистрирован
    if (!wp_script_is('slick', 'registered')) {
        wp_register_script(
            'slick',
            get_template_directory_uri() . '/js/slick.min.js',
            array('jquery'),
            _S_VERSION,
            true
        );
    }
    if (!wp_style_is('slick', 'registered')) {
        wp_register_style(
            'slick',
            get_template_directory_uri() . '/css/slick.css',
            array(),
            _S_VERSION
        );
    }

    wp_register_script(
        'sws-home-slider-editor',
        get_template_directory_uri() . '/blocks/home-slider/edit.js',
        array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-media-utils'),
        _S_VERSION,
        true
    );
    wp_register_script(
        'sws-home-slider-front',
        get_template_directory_uri() . '/blocks/home-slider/front.js',
        array('jquery', 'slick'),
        _S_VERSION,
        true
    );
}
add_action('init', 'sws_home_slider_assets');

/**
 * Регистрация блока.
 */
function sws_register_home_slider_block()
{
    register_block_type(
        __DIR__ . '/home-slider',
        array(
            'render_callback' => 'sws_render_home_slider_block',
            'editor_script'   => 'sws-home-slider-editor',
            'script'          => 'sws-home-slider-front',
            'style'           => 'slick',
        )
    );
}
add_action('init', 'sws_register_home_slider_block');

/**
 * Рендер блока на фронтенде.
 *
 * @param array $attributes Атрибуты блока.
 * @return string HTML.
 */
function sws_render_home_slider_block($attributes)
{
    $images = $attributes['images'] ?? [];
    $autoplay = isset($attributes['autoplay']) ? (bool) $attributes['autoplay'] : true;
    $autoplay_speed = isset($attributes['autoplaySpeed']) ? (int) $attributes['autoplaySpeed'] : 3000;
    $dots = isset($attributes['dots']) ? (bool) $attributes['dots'] : true;
    $arrows = isset($attributes['arrows']) ? (bool) $attributes['arrows'] : false;
    $fade = isset($attributes['fade']) ? (bool) $attributes['fade'] : false;
    $speed = isset($attributes['speed']) ? (int) $attributes['speed'] : 500;

    if (empty($images)) {
        return '<p>' . esc_html__('Изображения не выбраны.', 'sws') . '</p>';
    }

    // Получаем URL изображений
    $image_urls = array_map(function ($id) {
        return wp_get_attachment_image_url($id, 'large');
    }, $images);

    // Убираем пустые
    $image_urls = array_filter($image_urls);

    if (empty($image_urls)) {
        return '<p>' . esc_html__('Нет доступных изображений.', 'sws') . '</p>';
    }

    ob_start();
?>
    <div class="homeSlider"
        data-autoplay="<?php echo esc_attr($autoplay ? 'true' : 'false'); ?>"
        data-autoplay-speed="<?php echo esc_attr($autoplay_speed); ?>"
        data-dots="<?php echo esc_attr($dots ? 'true' : 'false'); ?>"
        data-arrows="<?php echo esc_attr($arrows ? 'true' : 'false'); ?>"
        data-fade="<?php echo esc_attr($fade ? 'true' : 'false'); ?>"
        data-speed="<?php echo esc_attr($speed); ?>">
        <?php foreach ($image_urls as $url) : ?>
            <div class="homeSlider__slide">
                <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr__('Слайд', 'sws'); ?>" class="homeSlider__image" />
            </div>
        <?php endforeach; ?>
    </div>
    <style>
        :root {
            --home-slider-autoplay-speed: <?php echo ($autoplay_speed + $speed) / 1000; ?>s;
        }
    </style>
<?php
    return ob_get_clean();
}