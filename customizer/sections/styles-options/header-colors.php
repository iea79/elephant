<?php

/**
 * Get header colors options section configuration
 *
 * @return array
 */
function get_header_colors_section()
{
	return [
		'title' => 'Шапка сайта',
		'description' => '',
		'controls' => [
			'header_logo_height' => [
				'default' => HEADER_LOGO_HEIGHT,
				'control' => array(
					'label'   => 'Максимальная высота логотипа в пикселях',
					'type'	=> 'number',
					'description' => 'При установке в 0, высота логотипа будет автоматически рассчитываться по размеру логотипа',
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					)
				)
			],
			'header_bg'  => [
				'default' => HEADER_BG_COLOR,
				'control' => array(
					'label'   => 'Цвет фона шапки',
					'type'	=> 'color',
				)
			],
			'header_text'  => [
				'default' => HEADER_TEXT_COLOR,
				'control' => array(
					'label'   => 'Цвет текста шапки',
					'type'	=> 'color',
				)
			],
			'header_top_bg'  => [
				'default' => HEADER_TOP_BG,
				'control' => array(
					'label'   => 'Цвет фона верхней панели шапки',
					'type'	=> 'color',
				)
			],
			'header_top_color'  => [
				'default' => HEADER_TOP_COLOR,
				'control' => array(
					'label'   => 'Цвет текста верхней панели шапки',
					'type'	=> 'color',
				)
			],
			'header_nav_color'  => [
				'default' => HEADER_TEXT_COLOR,
				'control' => array(
					'label'   => 'Цвет текста меню',
					'type'	=> 'color',
				)
			],
			'header_nav_color_hover'  => [
				'default' => HEADER_TEXT_COLOR,
				'control' => array(
					'label'   => 'Цвет текста меню при наведении',
					'type'	=> 'color',
				)
			],
			'header_nav_color_active'  => [
				'default' => HEADER_TEXT_COLOR,
				'control' => array(
					'label'   => 'Цвет активного пункта меню',
					'type'	=> 'color',
				)
			],
			'header_callback_style' => [
				'default' => 'btn_success',
				'control' => array(
					'label'   => 'Стиль кнопки обратного звонка',
					'type'	=> 'select',
					'choices' => [
						'btn_primary' => 'По умолчанию',
						'btn_secondary' => 'Дополнительный цвет',
						'btn_success' => 'Цвет success',
						'btn_danger' => 'Цвет danger',
						'btn_border' => 'С обводкой',
					],
				)
			],
		]
	];
}
