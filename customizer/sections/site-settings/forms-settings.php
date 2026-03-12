<?php

define('FORM_CALLBACK_TITLE', 'заказать обратный звонок');
define('FORM_CALLBACK_TEXT', 'Мы перезвоним вам в ближайшее время');

/**
 * Get forms settings section configuration
 *
 * @return array
 */
function get_forms_settings_section()
{
	$transport = 'refresh';

	return array(
		'title' => 'Формы',
		'description' => 'Вставляйте шот-коды форм удобного для вас плагина',
		'controls' => array(
			'forms_separator' => array(
				'control' => array(
					'label' => 'Форма обратного звонка',
					'type' => 'hr',
				),
			),
			'form__callback_title' => array(
				'default' => FORM_CALLBACK_TITLE,
				'control' => array(
					'label' => 'Заголовок формы',
					'type' => 'text',
				),
			),
			'form__callback_img' => array(
				'default' => null,
				'control' => array(
					'label' => 'Изображение формы',
					'type' => 'media',
				),
			),
			'form__callback' => array(
				'default' => '',
				'control' => array(
					'label' => 'Шорткод формы',
					'type' => 'text',
				),
			),
			'forms_separator_2' => array(
				'control' => array(
					'label' => 'Форма консультации',
					'type' => 'hr',
				),
			),
			'form__consult_title' => array(
				'default' => 'Заказать консультацию',
				'control' => array(
					'label' => 'Заголовок формы',
					'type' => 'text',
				),
			),
			'form__consult_img' => array(
				'default' => null,
				'control' => array(
					'label' => 'Изображение формы',
					'type' => 'media',
				),
			),
			'form__consult' => array(
				'default' => '',
				'control' => array(
					'label' => 'Шорткод формы',
					'type' => 'text',
				),
			),
			'forms_separator_3' => array(
				'control' => array(
					'label' => 'Форма вопроса',
					'type' => 'hr',
				),
			),
			'form__question_title' => array(
				'default' => 'Задать вопрос',
				'control' => array(
					'label' => 'Заголовок формы',
					'type' => 'text',
				),
			),
			'form__question_img' => array(
				'default' => null,
				'control' => array(
					'label' => 'Изображение формы',
					'type' => 'media',
				),
			),
			'form__question' => array(
				'default' => '',
				'control' => array(
					'label' => 'Шорткод формы',
					'type' => 'text',
				),
			),
		)
	);
}
