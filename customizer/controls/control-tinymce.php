<?php

/**
 * Customizer TinyMCE (WYSIWYG) control.
 *
 * @package frondendie
 */

if (! class_exists('WP_Customize_Control')) {
	return;
}

/**
 * TinyMCE control for Customizer.
 *
 * Пример использования в конфигурации кастомайзера:
 *
 * 'custom_text' => array(
 *     'default'   => '',
 *     'transport' => 'refresh',
 *     'control'   => array(
 *         'label'       => 'Текст',
 *         'description' => 'Произвольный текст с форматированием',
 *         'type'        => 'tinymce',
 *     ),
 * ),
 */
class Customize_Control_TinyMCE extends WP_Customize_Control
{
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'tinymce';

	/**
	 * Render control content.
	 */
	public function render_content()
	{
		$textarea_id = '_customize-tinymce-' . esc_attr($this->id);
		?>
		<?php if (! empty($this->label)) : ?>
			<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
		<?php endif; ?>

		<?php if (! empty($this->description)) : ?>
			<span class="description customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
		<?php endif; ?>

		<div class="customize-control-content customize-control-tinymce">
			<textarea
				id="<?php echo esc_attr($textarea_id); ?>"
				class="customize-tinymce-editor"
				rows="8"
				<?php $this->link(); ?>
			><?php echo esc_textarea($this->value()); ?></textarea>
		</div>
		<?php
	}
}

