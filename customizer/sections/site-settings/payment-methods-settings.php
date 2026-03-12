<?php

/**
 * Get payment methods settings section configuration
 *
 * @return array
 */
function get_payment_methods_settings_section()
{
	return array(
		'title' => 'Способы платежа',
		'description' => 'Выберите доступные способы оплаты',
		'controls' => array(
			'payment_methods' => array(
				'default' => array('mir', 'sbp'),
				'control' => array(
					'label' => 'Способы оплаты',
					'type' => 'checkbox-multiple',
					'choices' => array(
						'mir' => 'МИР',
						'sbp' => 'СБП',
						'yoomoney' => 'Юмани',
						'visa' => 'Visa',
						'mc' => 'Master Card',
						'paypal' => 'PayPal',
						'bitcoin' => 'Bitcoin',
						'ethereum' => 'Ethereum',
					),
				),
			),
		)
	);
}
