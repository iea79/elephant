<?php

class Smart_Custom_Fields_Field_Number extends Smart_Custom_Fields_Field_Base
{
	/**
	 * Set the required items
	 *
	 * @return array
	 */
	protected function init()
	{
		return array(
			'type'         => 'number',
			'display-name' => 'Число',
			'optgroup'     => 'basic-fields',
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
			'min'     => 0,
			'max'     => 10,
			'step'     => 1,
			'default'     => '',
			'instruction' => '',
			'notes'       => '',
		);
	}

	/**
	 * Getting the field
	 *
	 * @param int $index
	 * @param string $value
	 * @return string html
	 */
	public function get_field($index, $value)
	{
		$name     = $this->get_field_name_in_editor($index);
		$disabled = $this->get_disable_attribute($index);
		$min	  = $this->get('min');
		$max	  = $this->get('max');
		$step	  = $this->get('step');
		return sprintf(
			'<input type="number" name="%s" value="%s" class="widefat" %s min="%s" max="%s" step="%s" style="max-width: 150px" />',
			esc_attr($name),
			esc_attr($value),
			disabled(true, $disabled, false),
			esc_attr($min),
			esc_attr($max),
			esc_attr($step),
		);
	}

	public function display_field_options($group_key, $field_key)
	{
		$this->display_label_option($group_key, $field_key);
		$this->display_name_option($group_key, $field_key);
?>
		<tr>
			<th><?php esc_html_e('Default', 'smart-custom-fields'); ?></th>
			<td>
				<input type="text"
					name="<?php echo esc_attr($this->get_field_name_in_setting($group_key, $field_key, 'default')); ?>"
					class="widefat"
					value="<?php echo esc_attr($this->get('default')); ?>" />
			</td>
		</tr>
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
}

function scf_add_number_field()
{
	new Smart_Custom_Fields_Field_Number();
}

add_action('init', 'scf_add_number_field');
