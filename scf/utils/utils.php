<?php

/**
 * Возвращает готовую разметку секций
 *
 * @param object $post           — объект записи
 * @param array $templates       — массив шаблонов секций
 * 
 * Пример $templates:
 * $templates = array(
 *     'home-1' => function() {
 *         ?>
 *         <section class="home-section-1">
 *             <h2><?php echo SCF::get('home_title_1'); ?></h2>
 *             <p><?php echo SCF::get('home_text_1'); ?></p>
 *         </section>
 *         <?php
 *     },
 *     'home-2' => function() {
 *         ?>
 *         <section class="home-section-2">
 *         <h2><?php echo SCF::get('home_title_2'); ?></h2>
 *             <p><?php echo SCF::get('home_text_2'); ?></p>
 *         </section>
 *         <?php
 *     }
 * );
 * 
 * Пример $sections:
 * $sections = array( [0] => 'home-1' [1] => 'home-2' [2] => 'home-3' );
 * 
 * @return string                — готовая разметка секций
 */
function get_scf_sections_markup($post, $templates = array())
{
	$sections = SCF::get('sections', $post->ID);
	$output = '';

	// Если не указаны sections, используем все доступные шаблоны
	if (empty($sections)) {
		foreach ($templates as $id => $template) {
			// Проверяем, показывается ли секция в настройках
			$show_section = SCF::get($id . '-show', $post->ID);
			// Если секция показывается или нет настройки для показа/скрытия добавляем шаблон в вывод
			if ($show_section === true || $show_section === NULL) {
				$output .= call_user_func($template);
			}
		}
	} else {
		// Создаем новый массив шаблонов, отсортированный в порядке, указанном в $sections
		$sorted_templates = array();
		foreach ($sections as $section_id) {
			if (isset($templates[$section_id])) {
				$sorted_templates[$section_id] = $templates[$section_id];
			}
		}

		// Вызываем каждый шаблон в отсортированном порядке
		foreach ($sorted_templates as $id => $template) {
			$output .= call_user_func($template);
		}
	}

	echo $output;
}
