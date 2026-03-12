<?php
require_once __DIR__ . '/post-selector.php';
require_once __DIR__ . '/works-categories.php';
require_once __DIR__ . '/home-slider.php';
require_once __DIR__ . '/steps.php';
require_once __DIR__ . '/work-single-gallery.php';
require_once __DIR__ . '/yandex-map.php';

/**
 * Подключаем скрипты для педактирования gutenberg блоков
 */
function sws_block_editor_assets()
{
	wp_enqueue_script('sws-admin', get_template_directory_uri() . '/js/editor.js', array('wp-blocks'), _S_VERSION, true);
	wp_enqueue_style('sws-editor', get_template_directory_uri() . '/css/editor.css', array('wp-editor'), _S_VERSION);

	// Переменные :root из кастомайзера должны быть в том же документе, что и editor.css
	if (function_exists('customizer_style_root')) {
		$root_css = implode("\n", customizer_style_root());
		if ($root_css) {
			wp_add_inline_style('sws-editor', $root_css);
		}
	}
}

/*
* Подключение root стилей для gutenberg block editor
* Подробности https://developer.wordpress.org/news/2022/12/leveraging-theme-json-and-per-block-styles-for-more-performant-themes/
*/
function sws_modify_editor_settings($editor_settings, $editor_context)
{
	if (!function_exists('customizer_style_root')) {
		return $editor_settings;
	}
	$customizer_root = customizer_style_root();
	if (!$customizer_root) {
		return $editor_settings;
	}
	$customizer_root_css = implode("\n", $customizer_root);
	if (!isset($editor_settings['styles'])) {
		$editor_settings['styles'] = array();
	}
	// :root в начале, чтобы переменные были доступны до загрузки editor.css
	array_unshift($editor_settings['styles'], array('css' => $customizer_root_css));

	return $editor_settings;
}

/*
* Подключение стилей для gutenberg block editor
*/
function sws_enqueue_blocks_styles()
{
	// Подключаем editor.css для редактора
	wp_enqueue_style('sws-editor-styles', get_template_directory_uri() . '/css/editor.css');

	// Регистрируем стили для использования в редакторе
	wp_enqueue_block_style('core/block', array(
		'handle' => 'sws-block-editor',
		'src'    => get_template_directory_uri() . '/css/editor.css',
		'path'   => get_template_directory_uri() . '/css/editor.css'
	));
}

/**
 * Включаем фильтры и actions для gutenberg block editor
 * Только для админки чтобы не нагружать фронт лишними actions и фильтрами
 */
if (is_admin()) {
	add_filter('block_editor_settings_all', 'sws_modify_editor_settings', 10, 2);
	add_action('enqueue_block_editor_assets', 'sws_block_editor_assets');
	add_action('init', 'sws_enqueue_blocks_styles');
}
