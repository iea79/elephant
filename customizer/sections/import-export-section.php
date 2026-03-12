<?php

/**
 * Файл конфигурации для секции "Импорт/Экспорт" в Customizer
 *
 * @package frondendie
 */

function get_import_export_section()
{
	$array = array(
		'title' => 'Демо-контент',
		'priority' => 1000,
		'controls' => array(),
	);

	$array['controls']['import_button'] = array(
		'control' => array(
			'label' => 'Загрузить демо-контент',
			'description' => 'Импортировать демо-контент. Осторожно!!! При использовании данной функции все данные текущего сайта будут удалены.',
			'type' => 'button',
			'input_attrs' => array(
				'value' => 'Импортировать данные',
				'class' => 'button button-secondary import_button',
				'id' => 'import_button',
			),
		),
	);

	if (WP_DEBUG) {
		$array['controls']['export_hr'] = array(
			'control' => array(
				'type' => 'hr',
				'label' => 'Для разработчиков'
			),
		);
		$array['controls']['export_button'] = array(
			'control' => array(
				'label' => 'Создать демо-данные для шаблона',
				'description' => 'Сохранить состояние текущего сайта. <br>Внимание!!! Старые файлы в папке сохранения будут удалены. Файлы предназначены для установки на другом домене и при активном текущем как сайте-доноре для медиафайлов.<br><br><small>Данная функция доступна только в режиме дебага - WP_DEBUG = true<small>',
				'type' => 'button',
				'input_attrs' => array(
					'value' => 'Сохранить данные',
					'class' => 'button button-secondary export_button',
					'id' => 'export_button',
				),
			),
		);
	}

	return $array;
}
