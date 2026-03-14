<?php

/**
 * Блок "Шаги" для Гутенберга.
 *
 * @package sws
 */

/**
 * Регистрация скриптов и стилей для блока.
 */
function sws_steps_assets()
{
    // GSAP Core from CDN
    wp_register_script(
        'gsap-core',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js',
        array(),
        '3.12.5',
        true
    );
    // GSAP ScrollTrigger plugin
    wp_register_script(
        'gsap-scrolltrigger',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js',
        array('gsap-core'),
        '3.12.5',
        true
    );

    wp_register_script(
        'sws-steps-editor',
        get_template_directory_uri() . '/blocks/steps/edit.js',
        array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-data', 'wp-components', 'wp-block-editor', 'wp-media-utils'),
        _S_VERSION,
        true
    );

    // Frontend script with GSAP dependency
    wp_register_script(
        'sws-steps-front',
        get_template_directory_uri() . '/blocks/steps/front.js',
        array('gsap-core', 'gsap-scrolltrigger'),
        _S_VERSION,
        true
    );
}
add_action('init', 'sws_steps_assets');

/**
 * Регистрация блока.
 */
function sws_register_steps_block()
{
    register_block_type(
        __DIR__ . '/steps',
        array(
            'render_callback' => 'sws_render_steps_block',
            'editor_script'   => 'sws-steps-editor',
            'script'          => 'sws-steps-front',
        )
    );
}
add_action('init', 'sws_register_steps_block');

/**
 * Рендер блока на фронтенде.
 *
 * @param array $attributes Атрибуты блока.
 * @return string HTML.
 */
function sws_render_steps_block($attributes)
{
    $steps = $attributes['steps'] ?? array();

    if (empty($steps)) {
        return '';
    }

    ob_start();
?>
    <div class="steps">
        <?php foreach ($steps as $index => $step) : ?>
            <?php
            $image_id = isset($step['imageId']) ? (int) $step['imageId'] : 0;
            $title    = isset($step['title']) ? $step['title'] : '';
            $text     = isset($step['text']) ? $step['text'] : '';
            $num = $index + 1;
            ?>
            <div class="steps__item">
                <?php if ($image_id) : ?>
                    <div class="steps__imageWrapper">
                        <?php
                        echo wp_get_attachment_image(
                            $image_id,
                            'medium',
                            false,
                            array(
                                'class'   => 'steps__image',
                                'loading' => 'lazy',
                                'alt'     => $title,
                            )
                        );
                        ?>
                    </div>
                <?php endif; ?>

                <div class="steps__body">
                    <?php if ($title) : ?>
                        <h3 class="steps__title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if ($text) : ?>
                        <div class="steps__text">
                            <?php echo esc_html($text); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php
    return ob_get_clean();
}
