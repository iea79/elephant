<?php

if (! defined('_S_VERSION')) {
	define('_S_VERSION', '1.0.0');
}

function sws_setup()
{
	load_theme_textdomain('sws', get_template_directory() . '/languages');

	add_theme_support('automatic-feed-links');
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');

	register_nav_menus(
		array(
			'main-menu' => esc_html__('Основное меню', 'sws'),
			'full-menu' => esc_html__('Выпадающее меню', 'sws'),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	add_theme_support(
		'custom-background',
		apply_filters(
			'sws_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// add_theme_support('customize-selective-refresh-widgets');

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	add_theme_support('editor-style');
	add_editor_style('css/editor.css');
}
add_action('after_setup_theme', 'sws_setup');

/**
 * Удаляем jquery-migrate на фронтенде.
 */
function sws_remove_jquery_migrate($scripts)
{
	if (is_admin() || !isset($scripts->registered['jquery'])) {
		return;
	}

	$script = $scripts->registered['jquery'];

	// Убираем jquery-migrate из зависимостей
	if ($script->deps) {
		$script->deps = array_diff($script->deps, array('jquery-migrate'));
	}

	// Переносим jquery в подвал
	$script->group = 1;
}
add_action('wp_default_scripts', 'sws_remove_jquery_migrate');

/**
 * Customizer additions.
 */
require get_template_directory() . '/customizer/customizer.php';

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function sws_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Сайдбар', 'sws'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'sws'),
			'before_widget' => '<div id="%1$s" class="aside %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="aside__title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action('widgets_init', 'sws_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function sws_scripts()
{
	wp_enqueue_style('sws-theme', get_stylesheet_uri(), array(), _S_VERSION);
	wp_enqueue_style('sws-style', get_template_directory_uri() . '/css/style.css', array(), _S_VERSION);
	// wp_enqueue_style('sws-patterns', get_template_directory_uri() . '/css/patterns.css', array(), _S_VERSION);

	wp_register_script('slick', get_template_directory_uri() . '/js/slick.min.js', array('jquery'), _S_VERSION, true);
	wp_register_style('slick', get_template_directory_uri() . '/css/slick.css', array(), _S_VERSION);

	// jQuery Masked Input с CDN — подключаем до functions.js, чтобы для [data-inputmask-mask] работала маска
	if (function_exists('wpforms')) {
		wp_enqueue_script(
			'jquery-maskedinput',
			'https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.min.js',
			array('jquery'),
			'1.4.1',
			true
		);
	}
	wp_enqueue_script('sws-lightbox', get_template_directory_uri() . '/js/lightbox.js', array(), _S_VERSION, true);
	wp_enqueue_script('sws-privacy-popup', get_template_directory_uri() . '/js/privacy-popup.js', array(), _S_VERSION, true);
	wp_enqueue_script('sws-modal', get_template_directory_uri() . '/js/modal.js', array('jquery'), _S_VERSION, true);
	$favorites_page_id = (int) get_theme_mod('favorites_page');
	$is_favorites_page = $favorites_page_id && is_page() && (int) get_queried_object_id() === $favorites_page_id;

	wp_enqueue_script('sws-works-archive', get_template_directory_uri() . '/js/works-archive.js', array('jquery'), _S_VERSION, true);
	wp_localize_script('sws-works-archive', 'swsWorksArchive', array(
		'ajaxurl'      => admin_url('admin-ajax.php'),
		'nonce'        => wp_create_nonce('works_archive_nonce'),
		'favoritesPage' => $is_favorites_page,
	));
	wp_enqueue_script(
		'sws-functions',
		get_template_directory_uri() . '/js/functions.js',
		array('jquery', 'jquery-maskedinput'),
		_S_VERSION,
		true
	);


	// Slick для слайдера в карточке объектов на архиве и избранном
	if (is_post_type_archive('works') || is_tax('works_category') || $is_favorites_page) {
		wp_enqueue_style(
			'jquery-ui-theme',
			'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css',
			array(),
			'1.13.2'
		);
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script(
			'jquery-ui-touch-punch',
			get_template_directory_uri() . '/js/jquery.ui.touch-punch.min.js',
			array('jquery-ui-slider'),
			_S_VERSION,
			true
		);

		wp_enqueue_style('slick');
		wp_enqueue_script('slick');
		if (wp_script_is('sws-home-slider-front', 'registered')) {
			wp_enqueue_script('sws-home-slider-front');
		}
	}

	// Slick + скрипт для одиночной страницы объекта works
	if (is_singular('works')) {
		wp_enqueue_style('slick');
		wp_enqueue_script('slick');
		wp_enqueue_script(
			'sws-work-single',
			get_template_directory_uri() . '/js/work-single.js',
			array('jquery', 'slick'),
			_S_VERSION,
			true
		);

		// Инициализация слайдеров .homeSlider в блоке works__featured и других местах
		if (wp_script_is('sws-home-slider-front', 'registered')) {
			wp_enqueue_script('sws-home-slider-front');
		}
	}

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'sws_scripts');

/**
 * Не даём подгружать Inputmask (WPForms и др.): убираем тег скрипта по URL.
 * Скрипт маски часто подключается как зависимость или под другим handle — по src надёжнее.
 */
function sws_strip_inputmask_script_tag($tag, $handle, $src)
{
	if (stripos($src, 'inputmask') !== false) {
		return '';
	}
	return $tag;
}
add_filter('script_loader_tag', 'sws_strip_inputmask_script_tag', 10, 3);

/**
 * Enqueue footer styles
 */
function sws_footer_styles()
{
	// Зарезервировано для стилей в подвале (сейчас не используется).
}
add_action('get_footer', 'sws_footer_styles');

/**
 * Enqueue admin edito scripts
 */
function sws_admin_scripts()
{
	wp_enqueue_script('sws-admin', get_template_directory_uri() . '/js/admin.js', array('wp-editor'), _S_VERSION, true);
	wp_enqueue_style('sws-admin', get_template_directory_uri() . '/css/admin.css', array('wp-editor'), _S_VERSION);
}
add_action('admin_enqueue_scripts', 'sws_admin_scripts');


/**
 * Gutenberg blocks
 */
require_once get_template_directory() . '/blocks/index.php';

/**
 * Проверка необходимых плагинов
 */
require_once get_template_directory() . '/inc/plugins-required.php';

/**
 * Подключение кстомных полей
 */
require_once get_template_directory() . '/scf/index.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Элемент поиска по works
 */
require_once get_template_directory() . '/inc/works-search.php';

/**
 * AJAX для архива works
 */
require_once get_template_directory() . '/inc/works-archive-ajax.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}
