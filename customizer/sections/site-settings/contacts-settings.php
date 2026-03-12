<?php
define('SITE_PHONE', '');
define('SITE_EMAIL', '');
define('SITE_ADDRESS', '');
define('SITE_JOB_TIME', '');

/**
 * Get contacts settings section configuration
 *
 * @return array
 */
function get_contacts_settings_section()
{
	return array(
		'title' => 'Контакты',
		'controls' => array(
			'site_phone' => array(
				'default' => SITE_PHONE,
				'control' => array(
					'label' => 'Номер телефона',
					'type' => 'text',
				),
			),
			'site_email' => array(
				'default' => SITE_EMAIL,
				'control' => array(
					'label' => 'Email',
					'type' => 'text',
				),
			),
			'site_address' => array(
				'default' => SITE_ADDRESS,
				'control' => array(
					'label' => 'Адрес',
					'type' => 'textarea',
				),
			),
			'site_job_time' => array(
				'default' => SITE_JOB_TIME,
				'control' => array(
					'label' => 'Время работы',
					'type' => 'text',
				),
			),
			'site_map' => array(
				'default' => '',
				'control' => array(
					'label' => 'Карта',
					'type' => 'textarea',
					'description' => 'Вставьте код карты. Можно скопировать тут https://yandex.ru/maps',
				),
			),
		)
	);
}
