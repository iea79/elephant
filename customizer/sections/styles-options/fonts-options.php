<?php

/**
 * Get fonts options section configuration
 *
 * @return array
 */
function get_fonts_options_section()
{
	return [
		'title' => 'Шрифты',
		'description' => '',
		'controls' => [
			'font_main' => [
				'default' => MAIN_FONT,
				'control' => array(
					'label'   => 'Основной шрифт',
					'type'	=> 'select',
					'choices' => FONTS,
				)
			],
			'font_second' => [
				'default' => SECOND_FONT,
				'control' => array(
					'label'   => 'Дополнительный шрифт',
					'type'	=> 'select',
					'choices' => FONTS,
				)
			],
			'font_btns' => [
				'default' => MAIN_FONT,
				'control' => array(
					'label'   => 'Шрифт кнопок',
					'type'	=> 'select',
					'choices' => FONTS,
				)
			],
			'font_size' => array(
				'default' => FONT_SIZE,
				'control' => array(
					'label'   => 'Размер основного текста',
					'description' => 'Размер текста в em (1em = 16px) под Full HD (1920x1080)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				)
			),
			'font_size_mobile' => array(
				'default' => FONT_SIZE_MOBILE,
				'control' => array(
					'label'   => 'Размер основного текста (мобильная версия)',
					'description' => 'Размер текста в em (1em = 16px)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				)
			),
			'font_line_height' => array(
				'default' => FONT_LINE_HEIGHT,
				'control' => array(
					'label'   => 'Высота строки текста',
					'description' => 'Высота строки текста в условных единицах (1 = 100%)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				)
			),
		]
	];
}
