<?php
if (! defined('HOME_ID')) {
	// $homeId = get_option('page_on_front');
	define('HOME_ID', null);
}
// echo HOME_ID;

function home_first_section_fields($settings, $type, $id, $meta_type, $types)
{
	if (HOME_ID == $id) {

		$Section = SCF::add_setting('home-1', 'Секция 1');

		$Section->add_group(
			'first-section',
			false,
			array(
				array(
					'name'        => 'home__title',
					'label'       => 'Заголовок',
					'type'        => 'text',
				),
				array(
					'name'        => 'home__sub',
					'label'       => 'Подзаголовок',
					'type'        => 'textarea',
				),
				array(
					'name'        => 'home__button',
					'label'       => 'Ссылка',
					'type'        => 'link',
				),
				array(
					'name'        => 'home__notiffication',
					'label'       => 'Текст после кнопки',
					'type'        => 'text',
				),
				array(
					'name'        => 'home__image',
					'label'       => 'Изображение или видео',
					'type'        => 'media',
					'size'        => 'full',
				),
				array(
					'name'        => 'home__text',
					'label'       => 'Текст после изображения',
					'type'        => 'text',
				),
				array(
					'name'        => 'homeAdv_list__image',
					'label'       => 'Иконка в списке по умолчанию',
					'type'        => 'image',
					'size'        => 'thumbnail',
					'notes'	=> 'Если не указано для элементов в списке, будет использоваться по умолчанию'
				),
			)
		);

		$Section->add_group(
			'homeAdv',
			true,
			array(
				array(
					'name'		=> 'homeAdv__icon',
					'label'	   => 'Иконка в списке',
					'type'		=> 'image',
					'size'		=> 'thumbnail',
				),
				array(
					'name'		=> 'homeAdv__text',
					'label'	   => 'Текст в списке',
					'type'		=> 'text',
				),
			)
		);

		$settings[] = $Section;
	}

	return $settings;
}
add_filter('smart-cf-register-fields', 'home_first_section_fields', 1, 5);

function home_start_section_fields($settings, $type, $id, $meta_type, $types)
{
	if (HOME_ID == $id) {

		$Section = SCF::add_setting('home-2', 'Секция 2');

		$Section->add_group(
			'start-section',
			false,
			array(
				array(
					'name'        => 'automated_title',
					'label'       => 'Заголовок',
					'type'        => 'text',
				),
				array(
					'name'        => 'automated_text',
					'label'       => 'Подзаголовок',
					'type'        => 'textarea',
				),
				array(
					'name'        => 'automated_link',
					'label'       => 'Ссылка',
					'type'        => 'link',
				),
				array(
					'name'        => 'automated_background',
					'label'       => 'Изображение',
					'type'        => 'image',
					'size'        => 'full',
				),
			)
		);

		$settings[] = $Section;
	}

	return $settings;
}
add_filter('smart-cf-register-fields', 'home_start_section_fields', 2, 5);

function home_risk_section_fields($settings, $type, $id, $meta_type, $types)
{
	if (HOME_ID == $id) {

		$Section = SCF::add_setting('home-3', 'Секция 3');

		$Section->add_group(
			'risk-section',
			false,
			array(
				array(
					'name'		=> 'home_risk_title',
					'label'	   => 'Заголовок',
					'type'		=> 'text',
				),
				array(
					'name'		=> 'home_risk_description',
					'label'	   => 'Подзаголовок',
					'type'		=> 'textarea',
				),
			)
		);

		$Section->add_group(
			'home_risk_list',
			true,
			array(
				array(
					'name'		=> 'home_risk_list_ttile',
					'label'	   => 'Заголовок',
					'type'		=> 'text',
				),
				array(
					'name'		=> 'home_risk_list_text',
					'label'	   => 'Описание',
					'type'		=> 'textarea',
				),
				array(
					'name'		=> 'home_risk_list_img',
					'label'	   => 'Изображение',
					'type'		=> 'image',
					'size'		=> 'medium',
				),
				array(
					'name'		=> 'home_risk_page_link',
					'label'	   => 'Ссылка',
					'type'		=> 'link',
				),
				// array(
				// 	'type'        => 'relation',
				// 	'name'		=> 'home_risk_page_link',
				// 	'label'       => 'Ссылка на страницу',
				// 	'post-type'   => array('post', 'page'),
				// 	'limit'       => 1,
				// ),
			)
		);

		$settings[] = $Section;
	}

	return $settings;
}
add_filter('smart-cf-register-fields', 'home_risk_section_fields', 3, 5);

function home_test_section_fields($settings, $type, $id, $meta_type, $types)
{
	if (HOME_ID == $id) {

		$Section = SCF::add_setting('home-4', 'Секция 4');

		$Section->add_group(
			'section',
			false,
			array(
				array(
					'name'		=> 'home_text_before_book',
					'label'	   => 'Текст перед кнопкой',
					'type'		=> 'wysiwyg',
				),
				array(
					'name'		=> 'home_book_link',
					'label'	   => 'Кнопка',
					'type'		=> 'link',
				),
			)
		);

		$settings[] = $Section;
	}

	return $settings;
}
add_filter('smart-cf-register-fields', 'home_test_section_fields', 4, 5);

function home_five_section_fields($settings, $type, $id, $meta_type, $types)
{
	if (HOME_ID == $id) {

		$Section = SCF::add_setting('home-5', 'Секция 5');

		$Section->add_group(
			'section',
			false,
			array(
				array(
					'name'		=> 'home_predictive_title',
					'label'	   => 'Заголовок',
					'type'		=> 'text',
				),
				array(
					'name'		=> 'home_predictive_text',
					'label'	   => 'Описание',
					'type'		=> 'wysiwyg',
				),
			)
		);

		$Section->add_group(
			'home_predictive_point',
			true,
			array(
				array(
					'name'		=> 'home_advantages_item_title',
					'label'	   => 'Заголовок',
					'type'		=> 'text',
				),
				array(
					'name'		=> 'home_advantages_item_text',
					'label'	   => 'Описание',
					'type'		=> 'wysiwyg',
				),
				array(
					'name'		=> 'home_advantages_item_image',
					'label'	   => 'Изображение',
					'type'		=> 'image',
					'size'		=> 'thumbs',
				),
			)
		);

		$Section->add_group(
			'home_predictive_list',
			true,
			array(
				array(
					'name'		=> 'home_predictive_list_title',
					'label'	   => 'Заголовок',
					'type'		=> 'text',
				),
				array(
					'name'		=> 'home_predictive_list_text',
					'label'	   => 'Описание',
					'type'		=> 'wysiwyg',
				),
				array(
					'name'		=> 'home_predictive_item_list_image',
					'label'	   => 'Изображение',
					'type'		=> 'image',
					'size'		=> 'medium',
				),
				array(
					'type'        => 'relation',
					'name'        => 'home_predictive_item_page_link',
					'label'       => 'Ссылка на страницу',
					'post-type'   => array('post', 'page'),
					'limit'       => 1,
				),
			)
		);

		$Section->add_group(
			'home_predictive_after',
			false,
			array(
				// bool
				array(
					'name'		=> 'home_predictive_after_show',
					'label'	   => 'Показывать блок',
					'type'		=> 'boolean',
					'default'	 => false,
					'true_label'  => 'Да',
					'false_label' => 'Нет',
				),
				array(
					'name'		=> 'home_predictive_after_text',
					'label'	   => 'Описание',
					'type'		=> 'wysiwyg',
				),
				array(
					'name'		=> 'home_predictive_after_link',
					'label'	   => 'Ссылка',
					'type'		=> 'link',
				),
			),
		);


		$settings[] = $Section;
	}

	return $settings;
}
add_filter('smart-cf-register-fields', 'home_five_section_fields', 5, 5);

// cases
function home_cases_section_fields($settings, $type, $id, $meta_type, $types)
{
	if (HOME_ID == $id) {

		$Section = SCF::add_setting('home-6', 'Секция 6');

		$Section->add_group(
			'section',
			false,
			array(
				array(
					'name'		=> 'home_cases_title',
					'label'	   => 'Заголовок',
					'type'		=> 'text',
				),
				array(
					'name'		=> 'home_cases_text',
					'label'	   => 'Описание',
					'type'		=> 'wysiwyg',
				),
				array(
					'type'        => 'relation',
					'name'        => 'home_cases_list',
					'label'       => 'Список страниц',
					'post-type'   => array('cases'),
					'limit'       => 4,
				),
			)
		);

		$settings[] = $Section;
	}

	return $settings;
}
add_filter('smart-cf-register-fields', 'home_cases_section_fields', 5, 5);

// getlive
function home_getlive_section_fields($settings, $type, $id, $meta_type, $types)
{
	if (HOME_ID == $id) {

		$Section = SCF::add_setting('home-7', 'Секция 7');

		$Section->add_group(
			'section',
			false,
			array(
				array(
					'name'		=> 'home_getlive_title',
					'label'	   => 'Заголовок',
					'type'		=> 'text',
				),
				array(
					'name'		=> 'home_getlive_text',
					'label'	   => 'Описание',
					'type'		=> 'wysiwyg',
				),
				array(
					'name'		=> 'home_getlive_link',
					'label'	   => 'Ссылка',
					'type'		=> 'link',
				),
			)
		);

		$settings[] = $Section;
	}

	return $settings;
}
add_filter('smart-cf-register-fields', 'home_getlive_section_fields', 5, 5);

function home_sections_section_template_fields($settings, $type, $id, $meta_type, $types)
{

	if (HOME_ID == $id) {


		$Section = SCF::add_setting('sections', 'Настройка секций');
		$Section->add_group(
			'sections_list',
			false,
			array(
				array(
					'label'		  => 'Настройка секций',
					'name'		  => 'sections',
					'type'		  => 'sections',
				),
			)
		);
		$settings[] = $Section;
	}

	return $settings;
}
add_filter('smart-cf-register-fields', 'home_sections_section_template_fields', 10, 5);
