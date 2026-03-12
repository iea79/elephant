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
			wp_send_json_error(array('message' => 'Ошибка установки плагина wordpress-importer.'));
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
		wp_send_json_error(array('message' => 'Ошибка импорта контента из xml файла.'));
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

	sleep(1);

	flush_rewrite_rules();

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
