<?php

/**
 * Универсальная функция для регистрации панелей, секций и контролов в Customizer
 *
 * @param WP_Customize_Manager $wp_customize Объект Customizer
 * @param array $config Массив конфигурации панелей, секций и контролов
 * @return void
 *
 * Пример массива конфигурации:
 * ```php
 * $customizer_config = array(
 *     'panels' => array(
 *         'panel_id' => array(
 *             'priority'       => 10,
 *             'title'          => 'Название панели',
 *             'description'    => 'Описание панели',
 *             'sections' => array(
 *                 'section_id' => array(
 *                     'title' => 'Название секции',
 *                     'description' => 'Описание секции',
 *                     'priority' => 10,
 *                     'controls' => array(
 *                         'setting_id' => array(
 *                             'default' => 'Значение по умолчанию',
 *                             'transport' => 'refresh|postMessage',
 *                             'control' => array(
 *                                 'label' => 'Название контрола',
 *                                 'type' => 'text|checkbox|color|select|textarea|range|radio|dropdown-pages|email|url|number|hidden|date|time',
 *                                 // Дополнительные атрибуты контрола
 *                             ),
 *                         ),
 *                     ),
 *                 ),
 *             ),
 *         ),
 *     ),
 *     'sections' => array(
 *         'section_id' => array(
 *             'title' => 'Название секции',
 *             'description' => 'Описание секции',
 *             'priority' => 10,
 *             'controls' => array(
 *                 'setting_id' => array(
 *                     'default' => 'Значение по умолчанию',
 *                     'transport' => 'refresh|postMessage',
 *                     'control' => array(
 *                         'label' => 'Название контрола',
 *                         'type' => 'text|checkbox|color|select|textarea|range|radio|dropdown-pages|email|url|number|hidden|date|time',
 *                         // Дополнительные атрибуты контрола
 *                     ),
 *                 ),
 *             ),
 *         ),
 *     ),
 *     'settings' => array(
 *         'setting_id' => array(
 *             'default' => 'Значение по умолчанию',
 *             'transport' => 'refresh|postMessage',
 *             'control' => array(
 *                 'label' => 'Название контрола',
 *                 'section' => 'section_id',
 *                 'type' => 'text|checkbox|color|select|textarea|range|radio|dropdown-pages|email|url|number|hidden|date|time',
 *                 // Дополнительные атрибуты контрола
 *             ),
 *         ),
 *     ),
 * );
 * ```
 */
function register_customizer_elements($wp_customize, $config)
{
	// Регистрация панелей
	if (isset($config['panels']) && is_array($config['panels'])) {
		foreach ($config['panels'] as $panel_id => $panel_args) {
			// Регистрируем панель, если она еще не зарегистрирована
			if (!$wp_customize->get_panel($panel_id)) {
				$wp_customize->add_panel($panel_id, $panel_args);
			}

			// Регистрация секций, вложенных в панель
			if (isset($panel_args['sections']) && is_array($panel_args['sections'])) {
				foreach ($panel_args['sections'] as $section_id => $section_args) {
					// Добавляем привязку к панели
					$section_args['panel'] = $panel_id;
					$section_args['title'] = isset($section_args['title']) ? $section_args['title'] : $section_id;

					// Проверяем, существует ли панель
					if (!$wp_customize->get_panel($panel_id)) {
						// Если панель не существует, пропускаем секцию
						continue;
					}

					// Регистрируем секцию, если она еще не зарегистрирована
					if (!$wp_customize->get_section($section_id)) {
						$wp_customize->add_section($section_id, $section_args);
					}

					// Регистрация контролов, вложенных в секцию
					if (isset($section_args['controls']) && is_array($section_args['controls'])) {
						foreach ($section_args['controls'] as $setting_id => $setting_args) {
							// Регистрация настройки
							$transport = isset($setting_args['transport']) ? $setting_args['transport'] : 'refresh';
							$setting_defaults = [
								'transport' => $transport,
							];
							$setting_args = wp_parse_args($setting_args, $setting_defaults);

							// Регистрируем настройку, если она еще не зарегистрирована
							if (!$wp_customize->get_setting($setting_id)) {
								$wp_customize->add_setting($setting_id, $setting_args);
							}

							// Регистрация контрола, если он указан
							if (isset($setting_args['control']) && is_array($setting_args['control'])) {
								$control_args = $setting_args['control'];
								$control_type = isset($control_args['type']) ? $control_args['type'] : 'text';

								// Убедимся, что секция существует и указана в контроле
								$control_args['section'] = $section_id;

								// Добавляем ID настройки в аргументы контрола
								$control_args['settings'] = $setting_id;

								// Проверяем, существует ли контрол
								if (!$wp_customize->get_control($setting_id)) {
								// Обработка специальных типов контроллов
									switch ($control_type) {
										case 'color':
											$wp_customize->add_control(
												new WP_Customize_Color_Control($wp_customize, $setting_id, $control_args)
											);
											break;
										case 'checkbox-multiple':
											$wp_customize->add_control(
												new Customize_Control_Checkbox_Multiple($wp_customize, $setting_id, $control_args)
											);
											break;
										case 'sortable-list':
											$wp_customize->add_control(
												new Customize_Control_Sortable_List($wp_customize, $setting_id, $control_args)
											);
											break;
										case 'media':
											$wp_customize->add_control(
												new WP_Customize_Media_Control($wp_customize, $setting_id, $control_args)
											);
											break;
										case 'hr':
											// Добавляем разделитель (тег hr)
											$wp_customize->add_control(new Customize_Control_HR($wp_customize, $setting_id, $control_args));
											break;
										case 'button':
											$wp_customize->add_control(
												new Customize_Control_Button($wp_customize, $setting_id, $control_args)
											);
											break;
										case 'tinymce':
											$wp_customize->add_control(
												new Customize_Control_TinyMCE($wp_customize, $setting_id, $control_args)
											);
											break;
										default:
											$wp_customize->add_control($setting_id, $control_args);
											break;
									}
								}
							}
						}
					}
				}
			}
		}
	}

	// Регистрация отдельных секций (не привязанных к панелям)
	if (isset($config['sections']) && is_array($config['sections'])) {
		foreach ($config['sections'] as $section_id => $section_args) {
			// Если секция принадлежит панели, убедимся, что панель указана корректно
			if (isset($section_args['panel']) && !empty($section_args['panel'])) {
				// Проверяем, существует ли панель
				if (!$wp_customize->get_panel($section_args['panel'])) {
					// Если панель не существует, можно либо пропустить секцию, либо создать панель по умолчанию
					// В данном случае пропускаем секцию
					continue;
				}
			}

			// Регистрируем секцию, если она еще не зарегистрирована
			if (!$wp_customize->get_section($section_id)) {
				$section_args['title'] = isset($section_args['title']) ? $section_args['title'] : $section_id;
				$wp_customize->add_section($section_id, $section_args);
			}

			// Регистрация контролов, вложенных в секцию
			if (isset($section_args['controls']) && is_array($section_args['controls'])) {
				foreach ($section_args['controls'] as $setting_id => $setting_args) {
					$transport = isset($setting_args['transport']) ? $setting_args['transport'] : 'refresh';
					// Регистрация настройки
					$setting_defaults = [
						'transport' => $transport,
					];
					$setting_args = wp_parse_args($setting_args, $setting_defaults);

					// Регистрируем настройку, если она еще не зарегистрирована
					if (!$wp_customize->get_setting($setting_id)) {
						$wp_customize->add_setting($setting_id, $setting_args);
					}

					// Регистрация контрола, если он указан
					if (isset($setting_args['control']) && is_array($setting_args['control'])) {
						$control_args = $setting_args['control'];
						$control_type = isset($control_args['type']) ? $control_args['type'] : 'text';

						// Убедимся, что секция существует и указана в контроле
						$control_args['section'] = $section_id;

						// Добавляем ID настройки в аргументы контрола
						$control_args['settings'] = $setting_id;

						// Проверяем, существует ли контрол
						if (!$wp_customize->get_control($setting_id)) {
							// Обработка специальных типов контроллов
							switch ($control_type) {
								case 'color':
									$wp_customize->add_control(
										new WP_Customize_Color_Control($wp_customize, $setting_id, $control_args)
									);
									break;
								case 'checkbox-multiple':
									$wp_customize->add_control(
										new Customize_Control_Checkbox_Multiple($wp_customize, $setting_id, $control_args)
									);
									break;
								case 'sortable-list':
									$wp_customize->add_control(
										new Customize_Control_Sortable_List($wp_customize, $setting_id, $control_args)
									);
									break;
								case 'media':
									$wp_customize->add_control(
										new WP_Customize_Media_Control($wp_customize, $setting_id, $control_args)
									);
									break;
								case 'hr':
									$wp_customize->add_control(new Customize_Control_HR($wp_customize, $setting_id, $control_args));
									break;
								case 'button':
									$wp_customize->add_control(
										new Customize_Control_Button($wp_customize, $setting_id, $control_args)
									);
									break;
								case 'tinymce':
									$wp_customize->add_control(
										new Customize_Control_TinyMCE($wp_customize, $setting_id, $control_args)
									);
									break;
								default:
									$wp_customize->add_control($setting_id, $control_args);
									break;
							}
						}
					}
				}
			}
		}
	}

	// Регистрация глобальных настроек и контролов (не привязанных к секциям)
	if (isset($config['settings']) && is_array($config['settings'])) {
		foreach ($config['settings'] as $setting_id => $setting_args) {
			// Регистрация настройки
			$setting_defaults = [
				'transport' => $transport,
			];
			$setting_args = wp_parse_args($setting_args, $setting_defaults);

			// Регистрируем настройку, если она еще не зарегистрирована
			if (!$wp_customize->get_setting($setting_id)) {
				$wp_customize->add_setting($setting_id, $setting_args);
			}

			// Регистрация контрола, если он указан
			if (isset($setting_args['control']) && is_array($setting_args['control'])) {
				$control_args = $setting_args['control'];
				$control_type = isset($control_args['type']) ? $control_args['type'] : 'text';

				// Убедимся, что секция существует
				if (isset($control_args['section']) && !empty($control_args['section'])) {
					if (!$wp_customize->get_section($control_args['section'])) {
						// Если секция не существует, пропускаем контрол
						continue;
					}
				}

				// Добавляем ID настройки в аргументы контрола
				$control_args['settings'] = $setting_id;

				// Проверяем, существует ли контрол
				if (!$wp_customize->get_control($setting_id)) {
					// Обработка специальных типов контроллов
					switch ($control_type) {
						case 'color':
							$wp_customize->add_control(
								new WP_Customize_Color_Control($wp_customize, $setting_id, $control_args)
							);
							break;
						case 'checkbox-multiple':
							$wp_customize->add_control(
								new Customize_Control_Checkbox_Multiple($wp_customize, $setting_id, $control_args)
							);
							break;
						case 'sortable-list':
							$wp_customize->add_control(
								new Customize_Control_Sortable_List($wp_customize, $setting_id, $control_args)
							);
							break;
						case 'media':
							$wp_customize->add_control(
								new WP_Customize_Media_Control($wp_customize, $setting_id, $control_args)
							);
							break;
						case 'hr':
							$wp_customize->add_control(new Customize_Control_HR($wp_customize, $setting_id, $control_args));
							break;
						case 'button':
							$wp_customize->add_control(
								new Customize_Control_Button($wp_customize, $setting_id, $control_args)
							);
							break;
						default:
							$wp_customize->add_control($setting_id, $control_args);
							break;
					}
				}
			}
		}
	}
}
