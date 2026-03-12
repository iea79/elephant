<?php

/**
 * Get settings site options panel configuration
 *
 * @return array
 */

require_once get_template_directory() . '/customizer/sections/site-settings/contacts-settings.php';
require_once get_template_directory() . '/customizer/sections/site-settings/forms-settings.php';
require_once get_template_directory() . '/customizer/sections/site-settings/header-settings.php';
require_once get_template_directory() . '/customizer/sections/site-settings/footer-settings.php';
require_once get_template_directory() . '/customizer/sections/site-settings/socials-settings.php';
require_once get_template_directory() . '/customizer/sections/site-settings/payment-methods-settings.php';
require_once get_template_directory() . '/customizer/sections/site-settings/seo-settings.php';
require_once get_template_directory() . '/customizer/sections/site-settings/breadcrumbs-settings.php';
require_once get_template_directory() . '/customizer/sections/site-settings/404-settings.php';

function get_site_settings_panel()
{
	return [
		'priority'       => 30,
		'title' => 'Настройки сайта',
		'description' => '',
		'theme_supports' => 'settings_site_options',
		'sections' => [
			'header_settings' => get_header_settings_section(),
			'footer_settings' => get_footer_settings_section(),
			'contacts_settings' => get_contacts_settings_section(),
			'forms_settings' => get_forms_settings_section(),
			'socials_settings' => get_socials_settings_section(),
			// 'payment_methods_settings' => get_payment_methods_settings_section(),
			'breadcrumbs_settings' => get_breadcrumbs_settings_section(),
			'seo_settings' => get_seo_settings_section(),
			'page_404_settings' => get_404_settings_section(),
			'consult_settings' => [
				'title' => 'Блок консультации',
				'description' => '',
				'controls' => [
					'consult_title' => [
						'default' => '',
						'control' => [
							'type' => 'textarea',
							'label' => 'Заголовок блока',
						],
					],
					'consult_description' => [
						'default' => '',
						'control' => [
							'type' => 'tinymce',
							'label' => 'Описание блока',
						],
					],
					'consult_button_text' => [
						'default' => '',
						'control' => [
							'type' => 'text',
							'label' => 'Текст кнопки 1',
						],
					],
					'consult_button2_text' => [
						'default' => '',
						'control' => [
							'type' => 'text',
							'label' => 'Текст кнопки 2',
						],
					],
					'consult_img' => [
						'default' => '',
						'control' => [
							'type' => 'media',
							'label' => 'Изображение блока',
						],
					],
				]
			]
		],
	];
}
