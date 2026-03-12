<?php

/**
 * Multiple checkbox customize control class.
 *
 * @since  1.0.0
 * @access public
 */
class Customize_Control_Checkbox_Multiple extends WP_Customize_Control
{

	/**
	 * The type of customize control being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'checkbox-multiple';

	/**
	 * Enqueue scripts/styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue()
	{
		wp_enqueue_script('customize-controls', get_template_directory_uri() . '/js/customize-controls.js', array('jquery'), '1.0.0', true);
	}

	/**
	 * Displays the control content.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function render_content()
	{
		if (empty($this->choices))
			return; ?>

		<?php if (!empty($this->label)) : ?>
			<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
		<?php endif; ?>

		<?php if (!empty($this->description)) : ?>
			<span class="description customize-control-description"><?php echo $this->description; ?></span>
		<?php endif; ?>

		<?php
		$value = $this->value();
		if (is_string($value)) {
			$decoded = json_decode($value, true);
			if (is_array($decoded)) {
				// Извлекаем ключи из массива объектов
				$multi_values = array();
				foreach ($decoded as $item) {
					if (is_array($item) && isset($item['key'])) {
						$multi_values[] = $item['key'];
					} elseif (is_string($item)) {
						$multi_values[] = $item;
					}
				}
			} else {
				$multi_values = explode(',', $value);
			}
		} else {
			$multi_values = (array) $value;
		}
		?>

		<ul class="customize-control-multy-choice">
			<?php foreach ($this->choices as $value => $label) : ?>
				<li>
					<label>
						<input type="checkbox" value="<?php echo esc_attr($value); ?>" <?php checked(in_array($value, $multi_values)); ?> data-label="<?php echo esc_attr($label); ?>" />
						<?php echo esc_html($label); ?>
					</label>
				</li>

			<?php endforeach; ?>
			<li style="display: none;">
				<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr(wp_json_encode($multi_values)); ?>" />
			</li>
		</ul>
		<style>
			.customize-control-multy-choice {
				display: flex;
				gap: 10px;
				flex-wrap: wrap;
			}
		</style>
<?php }
}

function sanitize_checkbox_multiple($values)
{
	// Если значения пришли в виде JSON строки, декодируем их
	if (is_string($values)) {
		$decoded = json_decode($values, true);
		if (is_array($decoded)) {
			$multi_values = array();
			foreach ($decoded as $item) {
				if (is_array($item) && isset($item['key'])) {
					$multi_values[] = $item;
				} elseif (is_string($item)) {
					$multi_values[] = $item;
				}
			}
		} else {
			$multi_values = explode(',', $values);
		}
	} else {
		$multi_values = (array) $values;
	}

	// Удаляем пустые значения
	$multi_values = array_filter($multi_values);

	return $multi_values;
}

// Регистрируем санитайзер для настройки payment_methods
add_action('customize_register', function ($wp_customize) {
	$setting = $wp_customize->get_setting('payment_methods');
	if ($setting) {
		$setting->sanitize_callback = 'sanitize_checkbox_multiple';
	}
}, 999);
