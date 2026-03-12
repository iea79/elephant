<?php

/**
 * Customizer HR control
 *
 * @package frondendie
 */

if (! class_exists('WP_Customize_Control')) {
	return;
}

/**
 * HR control
 *
 * Этот контрол добавляет визуальный разделитель (тег hr) в настройки кастомайзера.
 *
 * Пример использования в конфигурации кастомайзера:
 * ```php
 * 'section_separator' => array(
 *     'control' => array(
 *         'label' => '', // Метка если нужен заголовок разделителя
 *         'type' => 'hr',
 *     ),
 * ),
 * ```
 *
 * @since 1.0.0
 */
class Customize_Control_HR extends WP_Customize_Control
{

	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'hr';

	/**
	 * Render the control in the customizer
	 */
	public function render_content()
	{
		$title = $this->label;
?>
		<div style="margin: 15px 0 0; display: flex; align-items: center; gap: 10px">
			<?php
			if ($title) {
				echo '<h3 style="margin:0">' . $title . '</h3>';
			}
			?>
			<hr style="border: 0; border-top: 1px solid #b8a7a7; flex-grow: 1; min-width: 0">
		</div>
<?php
	}
}
