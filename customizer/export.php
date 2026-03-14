<?php

/**
 * Функции для экспорта настроек кастомайзера
 *
 * @package frondendie
 */

// Добавляем обработчики для экспорта
add_action('admin_post_sws_export_customizer_settings', 'sws_export_customizer_settings_handler');
add_action('wp_ajax_sws_export_customizer_settings', 'sws_export_customizer_settings_handler');

// Добавляем обработчик для скачивания экспортированной темы
add_action('admin_post_sws_download_theme_export', 'sws_download_theme_export_handler');

/**
 * Обработчик экспорта настроек кастомайзера
 */
function sws_export_customizer_settings_handler()
{
	// Проверяем права доступа
	if (!current_user_can('manage_options')) {
		wp_die('У вас нет прав для выполнения этой операции.');
	}

	// Проверяем nonce
	$nonce = isset($_POST['sws_export_nonce']) ? $_POST['sws_export_nonce'] : '';
	if (!wp_verify_nonce($nonce, 'sws_import_export_nonce')) {
		wp_die('Nonce проверка не удалась.');
	}

	// Собираем данные для экспорта
	$export_data = array(
		'customizer_settings' 	=> sws_get_customizer_settings(),
		'site_options' 			=> sws_get_site_options(),
		'menus' 				=> sws_get_menus(),
		'widgets' 				=> sws_get_widgets(),
		'wpforms'               => sws_get_wpforms_data(),
	);

	$exportDir = get_template_directory() . '/demo';

	// Проверяем, что директория существует, если нет - создаем
	if (!is_dir($exportDir)) {
		$created = mkdir($exportDir, 0755, true);
		if (!$created) {
			wp_send_json_error(array(
				'message' => 'Не удалось создать директорию: ' . $exportDir,
			));
			wp_die();
		}
	}

	// Проверяем права на запись в директорию
	if (!is_writable($exportDir)) {
		wp_send_json_error(array(
			'message' => 'Нет прав на запись в директорию: ' . $exportDir,
		));
		wp_die();
	}

	// Сохраняем данные в файл по адресу <theme-folder>/demo/customizer-settings.json
	$file = $exportDir . '/customizer-settings.json';

	// Проверяем права на запись в файл, если он существует
	if (file_exists($file) && !is_writable($file)) {
		wp_send_json_error(array(
			'message' => 'Нет прав на запись в файл: ' . $file,
		));
		wp_die();
	}

	// Сохраняем данные в файл
	$result = file_put_contents($file, wp_json_encode($export_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

	// Проверяем результат сохранения
	if ($result === false) {
		wp_send_json_error(array(
			'message' => 'Ошибка сохранения JSON файла.',
		));
		wp_die();
	}

	// Делаем экспорт контента в эту же папку в xml формате, используя wp export 
	$command = sprintf(
		'wp export --dir=' . $exportDir . ' --filename_format=content.xml',
		escapeshellarg(ABSPATH)
	);
	$output = array();
	exec($command, $output, $return_var);

	// Проверяем результат выполнения команды
	if ($return_var !== 0) {
		wp_send_json_error(array(
			'message' => 'Ошибка сохранения XML файла.',
		));
		wp_die();
	}

	// Делаем дамп базы данных в эту же папку, используя wp db export
	$db_dump_file = $exportDir . '/database.sql';
	$command      = sprintf(
		'wp db export %s',
		escapeshellarg($db_dump_file)
	);
	$output     = array();
	$return_var = 0;
	exec($command, $output, $return_var);

	if ($return_var !== 0) {
		wp_send_json_error(array(
			'message' => 'Ошибка создания дампа базы данных.',
		));
		wp_die();
	}

	// Создаем временный файл для архива с названием темы
	$theme_name = get_template();
	$temp_zip = tempnam(sys_get_temp_dir(), $theme_name);

	// Упаковываем всю тему в архив zip
	$zip = new ZipArchive();
	$zip_result = $zip->open($temp_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE);
	if ($zip_result !== TRUE) {
		wp_send_json_error(array(
			'message' => 'Не удалось создать ZIP архив. Код ошибки: ' . $zip_result,
		));
		wp_die();
	}

	// Добавляем файлы в архив
	$template_dir = get_template_directory();
	$files = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($template_dir, RecursiveDirectoryIterator::SKIP_DOTS),
		RecursiveIteratorIterator::LEAVES_ONLY
	);

	foreach ($files as $file) {
		$filepath = $file->getRealPath();
		$relative_path = substr($filepath, strlen($template_dir) + 1);

		if (is_file($filepath)) {
			$zip->addFile($filepath, $relative_path);
		}
	}

	$zip->close();

	// Создаем уникальный ключ для транзиента
	$transient_key = 'sws_theme_export_' . uniqid();

	// Сохраняем путь к временному файлу в транзиент для последующего скачивания
	set_transient($transient_key, $temp_zip, 300); // Сохраняем на 5 минут

	// Отправляем сообщение об успешном экспорте с URL для скачивания
	wp_send_json_success(array(
		'message' => 'Экспорт успешно завершен. Архив с темой готов к скачиванию.',
		'download_url' => admin_url('admin-post.php?action=sws_download_theme_export&file_key=' . $transient_key . '&nonce=' . wp_create_nonce('sws_download_theme_export'))
	));
	exit;
}

/**
 * Получение опций сайта
 */
function sws_get_site_options()
{
	$options = array(
		'blogname' => get_option('blogname'),
		'blogdescription' => get_option('blogdescription'),
		'posts_per_page' => get_option('posts_per_page'),
		'timezone_string' => get_option('timezone_string'),
		'date_format' => get_option('date_format'),
		'time_format' => get_option('time_format'),
		'show_on_front' => get_option('show_on_front'),
		'page_on_front' => get_option('page_on_front'),
		'page_for_posts' => get_option('page_for_posts'),
		'permalink_structure' => get_option('permalink_structure'),
		'wp_page_for_privacy_policy' => get_option('wp_page_for_privacy_policy'),
		// Добавьте другие опции по необходимости
	);

	// Добавляем slug страниц, если есть ID страниц в опциях
	foreach ($options as $key => $value) {
		if (is_numeric($value) && get_post_type($value) === 'page') {
			$page = get_post($value);
			if ($page) {
				$options[$key . '_slug'] = $page->post_name;
			}
		}
	}

	return $options;
}

/**
 * Получение меню
 */
function sws_get_menus()
{
	$menus = array();

	// Получаем все меню
	$menu_locations = get_nav_menu_locations();

	// Получаем все зарегистрированные меню
	$all_menus = wp_get_nav_menus();

	// Экспортируем все меню
	foreach ($all_menus as $menu) {
		$menus[$menu->slug] = array(
			'name' => $menu->name,
			'slug' => $menu->slug,
			'items' => sws_get_menu_items($menu->term_id)
		);
	}

	return $menus;
}

/**
 * Получение элементов меню
 */
function sws_get_menu_items($menu_id)
{
	$menu_items = wp_get_nav_menu_items($menu_id);
	$items = array();

	foreach ($menu_items as $item) {
		$item_data = array(
			'title' => $item->title,
			'url' => $item->url,
			'menu_item_parent' => $item->menu_item_parent,
			'object_id' => $item->object_id,
			'object' => $item->object,
			'type' => $item->type,
			'type_label' => $item->type_label,
			'target' => $item->target,
			'attr_title' => $item->attr_title,
			'description' => $item->description,
			'classes' => $item->classes,
			'xfn' => $item->xfn,
			'position' => $item->position,
		);

		// Добавляем slug страницы, если элемент меню ссылается на страницу
		if ($item->object == 'page' && is_numeric($item->object_id)) {
			$page = get_post($item->object_id);
			if ($page) {
				$item_data['page_slug'] = $page->post_name;
			}
		}

		// Добавляем slug родительского элемента, если есть
		if ($item->menu_item_parent > 0) {
			$parent_item = wp_filter_object_list($menu_items, array('ID' => $item->menu_item_parent), 'and', 'post_name');
			if (!empty($parent_item)) {
				$item_data['parent_slug'] = reset($parent_item);
			}
		}

		$items[] = $item_data;
	}

	return $items;
}

/**
 * Получение настроек кастомайзера
 */
function sws_get_customizer_settings()
{
	global $wp_customize;

	// Получаем все настройки кастомайзера
	$settings = get_theme_mods();

	// Добавляем slug страниц, если есть ID страниц в настройках
	foreach ($settings as $key => $value) {
		if (is_numeric($value) && get_post_type($value) === 'page') {
			$page = get_post($value);
			if ($page) {
				$settings[$key . '_slug'] = $page->post_name;
			}
		}
	}

	// Добавляем slug меню, если есть ID меню в настройках
	if (isset($settings['nav_menu_locations']) && is_array($settings['nav_menu_locations'])) {
		foreach ($settings['nav_menu_locations'] as $location => $menu_id) {
			if (is_numeric($menu_id)) {
				$menu = wp_get_nav_menu_object($menu_id);
				if ($menu) {
					$settings['nav_menu_locations'][$location . '_slug'] = $menu->slug;
				}
			}
		}
	}

	return $settings;
}

/**
 * Получение виджетов
 */
function sws_get_widgets()
{
	// Получаем все виджеты
	$widgets = get_option('sidebars_widgets');

	// Получаем настройки виджетов
	$widget_settings = array();

	// Проходим по всем зарегистрированным типам виджетов
	global $wp_widget_factory;
	foreach ($wp_widget_factory->widgets as $widget) {
		$widget_options = get_option('widget_' . $widget->id_base);
		if ($widget_options) {
			$widget_settings[$widget->id_base] = $widget_options;
		}
	}

	// Добавляем slug страниц, если есть ID страниц в настройках виджетов
	// Добавляем slug меню, если есть ID меню в настройках виджетов (например, nav_menu-2)
	foreach ($widget_settings as $widget_type => $widgets_data) {
		foreach ($widgets_data as $widget_id => $widget_data) {
			if (is_array($widget_data)) {
				foreach ($widget_data as $key => $value) {
					// Проверяем страницы
					if (is_numeric($value) && get_post_type($value) === 'page') {
						$page = get_post($value);
						if ($page) {
							$widget_settings[$widget_type][$widget_id][$key . '_slug'] = $page->post_name;
						}
					}
					// Проверяем меню (например, nav_menu-2)
					elseif (is_numeric($value) && strpos($key, 'nav_menu') !== false) {
						$menu = wp_get_nav_menu_object($value);
						if ($menu) {
							$widget_settings[$widget_type][$widget_id][$key . '_slug'] = $menu->slug;
						}
					}
				}
			}
		}
	}

	return array(
		'sidebars_widgets' => $widgets,
		'widget_settings' => $widget_settings
	);
}

/**
 * Получение данных WPForms (если плагин установлен)
 */
function sws_get_wpforms_data()
{
	$data = array(
		'forms'    => array(),
		'settings' => array(),
	);

	// Настройки WPForms
	$settings = get_option('wpforms_settings');
	if (is_array($settings)) {
		$data['settings'] = $settings;
	}

	// Формы WPForms (кастомный тип записи wpforms)
	// Не полагаемся на глобальный объект wpforms, просто читаем записи post_type=wpforms.
	$forms = get_posts(
		array(
			'post_type'      => 'wpforms',
			'post_status'    => array('publish', 'draft', 'pending', 'private'),
			'posts_per_page' => -1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
		)
	);

	if (!empty($forms) && is_array($forms)) {
		foreach ($forms as $form) {
			$data['forms'][] = array(
				'id'      => (int) $form->ID,
				'title'   => $form->post_title,
				'content' => $form->post_content,
				'status'  => $form->post_status,
				'slug'    => $form->post_name,
			);
		}
	}

	return $data;
}

/**
 * Обработчик для скачивания экспортированной темы
 */
function sws_download_theme_export_handler()
{
	// Проверяем nonce
	if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'sws_download_theme_export')) {
		wp_die('Nonce проверка не удалась');
	}

	// Проверяем права доступа
	if (!current_user_can('edit_theme_options')) {
		wp_die('Недостаточно прав для выполнения операции');
	}

	// Получаем путь к временному файлу из транзиента
	$transient_key = isset($_GET['file_key']) ? sanitize_text_field($_GET['file_key']) : '';
	if (empty($transient_key)) {
		wp_die('Неверный ключ файла');
	}

	$file_path = get_transient($transient_key);
	if (empty($file_path) || !file_exists($file_path)) {
		wp_die('Файл не найден');
	}

	// Отправляем файл для скачивания с правильным именем
	$theme_name = get_template();
	// Если в названии нет преффикса sws- добавляем его
	if (strpos($theme_name, 'sws-') !== 0) {
		$theme_name = 'sws-' . $theme_name;
	}
	$filename = $theme_name . '.zip';
	header('Content-Description: File Transfer');
	header('Content-Type: application/zip');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file_path));

	// Очищаем буфер вывода
	if (ob_get_level()) {
		ob_end_clean();
	}

	// Читаем файл и отправляем его
	readfile($file_path);

	// Удаляем временный файл и транзиент
	delete_transient($transient_key);
	unlink($file_path);

	// Завершаем выполнение
	exit;
}
