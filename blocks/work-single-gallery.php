<?php

/**
 * Блок "Галерея объекта (workSingle)" для Гутенберга.
 *
 * @package sws
 */

/**
 * Регистрация скриптов для блока.
 */
function sws_work_single_gallery_assets()
{
    wp_register_script(
        'sws-work-single-gallery-editor',
        get_template_directory_uri() . '/blocks/work-single-gallery/edit.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-data'),
        _S_VERSION,
        true
    );
}
add_action('init', 'sws_work_single_gallery_assets');

/**
 * Регистрация блока.
 */
function sws_register_work_single_gallery_block()
{
    register_block_type(
        __DIR__ . '/work-single-gallery',
        array(
            'render_callback' => 'sws_render_work_single_gallery_block',
            'editor_script'   => 'sws-work-single-gallery-editor',
        )
    );
}
add_action('init', 'sws_register_work_single_gallery_block');

/**
 * Рендер блока на фронтенде.
 *
 * @param array $attributes Атрибуты блока.
 * @return string HTML.
 */
function sws_render_work_single_gallery_block($attributes)
{
    $images = isset($attributes['images']) && is_array($attributes['images']) ? $attributes['images'] : array();

    if (empty($images)) {
        return '';
    }

    ob_start();
    ?>
    <div class="workSingle__gallery">
        <div class="workSingle__sliderWrapper">
            <div class="workSingle__slider js-workSingle-main">
                <?php foreach ($images as $image) :
                    $image_id  = isset($image['id']) ? (int) $image['id'] : 0;
                    $full_url  = isset($image['url']) ? $image['url'] : '';
                    $thumb_url = isset($image['thumb']) && $image['thumb'] ? $image['thumb'] : $full_url;

                    if (!$full_url) {
                        continue;
                    }

                    $alt = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
                    if ($alt === '') {
                        $alt = $image_id ? get_the_title($image_id) : '';
                    }
                    ?>
                    <div class="workSingle__slide">
                        <a href="<?php echo esc_url($full_url); ?>"
                           class="workSingle__slideLink"
                           data-fancybox="work-gallery">
                            <img src="<?php echo esc_url($full_url); ?>"
                                 alt="<?php echo esc_attr($alt); ?>">
                            <span class="workSingle__zoom">
                                <span class="ie-icon_zoom"></span>
                            </span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (count($images) > 1) : ?>
            <div class="workSingle__thumbs js-workSingle-thumbs">
                <?php foreach ($images as $index => $image) :
                    $thumb_url = isset($image['thumb']) && $image['thumb'] ? $image['thumb'] : (isset($image['url']) ? $image['url'] : '');
                    $image_id  = isset($image['id']) ? (int) $image['id'] : 0;
                    $alt       = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
                    if ($alt === '') {
                        $alt = $image_id ? get_the_title($image_id) : '';
                    }
                    if (!$thumb_url) {
                        continue;
                    }
                    ?>
                    <button type="button"
                            class="workSingle__thumb"
                            data-slide="<?php echo esc_attr($index); ?>">
                        <img src="<?php echo esc_url($thumb_url); ?>"
                             alt="<?php echo esc_attr($alt); ?>">
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php

    return ob_get_clean();
}

