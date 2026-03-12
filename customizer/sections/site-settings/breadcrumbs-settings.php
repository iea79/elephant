<?php
define('SHOW_BREADCRUMBS', true);
define('BREADCRUMBS_SEPARATOR', '/');

/**
 * Get breadcrumbs settings section configuration
 *
 * @return array
 */
function get_breadcrumbs_settings_section()
{
	return array(
		'title' => 'Хлебные крошки',
		'controls' => array(
			'show_breadcrumbs' => array(
				'default' => SHOW_BREADCRUMBS,
				'control' => array(
					'label' => 'Показывать хлебные крошки',
					'type' => 'checkbox',
				),
			),
			'breadcrumbs_separator' => array(
				'default' => BREADCRUMBS_SEPARATOR,
				'control' => array(
					'label' => 'Разделитель',
					'type' => 'text',
				),
			),
		),
	);
}
