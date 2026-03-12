<?php

define('SHOW_FOOTER_REVIEW', true);
define('SHOW_FOOTER_ACTION', true);
define('SHOW_FOOTER_LOGO', true);
define('SHOW_FOOTER_MENU', false);
define('FOOTER_CONTACT_TITLE', 'Контакты');
define('SHOW_FOOTER_ADDRESS', false);
define('FOOTER_ADDRESS_TITLE', 'Адрес');
define('SHOW_FOOTER_SOCIAL', false);
define('SHOW_FOOTER_COPYRIGHT', true);
define('FOOTER_COPYRIGHT_TEXT', 'Все права защищены ');
define('SHOW_FOOTER_POLICY', false);
define('SHOW_FOOTER_ACCEPT_POPUP', true);
define('FOOTER_ACCEPT_POPUP_TEXT', '');
define('POLICY_POPUP_BUTTON_TEXT', 'Ok');
define('SITEMAP_SHOW', false);
define('SITEMAP_LINK', '/sitemap.xml');
define('SITEMAP_LINK_TEXT', 'Карта сайта');
define('FOOTER_ACTION_TITLE', 'Готовы запустить свой следующий проект?');
define('FOOTER_ACTION_TEXT', 'Благодаря множеству уникальных блоков вы можете легко создать страницу без программирования. Создайте следующую целевую страницу.');
define('FOOTER_ACTION_LINK', '#');
define('FOOTER_ACTION_LINK_TEXT', 'Начать проект');

/**
 * Get footer settings section configuration
 *
 * @return array
 */
function get_footer_settings_section()
{
	return array(
		'title' => 'Подвал сайта',
		'description' => 'Настройка футера сайта',
		'controls' => array(
			'show_footer_logo' => array(
				'default' => SHOW_FOOTER_LOGO,
				'control' => array(
					'label' => 'Показать логотип',
					'type' => 'checkbox',
				),
			),
			// 'footer_hr1' => array(
			// 	'control' => array(
			// 		'label' => 'Настройки меню',
			// 		'type' => 'hr',
			// 	),
			// ),
			// 'show_footer_menu' => array(
			// 	'default' => SHOW_FOOTER_MENU,
			// 	'control' => array(
			// 		'label' => 'Показать меню',
			// 		'type' => 'checkbox',
			// 	),
			// ),
			// 'footer_contact_title' => array(
			// 	'default' => FOOTER_CONTACT_TITLE,
			// 	'control' => array(
			// 		'label' => 'Заголовок колонки с контактами',
			// 		'type' => 'text',
			// 	),
			// ),
			// 'show_footer_address' => array(
			// 	'default' => SHOW_FOOTER_ADDRESS,
			// 	'control' => array(
			// 		'label' => 'Показать адрес',
			// 		'type' => 'checkbox',
			// 	),
			// ),
			// 'footer_address_title' => array(
			// 	'default' => FOOTER_ADDRESS_TITLE,
			// 	'control' => array(
			// 		'label' => 'Заголовок с адресом и соцсетями',
			// 		'type' => 'text',
			// 	),
			// ),
			'show_footer_social' => array(
				'default' => SHOW_FOOTER_SOCIAL,
				'control' => array(
					'label' => 'Показать социальные сети',
					'type' => 'checkbox',
				),
			),
			'footer_hr2' => array(
				'control' => array(
					'label' => 'Нижняя часть подвала',
					'type' => 'hr',
				),
			),
			'show_footer_copyright' => array(
				'default' => SHOW_FOOTER_COPYRIGHT,
				'control' => array(
					'label' => 'Показать копирайт',
					'type' => 'checkbox',
				),
			),
			'footer_copyright_text' => array(
				'default' => FOOTER_COPYRIGHT_TEXT,
				'control' => array(
					'label' => 'Текст копирайта (символ и год подставляются автоматически)',
					'type' => 'text',
				),
			),
			'show_footer_policy' => array(
				'default' => SHOW_FOOTER_POLICY,
				'control' => array(
					'label' => 'Показать ссылку политики конфиденциальности',
					'description' => 'Показывает ссылку на страницу политики конфиденциальности если она настроена',
					'type' => 'checkbox',
				),
			),
			'show_footer_accept_popup' => array(
				'default' => SHOW_FOOTER_ACCEPT_POPUP,
				'control' => array(
					'label' => 'Показать всплывающее окно принятия cookie',
					'type' => 'checkbox',
				),
			),
			'footer_accept_popup_text' => array(
				'default' => FOOTER_ACCEPT_POPUP_TEXT,
				'control' => array(
					'label' => 'Текст всплывающего окна принятия cookie',
					'type' => 'tinymce',
				),
			),
			'policy_popup_button_text' => array(
				'default' => POLICY_POPUP_BUTTON_TEXT,
				'control' => array(
					'label' => 'Текст кнопки окна принятия cookie',
					'type' => 'text',
				),
			),
			// 'sitemap_show' => array(
			// 	'default' => SITEMAP_SHOW,
			// 	'control' => array(
			// 		'label' => 'Показывать карту сайта',
			// 		'type' => 'checkbox',
			// 	),
			// ),
			// 'sitemap_link' => array(
			// 	'default' => SITEMAP_LINK,
			// 	'control' => array(
			// 		'label' => 'Ссылка на карту сайта',
			// 		'type' => 'text',
			// 	),
			// ),
			// 'sitemap_text' => array(
			// 	'default' => SITEMAP_LINK_TEXT,
			// 	'control' => array(
			// 		'label' => 'Текст ссылки на карту сайта',
			// 		'type' => 'text',
			// 	),
			// ),
		)
	);
}
