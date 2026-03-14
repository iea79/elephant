<?php

/**
 * SCF: доп. поля для таксономий works_*
 */

function works_taxonomies_custom_fields($settings, $type, $id, $meta_type, $types)
{
	// Нас интересуют только термины таксономий
	if ($meta_type !== 'term') {
		return $settings;
	}

	// Добавляем группу только для таксономий works_category, works_tags, works_directions
	if (in_array($type, array('works_category', 'works_tags', 'works_directions'), true)) {
		$Section = SCF::add_setting('works-taxonomy', 'Доп настройки');

		$Section->add_group(
			'works_taxonomy_options',
			false,
			array(
				array(
					'name'  => 'works_term_image',
					'label' => 'Изображение',
					'type'  => 'image',
					'size'  => 'medium',
				),
			)
		);

		$settings[] = $Section;
	}

	return $settings;
}

add_filter('smart-cf-register-fields', 'works_taxonomies_custom_fields', 5, 5);
