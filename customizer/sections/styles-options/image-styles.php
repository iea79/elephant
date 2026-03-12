<?php
define('IMAGE_BORDER_RADIUS', 0);
define('IMAGE_MARGIN', 2);
define('IMAGE_SHADOW_SIZE', 0);
define('IMAGE_SHADOW_COLOR', '#000000');
define('IMAGE_SHADOW_OPACITY', 0.5);

/**
 * Get image styles options section configuration
 *
 * @return array
 */

function get_image_styles_section()
{
	return [
		'title' => 'Изображения',
		'description' => 'Настройка стилей изображений',
		'controls' => [
			'image_border_radius' => [
				'default' => IMAGE_BORDER_RADIUS,
				'control' => array(
					'label' => 'Радиус скругления изображения (в em)',
					'type' => 'range',
					'description' => 'применяется ко всем изображениям wp-block-image включая галлереи',
					'input_attrs' => array(
						'min' => 0,
						'max' => 30,
						'step' => 0.1,
					),
				),
			],
			'image_margin' => [
				'default' => IMAGE_MARGIN,
				'control' => array(
					'label' => 'Отступы изображения (в em).',
					'type' => 'number',
					'description' => 'Только для одиночных изображений!',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				),
			],
			'image_shadow_size' => [
				'default' => IMAGE_SHADOW_SIZE,
				'control' => array(
					'label' => 'Тень изображения (в em)',
					'type' => 'range',
					'description' => 'применяется ко всем изображениям wp-block-image включая галлереи',
					'input_attrs' => array(
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					),
				),
			],
			'image_shadow_color' => [
				'default' => IMAGE_SHADOW_COLOR,
				'control' => array(
					'label' => 'Цвет тени изображения',
					'type' => 'color',
					'description' => 'применяется ко всем изображениям wp-block-image включая галлереи',
				),
			],
			'image_shadow_opacity' => [
				'default' => IMAGE_SHADOW_OPACITY,
				'control' => array(
					'label' => 'Прозрачность тени изображения',
					'type' => 'range',
					'description' => 'применяется ко всем изображениям wp-block-image включая галлереи',
					'input_attrs' => array(
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					),
				),
			],
		],
	];
}
