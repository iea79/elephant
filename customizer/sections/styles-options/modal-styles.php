<?php

/** Переменные */
define('MODAL_TITLE_SIZE', 1.5);
define('MODAL_WIDTH', 28.75);
define('MODAL_WIDTH_SM', 30);
define('MODAL_WIDTH_LG', 60);
define('MODAL_PADDING', 1.25);
define('MODAL_RADIUS', 0.125);
define('MODAL_SHADOW_SIZE', 1);
define('MODAL_SHADOW_COLOR', '#000');
define('MODAL_OVERLAY_COLOR', '#284A42');
define('MODAL_OVERLAY_OPACITY', 0.9);
define('MODAL_CLOSE_COLOR', '#E2DFD7');
define('MODAL_CLOSE_HOVER_COLOR', '#82A49C');
define('MODAL_CLOSE_SIZE', 1.1);
define('MODAL_CLOSE_RIGHT', 0.625);
define('MODAL_CLOSE_TOP', 0.625);

/**
 * Модальное окно
 *
 * @return array
 */
function get_modal_styles_section()
{
	return [
		'title' => 'Модальное окно',
		'description' => '',
		'controls' => [
			'modal_bg'  => [
				'default' => get_theme_mod('bg_main', BG_MAIN),
				'control' => array(
					'label'   => 'Цвет фона',
					'type'	=> 'color',
				)
			],
			'modal_color'  => [
				'default' => get_theme_mod('color_main', COLOR_MAIN),
				'control' => array(
					'label'   => 'Цвет текста',
					'type'	=> 'color',
				)
			],
			'modal_title_color'  => [
				'default' => TITLE_COLORS,
				'control' => array(
					'label'   => 'Цвет текста заголовка',
					'type'	=> 'color',
				)
			],
			'modal_title_size'  => [
				'default' => MODAL_TITLE_SIZE,
				'control' => array(
					'label'   => 'Размер заголовка в em',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'modal_width'  => [
				'default' => MODAL_WIDTH,
				'control' => array(
					'label'   => 'Ширина окна по умолчанию в em',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 10,
						'max' => 100,
						'step' => 0.1,
					),
				)
			],
			'modal_width_sm'  => [
				'default' => MODAL_WIDTH_SM,
				'control' => array(
					'label'   => 'Ширина малого окна в em',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 10,
						'max' => 100,
						'step' => 0.1,
					),
				)
			],
			'modal_width_lg'  => [
				'default' => MODAL_WIDTH_LG,
				'control' => array(
					'label'   => 'Ширина большого окна в em',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 10,
						'max' => 100,
						'step' => 0.1,
					),
				)
			],
			'modal_padding'  => [
				'default' => MODAL_PADDING,
				'control' => array(
					'label'   => 'Отступы внутренний в em',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'modal_radius'  => [
				'default' => MODAL_RADIUS,
				'control' => array(
					'label'   => 'Радиус скругления в em',
					'type'	=> 'range',
					'input_attrs' => array(
						'min' => 0,
						'max' => 2,
						'step' => 0.001,
					),
				)
			],
			'modal_shadow_size'  => [
				'default' => MODAL_SHADOW_SIZE,
				'control' => array(
					'label'   => 'Размер тени в em',
					'type'	=> 'range',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'modal_shadow_color'  => [
				'default' => MODAL_SHADOW_COLOR,
				'control' => array(
					'label'   => 'Цвет тени',
					'type'	=> 'color',
				)
			],
			'modal_overlay_color'  => [
				'default' => MODAL_OVERLAY_COLOR,
				'control' => array(
					'label'   => 'Цвет подложки окна',
					'type'	=> 'color',
				)
			],
			'modal_overlay_opacity'  => [
				'default' => MODAL_OVERLAY_OPACITY,
				'control' => array(
					'label'   => 'Прозрачность подложки окна',
					'type'	=> 'range',
					'input_attrs' => array(
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					),
				)
			],
			'modal_close_color'  => [
				'default' => MODAL_CLOSE_COLOR,
				'control' => array(
					'label'   => 'Цвет крестика',
					'type'	=> 'color',
				)
			],
			'modal_close_hover_color'  => [
				'default' => MODAL_CLOSE_HOVER_COLOR,
				'control' => array(
					'label'   => 'Цвет крестика при наведении',
					'type'	=> 'color',
				)
			],
			'modal_close_size'  => [
				'default' => MODAL_CLOSE_SIZE,
				'control' => array(
					'label'   => 'Размер крестика в em',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'modal_close_right'  => [
				'default' => MODAL_CLOSE_RIGHT,
				'control' => array(
					'label'   => 'Отступ крестика от правого края в em',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => -5,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'modal_close_top'  => [
				'default' => MODAL_CLOSE_TOP,
				'control' => array(
					'label'   => 'Отступ крестика от верхнего края в em',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => -5,
						'max' => 5,
						'step' => 0.1,
					),
				)
			]
		]
	];
}
