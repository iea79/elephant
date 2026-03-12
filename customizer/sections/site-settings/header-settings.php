<?php

define('HEADER_STICKED', true);
define('HEADER_TOP', false);
define('SHOW_ADDRESS', false);
define('SHOW_PHONE', false);
define('SHOW_EMAIL', false);
define('SHOW_JOB_TIME', false);
define('SHOW_CALLBACK_BTH', true);

/**
 * Get header settings section configuration
 *
 * @return array
 */
function get_header_settings_section()
{
	return array(
		'title' => 'Шапка сайта',
		'description' => 'Отображение элементов шапки сайта и её поведение',
		'controls' => array(
			'sticked' => array(
				'default' => HEADER_STICKED,
				'control' => array(
					'label' => 'Липкая шапка',
					'type' => 'checkbox',
				),
			),
			'top' => array(
				'default' => HEADER_TOP,
				'control' => array(
					'label' => 'Показывать панель с контактами',
					'type' => 'checkbox',
				),
			),
			'show_address' => array(
				'default' => SHOW_ADDRESS,
				'control' => array(
					'label' => 'Показать адрес',
					'type' => 'checkbox',
				),
			),
			'show_phone' => array(
				'default' => SHOW_PHONE,
				'control' => array(
					'label' => 'Показать номер телефона',
					'type' => 'checkbox',
				),
			),
			'show_email' => array(
				'default' => SHOW_EMAIL,
				'control' => array(
					'label' => 'Показать Email',
					'type' => 'checkbox',
				),
			),
			'show_job_time' => array(
				'default' => SHOW_JOB_TIME,
				'control' => array(
					'label' => 'Показать время работы',
					'type' => 'checkbox',
				),
			),
			'show_callback_bth' => array(
				'default' => SHOW_CALLBACK_BTH,
				'control' => array(
					'label' => 'Показать кнопку обратного звонка',
					'type' => 'checkbox',
				),
			),
		)
	);
}
