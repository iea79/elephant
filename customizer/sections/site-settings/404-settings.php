<?php

/**
 * Настройки страницы 404 для панели "Настройки сайта".
 *
 * @return array
 */
function get_404_settings_section()
{
    return array(
        'title'       => 'Страница 404',
        'description' => '',
        'controls'    => array(
            'page_404_title' => array(
                'default' => 'Страница не найдена',
                'control' => array(
                    'label' => 'Заголовок страницы',
                    'type'  => 'text',
                ),
            ),
            'page_404_content' => array(
                'default' => '',
                'control' => array(
                    'label' => 'Текст страницы',
                    'type'  => 'tinymce',
                ),
            ),
            'page_404_image' => array(
                'default' => '',
                'control' => array(
                    'label' => 'Картинка страницы',
                    'type'  => 'media',
                ),
            ),
        ),
    );
}

