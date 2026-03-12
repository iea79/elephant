<?php
define('BLOCKQUOTE_BG', BG_MAIN);
define('BLOCKQUOTE_COLOR', '#B2B1AC');
define('BLOCKQUOTE_BORDER_SIZE', 0.5);
define('BLOCKQUOTE_BORDER_COLOR', '#B2B1AC');
define('BLOCKQUOTE_BORDER_PADDING', 1.5);
define('BLOCKQUOTE_PADDING', 1);
define('BLOCKQUOTE_MARGIN', 1);
define('PULLQUOTE_BG', BG_MAIN);
define('PULLQUOTE_COLOR', '#284A42');
define('PULLQUOTE_COLOR_SECOND', '#B2B1AC');
define('PULLQUOTE_CITE_FZ', 0.8);
define('PULLQUOTE_BORDER_COLOR', '#B2B1AC');
define('PULLQUOTE_BORDER_SIZE', 0.1);
define('PULLQUOTE_PADDING_X', 0);
define('PULLQUOTE_PADDING_Y', 0.9);

/**
 * Get blockquote styles options section configuration
 *
 * @return array
 */
function get_blockquote_styles_section()
{
	return [
		'title' => 'Цитата',
		'description' => '',
		'controls' => [
			'blockquote_bg' => [
				'default' => BLOCKQUOTE_BG,
				'control' => array(
					'label'   => 'Цвет фона цитаты',
					'type'	=> 'color',
				)
			],
			'blockquote_color' => [
				'default' => BLOCKQUOTE_COLOR,
				'control' => array(
					'label'   => 'Цвет текста цитаты',
					'type'	=> 'color',
				)
			],
			'blockquote_border_size' => [
				'default' => BLOCKQUOTE_BORDER_SIZE,
				'control' => array(
					'label'   => 'Толщина границы цитаты (в em)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'blockquote_border_color' => [
				'default' => BLOCKQUOTE_BORDER_COLOR,
				'control' => array(
					'label'   => 'Цвет границы цитаты',
					'type'	=> 'color',
				)
			],
			'blockquote_border_padding' => [
				'default' => BLOCKQUOTE_BORDER_PADDING,
				'control' => array(
					'label'   => 'Отступы от границы цитаты (в em)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'blockquote_padding' => [
				'default' => BLOCKQUOTE_PADDING,
				'control' => array(
					'label'   => 'Отступы цитаты внутренний (в em)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'blockquote_margin' => [
				'default' => BLOCKQUOTE_MARGIN,
				'control' => array(
					'label'   => 'Отступы цитаты внешний (в em)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'blockquote_hr' => [
				'control' => array(
					'label'   => 'Блок "Выдержка"',
					'type'	=> 'hr',
				)
			],
			'pullquote_bg' => [
				'default' => PULLQUOTE_BG,
				'control' => array(
					'label'   => 'Цвет фона',
					'type'	=> 'color',
				)
			],
			'pullquote_color' => [
				'default' => PULLQUOTE_COLOR,
				'control' => array(
					'label'   => 'Цвет текста',
					'type'	=> 'color',
				)
			],
			'pullquote_color_second' => [
				'default' => PULLQUOTE_COLOR_SECOND,
				'control' => array(
					'label'   => 'Цвет текста подписи',
					'type'	=> 'color',
				)
			],
			'pullquote_cite_fz' => [
				'default' => PULLQUOTE_CITE_FZ,
				'control' => array(
					'label'   => 'Размер текста подписи (в em)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'pullquote_border_color' => [
				'default' => PULLQUOTE_BORDER_COLOR,
				'control' => array(
					'label'   => 'Цвет границ',
					'type'	=> 'color',
				)
			],
			'pullquote_border_size' => [
				'default' => PULLQUOTE_BORDER_SIZE,
				'control' => array(
					'label'   => 'Толщина границ (в em)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'pullquote_padding_x' => [
				'default' => PULLQUOTE_PADDING_X,
				'control' => array(
					'label'   => 'Отступы внутренние по горизонтали (в em)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'pullquote_padding_y' => [
				'default' => PULLQUOTE_PADDING_Y,
				'control' => array(
					'label'   => 'Отступы внутренние по вертикали (в em)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			]
		],
	];
}
