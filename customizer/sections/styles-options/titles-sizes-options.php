<?php

/**
 * Get titles sizes options section configuration
 *
 * @return array
 */
function get_titles_sizes_section()
{
	return [
		'title' => 'Заголовки',
		'description' => 'Размер заголовка в em (1em = 16px)',
		'controls' => [
			'titles_hr1' => [
				'control' => array(
					'label'   => 'Основные заголовки',
					'type'	=> 'hr',
				)
			],
			'titles_font' => [
				'default' => MAIN_FONT,
				'control' => array(
					'label'   => 'Шрифт не стилизованых заголовков',
					'type'	=> 'select',
					'choices' => FONTS,
				)
			],
			'title_line_height' => array(
				'default' => TITLES_LINE_HEIGHT,
				'control' => array(
					'label'   => 'Высота строки заголовка',
					'description' => 'Высота строки заголовка в условных единицах (1 = 100%)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				)
			),
			'h1_size' => [
				'default' => H1_SIZE,
				'control' => array(
					'label'   => 'Размер заголовка H1',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				)
			],
			'h2_size' => [
				'default' => H2_SIZE,
				'control' => array(
					'label'   => 'Размер заголовка H2',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				)
			],
			'h3_size' => [
				'default' => H3_SIZE,
				'control' => array(
					'label'   => 'Размер заголовка H3',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				)
			],
			'h4_size' => [
				'default' => H4_SIZE,
				'control' => array(
					'label'   => 'Размер заголовка H4',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				)
			],
			'h5_size' => [
				'default' => H5_SIZE,
				'control' => array(
					'label'   => 'Размер заголовка H5',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				)
			],
			'h6_size' => [
				'default' => H6_SIZE,
				'control' => array(
					'label'   => 'Размер заголовка H6',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				)
			],
			'titles_hr2' => [
				'control' => array(
					'label'   => 'Заголовок страниц',
					'type'	=> 'hr',
				)
			],
			'page_title_font' => [
				'default' => MAIN_FONT,
				'control' => array(
					'label'   => 'Шрифт заголовка страницы',
					'type'	=> 'select',
					'choices' => FONTS,
				)
			],
			'page_title_size' => [
				'default' => PAGE_TITLE_SIZE,
				'control' => array(
					'label'   => 'Размер заголовка страницы',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
			'page_title_size_mobile' => [
				'default' => PAGE_TITLE_SIZE_MOBILE,
				'control' => array(
					'label'   => 'Размер заголовка страницы (мобильная версия)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				)
			],
			'titles_hr3' => [
				'control' => array(
					'label'   => 'Заголовки для секций',
					'type'	=> 'hr',
				)
			],
			'section_title_font' => [
				'default' => MAIN_FONT,
				'control' => array(
					'label'   => 'Шрифт заголовка секций',
					'type'	=> 'select',
					'choices' => FONTS,
				)
			],
			'section_title_size' => [
				'default' => SECTION_TITLE_SIZE,
				'control' => array(
					'label'   => 'Размер заголовка секций',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				)
			],
			'section_title_size_mobile' => [
				'default' => SECTION_TITLE_SIZE_MOBILE,
				'control' => array(
					'label'   => 'Размер заголовка секций (мобильная версия)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				)
			],
		],
	];
}
