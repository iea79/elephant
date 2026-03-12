<?php

define('POLICY_POPUP_FZ', 0.58);
define('POLICY_POPUP_WIDTH', 23);
define('POLICY_POPUP_PADDING_X', 0.9);
define('POLICY_POPUP_PADDING_Y', 0.9);
define('POLICY_POPUP_MARGIN', 1.25);
define('POLICY_POPUP_RADIUS', 0);
define('POLICY_POPUP_SHADOW', 1);
define('POLICY_POPUP_SHADOW_OPACITY', 0.2);
define('POLICY_POPUP_SHADOW_COLOR', '#000');
define('POLICY_POPUP_BUTTON', 'btn_primary');

/**
 * Get policy popup styles options section configuration
 *
 * @return array
 */
function get_policy_popup_styles_section()
{
	return [
		'title' => 'Согласие на обработку данных',
		'description' => '',
		'controls' => [
			'policy_popup_bg'  => [
				'default' => BG_MAIN,
				'control' => array(
					'label'   => 'Цвет фона',
					'type'	=> 'color',
				)
			],
			'policy_popup_color'  => [
				'default' => COLOR_MAIN,
				'control' => array(
					'label'   => 'Цвет текста',
					'type'	=> 'color',
				)
			],
			'policy_popup_fz'  => [
				'default' => POLICY_POPUP_FZ,
				'control' => array(
					'label'   => 'Размер текста',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0.5,
						'max' => 10,
						'step' => 0.1,
					),
				)
			],
			'policy_popup_width'  => [
				'default' => POLICY_POPUP_WIDTH,
				'control' => array(
					'label'   => 'Ширина блока',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 10,
						'max' => 100,
						'step' => 0.1,
					),
				)
			],
			'policy_popup_padding_x'  => [
				'default' => POLICY_POPUP_PADDING_X,
				'control' => array(
					'label'   => 'Отступы внутренний по горизонтали',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 4,
						'step' => 0.1,
					),
				)
			],
			'policy_popup_padding_y' => [
				'default' => POLICY_POPUP_PADDING_Y,
				'control' => array(
					'label'   => 'Отступы внутренний по вертикали',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 4,
						'step' => 0.1,
					),
				)
			],
			'policy_popup_margin'  => [
				'default' => POLICY_POPUP_MARGIN,
				'control' => array(
					'label'   => 'Отступы внешний',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					),
				)
			],
			'policy_popup_radius'  => [
				'default' => POLICY_POPUP_RADIUS,
				'control' => array(
					'label'   => 'Радиус скругления',
					'type'	=> 'range',
					'input_attrs' => array(
						'min' => 0,
						'max' => 2,
						'step' => 0.1,
					),
				)
			],
			'policy_popup_shadow'  => [
				'default' => POLICY_POPUP_SHADOW,
				'control' => array(
					'label'   => 'Размер тени',
					'type'	=> 'range',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'policy_popup_shadow_color'  => [
				'default' => POLICY_POPUP_SHADOW_COLOR,
				'control' => array(
					'label'   => 'Цвет тени',
					'type'	=> 'color',
				)
			],
			'policy_popup_shadow_opacity'  => [
				'default' => POLICY_POPUP_SHADOW_OPACITY,
				'control' => array(
					'label'   => 'Прозрачность тени',
					'type'	=> 'range',
					'input_attrs' => array(
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					),
				)
			],
			'policy_popup_button'  => [
				'default' => POLICY_POPUP_BUTTON,
				'control' => array(
					'label'   => 'Цвет кнопки',
					'type'	=> 'select',
					'choices' => array(
						'btn_primary' => 'По умолчанию',
						'btn_secondary' => 'Дополнительный цвет',
						'btn_success' => 'Цвет success',
						'btn_danger' => 'Цвет danger',
						'btn_border' => 'С обводкой',
					)
				)
			],
		]
	];
}
