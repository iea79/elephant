<?php
function reviews_custom_fields($settings, $type, $id, $meta_type, $types)
{
	if ($type == 'reviews') {

		$Section = SCF::add_setting('review-1', 'Настройки отзыва');

		$Section->add_group(
			'first-section',
			false,
			array(
				array(
					'name'        => 'review_rate',
					'label'       => 'Оценка',
					'type'        => 'radio',
					'radio_direction' => 'horizontal',
					'choices'         => array(1, 2, 3, 4, 5),
				),
			)
		);

		$settings[] = $Section;
	}

	return $settings;
}
add_filter('smart-cf-register-fields', 'reviews_custom_fields', 1, 5);
