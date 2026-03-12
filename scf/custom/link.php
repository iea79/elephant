<?php
class Smart_Custom_Fields_Field_Link extends Smart_Custom_Fields_Field_Base
{
	/**
	 * Основные параметры поля.
	 *
	 * @return array
	 */
	protected function init()
	{
		return array(
			'type'         => 'link',
			'display-name' => __('Ссылка с текстом', 'smart-custom-fields'),
			'optgroup'     => 'other-fields',
			'allow-multiple-data' => true,
		);
	}

	/**
	 * Дополнительные опции поля.
	 *
	 * @return array
	 */
	protected function options()
	{
		return array(
			'instruction'      => '',
			'notes'            => '',
			'default_text'     => '',
			'default_url'      => '',
			'new_window'       => false,
			'placeholder_url'  => 'https://',
			'placeholder_text' => __('Текст ссылки', 'smart-custom-fields'),
			'css_class' 	   => '',
			'css_classes' 	   => array(
				'',
				'primary',
				'secondary',
				'success',
				'danger',
				'warning',
				'info',
			),
		);
	}

	/**
	 * Генерация HTML поля в редакторе.
	 *
	 * @param int $index
	 * @param mixed $value
	 * @return string
	 */
	public function get_field($index, $value)
	{
		$name     = $this->get_field_name_in_editor($index);
		$disabled = $this->get_disable_attribute($index);

		// Распаковываем значение
		$link_text    = '';
		$link_url     = '';
		$new_window   = false;
		$css_class	  = '';
		$css_classes  = $this->get('css_classes');

		if (!empty($value) && is_array($value)) {
			$link_text    = !empty($value[0]) ? $value[0] : $this->get('default_text');
			$link_url     = !empty($value[1]) ? $value[1] : $this->get('default_url');
			$css_class	  = !empty($value[2]) ? $value[2] : $this->get('css_class');
			$new_window   = !empty($value[3]);
		} elseif (!empty($value) && is_string($value)) {
			$link_text = $value;
		}

		$field_id = 'scf-link-' . $index;

		$options = '';
		foreach ($css_classes as $class) {
			$options .= '<option value="' . esc_attr($class) . '" ' . esc_attr($css_class === $class ? 'selected' : '') . '>' . esc_html($class) . '</option>';
		}

		$html = '
		<div class="scf-link-wrapper" id="' . esc_attr($field_id) . '">
			<div style="margin-bottom: 10px;">
				<label style="display:block; margin-bottom:4px; font-size:13px;">Текст ссылки</label>
				<input type="text"
					class="widefat"
					name="' . $name . '[0]"
					value="' . esc_attr($link_text) . '"
					placeholder="' . esc_attr($this->get('placeholder_text')) . '"
					' . disabled(true, $disabled, false) . ' />
			</div>
			<div style="margin-bottom: 10px;">
				<label style="display:block; margin-bottom:4px; font-size:13px;">URL</label>
				<input type="url"
					class="widefat"
					name="' . $name . '[1]"
					value="' . esc_attr($link_url) . '"
					placeholder="' . esc_attr($this->get('placeholder_url')) . '"
					' . disabled(true, $disabled, false) . ' />
			</div>
			<div style="margin-bottom: 4px; max-width: 250px">
				<label style="display:block; margin-bottom:4px; font-size:13px;">Класс CSS </label>
				<select 
					name="' . $name . '[2]" 
					value="' . esc_attr($css_class) . '"
					class="widefat"
					' . disabled(true, $disabled, false) . '>
					' . $options . '
				</select>
			</div>
			<div style="margin: 0 0 10px;" class="description">' . __('Для оформления в виде кнопки будет использован класс. Например: btn_primary', 'smart-custom-fields') . '</div>
			<label>
				<input type="checkbox"
					name="' . $name . '[3]"
					value="1"
					' . checked($new_window, true, false) . '
					' . disabled(true, $disabled, false) . ' /> Открывать в новой вкладке
			</label>
		</div>';

		return $html;
	}

	/**
	 * Отображение опций в настройках поля.
	 *
	 * @param int $group_key
	 * @param int $field_key
	 */
	public function display_field_options($group_key, $field_key)
	{
		$this->display_label_option($group_key, $field_key);
		$this->display_name_option($group_key, $field_key);
?>
		<tr>
			<th><?php __('Инструкция', 'smart-custom-fields'); ?></th>
			<td>
				<textarea name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'instruction')); ?>"
					class="widefat" rows="3"><?php echo esc_textarea($this->get('instruction')); ?></textarea>
			</td>
		</tr>
		<tr>
			<th><?php __('Текст по умолчанию', 'smart-custom-fields'); ?></th>
			<td>
				<input type="text"
					name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'default_text')); ?>"
					class="widefat"
					value="<?php echo esc_attr($this->get('default_text')); ?>"
					placeholder="Например: Узнать больше" />
			</td>
		</tr>
		<tr>
			<th><?php __('Ссылка по умолчанию', 'smart-custom-fields'); ?></th>
			<td>
				<input type="url"
					name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'default_url')); ?>"
					class="widefat"
					value="<?php echo esc_attr($this->get('default_url')); ?>"
					placeholder="https://example.com" />
			</td>
		</tr>
		<tr>
			<th><?php __('Класс CSS', 'smart-custom-fields'); ?></th>
			<td>
				<input type="text"
					name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'css_class')); ?>"
					class="widefat"
					value="<?php echo esc_attr($this->get('css_class')); ?>" />
			</td>
		</tr>
		<tr>
			<th><?php __('Открывать в новой вкладке', 'smart-custom-fields'); ?></th>
			<td>
				<label>
					<input type="checkbox"
						name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'new_window')); ?>"
						value="1"
						<?php checked($this->get('new_window'), true); ?> />
					Включить по умолчанию
				</label>
			</td>
		</tr>
		<tr>
			<th><?php __('Заметки', 'smart-custom-fields'); ?></th>
			<td>
				<input type="text"
					name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'notes')); ?>"
					class="widefat"
					value="<?php echo esc_attr($this->get('notes')); ?>" />
			</td>
		</tr>
<?php
	}
}

/**
 * Регистрация поля
 */
function scf_add_link_field()
{
	new Smart_Custom_Fields_Field_Link();
}
add_action('init', 'scf_add_link_field');

function getSCFLink($link, $custom_class = '')
{
	// var_dump($link, $custom_class);
	if ($link) {
		$text = !empty($link[0]) ? $link[0] : '';
		$url  = esc_url($link[1]);
		$css_class = !empty($link[2]) ? ' class="btn btn_' . esc_attr($link[2]) . '"' : '';
		$target = isset($link[3]) && !empty($link[3]) ? ' target="_blank" rel="noopener"' : '';

		if (!empty($custom_class)) {
			if ($css_class) {
				$css_class .= ' ' . esc_attr($custom_class);
			} else {
				$css_class = ' class="' . esc_attr($custom_class) . '"';
			}
		}

		if ($text) {
			echo sprintf('<a href="%s" %s %s>%s</a>', $url, $target, $css_class, esc_html($text));
		}
	}
}
