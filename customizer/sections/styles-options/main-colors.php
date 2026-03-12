<?php

/**
 * Get main colors options section configuration
 *
 * @return array
 */
function get_main_colors_section()
{
	return [
		'title' => 'Цвета',
		'description' => '',
		'controls' => [
			'color_main'  => [
				'default' => COLOR_MAIN,
				'control' => array(
					'label'   => 'Основной цвет текста',
					'type'	=> 'color',
				)
			],
			'color_second'  => [
				'default' => COLOR_SECOND,
				'control' => array(
					'label'   => 'Дополнительный цвет текста',
					'type'	=> 'color',
				)
			],
			'bg_main'  => [
				'default' => BG_MAIN,
				'control' => array(
					'label'   => 'Цвет фона',
					'type'	=> 'color',
				)
			],
			'bg_second'  => [
				'default' => BG_SECOND,
				'control' => array(
					'label'   => 'Дополнительный цвет фона',
					'type'	=> 'color',
				)
			],
			'titles_color' => [
				'default' => TITLE_COLORS,
				'control' => array(
					'label'   => 'Цвет заголовков',
					'type'	=> 'color',
				)
			],
			'titles_color_secondary' => [
				'default' => TITLE_COLORS_SECONDARY,
				'control' => array(
					'label'   => 'Цвет заголовков (дополнитеьный)',
					'type'	=> 'color',
				)
			],
			'titles_mini_color' => [
				'default' => COLOR_MINI_TITLE,
				'control' => array(
					'label'   => 'Цвет мини-заголовков',
					'type'	=> 'color',
				)
			],
			'links_color' => [
				'default' => LINK_COLOR,
				'control' => array(
					'label'   => 'Цвет ссылок',
					'type'	=> 'color',
				)
			],
			'links_color_hover' => [
				'default' => LINK_COLOR_HOVER,
				'control' => array(
					'label'   => 'Цвет ссылок при наведении',
					'type'	=> 'color',
				)
			],
		],
	];
}
