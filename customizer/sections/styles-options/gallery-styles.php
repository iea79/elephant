<?php
define('GALLERY_MARGIN', 1);
define('GALLERY_GAP', 1);

/**
 * Get gallery styles options section configuration
 *
 * @return array
 */
function get_gallery_styles_section()
{
	return [
		'title' => 'Галерея',
		'description' => '',
		'controls' => [
			'gallery_margin' => [
				'default' => GALLERY_MARGIN,
				'control' => [
					'label' => 'Внешний отступ галереи (в em)',
					'type' => 'number',
					'input_attrs' => [
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					],
				],
			],
			'gallery_gap' => [
				'default' => GALLERY_GAP,
				'control' => [
					'label' => 'Отступ между фотографиями (в em)',
					'type' => 'number',
					'input_attrs' => [
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					],
				],
			]
		],
	];
}
