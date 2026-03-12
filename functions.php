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
	wp_enqueue_script('sws-functions', get_template_directory_uri() . '/js/functions.js', array(), _S_VERSION, true);
	wp_enqueue_script('sws-lightbox', get_template_directory_uri() . '/js/lightbox.js', array(), _S_VERSION, true);
	wp_enqueue_script('sws-privacy-popup', get_template_directory_uri() . '/js/privacy-popup.js', array(), _S_VERSION, true);
	wp_enqueue_script('sws-modal', get_template_directory_uri() . '/js/modal.js', array('jquery'), _S_VERSION, true);
	wp_enqueue_style(
		'jquery-ui-theme',
		'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css',
		array(),
		'1.13.2'
	);
	wp_enqueue_script('jquery-ui-slider');
	$favorites_page_id = (int) get_theme_mod('favorites_page');
	$is_favorites_page = $favorites_page_id && is_page() && (int) get_queried_object_id() === $favorites_page_id;

	wp_enqueue_script('sws-works-archive', get_template_directory_uri() . '/js/works-archive.js', array('jquery', 'jquery-ui-slider'), _S_VERSION, true);
	wp_localize_script('sws-works-archive', 'swsWorksArchive', array(
		'ajaxurl'      => admin_url('admin-ajax.php'),
		'nonce'        => wp_create_nonce('works_archive_nonce'),
		'favoritesPage' => $is_favorites_page,
	));

	// Slick для слайдера в карточке объектов на архиве и избранном
	if (is_post_type_archive('works') || is_tax('works_category') || $is_favorites_page) {
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
	}

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'sws_scripts');

/**
 * Enqueue footer styles
 */
function sws_footer_styles()
{
	// Иконки соц сетей и платежных систем
	wp_enqueue_style('sws-icons-style', get_template_directory_uri() . '/fonts/sws/style.css', array(), _S_VERSION);
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
