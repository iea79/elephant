<?php

/**
 * Get socials settings section configuration
 *
 * @return array
 */
function get_socials_settings_section()
{
	return array(
		'title' => 'Социальные сети',
		'description' => 'Укажите и выберите ссылки на ваши соцсети',
		'controls' => array(
			'socials_list' => array(
				'default' => 'tg,vk',
				'control' => array(
					'label' => 'Социальные сети',
					'type' => 'sortable-list',
					'choices' => array(
						'vk' => 'ВКонтакте',
						'tg' => 'Telegram',
						'max' => 'Max',
						'rutube' => 'Rutube',
						'whatsapp' => 'WhatsApp',
						'dzen' => 'Яндекс Дзен',
					),
				),
			),
		)
	);
}
