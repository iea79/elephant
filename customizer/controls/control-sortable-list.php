<?php

/**
 * Customizer sortable list control
 *
 * @package frondendie
 */

if (! class_exists('WP_Customize_Control')) {
	return;
}

/**
 * Sortable list control
 *
 * Этот контрол позволяет пользователю выбирать элементы из списка и менять их порядок перетаскиванием.
 *
 * Пример использования в конфигурации кастомайзера:
 * ```php
 * 'socials_list' => array(
 *     'default' => 'tg,vk',
 *     'transport' => 'refresh',
 *     'control' => array(
 *         'label' => 'Социальные сети',
 *         'type' => 'sortable-list',
 *         'choices' => array(
 *             'vk' => 'ВКонтакте',
 *             'tg' => 'Telegram',
 *             'ok' => 'Одноклассники',
 *             'max' => 'Max',
 *             'rutube' => 'Rutube',
 *             'wa' => 'WhatsApp',
 *             'tw' => 'Twitter',
 *             'yt' => 'YouTube',
 *             'inst' => 'Instagram',
 *             'fb' => 'Facebook',
 *         ),
 *     ),
 * ),
 * ```
 *
 * Для получения значений в новом формате:
 * ```php
 * $socials_list = get_theme_mod('socials_list', array());
 *
 * // Убедимся, что это массив
 * if (!is_array($socials_list)) {
 *     $socials_list = json_decode($socials_list, true);
 * }
 *
 * foreach ($socials_list as $social) {
 *     $key = isset($social['key']) ? $social['key'] : '';
 *     $label = isset($social['label']) ? $social['label'] : '';
 *     $url = isset($social['url']) ? $social['url'] : '';
 *     echo '<a href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
 * }
 * ```
 *
 * @since 1.0.0
 */
class Customize_Control_Sortable_List extends WP_Customize_Control
{

	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'sortable-list';

	/**
	 * Enqueue scripts and styles
	 */
	public function enqueue()
	{
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_style('customize-controls-sortable-list', get_template_directory_uri() . '/css/customize-controls.css');
	}

	/**
	 * Render the control in the customizer
	 */
	public function render_content()
	{
		if (empty($this->choices)) {
			return;
		}

		// Ensure choices is an array
		if (!is_array($this->choices)) {
			$this->choices = array();
		}

		// Get saved values
		$saved_values = $this->value();
		if (is_string($saved_values)) {
			// Try to decode as JSON first (new format)
			$decoded = json_decode($saved_values, true);
			if (is_array($decoded)) {
				$saved_values = $decoded;
			} else {
				// Fallback to old format
				$saved_values = explode(',', $saved_values);
			}
		}

		// Convert old format to new format if needed
		if (!empty($saved_values) && is_array($saved_values) && !isset($saved_values[0]['key'])) {
			$new_format = array();
			foreach ($saved_values as $value) {
				if (is_string($value) && isset($this->choices[$value])) {
					$new_format[] = array(
						'key' => $value,
						'label' => $this->choices[$value],
						'url' => ''
					);
				}
			}
			$saved_values = $new_format;
		}

		// Create array with saved values in correct order
		$ordered_choices = array();
		if (!empty($saved_values) && is_array($saved_values)) {
			foreach ($saved_values as $value) {
				if (is_array($value) && isset($value['key']) && isset($this->choices[$value['key']])) {
					$ordered_choices[$value['key']] = $this->choices[$value['key']];
					unset($this->choices[$value['key']]);
				} elseif (is_string($value) && isset($this->choices[$value])) {
					$ordered_choices[$value] = $this->choices[$value];
					unset($this->choices[$value]);
				}
			}
		}

		// Add remaining choices
		if (is_array($this->choices)) {
			foreach ($this->choices as $value => $label) {
				$ordered_choices[$value] = $label;
			}
		}
?>
		<div class="sortable-list-control">
			<?php if (! empty($this->label)) : ?>
				<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
			<?php endif; ?>

			<?php if (! empty($this->description)) : ?>
				<span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
			<?php endif; ?>

			<ul class="sortable-list">
				<?php foreach ($ordered_choices as $value => $label) : ?>
					<?php
					// Find saved item data
					$item_data = array();
					if (!empty($saved_values) && is_array($saved_values)) {
						foreach ($saved_values as $item) {
							if (is_array($item) && isset($item['key']) && $item['key'] === $value) {
								$item_data = $item;
								break;
							}
						}
					}
					?>
					<li class="sortable-list-item" data-value="<?php echo esc_attr($value); ?>">
						<span class="dashicons dashicons-move"></span>
						<label>
							<input type="checkbox"
								value="<?php echo esc_attr($value); ?>"
								<?php checked(!empty($item_data)); ?> />
							<?php echo esc_html($label); ?>
						</label>
						<input type="text"
							class="sortable-list-item-link"
							placeholder="Введите ссылку"
							value="<?php echo isset($item_data['url']) ? esc_attr($item_data['url']) : ''; ?>"
							data-key="<?php echo esc_attr($value); ?>" />
					</li>
				<?php endforeach; ?>
			</ul>

			<input type="hidden"
				id="<?php echo esc_attr($this->id); ?>"
				name="<?php echo esc_attr($this->id); ?>"
				value="<?php echo esc_attr(wp_json_encode($saved_values)); ?>"
				<?php $this->link(); ?> />
		</div>
<?php
	}
}
