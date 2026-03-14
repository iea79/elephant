<?php

/**
 * Функции для импорта настроек кастомайзера
 *
 * @package frondendie
 */

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
define('IMPORT_DEBUG', true);

/**
 * Поиск и замена ID форм в шорткодах [wpforms id="123" ...]
 */
function sws_update_text_with_wpforms_ids($text, $id_map)
{
	if (!is_string($text) || empty($id_map) || !is_array($id_map)) {
		return $text;
	}

	$callback = function ($matches) use ($id_map) {
		$before   = $matches[1];
		$old_id   = (int) $matches[2];
		$after    = $matches[3];

		if (!isset($id_map[$old_id])) {
			return $matches[0];
		}

		$new_id = (int) $id_map[$old_id];

		// Собираем шорткод с новым ID, сохраняя остальные атрибуты
		return '[wpforms ' . $before . 'id="' . $new_id . '"' . $after . ']';
	};

	// Ищем шорткоды вида [wpforms id="123" ...] или [wpforms ... id='123' ...]
	return preg_replace_callback(
		'/\[wpforms\s+([^\]]*?)id=["\'](\d+)["\']([^\]]*?)\]/i',
		$callback,
		$text
	);
}

/**
 * Рекурсивная замена ID форм в массивах настроек
 */
function sws_recursive_replace_wpforms_ids_in_array($value, $id_map)
{
	if (is_array($value)) {
		foreach ($value as $k => $v) {
			$value[$k] = sws_recursive_replace_wpforms_ids_in_array($v, $id_map);
		}
		return $value;
	}

	return sws_update_text_with_wpforms_ids($value, $id_map);
}

/**
 * Обновление шорткодов форм WPForms в кастомайзере и виджетах
 * после импорта форм (когда старые ID уже известны и сопоставлены новым).
 */
function sws_fix_wpforms_shortcodes_in_theme($id_map)
{
	if (empty($id_map) || !is_array($id_map)) {
		return;
	}

	// Обновляем все theme_mods (настройки кастомайзера)
	$mods = get_theme_mods();
	if (is_array($mods)) {
		foreach ($mods as $key => $value) {
			$original = $value;

			if (is_array($value)) {
				$value = sws_recursive_replace_wpforms_ids_in_array($value, $id_map);
			} else {
				$value = sws_update_text_with_wpforms_ids($value, $id_map);
			}

			if ($value !== $original) {
				set_theme_mod($key, $value);
			}
		}
	}

	// Обновляем настройки виджетов, где тоже могут быть шорткоды форм
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets) && is_array($wp_widget_factory->widgets)) {
		foreach ($wp_widget_factory->widgets as $widget) {
			if (empty($widget->id_base)) {
				continue;
			}

			$option_name = 'widget_' . $widget->id_base;
			$opts        = get_option($option_name);

			if (!is_array($opts) || empty($opts)) {
				continue;
			}

			$original_serialized = serialize($opts);
			$opts                = sws_recursive_replace_wpforms_ids_in_array($opts, $id_map);

			if (serialize($opts) !== $original_serialized) {
				update_option($option_name, $opts);
			}
		}
	}
}

// Добавляем обработчики для импорта
add_action('admin_post_sws_import_customizer_settings', 'sws_start_import_handler');
add_action('wp_ajax_sws_import_customizer_settings', 'sws_start_import_handler');
// Стартуем импорт настроек кастомайзера после завершения импорта контента
// add_action('import_end', 'sws_import_customizer_settings_handler');

/**
 * Полный импорт демоконтента с сайта донара
 * Используется плагин wordpress-importer
 * Файлы import.xml и customizer-settings.json должны быть в корневой директории темы в папке demo
 * @return void
 */
function sws_start_import_handler()
{
	// Проверяем права доступа
	if (!current_user_can('manage_options')) {
		wp_send_json_error(array('message' => 'У вас нет прав для выполнения этой операции.'));
		wp_die();
	}

	// Проверяем наличие функции exec (на некоторых хостингах она отключена)
	if (!function_exists('exec')) {
		wp_send_json_error(array(
			'message' => 'Импорт недоступен: функция exec() отключена на сервере. Обратитесь к хостингу или используйте wp-cli вручную.'
		));
		wp_die();
	}

	// Если все хорошо, удалаем все записи и страницы
	sws_delete_all_posts_and_pages();

	// Путь к WordPress
	$wpPath = ABSPATH;
	$return_var = 0;

	// Проверяем, что плагин wordpress-importer установлен
	if (!is_plugin_active('wordpress-importer/wordpress-importer.php')) {
		// Если нет, устанавливаем его командой и активируем
		$command = sprintf(
			'wp plugin install wordpress-importer --activate',
			escapeshellarg($wpPath)
		);
		$output = array();
		exec($command, $output, $return_var);

		if ($return_var !== 0) {
			$debug_message = 'Ошибка установки плагина wordpress-importer.';
			if ($return_var === 127) {
				$debug_message .= ' На вашем хостинге не установлен wp-cli.';
			}
			if (defined('IMPORT_DEBUG') && IMPORT_DEBUG) {
				$debug_message .= ' Код: ' . $return_var . '. Вывод: ' . implode("\n", $output);
				error_log($debug_message);
			}
			wp_send_json_error(array('message' => $debug_message));
			wp_die();
		}
	};

	// Запускаем импорт контента из xml файла
	$xml_file = get_template_directory() . '/demo/content.xml';

	if (!file_exists($xml_file)) {
		wp_send_json_error(array('message' => 'Файл import.xml не найден.'));
		wp_die();
	}

	$command = sprintf(
		'wp import ' . $xml_file . ' --authors=skip',
		escapeshellarg($wpPath)
	);
	$output = array();
	exec($command, $output, $return_var);

	if ($return_var !== 0) {
		$debug_message = 'Ошибка импорта контента из xml файла.';
		if ($return_var === 127) {
			$debug_message .= ' На вашем хостинге не установлен wp-cli.';
		}
		if (defined('IMPORT_DEBUG') && IMPORT_DEBUG) {
			$debug_message .= ' Код: ' . $return_var . '. Вывод: ' . implode("\n", $output);
			error_log($debug_message);
		}
		wp_send_json_error(array('message' => $debug_message));
		wp_die();
	}

	sws_import_customizer_settings_handler();
}

/**
 * Обработчик импорта настроек кастомайзера
 */
function sws_import_customizer_settings_handler()
{

	// Логируем начало выполнения функции
	error_log('sws_import_customizer_settings_handler started');

	// Получаем файл с настройками кастомайзера
	$file = get_template_directory() . '/demo/customizer-settings.json';

	if (!file_exists($file)) {
		wp_send_json_error(array('message' => 'Файл с настройками кастомайзера не найден.'));
		wp_die();
	}

	// Читаем содержимое файла
	$file_content = file_get_contents($file);
	$import_data = json_decode($file_content, true);

	if (json_last_error() !== JSON_ERROR_NONE) {
		wp_send_json_error(array('message' => 'Ошибка декодирования JSON файла.'));
		wp_die();
	}

	// Импортируем настройки кастомайзера
	sws_import_customizer_settings($import_data['customizer_settings']);

	// Импортируем опции сайта
	sws_import_site_options($import_data['site_options']);

	// Импортируем виджеты
	sws_import_widgets($import_data['widgets']);

	// Импортируем WPForms (если есть данные и плагин установлен)
	if (!empty($import_data['wpforms'])) {
		sws_import_wpforms_data($import_data['wpforms']);
	}

	sleep(1);

	// Обновляем правила пермалинков (жёсткий сброс, чтобы перезаписать .htaccess/nginx правила)
	flush_rewrite_rules(true);

	sleep(1);

	// Логируем завершение импорта
	error_log('sws_import_customizer_settings_handler completed');

	// Отправляем ответ об успешном импорте с задержкой в 1 секунду
	wp_send_json_success(array('message' => 'Импорт успешно завершен.'));
	exit;
}

/**
 * Удаление записей и страниц
 */
function sws_delete_all_posts_and_pages()
{
	// Получаем все записи и страницы
	$posts = get_posts(array(
		'post_type' => array('post', 'page', 'attachment'),
		'post_status' => 'any',
		'numberposts' => -1,
		'fields' => 'ids'
	));

	// Удаляем каждую запись/страницу
	foreach ($posts as $post_id) {
		wp_delete_post($post_id, true);
	}

	// Удаляем меню и виджеты
	wp_delete_all_menus();
}

/**
 * Удаление меню и виджетов
 */
function wp_delete_all_menus()
{
	// Получаем все меню
	$menus = get_terms('nav_menu', array('hide_empty' => false));

	// Удаляем каждое меню
	foreach ($menus as $menu) {
		wp_delete_nav_menu($menu->term_id);
	}
}

/**
 * Импорт опций сайта
 */
function sws_import_site_options($options)
{
	foreach ($options as $key => $value) {
		// Проверяем, есть ли slug страницы для этой опции
		$slug_key = $key . '_slug';
		if (isset($options[$slug_key])) {
			// Проверяем существование страницы по slug
			$page = get_page_by_path($options[$slug_key]);
			if ($page) {
				// Если страница существует, используем её ID
				$value = $page->ID;
			}
		}

		update_option($key, $value);
	}
}

/**
 * Импорт меню
 */
function sws_import_menus($menus)
{
	foreach ($menus as $menu_slug => $menu_data) {
		// Проверяем, существует ли меню
		$existing_menu = wp_get_nav_menu_object($menu_slug);

		if ($existing_menu) {
			$menu_id = $existing_menu->term_id;
		} else {
			// Создаем новое меню
			$menu_id = wp_create_nav_menu($menu_data['name']);
		}

		// Импортируем элементы меню
		if ($menu_id && !is_wp_error($menu_id)) {
			foreach ($menu_data['items'] as $item_data) {
				// Проверяем, есть ли slug страницы для этого элемента меню
				if (isset($item_data['page_slug'])) {
					// Проверяем существование страницы по slug
					$page = get_page_by_path($item_data['page_slug']);
					if ($page) {
						// Если страница существует, используем её ID
						$item_data['object_id'] = $page->ID;
					}
				}

				// Проверяем, есть ли slug родительского элемента
				if (isset($item_data['parent_slug'])) {
					// Проверяем существование родительского элемента по slug
					$parent_item = get_page_by_path($item_data['parent_slug'], OBJECT, 'nav_menu_item');
					if ($parent_item) {
						// Если родительский элемент существует, используем его ID
						$item_data['menu_item_parent'] = $parent_item->ID;
					}
				}

				// Проверяем, существует ли элемент меню
				$existing_item = get_posts(array(
					'post_type' => 'nav_menu_item',
					'meta_key' => '_menu_item_object_id',
					'meta_value' => $item_data['object_id'],
					'post_status' => 'publish',
					'numberposts' => 1,
				));

				if (empty($existing_item)) {
					// Создаем новый элемент меню
					wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-title' => $item_data['title'],
						'menu-item-url' => $item_data['url'],
						'menu-item-parent-id' => $item_data['menu_item_parent'],
						'menu-item-object-id' => $item_data['object_id'],
						'menu-item-object' => $item_data['object'],
						'menu-item-type' => $item_data['type'],
						'menu-item-target' => $item_data['target'],
						'menu-item-attr-title' => $item_data['attr_title'],
						'menu-item-description' => $item_data['description'],
						'menu-item-classes' => implode(' ', $item_data['classes']),
						'menu-item-xfn' => $item_data['xfn'],
						'menu-item-position' => $item_data['position'],
						'menu-item-status' => 'publish',
					));
				}
			}
		}
	}

	// Обновляем menu_item_parent для элементов меню, так как ID могли измениться при импорте
	foreach ($menus as $menu_slug => $menu_data) {
		// Получаем меню по slug
		$menu = wp_get_nav_menu_object($menu_slug);

		if ($menu) {
			$menu_items = wp_get_nav_menu_items($menu->term_id);

			foreach ($menu_items as $menu_item) {
				// Находим оригинальный элемент меню в импортированных данных
				foreach ($menu_data['items'] as $original_item) {
					// Проверяем, есть ли parent_slug у оригинального элемента
					if (isset($original_item['parent_slug']) && $original_item['parent_slug']) {
						// Проверяем, совпадает ли slug текущего элемента меню с оригинальным
						if ($menu_item->post_name === sanitize_title($original_item['title'])) {
							// Получаем новый ID родительского элемента по его slug
							$parent_item = get_page_by_path($original_item['parent_slug'], OBJECT, 'nav_menu_item');
							if ($parent_item) {
								// Обновляем menu_item_parent
								wp_update_post(array(
									'ID' => $menu_item->ID,
									'menu_item_parent' => $parent_item->ID
								));
							}
							break;
						}
					}
				}
			}
		}
	}
}

/**
 * Импорт виджетов
 */
function sws_import_widgets($widgets_data)
{
	if (empty($widgets_data)) {
		return;
	}

	// Импортируем настройки виджетов
	if (isset($widgets_data['widget_settings'])) {
		foreach ($widgets_data['widget_settings'] as $widget_type => $widgets_settings) {
			// Проверяем slug страниц в настройках виджетов
			foreach ($widgets_settings as $widget_id => $widget_data) {
				if (is_array($widget_data)) {
					foreach ($widget_data as $key => $value) {
						// Проверяем, есть ли slug для этого параметра
						$slug_key = $key . '_slug';
						if (isset($widget_data[$slug_key])) {
							// Проверяем тип параметра по ключу
							if (strpos($key, 'nav_menu') !== false) {
								// Это меню - проверяем по slug меню
								$menu = wp_get_nav_menu_object($widget_data[$slug_key]);
								if ($menu) {
									// Если меню существует, используем его ID
									$widgets_settings[$widget_id][$key] = $menu->term_id;
								}
							} else {
								// Это страница - проверяем по slug страницы
								$page = get_page_by_path($widget_data[$slug_key]);
								if ($page) {
									// Если страница существует, используем её ID
									$widgets_settings[$widget_id][$key] = $page->ID;
								}
							}
						}
					}
				}
			}

			// Обновляем настройки виджетов
			update_option('widget_' . $widget_type, $widgets_settings);
		}
	}

	// Импортируем виджеты в сайдбарах
	if (isset($widgets_data['sidebars_widgets'])) {
		update_option('sidebars_widgets', $widgets_data['sidebars_widgets']);
	}
}

/**
 * Импорт настроек кастомайзера
 */
function sws_import_customizer_settings($settings)
{
	// Проверяем nav_menu_locations и заменяем ID меню на slug
	if (isset($settings['nav_menu_locations']) && is_array($settings['nav_menu_locations'])) {
		foreach ($settings['nav_menu_locations'] as $location => $menu_id) {
			// Проверяем, есть ли slug меню для этой локации
			$slug_key = $location . '_slug';
			if (isset($settings['nav_menu_locations'][$slug_key])) {
				// Проверяем существование меню по slug
				$menu = wp_get_nav_menu_object($settings['nav_menu_locations'][$slug_key]);
				if ($menu) {
					// Если меню существует, используем его ID
					$settings['nav_menu_locations'][$location] = $menu->term_id;
				}
			}
		}
	}

	foreach ($settings as $key => $value) {
		// Проверяем, есть ли slug страницы для этой настройки
		$slug_key = $key . '_slug';
		if (isset($settings[$slug_key])) {
			// Проверяем существование страницы по slug
			$page = get_page_by_path($settings[$slug_key]);
			if ($page) {
				// Если страница существует, используем её ID
				$value = $page->ID;
			}
		}

		set_theme_mod($key, $value);
	}
}

/**
 * Импорт форм и настроек WPForms
 *
 * Ожидает структуру, сохранённую sws_get_wpforms_data().
 * Выполняется только если плагин WPForms активен.
 */
function sws_import_wpforms_data($data)
{
	// Проверяем, установлен и активен ли WPForms
	if (!class_exists('WPForms')) {
		return;
	}

	if (!is_array($data)) {
		return;
	}

	// Восстанавливаем настройки плагина
	if (!empty($data['settings']) && is_array($data['settings'])) {
		update_option('wpforms_settings', $data['settings']);
	}

	// Восстанавливаем формы
	if (empty($data['forms']) || !is_array($data['forms'])) {
		return;
	}

	$id_map = array(); // соответствие старых ID форм к новым

	foreach ($data['forms'] as $form) {
		if (empty($form['content'])) {
			continue;
		}

		$title  = isset($form['title']) ? $form['title'] : '';
		$status = !empty($form['status']) ? $form['status'] : 'publish';
		$slug   = !empty($form['slug']) ? $form['slug'] : '';
		$old_id = isset($form['id']) ? (int) $form['id'] : 0;

		// Пытаемся найти уже существующую форму по заголовку
		$existing = null;
		if ($title !== '') {
			$existing = get_page_by_title($title, OBJECT, 'wpforms');
		}

		$postarr = array(
			'post_title'   => $title,
			'post_content' => $form['content'],
			'post_status'  => $status,
			'post_type'    => 'wpforms',
		);

		if ($slug !== '') {
			$postarr['post_name'] = $slug;
		}

		$new_id = 0;

		if ($existing && !is_wp_error($existing)) {
			$postarr['ID'] = $existing->ID;
			$new_id        = (int) $existing->ID;
			wp_update_post(wp_slash($postarr));
		} else {
			$new_id = (int) wp_insert_post(wp_slash($postarr));
		}

		// Сохраняем соответствие старого и нового ID
		if ($old_id > 0 && $new_id > 0) {
			$id_map[$old_id] = $new_id;
		}
	}

	// После импорта форм обновляем все шорткоды [wpforms id="..."] в кастомайзере и виджетах
	if (!empty($id_map)) {
		sws_fix_wpforms_shortcodes_in_theme($id_map);
	}
}
