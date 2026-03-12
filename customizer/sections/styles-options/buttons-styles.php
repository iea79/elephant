<?php
define('BTN_ROUND', 0);
define('BTN_PADDING_Y', .875);
define('BTN_PADDING_X', 1.25);
define('BTN_FONT_SIZE', 0.75);
// Primary
define('BTN_PRIMARY_BG', '#284A42');
define('BTN_PRIMARY_BG_HOVER', '#286052');
define('BTN_PRIMARY_COLOR', '#ffffff');
define('BTN_PRIMARY_COLOR_HOVER', '#ffffff');
// Border
define('BTN_BORDER_COLOR', '#284A42');
define('BTN_BORDER_COLOR_HOVER', '#B2B1AC');
// Border contrast
define('BTN_BORDER_CONTRAST_COLOR', '#EEEDE8');
define('BTN_BORDER_CONTRAST_COLOR_HOVER', '#EEEDE8');
// Secondary
define('BTN_SECONDARY_BG', '#EEEDE8');
define('BTN_SECONDARY_BG_HOVER', '#BEB3A5');
define('BTN_SECONDARY_COLOR', '#1E412C');
define('BTN_SECONDARY_COLOR_HOVER', '#ffffff');
// Success
define('BTN_SUCCESS_BG', '#BEB3A5');
define('BTN_SUCCESS_BG_HOVER', 'transparent');
define('BTN_SUCCESS_COLOR', '#ffffff');
define('BTN_SUCCESS_COLOR_HOVER', '#BEB3A5');
// Danger
define('BTN_DANGER_BG', '#F64B4B');
define('BTN_DANGER_BG_HOVER', '#F64B4B');
define('BTN_DANGER_COLOR', '#ffffff');
define('BTN_DANGER_COLOR_HOVER', '#ffffff');

/**
 * Get buttons styles options section configuration
 *
 * @return array
 */
function get_buttons_styles_section()
{
	return [
		'title' => 'Кнопки',
		'description' => '',
		'controls' => array(
			'btn_round' => [
				'default' => BTN_ROUND,
				'control' => array(
					'label'   => 'Скруление углов кнопок',
					'description' => 'Диапазон от 0 до 2 с шагом 0.1. Единицы измерения em',
					'type'	=> 'range',
					'input_attrs' => array(
						'min' => 0,
						'max' => 2,
						'step' => 0.01,
					)
				)
			],
			'btn_padding_x' => [
				'default' => BTN_PADDING_X,
				'control' => array(
					'label'   => 'Отступы кнопок по горизонтали',
					'description' => 'Диапазон от 0 до 2 с шагом 0.1. Единицы измерения em',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 2,
						'step' => 0.1,
					)
				)
			],
			'btn_padding_y' => [
				'default' => BTN_PADDING_Y,
				'control' => array(
					'label'   => 'Отступы кнопок по вертикали',
					'description' => 'Диапазон от 0 до 2 с шагом 0.1. Единицы измерения em',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 2,
						'step' => 0.1,
					)
				)
			],
			'btn_font_size' => [
				'default' => BTN_FONT_SIZE,
				'control' => array(
					'label'   => 'Размер текста кнопок',
					'description' => 'Диапазон от 0 до 2 с шагом 0.1. Единицы измерения em',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 2,
						'step' => 0.1,
					)
				)
			],
			// Primary
			'btn_hr_1' => [
				'control' => array(
					'label' => 'Цвет кнопки основной',
					'type'	=> 'hr',
				)
			],
			'btn_primary_bg' => [
				'default' => BTN_PRIMARY_BG,
				'control' => array(
					'label'   => 'Цвет фона',
					'type'	=> 'color',
				)
			],
			'btn_primary_bg_hover' => [
				'default' => BTN_PRIMARY_BG_HOVER,
				'control' => array(
					'label'   => 'Цвет фона при наведении',
					'type'	=> 'color',
				),
			],
			'btn_primary_color' => [
				'default' => BTN_PRIMARY_COLOR,
				'control' => array(
					'label'   => 'Цвет текста',
					'type'	=> 'color',
				)
			],
			'btn_primary_color_hover' => [
				'default' => BTN_PRIMARY_COLOR_HOVER,
				'control' => array(
					'label'   => 'Цвет текста при наведении',
					'type'	=> 'color',
				)
			],
			// Border
			'btn_hr_0' => [
				'control' => array(
					'label' => 'Цвет границы кнопки',
					'type'	=> 'hr',
				)
			],
			'btn_border_color' => [
				'default' => BTN_BORDER_COLOR,
				'control' => array(
					'label'   => 'Цвет границы',
					'type'	=> 'color',
				)
			],
			'btn_border_color_hover' => [
				'default' => BTN_BORDER_COLOR_HOVER,
				'control' => array(
					'label'   => 'Цвет границы при наведении',
					'type'	=> 'color',
				)
			],
			'btn_border_contrast_color' => [
				'default' => BTN_BORDER_CONTRAST_COLOR,
				'control' => array(
					'label'   => 'Цвет контраста границы',
					'type'	=> 'color',
				)
			],
			'btn_border_contrast_color_hover' => [
				'default' => BTN_BORDER_CONTRAST_COLOR_HOVER,
				'control' => array(
					'label'   => 'Цвет контраста границы при наведении',
					'type'	=> 'color',
				)
			],
			'btn_hr_2' => [
				'control' => array(
					'label' => 'Цвет кнопки дополнительный',
					'type'	=> 'hr',
				)
			],
			'btn_secondary_bg' => [
				'default' => BTN_SECONDARY_BG,
				'control' => array(
					'label'   => 'Цвет фона',
					'type'	=> 'color',
				)
			],
			'btn_secondary_bg_hover' => [
				'default' => BTN_SECONDARY_BG_HOVER,
				'control' => array(
					'label'   => 'Цвет фона при наведении',
					'type'	=> 'color',
				)
			],
			'btn_secondary_color' => [
				'default' => BTN_SECONDARY_COLOR,
				'control' => array(
					'label'   => 'Цвет текста',
					'type'	=> 'color',
				)
			],
			'btn_secondary_color_hover' => [
				'default' => BTN_SECONDARY_COLOR_HOVER,
				'control' => array(
					'label'   => 'Цвет текста при наведении',
					'type'	=> 'color',
				)
			],
			'btn_hr_3' => [
				'control' => array(
					'label' => 'Цвет кнопки success',
					'type'	=> 'hr',
				)
			],
			'btn_success_bg' => [
				'default' => BTN_SUCCESS_BG,
				'control' => array(
					'label'   => 'Цвет фона',
					'type'	=> 'color',
				)
			],
			'btn_success_bg_hover' => [
				'default' => BTN_SUCCESS_BG_HOVER,
				'control' => array(
					'label'   => 'Цвет фона при наведении',
					'type'	=> 'color',
				)
			],
			'btn_success_color' => [
				'default' => BTN_SUCCESS_COLOR,
				'control' => array(
					'label'   => 'Цвет текста',
					'type'	=> 'color',
				)
			],
			'btn_success_color_hover' => [
				'default' => BTN_SUCCESS_COLOR_HOVER,
				'control' => array(
					'label'   => 'Цвет текста при наведении',
					'type'	=> 'color',
				)
			],
			// Danger
			'btn_hr_4' => [
				'control' => array(
					'label' => 'Цвет кнопки danger',
					'type'	=> 'hr',
				)
			],
			'btn_danger_bg' => [
				'default' => BTN_DANGER_BG,
				'control' => array(
					'label'   => 'Цвет фона',
					'type'	=> 'color',
				)
			],
			'btn_danger_bg_hover' => [
				'default' => BTN_DANGER_BG_HOVER,
				'control' => array(
					'label'   => 'Цвет фона при наведении',
					'type'	=> 'color',
				)
			],
			'btn_danger_color' => [
				'default' => BTN_DANGER_COLOR,
				'control' => array(
					'label'   => 'Цвет текста',
					'type'	=> 'color',
				)
			],
			'btn_danger_color_hover' => [
				'default' => BTN_DANGER_COLOR_HOVER,
				'control' => array(
					'label'   => 'Цвет текста при наведении',
					'type'	=> 'color',
				)
			],
		)
	];
}
