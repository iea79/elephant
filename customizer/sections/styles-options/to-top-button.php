<?php
define('TO_TOP_SHOW', true);
define('TO_TOP_IMAGE', null);
define('TO_TOP_IMAGE_SIZE', 3.5);
define('TO_TOP_IMAGE_POSITION', 'right');
define('TO_TOP_IMAGE_SPEED', 500);


/**
 * Get to top button options section configuration
 *
 * @return array
 */
function get_to_top_button_section()
{
	return [
		'title' => 'Кнопка "Наверх"',
		'description' => 'Размер заголовка в em (1em = 16px)',
		'controls' => [
			'to_top_show' => [
				'default' => TO_TOP_SHOW,
				'control' => array(
					'label'   => 'Показать кнопку "Наверх"',
					'type'	=> 'checkbox',
				)
			],
			'to_top_image' => [
				'default' => TO_TOP_IMAGE,
				'control' => array(
					'label'   => 'Кнопка "Наверх"',
					'type'	=> 'media',
				)
			],
			'to_top_image_size' => [
				'default' => TO_TOP_IMAGE_SIZE,
				'control' => array(
					'label'   => 'Размер кнопки "Наверх"',
					'type'	=> 'number',
					'input_attrs' => [
						'min' => 1,
						'max' => 5,
						'step' => 0.1,
					],
					'description' => 'От 1 до 5 с шагом 0.1'
				)
			],
			'to_top_image_position' => [
				'default' => TO_TOP_IMAGE_POSITION,
				'control' => array(
					'label'   => 'Позиция кнопки "Наверх"',
					'type'	=> 'select',
					'choices' => [
						'left' => 'Слева',
						'right' => 'Справа',
					],
				)
			],
			'to_top_image_speed' => [
				'default' => TO_TOP_IMAGE_SPEED,
				'control' => array(
					'label'   => 'Скорость прокрутки',
					'type'	=> 'number',
					'input_attrs' => [
						'min' => 100,
						'max' => 1000,
						'step' => 100,
					],
					'description' => 'От 100 до 1000 мс с шагом 100'
				)
			]
		],
	];
}
