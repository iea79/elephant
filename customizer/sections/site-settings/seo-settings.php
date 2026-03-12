<?php

/**
 * Get SEO settings section configuration
 *
 * @return array
 */
function get_seo_settings_section()
{
	return array(
		'title' => 'SEO настройки',
		'description' => '',
		'controls' => array(
			'show_google' => array(
				'default' => false,
				'control' => array(
					'label' => 'Включить Google Analytics',
					'type' => 'checkbox',
				),
			),
			'google_metrica' => array(
				'default' => '',
				'control' => array(
					'label' => 'Google Analytics',
					'type' => 'textarea',
				),
			),
			'show_yandex' => array(
				'default' => false,
				'control' => array(
					'label' => 'Включить Yandex Metrika',
					'type' => 'checkbox',
				),
			),
			'yandex_metrica' => array(
				'default' => '',
				'control' => array(
					'label' => 'Yandex Metrika',
					'type' => 'textarea',
				),
			),
		)
	);
}
