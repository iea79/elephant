<?php

/**
 * Get styles options panel configuration
 *
 * @return array
 */

require_once get_template_directory() . '/customizer/sections/styles-options/sizes-options.php';
require_once get_template_directory() . '/customizer/sections/styles-options/titles-sizes-options.php';
require_once get_template_directory() . '/customizer/sections/styles-options/fonts-options.php';
require_once get_template_directory() . '/customizer/sections/styles-options/main-colors.php';
require_once get_template_directory() . '/customizer/sections/styles-options/buttons-styles.php';
require_once get_template_directory() . '/customizer/sections/styles-options/form-fields-styles.php';
require_once get_template_directory() . '/customizer/sections/styles-options/header-colors.php';
require_once get_template_directory() . '/customizer/sections/styles-options/footer-colors.php';
require_once get_template_directory() . '/customizer/sections/styles-options/policy-popup-styles.php';
require_once get_template_directory() . '/customizer/sections/styles-options/hr-styles.php';
require_once get_template_directory() . '/customizer/sections/styles-options/modal-styles.php';
require_once get_template_directory() . '/customizer/sections/styles-options/table-styles.php';
require_once get_template_directory() . '/customizer/sections/styles-options/blockquote-styles.php';
require_once get_template_directory() . '/customizer/sections/styles-options/list-styles.php';
require_once get_template_directory() . '/customizer/sections/styles-options/gallery-styles.php';
require_once get_template_directory() . '/customizer/sections/styles-options/lightbox-styles.php';
require_once get_template_directory() . '/customizer/sections/styles-options/image-styles.php';
require_once get_template_directory() . '/customizer/sections/styles-options/to-top-button.php';

function get_styles_options_panel()
{
	return [
		'priority' => 25,
		'title' => 'Стили сайта',
		'description' => 'Настройка стилей сайта',
		'sections' => [
			'header_colors' => get_header_colors_section(),
			'footer_colors' => get_footer_colors_section(),
			'main_colors' => get_main_colors_section(),
			'fonts_options' => get_fonts_options_section(),
			'sizes_options' => get_sizes_options_section(),
			'titles_sizes' => get_titles_sizes_section(),
			'buttons_styles' => get_buttons_styles_section(),
			'form_fields_styles' => get_form_fields_styles_section(),
			'hr_styles' => get_hr_styles_section(),
			'table_styles' => get_table_styles_section(),
			'bloquote_styles' => get_blockquote_styles_section(),
			'list_styles' => get_list_styles_section(),
			'gallery_styles' => get_gallery_styles_section(),
			'image_styles' => get_image_styles_section(),
			'modal_styles' => get_modal_styles_section(),
			'lightbox_styles' => get_lightbox_styles_section(),
			'policy_popup_styles' => get_policy_popup_styles_section(),
			'to_top_button' => get_to_top_button_section(),
			'custom_css' => [
				'title' => 'Дополнительный CSS',
			],
		]
	];
}
