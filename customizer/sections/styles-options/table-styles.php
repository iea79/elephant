<?php
define('TABLE_BG', BG_MAIN);
define('TABLE_COLOR', COLOR_MAIN);
define('TABLE_BORDER_COLOR', COLOR_MAIN);
define('TABLE_BORDER_WIDTH', 1);
define('TABLE_BORDER_RADIUS', 0);
define('TABLE_HEADER_BG', BG_MAIN);
define('TABLE_HEADER_COLOR', COLOR_MAIN);
define('TABLE_HEADER_ALIGN', 'left');
define('TABLE_STRIPE_COLOR', '#e8e8e8');

/**
 * Get table styles options section configuration
 *
 * @return array
 */

function get_table_styles_section()
{
	return [
		'title' => 'Таблицы',
		'description' => '',
		'controls' => [
			'table_bg' => [
				'default' => TABLE_BG,
				'control' => [
					'label' => 'Цвет фона таблицы',
					'type' => 'color',
				],
			],
			'table_color' => [
				'default' => TABLE_COLOR,
				'control' => [
					'label' => 'Цвет текста таблицы',
					'type' => 'color',
				],
			],
			'table_border_color' => [
				'default' => TABLE_BORDER_COLOR,
				'control' => [
					'label' => 'Цвет границы таблицы',
					'type' => 'color',
				],
			],
			'table_border_width' => [
				'default' => TABLE_BORDER_WIDTH,
				'control' => [
					'label' => 'Толщина границы таблицы (в пикселях)',
					'type' => 'number',
					'input_attrs' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
			],
			'table_border_radius' => [
				'default' => TABLE_BORDER_RADIUS,
				'control' => [
					'label' => 'Радиус скругления углов таблицы (в em)',
					'type' => 'range',
					'input_attrs' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
				],
			],
			'table_header_bg' => [
				'default' => TABLE_HEADER_BG,
				'control' => [
					'label' => 'Цвет фона заголовка таблицы',
					'type' => 'color',
				],
			],
			'table_header_color' => [
				'default' => TABLE_HEADER_COLOR,
				'control' => [
					'label' => 'Цвет текста заголовка таблицы',
					'type' => 'color',
				],
			],
			'table_header_align' => [
				'default' => TABLE_HEADER_ALIGN,
				'control' => [
					'label' => 'Выравнивание заголовка таблицы',
					'type' => 'select',
					'choices' => [
						'left' => 'Слева',
						'center' => 'По центру',
						'right' => 'Справа',
					],
				],
			],
			'table_stripe_color' => [
				'default' => TABLE_STRIPE_COLOR,
				'control' => [
					'label' => 'Цвет фона нечетных строк таблицы при включении опции "Полосы"',
					'type' => 'color',
				],
			],
		],
	];
}
