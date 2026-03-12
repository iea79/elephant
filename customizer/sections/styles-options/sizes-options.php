<?php

/**
 * Get sizes options section configuration
 *
 * @return array
 */
function get_sizes_options_section()
{
	return [
		'title' => 'Размеры',
		'description' => '',
		'controls' => [
			'content_width' => array(
				'default' => CONTENT_SIZE,
				'control' => array(
					'label'   => 'Ширина контента',
					'description' => 'Ширина контента задается в em',
					'type'	=> 'number',
				)
			),
			'content_padding' => array(
				'default' => CONTENT_PADDING,
				'control' => array(
					'label'   => 'Отступы слева/справа у контента',
					'description' => 'Отступ задается в em (от 0 до 10em)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					)
				)
			),
			'content_padding_mobile' => array(
				'default' => CONTENT_PADDING_MOBILE,
				'control' => array(
					'label'   => 'Отступы слева/справа у контента для мобильных устройств',
					'description' => 'Отступ задается в em (от 0 до 10em)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					)
				)
			),
			'header_width' => array(
				'default' => HEADER_WIDTH,
				'control' => array(
					'label'   => 'Ширина шапки',
					'description' => 'Ширина шапки задается в em',
					'type'	=> 'number',
				)
			),
			'footer_width' => array(
				'default' => FOOTER_WIDTH,
				'control' => array(
					'label'   => 'Ширина подвала',
					'description' => 'Ширина футера задается пикселях в em',
					'type'	=> 'number',
				)
			),
		],
	];
}
