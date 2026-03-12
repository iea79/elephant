<?php

/**
 * Customizer Button control
 *
 * @package frondendie
 */

if (! class_exists('WP_Customize_Control')) {
	return;
}

/**
 * Button control
 *
 * Этот контрол добавляет кнопку в настройки кастомайзера.
 *
 * Пример использования в конфигурации кастомайзера:
 * ```php
 * 'export_button' => array(
 *     'control' => array(
 *         'label' => 'Экспорт настроек',
 *         'type' => 'button',
 *         'description' => 'Скачать текущие настройки темы в формате JSON',
 *         'input_attrs' => array(
 *             'value' => 'Экспортировать настройки',
 *             'class' => 'button button-secondary',
 *             'onclick' => 'window.location.href=\'' . admin_url('admin-post.php?action=sws_export_customizer') . '\'',
 *         ),
 *     ),
 * ),
 * ```
 *
 * @since 1.0.0
 */
class Customize_Control_Button extends WP_Customize_Control
{

	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'button';

	/**
	 * Render the control in the customizer
	 */
	public function render_content()
	{
		$input_id = '_customize-input-' . $this->id;
		$description_id = '_customize-description-' . $this->id;
		$description = $this->description;
		$input_attrs = $this->input_attrs;

		// Устанавливаем значения по умолчанию
		$input_attrs['type'] = 'button';
		$input_attrs['id'] = $input_id;
		$input_attrs['value'] = isset($input_attrs['value']) ? $input_attrs['value'] : $this->label;

		if (! isset($input_attrs['class'])) {
			$input_attrs['class'] = 'button button-secondary';
		}
?>
		<?php if (! empty($this->label)) : ?>
			<label for="<?php echo esc_attr($input_id); ?>" class="customize-control-title"><?php echo esc_html($this->label); ?></label>
		<?php endif; ?>
		<?php if (! empty($description)) : ?>
			<span id="<?php echo esc_attr($description_id); ?>" class="description customize-control-description"><?php echo $description; ?></span>
		<?php endif; ?>
		<div class="customize-control-content">
			<input
				<?php
				foreach ($input_attrs as $attr => $value) {
					echo esc_attr($attr) . '="' . esc_attr($value) . '" ';
				}
				?>>
		</div>
<?php
	}
}
