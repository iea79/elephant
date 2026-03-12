<?php
define('FORM_FIELD_FONT_SIZE', 0.75);
// Цвета полей формы
define('FORM_FIELD_BG', '#ffffff');
define('FORM_FIELD_BG_HOVER', '#ffffff');
define('FORM_FIELD_COLOR', '#5C6567');
define('FORM_FIELD_COLOR_HOVER', '#5C6567');
define('FORM_FIELD_BORDER', '#EEEDE8');
define('FORM_FIELD_BORDER_HOVER', '#EEEDE8');
define('FORM_FIELD_PLACEHOLDER', '#5C6567');
define('FORM_CHECKBOX_BG', '#ffffff');
define('FORM_CHECKBOX_COLOR', '#1E412C');
define('FORM_CHECKBOX_BORDER', '#EEEDE8');
define('FORM_FIELD_BORDER_RADIUS', 0.12);
define('FORM_CHECKBOX_SIZE', 2.3);

/**
 * Get form fields styles options section configuration
 *
 * @return array
 */
function get_form_fields_styles_section()
{
	return [
		'title' => 'Формы',
		'description' => '',
		'controls' => array(
			'form_hr1' => array(
				'control' => array(
					'label' => 'Поля формы',
					'type' => 'hr',
				),
			),
			'form_fields_fz' => array(
				'default' => FORM_FIELD_FONT_SIZE,
				'control' => array(
					'label' => 'Размер текста',
					'type' => 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				),
			),
			'form_fields_bg' => array(
				'default' => FORM_FIELD_BG,
				'control' => array(
					'label' => 'Фон полей формы',
					'type' => 'color',
				),
			),
			'form_fields_bg_hover' => array(
				'default' => FORM_FIELD_BG_HOVER,
				'control' => array(
					'label' => 'Фон полей формы при наведении',
					'type' => 'color',
				),
			),
			'form_fields_color' => array(
				'default' => FORM_FIELD_COLOR,
				'control' => array(
					'label' => 'Цвет текста',
					'type' => 'color',
				),
			),
			'form_fields_color_hover' => array(
				'default' => FORM_FIELD_COLOR_HOVER,
				'control' => array(
					'label' => 'Цвет текста при наведении',
					'type' => 'color',
				),
			),
			'form_fields_border' => array(
				'default' => FORM_FIELD_BORDER,
				'control' => array(
					'label' => 'Цвет границы',
					'type' => 'color',
				),
			),
			'form_fields_border_hover' => array(
				'default' => FORM_FIELD_BORDER_HOVER,
				'control' => array(
					'label' => 'Цвет границы при наведении',
					'type' => 'color',
				),
			),
			'form_fields_placeholder_color' => array(
				'default' => FORM_FIELD_PLACEHOLDER,
				'control' => array(
					'label' => 'Цвет текста placeholder',
					'type' => 'color',
				),
			),
			'form_fields_border_radius' => array(
				'default' => FORM_FIELD_BORDER_RADIUS,
				'control' => array(
					'label' => 'Радиус скругления',
					'type' => 'range',
					'input_attrs' => array(
						'min' => 0,
						'max' => 2,
						'step' => 0.01,
					)
				),
			),
			'form_hr2' => array(
				'control' => array(
					'label' => 'Chekbox и radio',
					'type' => 'hr',
				),
			),
			'form_checkbox_size' => array(
				'default' => FORM_CHECKBOX_SIZE,
				'control' => array(
					'label' => 'Размер чекбокса и радиокнопки',
					'type' => 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					)
				),
			),
			'form_checkbox_bg' => array(
				'default' => FORM_CHECKBOX_BG,
				'control' => array(
					'label' => 'Фон чекбокса и радиокнопки',
					'type' => 'color',
				),
			),
			'form_checkbox_color' => array(
				'default' => FORM_CHECKBOX_COLOR,
				'control' => array(
					'label' => 'Цвет маркера',
					'type' => 'color',
				),
			),
			'form_checkbox_border' => array(
				'default' => FORM_CHECKBOX_BORDER,
				'control' => array(
					'label' => 'Цвет границы',
					'type' => 'color',
				),
			),
		)
	];
}
