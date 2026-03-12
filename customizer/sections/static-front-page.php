<?php

/**
 * Get static front page controls
 *
 * @return array
 */

function get_static_front_page_controls()
{
	$transport = 'refresh';

	return [
		'title' => 'Статические страницы',
		'description' => 'Настройки для статических страниц',
		'priority' => 20,
		'controls' => array(
			'posts_per_page' => array(
				'default' => get_option('posts_per_page'),
				'transport' => $transport,
				'control' => array(
					'priority' => 10,
					'label' => 'Количество записей на странице блога',
					'type' => 'number',
					'input_attrs' => array(
						'min' => 1,
						'max' => 100,
						'step' => 1,
					),
				),
			),
			'static_hr1' => array(
				'control' => array(
					'priority' => 29,
					'type' => 'hr',
				),
			),
			'portfolio_page' => array(
				'default' => null,
				'transport' => $transport,
				'control' => array(
					'priority' => 30,
					'label' => 'Страница портфолио',
					'type' => 'dropdown-pages',
				),
			),
			'portfolio_per_page' => array(
				'default' => 10,
				'transport' => $transport,
				'control' => array(
					'priority' => 31,
					'label' => 'Количество работ на архивной странице',
					'type' => 'number',
					'input_attrs' => array(
						'min' => 1,
						'max' => 100,
						'step' => 1,
					),
				),
			),
			'favorites_page' => array(
				'default' => null,
				'transport' => $transport,
				'control' => array(
					'priority' => 32,
					'label' => 'Страница избранного',
					'type' => 'dropdown-pages',
				),
			),
			'offer_page' => array(
				'default' => null,
				'transport' => $transport,
				'control' => array(
					'priority' => 33,
					'label' => 'Страница оферты',
					'type' => 'dropdown-pages',
				),
			),
			'policy_page' => array(
				'default' => null,
				'transport' => $transport,
				'control' => array(
					'priority' => 34,
					'label' => 'Страница соглашения / политики обработки данных',
					'type' => 'dropdown-pages',
				),
			),
		)
	];
}
