<?php

/**
 * Файл для вывода способов оплаты
 *
 * Пример использования:
 * ```php
 * include_once get_template_directory() . '/inc/payment-methods.php';
 * ```
 *
 * Или с использованием функции:
 * ```php
 * display_payment_methods();
 * ```
 *
 * @package frondendie
 */

/**
 * Выводит способы оплаты в виде спанов с классом sws_icon_{ключ}
 */
function display_payment_methods()
{
	// Получаем список способов оплаты из настроек
	$payment_methods = get_theme_mod('payment_methods', array());

	// Если список пуст, выходим
	if (empty($payment_methods)) {
		return;
	}


	// Если значение пришло в виде JSON строки, декодируем его
	if (is_string($payment_methods)) {
		$decoded = json_decode($payment_methods, true);
		if (is_array($decoded)) {
			$payment_methods = $decoded;
		}
	}

?>
	<div class="paymentMethods">
		<div class="footer__title">Способы оплаты</div>
		<div class="paymentMethods__list">
			<?php foreach ($payment_methods as $method) : ?>
				<?php if (isset($method['key'])) : ?>
					<span class="sws_icon_<?php echo esc_attr($method['key']); ?>" title="<?php echo esc_attr($method['label']); ?>"></span>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
<?php
}

// Автоматический вывод способов оплаты
display_payment_methods();
?>