<?php
if (class_exists('SCF')) {
	require_once get_template_directory() . '/scf/custom/media.php';
	require_once get_template_directory() . '/scf/custom/number.php';
	require_once get_template_directory() . '/scf/custom/range.php';
	require_once get_template_directory() . '/scf/custom/link.php';
	require_once get_template_directory() . '/scf/custom/sections.php';

	require_once get_template_directory() . '/scf/pages/work.php';
	require_once get_template_directory() . '/scf/pages/works-taxonomies.php';
}

function sws_scf_enqueue_scripts()
{
	// Подключить стили ./css/style.css для админки
	if (is_admin()) {
		wp_enqueue_style('scf-admin-style', get_template_directory_uri() . '/scf/css/style.css', array(), _S_VERSION);
	}
}

add_action('admin_enqueue_scripts', 'sws_scf_enqueue_scripts');


// Подключаем файл с утилитами
require_once get_template_directory() . '/scf/utils/utils.php';
