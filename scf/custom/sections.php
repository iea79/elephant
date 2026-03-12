<?php

/**
 * @package smart-custom-fields
 * @author inc2734
 * @license GPL-2.0+
 */

/** 
 *  Пример использования секций
 *  Название setting может быть - sections, sections_opt, sections_options для исключения из выборки на фронте
 * 	$Section = SCF::add_setting('sections_opt', 'Настройка секций');
 * 	$Section->add_group(
 * 		'sections_list',
 *			false,
 *			array(
 *				array(
 *					'label'		  => 'Настройка секций',
 *					'name'		  => 'sections',
 *					'type'		  => 'sections',
 *				),
 *			)
 *		);
 *	$settings[] = $Section;
 */

/**
 * Smart_Custom_Fields_Field_Sections class.
 */
class Smart_Custom_Fields_Field_Sections extends Smart_Custom_Fields_Field_Base
{

	/**
	 * Set the required items.
	 *
	 * @return array
	 */
	protected function init()
	{
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
		add_action('wp_ajax_smart-cf-relational-sections-search', array($this, 'relational_sections_search'));
		add_filter('smart-cf-validate-get-value', array($this, 'validate_get_value'), 10, 2);
		return array(
			'type'                => 'sections',
			'display-name'        => __('Sections', 'smart-custom-fields'),
			'optgroup'            => 'other-fields',
			'allow-multiple-data' => true,
		);
	}

	/**
	 * Set the non required items.
	 *
	 * @return array
	 */
	protected function options()
	{
		return array(
			'instruction' => '',
			'notes'       => '',
		);
	}

	/**
	 * Loading resources.
	 */
	public function admin_enqueue_scripts()
	{
		wp_enqueue_script(
			SCF_Config::PREFIX . 'editor-relation-common',
			SMART_CUSTOM_FIELDS_URL . '/js/editor-relation-common.js',
			array('jquery'),
			filemtime(SMART_CUSTOM_FIELDS_PATH . '/js/editor-relation-common.js'),
			true
		);

		wp_enqueue_script(
			SCF_Config::PREFIX . 'editor-relation-post-types',
			SMART_CUSTOM_FIELDS_URL . '/js/editor-relation-post-types.js',
			array('jquery'),
			filemtime(SMART_CUSTOM_FIELDS_PATH . '/js/editor-relation-post-types.js'),
			true
		);

		wp_localize_script(
			SCF_Config::PREFIX . 'editor-relation-post-types',
			'smart_cf_relation_post_types',
			array(
				'endpoint' => admin_url('admin-ajax.php'),
				'action'   => SCF_Config::PREFIX . 'relational-sections-search',
				'nonce'    => wp_create_nonce(SCF_Config::NAME . '-relation-post-types'),
			)
		);
	}

	/**
	 * Process that loading sections when clicking section load button.
	 */
	public function relational_sections_search()
	{
		check_ajax_referer(SCF_Config::NAME . '-relation-post-types', 'nonce');

		$_posts = array();

		// Get all settings posts for the current page
		$post_id = filter_input(INPUT_POST, 'post_id');
		if ($post_id) {
			$post = get_post($post_id);
			if ($post) {
				$settings = SCF::get_settings($post);
				$field_name = filter_input(INPUT_POST, 'field_name');

				// Get all settings with their id and title
				$all_sections = array();
				foreach ($settings as $setting) {
					$setting_id = $setting->get_id();

					// Исключаем текущее поле sections и другие служебные секции
					if (
						$setting_id === $field_name ||
						$setting_id === 'sections' ||
						$setting_id === 'sections_opt' ||
						$setting_id === 'sections_options'
					) {
						continue;
					}

					$all_sections[$setting_id] = $setting->get_title();
				}

				// Filter sections based on search term if provided
				$s = filter_input(INPUT_POST, 's');
				if ($s) {
					$filtered_sections = array();
					foreach ($all_sections as $setting_id => $setting_title) {
						if (stripos($setting_title, $s) !== false) {
							$filtered_sections[$setting_id] = $setting_title;
						}
					}
					$all_sections = $filtered_sections;
				}

				// Convert to the format expected by the JavaScript
				foreach ($all_sections as $setting_id => $setting_title) {
					$section_post = new stdClass();
					$section_post->ID = $setting_id;
					$section_post->post_title = $setting_title;
					$section_post->post_status = 'publish';
					$_posts[] = $section_post;
				}
			}
		}

		header('Content-Type: application/json; charset=utf-8');
		echo wp_json_encode($_posts);
		die();
	}

	/**
	 * Getting the field.
	 *
	 * @param int   $index Field index.
	 * @param array $value The value.
	 * @return string
	 */
	public function get_field($index, $value)
	{
		global $post;
		$name      = $this->get_field_name_in_editor($index);
		$disabled  = $this->get_disable_attribute($index);

		$choices_sections = array();
		$posts_per_page = get_option('posts_per_page');

		// Get all settings for the current page
		if ($post) {
			$settings = SCF::get_settings($post);

			// Get all settings with their id and title (first batch)
			$all_settings = array();
			foreach ($settings as $setting) {
				$setting_id = $setting->get_id();

				// Исключаем текущее поле sections и другие служебные секции
				if (
					$setting_id === $name ||
					$setting_id === 'sections' ||
					$setting_id === 'sections_opt' ||
					$setting_id === 'sections_options'
				) {
					continue;
				}

				$all_settings[$setting_id] = $setting;
				$section_post = new stdClass();
				$section_post->ID = $setting_id;
				$section_post->post_title = $setting->get_title();
				$section_post->post_status = 'publish';
				$choices_sections[] = $section_post;
			}

			// Если значение пустое, выбираем все доступные секции по умолчанию
			if (empty($value)) {
				$value = array_keys($all_settings);
			}
		}

		$choices_li = array();
		foreach ($choices_sections as $section_post) {
			$post_title = $section_post->post_title;
			if (empty($post_title)) {
				$post_title = '&nbsp;';
			}
			$choices_li[] = sprintf(
				'<li data-id="%s" data-status="%s">%s</li>',
				$section_post->ID,
				$section_post->post_status,
				$post_title
			);
		}

		// selected
		$selected_posts = array();
		if (!empty($value) && is_array($value)) {
			// For selected sections, we need to get their titles
			foreach ($value as $setting_id) {
				$post_title = $setting_id; // Default to setting id
				if ($post) {
					$settings = SCF::get_settings($post);
					foreach ($settings as $setting) {
						if ($setting->get_id() === $setting_id) {
							$post_title = $setting->get_title();
							break;
						}
					}
				}
				$selected_posts[$setting_id] = $post_title;
			}
		}
		$selected_li = array();
		$hidden      = array();
		foreach ($selected_posts as $setting_id => $post_title) {
			$selected_li[] = sprintf(
				'<li data-id="%s" data-status="publish"><span class="%s"></span>%s<span class="relation-remove">-</span></li>',
				$setting_id,
				esc_attr(SCF_Config::PREFIX . 'icon-handle dashicons dashicons-menu'),
				$post_title
			);
			$hidden[]      = sprintf(
				'<input type="hidden" name="%s" value="%s" %s />',
				esc_attr($name . '[]'),
				$setting_id,
				disabled(true, $disabled, false)
			);
		}

		$hide_class = '';
		if (count($choices_li) < $posts_per_page) {
			$hide_class = 'hide';
		}

		return sprintf(
			'<div class="%s" data-post-types="" data-limit="0">
				<div class="%s">
					<input type="text" class="widefat search-input search-input-post-types" name="search-input" placeholder="%s" />
				</div>
				<div class="%s">
					<ul>%s</ul>
					<p class="load-relation-items load-relation-post-types %s">%s</p>
					<input type="hidden" name="%s" %s />
					<input type="hidden" id="smart-cf-field-name-data" data-js="' . $name . '" />
					%s
				</div>
			</div>
			<div class="%s"><ul>%s</ul></div>
			<div class="clear"></div>',
			SCF_Config::PREFIX . 'relation-left',
			SCF_Config::PREFIX . 'search',
			esc_attr__('Search...', 'smart-custom-fields'),
			SCF_Config::PREFIX . 'relation-children-select',
			implode('', $choices_li),
			esc_attr($hide_class),
			esc_html__('Load more', 'smart-custom-fields'),
			esc_attr($name),
			disabled(true, $disabled, false),
			implode('', $hidden),
			SCF_Config::PREFIX . 'relation-right',
			implode('', $selected_li)
		);
	}

	/**
	 * Displaying the option fields in custom field settings page.
	 *
	 * @param int $group_key Group key.
	 * @param int $field_key Field key.
	 */
	public function display_field_options($group_key, $field_key)
	{
		$this->display_label_option($group_key, $field_key);
		$this->display_name_option($group_key, $field_key);
?>
		<tr>
			<th><?php esc_html_e('Instruction', 'smart-custom-fields'); ?></th>
			<td>
				<textarea name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'instruction')); ?>"
					class="widefat" rows="5"><?php echo esc_attr($this->get('instruction')); ?></textarea>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e('Notes', 'smart-custom-fields'); ?></th>
			<td>
				<input type="text"
					name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'notes')); ?>"
					class="widefat"
					value="<?php echo esc_attr($this->get('notes')); ?>" />
			</td>
		</tr>
<?php
	}

	/**
	 * Validating when displaying meta data.
	 *
	 * @param array  $value      The value.
	 * @param string $field_type Field type.
	 * @return array
	 */
	public function validate_get_value($value, $field_type)
	{
		if ($field_type === $this->get_attribute('type')) {
			$validated_value = array();
			foreach ($value as $setting_id) {
				$validated_value[] = $setting_id;
			}
			$value = $validated_value;
		}
		return $value;
	}
}

// Hook into the SCF fields-loaded action to register our custom field
add_action('smart-cf-fields-loaded', function () {
	new Smart_Custom_Fields_Field_Sections();
});
