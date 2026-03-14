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

    // Фильтруем корректные ID
    $image_ids = array_filter(array_map('intval', (array) $images));
    if (empty($image_ids)) {
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
        <?php foreach ($image_ids as $image_id) : ?>
            <?php
            $image_html = wp_get_attachment_image(
                $image_id,
                'large',
                false,
                array(
                    'class'   => 'homeSlider__image',
                    'loading' => 'lazy',
                )
            );
            if (!$image_html) {
                continue;
            }
            ?>
            <div class="homeSlider__slide">
                <?php echo $image_html; ?>
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