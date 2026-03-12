<?php
define('FOOTER_REVIEW_BG', null);
define('FOOTER_REVIEW_COLOR', '#fff');

/**
 * Get footer colors options section configuration
 *
 * @return array
 */
function get_footer_colors_section()
{
	return [
		'title' => 'Подвал сайта',
		'description' => '',
		'controls' => [
			'footer_review_bg' => array(
				'default' => FOOTER_REVIEW_BG,
				'control' => array(
					'label' => 'Фоновое изображение блока с отзывами',
					'type' => 'media',
				),
			),
			'footer_review_color' => array(
				'default' => FOOTER_REVIEW_COLOR,
				'control' => array(
					'label' => 'Цвет текста блока с отзывами',
					'type' => 'color',
				),
			),
			'footer_logo_height' => array(
				'default' => FOOTER_LOGO_HEIGHT,
				'control' => array(
					'label'   => 'Максимальная высота логотипа в пикселях',
					'type' => 'number',
					'description' => 'При установке в 0, высота логотипа будет автоматически рассчитываться по размеру логотипа',
					'input_attrs' => array(
						'min' => 0,
						'max' => 200,
						'step' => 1,
					),
				),
			),
			'color_footer'  => [
				'default' => FOOTER_BG_COLOR,
				'control' => array(
					'label'   => 'Цвет фона подвала',
					'type'	=> 'color',
				)
			],
			'color_footer_text'  => [
				'default' => FOOTER_TEXT_COLOR,
				'control' => array(
					'label'   => 'Цвет текста подвала',
					'type'	=> 'color',
				)
			],
			'color_footer_nav'  => [
				'default' => FOOTER_NAV_COLOR,
				'control' => array(
					'label'   => 'Цвет ссылок подвала',
					'type'	=> 'color',
				)
			],
			'color_bottom_bg'  => [
				'default' => FOOTER_BOTTOM_BG,
				'control' => array(
					'label'   => 'Цвет фона подвала',
					'type'	=> 'color',
				)
			],
			'color_bottom_color'  => [
				'default' => FOOTER_BOTTOM_COLOR,
				'control' => array(
					'label'   => 'Цвет текста подвала',
					'type'	=> 'color',
				)
			]

		]
	];
}
