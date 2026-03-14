<?php

/**
 * Блок "Категории работ" для Гутенберга.
 *
 * @package sws
 */

/**
 * Регистрация скриптов и стилей для блока.
 */
function sws_works_categories_assets()
{
    wp_register_script(
        'sws-works-categories-editor',
        get_template_directory_uri() . '/blocks/works-categories/edit.js',
        array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-api-fetch', 'wp-components', 'wp-block-editor', 'wp-media-utils'),
        _S_VERSION,
        true
    );
    wp_register_script(
        'sws-works-categories-front',
        get_template_directory_uri() . '/blocks/works-categories/front.js',
        array(),
        _S_VERSION,
        true
    );
}
add_action('init', 'sws_works_categories_assets');

/**
 * Регистрация блока.
 */
function sws_register_works_categories_block()
{
    register_block_type(
        __DIR__ . '/works-categories',
        array(
            'render_callback' => 'sws_render_works_categories_block',
            'editor_script'   => 'sws-works-categories-editor',
            'script'          => 'sws-works-categories-front',
        )
    );
}
add_action('init', 'sws_register_works_categories_block');

/**
 * Рендер блока на фронтенде.
 *
 * @param array $attributes Атрибуты блока.
 * @return string HTML.
 */
function sws_render_works_categories_block($attributes)
{
    $categories = $attributes['categories'] ?? [];

    if (empty($categories)) {
        return '<p>' . esc_html__('Категории не выбраны.', 'sws') . '</p>';
    }

    ob_start();
?>
    <div class="worksCategories">
        <?php foreach ($categories as $cat) : ?>
            <?php
            $term_id = $cat['id'] ?? 0;
            $image_id = isset($cat['imageId']) ? (int) $cat['imageId'] : 0;
            $term = $term_id ? get_term($term_id, 'works_category') : null;
            if (!$term || is_wp_error($term)) {
                continue;
            }
            $term_name = $term->name;
            // Прямая ссылка на архив таксономии works_category.
            $term_link = get_term_link($term, 'works_category');
            ?>
            <div class="worksCategories__item">
                <a href="<?php echo esc_url($term_link); ?>" class="worksCategories__link">
                    <?php
                    if ($image_id) {
                        echo wp_get_attachment_image(
                            $image_id,
                            'medium',
                            false,
                            array(
                                'class'   => 'worksCategories__image',
                                'loading' => 'lazy',
                                'alt'     => $term_name,
                            )
                        );
                    }
                    ?>
                    <h3 class="worksCategories__title"><?php echo esc_html($term_name); ?></h3>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php
    return ob_get_clean();
}
