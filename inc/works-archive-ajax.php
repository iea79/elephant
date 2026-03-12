<?php

/**
 * AJAX подгрузка объектов на архиве works
 *
 * @package sws
 */

function sws_ajax_works_archive_load_more()
{
	check_ajax_referer('works_archive_nonce', 'nonce');

	$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
	if ($page < 1) {
		$page = 1;
	}

	$category_slug = isset($_POST['category']) ? sanitize_text_field(wp_unslash($_POST['category'])) : '';

	$selected_directions = isset($_POST['direction']) ? (array) $_POST['direction'] : array();
	$selected_directions = array_filter(array_map('sanitize_text_field', $selected_directions));
	$direction_other = !empty($_POST['direction_other']);

	$per_page = (int) get_theme_mod('portfolio_per_page');
	if ($per_page <= 0) {
		$per_page = (int) get_option('posts_per_page');
	}

	$params = array(
		'post_type'      => 'works',
		'posts_per_page' => $per_page,
		'paged'          => $page,
	);

	// Таксономии
	$tax_query = array();

	if ($category_slug) {
		$tax_query[] = array(
			'taxonomy' => 'works_category',
			'field'    => 'slug',
			'terms'    => $category_slug,
		);
	}

	$ajax_current_term = $category_slug ? get_term_by('slug', $category_slug, 'works_category') : null;
	$ajax_current_parent = null;
	if ($ajax_current_term && !is_wp_error($ajax_current_term)) {
		$ajax_current_parent = $ajax_current_term->parent ? get_term($ajax_current_term->parent, 'works_category') : $ajax_current_term;
	}

	$direction_clauses = array();
	if (!empty($selected_directions)) {
		$direction_clauses[] = array(
			'taxonomy' => 'works_directions',
			'field'    => 'slug',
			'terms'    => $selected_directions,
		);
	}
	if ($direction_other && $ajax_current_parent) {
		$other_ids = sws_get_other_direction_ids($ajax_current_parent->term_id);
		if (!empty($other_ids)) {
			$direction_clauses[] = array(
				'taxonomy' => 'works_directions',
				'field'    => 'term_id',
				'terms'    => $other_ids,
			);
		}
	}
	if (count($direction_clauses) === 1) {
		$tax_query[] = $direction_clauses[0];
	} elseif (count($direction_clauses) > 1) {
		$tax_query[] = array_merge(array('relation' => 'OR'), $direction_clauses);
	}

	if (!empty($tax_query)) {
		if (count($tax_query) > 1) {
			$tax_query['relation'] = 'AND';
		}
		$params['tax_query'] = $tax_query;
	}

	// Диапазоны числовых полей
	$meta_query = array();

	$plot_range     = sws_get_works_meta_range('plot_area');
	$plot_global_min = $plot_range['min'];
	$plot_global_max = $plot_range['max'] ?: $plot_global_min;
	$plot_area_min   = isset($_POST['plot_area_min']) ? (int) $_POST['plot_area_min'] : $plot_global_min;
	$plot_area_max   = isset($_POST['plot_area_max']) ? (int) $_POST['plot_area_max'] : $plot_global_max;

	// Если значения по умолчанию (глобальный min/max) — не фильтруем по полю
	if (!($plot_area_min === $plot_global_min && $plot_area_max === $plot_global_max) && ($plot_area_min > 0 || $plot_area_max > 0)) {
		$range = array(
			'key'  => 'plot_area',
			'type' => 'NUMERIC',
		);

		if ($plot_area_min > 0 && $plot_area_max > 0 && $plot_area_max >= $plot_area_min) {
			$range['value']   = array($plot_area_min, $plot_area_max);
			$range['compare'] = 'BETWEEN';
		} elseif ($plot_area_min > 0) {
			$range['value']   = $plot_area_min;
			$range['compare'] = '>=';
		} elseif ($plot_area_max > 0) {
			$range['value']   = $plot_area_max;
			$range['compare'] = '<=';
		}

		$meta_query[] = $range;
	}

	$home_range      = sws_get_works_meta_range('home_area');
	$home_global_min = $home_range['min'];
	$home_global_max = $home_range['max'] ?: $home_global_min;
	$home_area_min   = isset($_POST['home_area_min']) ? (int) $_POST['home_area_min'] : $home_global_min;
	$home_area_max   = isset($_POST['home_area_max']) ? (int) $_POST['home_area_max'] : $home_global_max;

	if (!($home_area_min === $home_global_min && $home_area_max === $home_global_max) && ($home_area_min > 0 || $home_area_max > 0)) {
		$range = array(
			'key'  => 'home_area',
			'type' => 'NUMERIC',
		);

		if ($home_area_min > 0 && $home_area_max > 0 && $home_area_max >= $home_area_min) {
			$range['value']   = array($home_area_min, $home_area_max);
			$range['compare'] = 'BETWEEN';
		} elseif ($home_area_min > 0) {
			$range['value']   = $home_area_min;
			$range['compare'] = '>=';
		} elseif ($home_area_max > 0) {
			$range['value']   = $home_area_max;
			$range['compare'] = '<=';
		}

		$meta_query[] = $range;
	}

	$rem_range       = sws_get_works_meta_range('remoteness');
	$rem_global_min  = $rem_range['min'];
	$rem_global_max  = $rem_range['max'] ?: $rem_global_min;
	$remoteness_min  = isset($_POST['remoteness_min']) ? (int) $_POST['remoteness_min'] : $rem_global_min;
	$remoteness_max  = isset($_POST['remoteness_max']) ? (int) $_POST['remoteness_max'] : $rem_global_max;

	if (!($remoteness_min === $rem_global_min && $remoteness_max === $rem_global_max) && ($remoteness_min > 0 || $remoteness_max > 0)) {
		$range = array(
			'key'  => 'remoteness',
			'type' => 'NUMERIC',
		);

		if ($remoteness_min > 0 && $remoteness_max > 0 && $remoteness_max >= $remoteness_min) {
			$range['value']   = array($remoteness_min, $remoteness_max);
			$range['compare'] = 'BETWEEN';
		} elseif ($remoteness_min > 0) {
			$range['value']   = $remoteness_min;
			$range['compare'] = '>=';
		} elseif ($remoteness_max > 0) {
			$range['value']   = $remoteness_max;
			$range['compare'] = '<=';
		}

		$meta_query[] = $range;
	}

	if (!empty($meta_query)) {
		if (count($meta_query) > 1) {
			$meta_query['relation'] = 'AND';
		}
		$params['meta_query'] = $meta_query;
	}

	$query = new WP_Query($params);

	ob_start();
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			get_template_part('template-parts/content', 'work');
		}
		wp_reset_postdata();
	}

	$html = ob_get_clean();

	wp_send_json_success(
		array(
			'html' => $html,
		)
	);
}

add_action('wp_ajax_works_archive_load_more', 'sws_ajax_works_archive_load_more');
add_action('wp_ajax_nopriv_works_archive_load_more', 'sws_ajax_works_archive_load_more');

/**
 * AJAX: HTML карточек для страницы избранного (по списку ID из localStorage).
 */
function sws_ajax_works_favorites_cards()
{
	check_ajax_referer('works_archive_nonce', 'nonce');

	// post_ids приходят как JSON-строка из localStorage (массив ID)
	$post_ids = array();
	if (!empty($_POST['post_ids'])) {
		if (is_string($_POST['post_ids'])) {
			$decoded = json_decode(stripslashes($_POST['post_ids']), true);
			$post_ids = is_array($decoded) ? $decoded : array();
		} else {
			$post_ids = (array) $_POST['post_ids'];
		}
	}
	$post_ids = array_filter(array_map('absint', $post_ids));
	$post_ids = array_unique(array_slice($post_ids, 0, 100));

	if (empty($post_ids)) {
		wp_send_json_success(array('html' => ''));
		return;
	}

	$query = new WP_Query(array(
		'post_type'      => 'works',
		'post_status'    => 'publish',
		'post__in'       => $post_ids,
		'orderby'        => 'post__in',
		'posts_per_page' => count($post_ids),
	));

	ob_start();
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			get_template_part('template-parts/content', 'work');
		}
		wp_reset_postdata();
	}
	$html = ob_get_clean();

	wp_send_json_success(array('html' => $html));
}

add_action('wp_ajax_works_favorites_cards', 'sws_ajax_works_favorites_cards');
add_action('wp_ajax_nopriv_works_favorites_cards', 'sws_ajax_works_favorites_cards');

