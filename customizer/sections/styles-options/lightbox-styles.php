<?php
define('LIGHTBOX_BG', '#000');
define('LIGHTBOX_OPACITY', 0.5);
define('LIGHTBOX_CLOSE_COLOR', '#fff');
define('LIGHTBOX_CLOSE_HOVER_COLOR', '#ddd');
define('LIGHTBOX_CLOSE_SIZE', 1.4);
define('LIGHTBOX_CLOSE_RIGHT', -1.5);
define('LIGHTBOX_CLOSE_TOP', -1.5);
define('LIGHTBOX_NAV_COLOR', '#fff');
define('LIGHTBOX_NAV_HOVER_COLOR', '#ddd');
define('LIGHTBOX_NAV_SIZE', 1.4);

/**
 * Get lightbox styles options section configuration
 *
 * @return array
 */
function get_lightbox_styles_section()
{
	return [
		'title' => 'Lightbox',
		'description' => 'Окно увеличенного изображения',
		'controls' => [
			'lightbox_bg' => [
				'default' => LIGHTBOX_BG,
				'control' => array(
					'label'   => 'Фон окна',
					'type'	=> 'color',
				)
			],
			'lightbox_opacity' => [
				'default' => LIGHTBOX_OPACITY,
				'control' => array(
					'label'   => 'Цвет разделителя',
					'type'	=> 'color',
					'input_attrs' => array(
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					),
				)
			],
			'lightbox_close_color' => [
				'default' => LIGHTBOX_CLOSE_COLOR,
				'control' => array(
					'label'   => 'Цвет крестика',
					'type'	=> 'color',
				)
			],
			'lightbox_close_hover_color' => [
				'default' => LIGHTBOX_CLOSE_HOVER_COLOR,
				'control' => array(
					'label'   => 'Цвет крестика при наведении',
					'type'	=> 'color',
				)
			],
			'lightbox_close_size' => [
				'default' => LIGHTBOX_CLOSE_SIZE,
				'control' => array(
					'label'   => 'Размер крестика',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					),
				)
			],
			'lightbox_close_right' => [
				'default' => LIGHTBOX_CLOSE_RIGHT,
				'control' => array(
					'label'   => 'Позиция крестика справа',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => -10,
						'max' => 10,
						'step' => 0.1,
					),
				)
			],
			'lightbox_close_top' => [
				'default' => LIGHTBOX_CLOSE_TOP,
				'control' => array(
					'label'   => 'Позиция крестика сверху',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => -10,
						'max' => 10,
						'step' => 0.1,
					),
				)
			],
			'lightbox_nav_color' => [
				'default' => LIGHTBOX_NAV_COLOR,
				'control' => array(
					'label'   => 'Цвет кнопок навигации',
					'type'	=> 'color',
				)
			],
			'lightbox_nav_hover_color' => [
				'default' => LIGHTBOX_NAV_HOVER_COLOR,
				'control' => array(
					'label'   => 'Цвет кнопок навигации при наведении',
					'type'	=> 'color',
				)
			],
			'lightbox_nav_size' => [
				'default' => LIGHTBOX_NAV_SIZE,
				'control' => array(
					'label'   => 'Размер кнопок навигации',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					),
				)
			],
		],
	];
}
