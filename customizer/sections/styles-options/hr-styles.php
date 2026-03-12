<?php
define('HR_COLOR', '#dedede');
define('HR_WEIGHT', 1);
define('HR_MARGIN', 1);

/**
 * Get hr styles options section configuration
 *
 * @return array
 */
function get_hr_styles_section()
{
	return [
		'title' => 'Разделитель',
		'description' => '',
		'controls' => [
			'hr_color' => [
				'default' => HR_COLOR,
				'control' => array(
					'label'   => 'Цвет разделителя',
					'type'	=> 'color',
				)
			],
			'hr_weight' => [
				'default' => HR_WEIGHT,
				'control' => array(
					'label'   => 'Толщина разделителя (в пикселях)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 1,
					),
				)
			],
			'hr_margin' => [
				'default' => HR_MARGIN,
				'control' => array(
					'label'   => 'Отступы разделителя (в em)',
					'type'	=> 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					),
				)
			],
		],
	];
}
