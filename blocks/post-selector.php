<?php

/**
 * Блок "Выбор поста" для Гутенберга.
 *
 * @package sws
 */

/**
 * Регистрация скриптов и стилей для блока.
 */
function sws_post_selector_assets()
{
	wp_register_script(
		'sws-post-selector-editor',
		get_template_directory_uri() . '/blocks/post-selector/edit.js',
		array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-api-fetch', 'wp-components', 'wp-block-editor'),
		_S_VERSION,
		true
	);
	wp_register_script(
		'sws-post-selector-front',
		get_template_directory_uri() . '/blocks/post-selector/front.js',
		array(),
		_S_VERSION,
		true
	);
}
add_action('init', 'sws_post_selector_assets');

/**
 * Регистрация блока.
 */
function sws_register_post_selector_block()
{
	register_block_type(
		__DIR__ . '/post-selector',
		array(
			'render_callback' => 'sws_render_post_selector_block',
			'editor_script'   => 'sws-post-selector-editor',
			'script'          => 'sws-post-selector-front',
		)
	);
}
add_action('init', 'sws_register_post_selector_block');

/**
 * Рендер блока на фронтенде.
 *
 * @param array $attributes Атрибуты блока.
 * @return string HTML.
 */
function sws_render_post_selector_block($attributes)
{
	$selected_post_id = $attributes['selectedPostId'] ?? 0;
	$placeholder      = $attributes['placeholder'] ?? 'Начните вводить название...';

	// Если есть выбранный пост, получаем его данные
	$selected_post = $selected_post_id ? get_post($selected_post_id) : null;
	$selected_title = $selected_post ? get_the_title($selected_post) : '';
	$permalink = $selected_post ? get_permalink($selected_post) : '#';

	ob_start();
?>
	<div class="objectSelector">
		<div class="objectSelector__input_wrapper">
			<input
				type="text"
				class="objectSelector__input"
				placeholder="<?php echo esc_attr($placeholder); ?>"
				value="<?php echo esc_attr($selected_title); ?>"
				autocomplete="off" />
			<input
				type="hidden"
				class="objectSelector__hidden"
				value="<?php echo esc_attr($selected_post_id); ?>" />
			<div class="objectSelector__results"></div>
		</div>
		<a href="<?php echo esc_url($permalink); ?>" class="objectSelector__button btn ie-icon_arrow"></a>
	</div>
<?php
	return ob_get_clean();
}
