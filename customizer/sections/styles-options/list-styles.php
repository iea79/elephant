<?php

define('LIST_COLOR', COLOR_MAIN);
define('LIST_MARGIN', 1);
define('LIST_PADDING', 1);
define('LIST_ITEMS_MARGIN', 1);
define('LIST_STYLE', 'disc');
define('LIST_STYLE_NUMBER', 'decimal');


/**
 * Get hr styles options section configuration
 *
 * @return array
 */
function get_list_styles_section()
{
	return [
		'title' => 'Списки',
		'description' => '',
		'controls' => [
			'list_color' => [
				'default' => COLOR_MAIN,
				'control' => array(
					'label'   => 'Цвет разделителя',
					'type'	=> 'color',
				)
			],
			'list_margin' => [
				'default' => LIST_MARGIN,
				'control' => array(
					'label'   => 'Внешний отступ списка (в em)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'list_padding' => [
				'default' => LIST_PADDING,
				'control' => array(
					'label'   => 'Внутренний отступ списка (в em)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'list_items_margin' => [
				'default' => LIST_ITEMS_MARGIN,
				'control' => array(
					'label'   => 'Отступ между элементами списка (в em)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'list_style' => [
				'default' => LIST_STYLE,
				'control' => array(
					'label'   => 'Вид маркерованого списка',
					'type'	=> 'select',
					'choices' => [
						'disc' => 'Круг',
						'square' => 'Квадрат',
						'circle' => 'Круг',
						'none' => 'Нет',
					],
				)
			],
			'list_style_number' => [
				'default' => LIST_STYLE_NUMBER,
				'control' => array(
					'label'   => 'Тип нумерации',
					'type'	=> 'select',
					'choices' => [
						'decimal' => 'Цифры',
						'decimal-leading-zero' => 'Цифры с ведущими нулями',
						'lower-alpha' => 'Алфавит в нижнем регистре',
						'upper-alpha' => 'Алфавит в верхнем регистре',
						'lower-roman' => 'Римские цифры в нижнем регистре',
						'upper-roman' => 'Римские цифры в верхнем регистре',
					],
				)
			]

		],
	];
}
