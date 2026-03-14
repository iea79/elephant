<?php

/**
 * sws Theme Customizer
 *
 * @package frondendie
 */

// Подключаем файл с цветовыми константами
require_once get_template_directory() . '/customizer/constants/color-constants.php';
require_once get_template_directory() . '/customizer/constants/size-constants.php';
require_once get_template_directory() . '/customizer/constants/fonts-constants.php';
require_once get_template_directory() . '/customizer/constants/titles-constants.php';

// Подключаем вспомогательные функции для Theme Customizer
require_once get_template_directory() . '/customizer/helpers/customizer-helpers.php';

// Подключаем панели и секции для Theme Customizer
require_once get_template_directory() . '/customizer/sections/index.php';
require_once get_template_directory() . '/customizer/export.php';
require_once get_template_directory() . '/customizer/import.php';


function sws_load_customize_controls()
{
	/**
	 * Подключаем кастомные контролы для Theme Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	require_once get_template_directory() . '/customizer/controls/control-sortable-list.php';
	require_once get_template_directory() . '/customizer/controls/control-hr.php';
	require_once get_template_directory() . '/customizer/controls/control-checkbox-multiple.php';
	require_once get_template_directory() . '/customizer/controls/control-button.php';
	require_once get_template_directory() . '/customizer/controls/control-tinymce.php';
}

add_action('customize_register', 'sws_load_customize_controls', 0);

/**
 * Подключение скриптов и стилей для Theme Customizer.
 */
function sws_customize_controls_scripts()
{
	// Подключаем скрипты и стили редактора, чтобы TinyMCE работал в кастомайзере.
	if (function_exists('wp_enqueue_editor')) {
		wp_enqueue_editor();
	}

	wp_enqueue_script('sws-customize', get_template_directory_uri() . '/customizer/js/customizer.js', array('jquery'), _S_VERSION, true);
	wp_enqueue_script('sws-import-export', get_template_directory_uri() . '/customizer/js/import-export.js', array('jquery'), _S_VERSION, true);
	wp_enqueue_script('sws-customize-controls', get_template_directory_uri() . '/customizer/js/customize-controls.js', array('jquery', 'jquery-ui-sortable'), _S_VERSION, true);
	wp_enqueue_script('sws-range-control', get_template_directory_uri() . '/customizer/js/range-control.js', array('jquery'), _S_VERSION, true);
	wp_enqueue_style('sws-customize-controls', get_template_directory_uri() . '/customizer/css/styles.css', array(), _S_VERSION);

	// Локализация скрипта для передачи данных в JavaScript
	wp_localize_script('sws-import-export', 'sws_import_export', array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('sws_import_export_nonce')
	));
}

add_action('customize_controls_enqueue_scripts', 'sws_customize_controls_scripts');

/**
 * Настраиваем Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sws_customize_register($wp_customize)
{
	// как обновлять превью сайта:
	// 'refresh'     - перезагрузкой фрейма (можно полностью отказаться от JavaScript)
	// 'postMessage' - отправкой AJAX запроса (требуется JavaScript. Примеры в gfgrt js)
	$transport = 'refresh';
	$wp_customize->remove_section('static_front_page');
	$wp_customize->remove_section('colors');
	$wp_customize->remove_section('header_image');
	$wp_customize->remove_section('background_image');
	$wp_customize->remove_section('custom_css');
	$wp_customize->remove_section('nav_menus');
	$wp_customize->get_setting('posts_per_page');
	$wp_customize->get_setting('blogname')->transport         = $transport;
	$wp_customize->get_setting('blogdescription')->transport  = $transport;

	$customizer_config = array(
		'panels' => array(
			'styles_options' => get_styles_options_panel(),
			'site_settings' => get_site_settings_panel(),
			'nav_menus' => array(
				'sections' => array(
					'nav_menu_images' => array(
						'title' => 'Изображения для меню',
						'controls' => array(
							'nav_menu_img' => array(
								'default' => null,
								'control' => array(
									'label' => 'Изображения для меню в шапке',
									'type' => 'media',
								),
							),
						),
					),
				),
			)
		),
		'sections' => array(
			'title_tagline' => array(
				'default' => null,
				'controls' => array(
					// 'footer_logo' => array(
					// 	'control' => array(
					// 		'label' => 'Логотип в футере',
					// 		'type' => 'media',
					// 	),
					// ),
				),
			),
			'static_front_page' => get_static_front_page_controls(),
			'import_export' => get_import_export_section(),
		),
	);

	// Добавляем поддержку панелей и секций в кастомайзер
	add_theme_support('settings_site_options');

	// Регистрируем панели и секции с помощью универсальной функции
	register_customizer_elements($wp_customize, $customizer_config);
}
add_action('customize_register', 'sws_customize_register');

/**
 * Сохраняем глобаьные переменные мз кастомайзера в псевдокласс :root
 */
function customizer_style_root()
{

	$main_font_family = getSelectedFont('font_main');
	$second_font_family = getSelectedFont('font_second');
	$btns_font_family = getSelectedFont('font_btns');
	$section_title_font_family = getSelectedFont('section_title_font');
	$page_title_font_family = getSelectedFont('page_title_font');
	$titles_font_family = getSelectedFont('titles_font');

	$style = [];

	$style[] = ':root { 
		--color-main: ' . get_theme_mod('color_main', COLOR_MAIN) . '; 
		--color-second: ' . get_theme_mod('color_second', COLOR_SECOND) . '; 
		--bg-main: ' . get_theme_mod('bg_main', BG_MAIN) . '; 
		--bg-second: ' . get_theme_mod('bg_second', BG_SECOND) . '; 
		--titles-color: ' . get_theme_mod('titles_color', TITLE_COLORS) . '; 
		--titles-color-secondary: ' . get_theme_mod('titles_color_secondary', TITLE_COLORS_SECONDARY) . '; 
		--titles-mini-color: ' . get_theme_mod('titles_mini_color', COLOR_MINI_TITLE) . '; 
		--links-color: ' . get_theme_mod('links_color', LINK_COLOR) . '; 
		--links-color-hover: ' . get_theme_mod('links_color_hover', LINK_COLOR_HOVER) . ';

		--btn-round: ' . get_theme_mod('btn_round', BTN_ROUND) . 'em; 
		--btn-padding: ' . get_theme_mod('btn_padding_y', BTN_PADDING_Y) . 'em ' . get_theme_mod('btn_padding_x', BTN_PADDING_X) . 'em;
		--btn-font-size: ' . get_theme_mod('btn_font_size', BTN_FONT_SIZE) . 'em; 
		--btn-primary-bg: ' . get_theme_mod('btn_primary_bg', BTN_PRIMARY_BG) . '; 
		--btn-primary-bg-hover: ' . get_theme_mod('btn_primary_bg_hover', BTN_PRIMARY_BG_HOVER) . '; 
		--btn-primary-color: ' . get_theme_mod('btn_primary_color', BTN_PRIMARY_COLOR) . '; 
		--btn-primary-color-hover: ' . get_theme_mod('btn_primary_color_hover', BTN_PRIMARY_COLOR_HOVER) . '; 
		--btn-border-color: ' . get_theme_mod('btn_border_color', BTN_BORDER_COLOR) . '; 
		--btn-border-color-hover: ' . get_theme_mod('btn_border_color_hover', BTN_BORDER_COLOR_HOVER) . ';
		--btn-border-contrast-color: ' . get_theme_mod('btn_border_contrast_color', BTN_BORDER_CONTRAST_COLOR) . '; 
		--btn-border-contrast-color-hover: ' . get_theme_mod('btn_border_contrast_color_hover', BTN_BORDER_CONTRAST_COLOR_HOVER) . ';
		--btn-secondary-bg: ' . get_theme_mod('btn_secondary_bg', BTN_SECONDARY_BG) . '; 
		--btn-secondary-bg-hover: ' . get_theme_mod('btn_secondary_bg_hover', BTN_SECONDARY_BG_HOVER) . '; 
		--btn-secondary-color: ' . get_theme_mod('btn_COLOR_SECOND', BTN_SECONDARY_COLOR) . '; 
		--btn-secondary-color-hover: ' . get_theme_mod('btn_COLOR_SECOND_hover', BTN_SECONDARY_COLOR_HOVER) . '; 
		--btn-success-bg: ' . get_theme_mod('btn_success_bg', BTN_SUCCESS_BG) . '; 
		--btn-success-bg-hover: ' . get_theme_mod('btn_success_bg_hover', BTN_SUCCESS_BG_HOVER) . '; 
		--btn-success-color: ' . get_theme_mod('btn_success_color', BTN_SUCCESS_COLOR) . '; 
		--btn-success-color-hover: ' . get_theme_mod('btn_success_color_hover', BTN_SUCCESS_COLOR_HOVER) . '; 
		--btn-danger-bg: ' . get_theme_mod('btn_danger_bg', BTN_DANGER_BG) . '; 
		--btn-danger-bg-hover: ' . get_theme_mod('btn_danger_bg_hover', BTN_DANGER_BG_HOVER) . '; 
		--btn-danger-color: ' . get_theme_mod('btn_danger_color', BTN_DANGER_COLOR) . '; 
		--btn-danger-color-hover: ' . get_theme_mod('btn_danger_color_hover', BTN_DANGER_COLOR_HOVER) . '; 

		--header-bg: ' . get_theme_mod('header_bg', HEADER_BG_COLOR) . '; 
		--header-color: ' . get_theme_mod('header_text', HEADER_TEXT_COLOR) . '; 
		--header-top-bg: ' . get_theme_mod('header_top_bg', HEADER_TOP_BG) . '; 
		--header-top-color: ' . get_theme_mod('header_top_color', HEADER_TOP_COLOR) . '; 
		--header-nav-color: ' . get_theme_mod('header_nav_color', HEADER_TEXT_COLOR) . '; 
		--header-nav-color-hover: ' . get_theme_mod('header_nav_color_hover', HEADER_TEXT_COLOR) . '; 
		--header-nav-color-active: ' . get_theme_mod('header_nav_color_active', HEADER_TEXT_COLOR) . '; 
		--header-width: ' . get_theme_mod('header_width', HEADER_WIDTH) . 'em;
		--header-logo-height: ' . (get_theme_mod('header_logo_height', HEADER_LOGO_HEIGHT) === 0 ? 'auto' : get_theme_mod('header_logo_height', HEADER_LOGO_HEIGHT) / 16 . 'em') . ';
		
		--color-review: ' . get_theme_mod('footer_review_color', FOOTER_REVIEW_COLOR) . '; 
		--footer-bg: ' . get_theme_mod('footer_bg', FOOTER_BG_COLOR) . '; 
		--footer-color: ' . get_theme_mod('footer_text', FOOTER_TEXT_COLOR) . '; 
		--footer-nav-color: ' . get_theme_mod('color_footer_nav', FOOTER_NAV_COLOR) . '; 
		--footer-bottom-bg: ' . get_theme_mod('footer_bottom_bg', FOOTER_BOTTOM_BG) . '; 
		--footer-bottom-color: ' . get_theme_mod('footer_bottom_color', FOOTER_BOTTOM_COLOR) . ';
		--footer-width: ' . get_theme_mod('footer_width', FOOTER_WIDTH) . 'em;
		--footer-logo-height: ' . (get_theme_mod('footer_logo_height', FOOTER_LOGO_HEIGHT) === 0 ? 'auto' : get_theme_mod('footer_logo_height', FOOTER_LOGO_HEIGHT) / 16 . 'em') . ';

		--font-size: ' . get_theme_mod('font_size', FONT_SIZE) . '; 
		--font-size-mobile: ' . get_theme_mod('font_size_mobile', FONT_SIZE_MOBILE) . ';

		--content-size: ' . get_theme_mod('content_width', CONTENT_SIZE) . 'em; 
		--content-padding: ' . get_theme_mod('content_padding', CONTENT_PADDING) . 'em;


		--content-padding-mobile: ' . get_theme_mod('content_padding_mobile', CONTENT_PADDING_MOBILE) . 'em;

		--font-family-main: ' . $main_font_family . '; 
		--font-family-secondary: ' . $second_font_family . ';
		--font-family-btns: ' . $btns_font_family . ';
		--font-line-height: ' . get_theme_mod('font_line_height', FONT_LINE_HEIGHT) . ';
		--titles-line-height: ' . get_theme_mod('titles_line_height', TITLES_LINE_HEIGHT) . ';
		--section-title-size: ' . get_theme_mod('section_title_size', SECTION_TITLE_SIZE) . 'em;
		--section-title-size-mobile: ' . get_theme_mod('section_title_size_mobile', SECTION_TITLE_SIZE_MOBILE) . 'em;
		--section-title-font: ' . $section_title_font_family . ';
		--page-title-size: ' . get_theme_mod('page_title_size', PAGE_TITLE_SIZE) . 'em;
		--page-title-size-mobile: ' . get_theme_mod('page_title_size_mobile', PAGE_TITLE_SIZE_MOBILE) . 'em;
		--page-title-font: ' . $page_title_font_family . ';
		--h1-size: ' . get_theme_mod('h1_size', H1_SIZE) . 'em;
		--h2-size: ' . get_theme_mod('h2_size', H2_SIZE) . 'em;
		--h3-size: ' . get_theme_mod('h3_size', H3_SIZE) . 'em;
		--h4-size: ' . get_theme_mod('h4_size', H4_SIZE) . 'em;
		--h5-size: ' . get_theme_mod('h5_size', H5_SIZE) . 'em;
		--h6-size: ' . get_theme_mod('h6_size', H6_SIZE) . 'em;
		--titles-font: ' . $titles_font_family . ';

		--hr-color: ' . get_theme_mod('hr_color', HR_COLOR) . ';
		--hr-weight: ' . get_theme_mod('hr_weight', HR_WEIGHT) . 'px;
		--hr-margin: ' . get_theme_mod('hr_margin', HR_MARGIN) . 'em;

		--blockquote-bg: ' . get_theme_mod('blockquote_bg', BLOCKQUOTE_BG) . ';
		--blockquote-color: ' . get_theme_mod('blockquote_color', BLOCKQUOTE_COLOR) . ';
		--blockquote-border-size: ' . get_theme_mod('blockquote_border_size', BLOCKQUOTE_BORDER_SIZE) . 'em;
		--blockquote-border-color: ' . get_theme_mod('blockquote_border_color', BLOCKQUOTE_BORDER_COLOR) . ';
		--blockquote-border-padding: ' . get_theme_mod('blockquote_border_padding', BLOCKQUOTE_BORDER_PADDING) . 'em;
		--blockquote-padding: ' . get_theme_mod('blockquote_padding', BLOCKQUOTE_PADDING) . 'em;
		--blockquote-margin: ' . get_theme_mod('blockquote_margin', BLOCKQUOTE_MARGIN) . 'em;
		--pullquote-bg: ' . get_theme_mod('pullquote_bg', PULLQUOTE_BG) . ';
		--pullquote-color: ' . get_theme_mod('pullquote_color', PULLQUOTE_COLOR) . ';
		--pullquote-color-second: ' . get_theme_mod('pullquote_color_second', PULLQUOTE_COLOR_SECOND) . ';
		--pullquote-cite-fz: ' . get_theme_mod('pullquote_cite_fz', PULLQUOTE_CITE_FZ) . 'em;
		--pullquote-border-color: ' . get_theme_mod('pullquote_border_color', PULLQUOTE_BORDER_COLOR) . ';
		--pullquote-border-size: ' . get_theme_mod('pullquote_border_size', PULLQUOTE_BORDER_SIZE) . 'em;
		--pullquote-padding: ' . get_theme_mod('pullquote_padding_y', PULLQUOTE_PADDING_Y) . 'em ' . get_theme_mod('pullquote_padding_x', PULLQUOTE_PADDING_X) . 'em;

		--table-bg: ' . get_theme_mod('table_bg', TABLE_BG) . ';
		--table-color: ' . get_theme_mod('table_color', TABLE_COLOR) . ';
		--table-border-color: ' . get_theme_mod('table_border_color', TABLE_BORDER_COLOR) . ';
		--table-border-width: ' . get_theme_mod('table_border_width', TABLE_BORDER_WIDTH) . 'px;
		--table-border-radius: ' . get_theme_mod('table_border_radius', TABLE_BORDER_RADIUS) . 'px;
		--table-header-bg: ' . get_theme_mod('table_header_bg', TABLE_HEADER_BG) . ';
		--table-header-color: ' . get_theme_mod('table_header_color', TABLE_HEADER_COLOR) . ';
		--table-header-align: ' . get_theme_mod('table_header_align', TABLE_HEADER_ALIGN) . ';
		--table-stripe-color: ' . get_theme_mod('table_stripe_color', TABLE_STRIPE_COLOR) . ';

		--form-font-size: ' . get_theme_mod('form_fields_fz', FORM_FIELD_FONT_SIZE) . 'em;
		--form-field-bg: ' . get_theme_mod('form_fields_bg', FORM_FIELD_BG) . ';
		--form-field-bg-hover: ' . get_theme_mod('form_field_bg_hover', FORM_FIELD_BG_HOVER) . ';
		--form-field-color: ' . get_theme_mod('form_fields_color', FORM_FIELD_COLOR) . ';
		--form-field-color-hover: ' . get_theme_mod('form_fields_color_hover', FORM_FIELD_COLOR_HOVER) . ';
		--form-field-border: ' . get_theme_mod('form_fields_border', FORM_FIELD_BORDER) . ';
		--form-field-border-hover: ' . get_theme_mod('form_fields_border_hover', FORM_FIELD_BORDER_HOVER) . ';
		--form-field-radius: ' . get_theme_mod('form_fields_border_radius', FORM_FIELD_BORDER_RADIUS) . ';
		--form-field-placeholder: ' . get_theme_mod('form_fields_placeholder_color', FORM_FIELD_PLACEHOLDER) . ';
		--form-checkbox-color: ' . get_theme_mod('form_checkbox_color', FORM_CHECKBOX_COLOR) . ';
		--form-checkbox-border: ' . get_theme_mod('form_checkbox_border', FORM_CHECKBOX_BORDER) . ';
		--form-checkbox-bg: ' . get_theme_mod('form_checkbox_bg', FORM_CHECKBOX_BG) . ';
		--form-checkbox-size: ' . get_theme_mod('form_checkbox_size', FORM_CHECKBOX_SIZE) . 'em;

		--list-color: ' . get_theme_mod('list_color', COLOR_MAIN) . ';
		--list-margin: ' . get_theme_mod('list_margin', LIST_MARGIN) . 'em;
		--list-padding: ' . get_theme_mod('list_padding', LIST_PADDING) . 'em;
		--list-items-margin: ' . get_theme_mod('list_items_margin', LIST_ITEMS_MARGIN) . 'em;
		--list-style: ' . get_theme_mod('list_style', LIST_STYLE) . ';
		--list-style-number: ' . get_theme_mod('list_style_number', LIST_STYLE_NUMBER) . ';

		--gallery-margin: ' . get_theme_mod('gallery_margin', GALLERY_MARGIN) . 'em;
		--gallery-gap: ' . get_theme_mod('gallery_gap', GALLERY_GAP) . 'em;

		--image-border-radius: ' . get_theme_mod('image_border_radius', IMAGE_BORDER_RADIUS) . 'em;
		--image-margin: ' . get_theme_mod('image_margin', IMAGE_MARGIN) . 'em;
		--image-shadow-size: ' . get_theme_mod('image_shadow_size', IMAGE_SHADOW_SIZE) . 'em;
		--image-shadow-color: ' . get_theme_mod('image_shadow_color', IMAGE_SHADOW_COLOR) . ';
		--image-shadow-opacity: ' . get_theme_mod('image_shadow_opacity', IMAGE_SHADOW_OPACITY) . ';
		
		--policy-popup-bg: ' . get_theme_mod('policy_popup_bg', BG_MAIN) . ';
		--policy-popup-color: ' . get_theme_mod('policy_popup_color', COLOR_MAIN) . ';
		--policy-popup-fz: ' . get_theme_mod('policy_popup_fz', POLICY_POPUP_FZ) . 'em;
		--policy-popup-width: ' . get_theme_mod('policy_popup_width', POLICY_POPUP_WIDTH) . 'em;
		--policy-popup-padding: ' . get_theme_mod('policy_popup_padding_y', POLICY_POPUP_PADDING_Y) . 'em ' . get_theme_mod('policy_popup_padding_x', POLICY_POPUP_PADDING_X) . 'em;
		--policy-popup-margin: ' . get_theme_mod('policy_popup_margin', POLICY_POPUP_MARGIN) . 'em;
		--policy-popup-radius: ' . get_theme_mod('policy_popup_radius', POLICY_POPUP_RADIUS) . 'em;
		--policy-popup-shadow: ' . get_theme_mod('policy_popup_shadow', POLICY_POPUP_SHADOW) . 'em;
		--policy-popup-shadow-color: ' . get_theme_mod('policy_popup_shadow_color', POLICY_POPUP_SHADOW_COLOR) . ';
		--policy-popup-shadow-opacity: ' . get_theme_mod('policy_popup_shadow_opacity', POLICY_POPUP_SHADOW_OPACITY) . ';

		--modal-bg: ' . get_theme_mod('modal_bg', BG_MAIN) . ';
		--modal-color: ' . get_theme_mod('modal_color', COLOR_MAIN) . ';
		--modal-title-color: ' . get_theme_mod('modal_title_color', TITLE_COLORS) . ';
		--modal-title-size: ' . get_theme_mod('modal_title_size', MODAL_TITLE_SIZE) . 'em;
		--modal-width: ' . get_theme_mod('modal_width', MODAL_WIDTH) . 'em;
		--modal-width-sm: ' . get_theme_mod('modal_width_sm', MODAL_WIDTH_SM) . 'em;
		--modal-width-lg: ' . get_theme_mod('modal_width_lg', MODAL_WIDTH_LG) . 'em;
		--modal-padding: ' . get_theme_mod('modal_padding', MODAL_PADDING) . 'em;
		--modal-radius: ' . get_theme_mod('modal_radius', MODAL_RADIUS) . 'em;
		--modal-shadow-size: ' . get_theme_mod('modal_shadow_size', MODAL_SHADOW_SIZE) . 'em;
		--modal-shadow-color: ' . get_theme_mod('modal_shadow_color', MODAL_SHADOW_COLOR) . ';
		--modal-overlay-color: ' . get_theme_mod('modal_overlay_color', MODAL_OVERLAY_COLOR) . ';
		--modal-overlay-opacity: ' . get_theme_mod('modal_overlay_opacity', MODAL_OVERLAY_OPACITY) . ';
		--modal-close-color: ' . get_theme_mod('modal_close_color', MODAL_CLOSE_COLOR) . ';
		--modal-close-color-hover: ' . get_theme_mod('modal_close_color_hover', MODAL_CLOSE_HOVER_COLOR) . ';
		--modal-close-size: ' . get_theme_mod('modal_close_size', MODAL_CLOSE_SIZE) . 'em;
		--modal-close-right: ' . get_theme_mod('modal_close_right', MODAL_CLOSE_RIGHT) . 'em;
		--modal-close-top: ' . get_theme_mod('modal_close_top', MODAL_CLOSE_TOP) . 'em;

		--lightbox-bg: ' . get_theme_mod('lightbox_bg', LIGHTBOX_BG) . ';
		--lightbox-opacity: ' . get_theme_mod('lightbox_opacity', LIGHTBOX_OPACITY) . ';
		--lightbox-close-color: ' . get_theme_mod('lightbox_close_color', LIGHTBOX_CLOSE_COLOR) . ';
		--lightbox-close-hover-color: ' . get_theme_mod('lightbox_close_hover_color', LIGHTBOX_CLOSE_HOVER_COLOR) . ';
		--lightbox-close-size: ' . get_theme_mod('lightbox_close_size', LIGHTBOX_CLOSE_SIZE) . 'em;
		--lightbox-close-right: ' . get_theme_mod('lightbox_close_right', LIGHTBOX_CLOSE_RIGHT) . 'em;
		--lightbox-close-top: ' . get_theme_mod('lightbox_close_top', LIGHTBOX_CLOSE_TOP) . 'em;
		--lightbox-nav-color: ' . get_theme_mod('lightbox_nav_color', LIGHTBOX_NAV_COLOR) . ';
		--lightbox-nav-color-hover: ' . get_theme_mod('lightbox_nav_hover_color', LIGHTBOX_NAV_HOVER_COLOR) . ';
		--lightbox-nav-size: ' . get_theme_mod('lightbox_nav_size', LIGHTBOX_NAV_SIZE) . 'em;
	}
	@media (min-width: ' . get_theme_mod('content_width', CONTENT_SIZE) * 16 . 'px) {
		body {
			font-size: ' . get_theme_mod('font_size', FONT_SIZE) . 'em;
		}
		.container_center, .header__content, .footer__content {
			max-width: ' . get_theme_mod('content_width', CONTENT_SIZE) * 16 . 'px;
		}
    }';

	// wp_add_inline_style('sws-vars', $style);

	return $style;
}

/**
 * Вывод :root переменных в head
 */
function sws_print_root_styles_to_head()
{
	echo ("<style>\n" . implode("\n", customizer_style_root()) . "\n</style>\n");
}
add_action('wp_head', 'sws_print_root_styles_to_head');

/**
 * Обновление опции posts_per_page при изменении значения в кастомайзере
 */
function sws_update_posts_per_page_on_save($wp_customize)
{
	if ($wp_customize->get_setting('posts_per_page')) {
		$posts_per_page = $wp_customize->get_setting('posts_per_page')->value();
		update_option('posts_per_page', $posts_per_page);
	}
}
add_action('customize_save_after', 'sws_update_posts_per_page_on_save');

/**
 * Обновление posts_per_page кастомайзера при изменении опции posts_per_page
 */
function sws_save_post_action($old_value, $value)
{
	set_theme_mod('posts_per_page', $value);
}
add_action('update_option_posts_per_page', 'sws_save_post_action', 10, 3);

/**
 * Обновление ЧПУ при изменении страницы портфолио или контактов
 */
function sws_update_permalinks_on_save($wp_customize)
{
	// Проверяем, изменилась ли страница портфолио
	if ($wp_customize->get_setting('portfolio_page')) {
		$portfolio_page = $wp_customize->get_setting('portfolio_page')->value();
		// Здесь можно добавить логику обновления ЧПУ для портфолио
		// Например, можно обновить rewrite rules или выполнить flush_rewrite_rules()
	}

	// Проверяем, изменилась ли страница контактов
	if ($wp_customize->get_setting('contacts_page')) {
		$contacts_page = $wp_customize->get_setting('contacts_page')->value();
		// Здесь можно добавить логику обновления ЧПУ для контактов
		// flush_rewrite_rules() может быть выполнен здесь при необходимости
	}

	// Обновляем rewrite rules если были изменения
	// flush_rewrite_rules() следует использовать осторожно, только при необходимости
	// flush_rewrite_rules();
}
add_action('customize_save_after', 'sws_update_permalinks_on_save');
