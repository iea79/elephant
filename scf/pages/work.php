<?php
function works_custom_fields($settings, $type, $id, $meta_type, $types)
{
	if ($type == 'works') {

		$Section = SCF::add_setting('works-1', 'Настройки объекта');

		$Section->add_group(
			'first-section',
			false,
			array(
				array(
					'name'        => 'work_id',
					'label'       => 'ID объекта',
					'type'        => 'text',
					'default'     => $id,
				),
				array(
					'name'        => 'work_price',
					'label'       => 'Цена объекта (руб)',
					'type'        => 'number',
				),
				array(
					'name'        => 'work_address',
					'label'       => 'Адрес объекта',
					'type'        => 'wysiwyg',
				),
				array(
					'name'        => 'work_market_type',
					'label'       => 'Тип недвижимости (первичная/вторичная)',
					'type'        => 'text',
				),
				array(
					'name'        => 'work_object_type',
					'label'       => 'Тип объекта (коттедж, дом, квартира и т.п.)',
					'type'        => 'text',
				),
				array(
					'name'        => 'work_house_area',
					'label'       => 'Площадь дома (м²)',
					'type'        => 'number',
				),
				array(
					'name'        => 'work_land_area',
					'label'       => 'Площадь участка (сотки)',
					'type'        => 'number',
				),
				array(
					'name'        => 'work_rooms',
					'label'       => 'Комнаты (кол-во)',
					'type'        => 'number',
				),
				array(
					'name'        => 'work_bedrooms',
					'label'       => 'Спальни (кол-во)',
					'type'        => 'number',
				),
				array(
					'name'        => 'work_bathrooms',
					'label'       => 'Санузлы (кол-во)',
					'type'        => 'number',
				),
				array(
					'name'        => 'work_security',
					'label'       => 'Охрана (есть/нет)',
					'type'        => 'text',
				),
				array(
					'name'        => 'work_water_supply',
					'label'       => 'Водоснабжение (центральное, скважина и т.п.)',
					'type'        => 'text',
				),
				array(
					'name'        => 'work_gas_supply',
					'label'       => 'Газоснабжение (магистральный, привозной, нет)',
					'type'        => 'text',
				),
				array(
					'name'        => 'work_garage_type',
					'label'       => 'Тип гаража',
					'type'        => 'text',
				),
			)
		);

		$Section->add_group(
			'work-properties',
			true,
			array(
				array(
					'name'  => 'work_property_title',
					'label' => 'Название свойства',
					'type'  => 'text',
				),
				array(
					'name'  => 'work_property_value',
					'label' => 'Значение свойства',
					'type'  => 'text',
				),
			)
		);

		$settings[] = $Section;
	}

	return $settings;
}
add_filter('smart-cf-register-fields', 'works_custom_fields', 1, 5);

function works_custom_galery($settings, $type, $id, $meta_type, $types)
{
	if ($type == 'works') {

		$Section = SCF::add_setting('works-2', 'Галерея объекта');

		$Section->add_group(
			'work-galery',
			true,
			array(
				array(
					'name'        => 'work_galery_item',
					'label'       => 'Медиа',
					'type'        => 'image',
					'size'        => 'medium',
				),
			)
		);

		$settings[] = $Section;
	}

	return $settings;
}
add_filter('smart-cf-register-fields', 'works_custom_galery', 2, 5);
