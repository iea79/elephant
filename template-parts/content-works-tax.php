<?php

/**
 * Общий контент-шаблон для таксономий works_*.
 * Основа — archive-works.php, но под списком работ выводится описание термина.
 *
 * Используется в taxonomy-works_category.php, taxonomy-works_tags.php, taxonomy-works_directions.php.
 *
 * @package sws
 */

$term     = get_queried_object();
$taxonomy = $term && ! is_wp_error($term) ? $term->taxonomy : '';

?>
<!-- Begin container_center -->
<div class="container_center">
	<?php sws_breadcrumbs(); ?>
	<section id="works" class="works">
		<?php
		global $post;

		?>
		<h1 class="page__title">
			<?php echo esc_html($term && isset($term->name) ? $term->name : get_the_archive_title()); ?>
		</h1>

		<?php
		get_template_part('inc/works-filter');

		// Пагинация
		$paged_query  = get_query_var('paged') ? (int) get_query_var('paged') : 0;
		$page_query   = get_query_var('page') ? (int) get_query_var('page') : 0;
		$current_page = $paged_query ?  $paged_query : ($page_query ? $page_query : 1);

		// Категория из GET-параметра (как в архиве), возможно переопределяет текущую категорию
		$category_slug = isset($_GET['category']) ? sanitize_text_field(wp_unslash($_GET['category'])) : '';

		// Текущая категория для логики направлений / фильтров
		if ('works_category' === $taxonomy) {
			// Для архива категории: если не выбрана через фильтр, используем текущий термин
			$current_category_slug = $category_slug ? $category_slug : ($term && ! is_wp_error($term) ? $term->slug : '');
		} else {
			$current_category_slug = $category_slug;
		}

		$per_page = (int) get_theme_mod('portfolio_per_page');
		if ($per_page <= 0) {
			$per_page = (int) get_option('posts_per_page');
		}

		$params = array(
			'posts_per_page' => $per_page,
			'post_type'      => 'works',
			'paged'          => $current_page,
		);

		// Таксономии: текущая таксономия + категория + направления
		$tax_query = array();

		// Основной термин текущей таксономии
		if ($term && ! is_wp_error($term) && in_array($taxonomy, array('works_category', 'works_tags', 'works_directions'), true)) {
			// Для works_category основной фильтр по категории пойдёт через $current_category_slug (см. ниже),
			// чтобы корректно отрабатывать смену категории через фильтр.
			if ('works_category' !== $taxonomy) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $term->slug,
				);
			}
		}

		// Фильтр по категории (works_category) — общий для всех таксономий
		if ($current_category_slug) {
			$tax_query[] = array(
				'taxonomy' => 'works_category',
				'field'    => 'slug',
				'terms'    => $current_category_slug,
			);
		}

		// Текущая родительская категория (для «других направлений»)
		$archive_current_term   = $current_category_slug ? get_term_by('slug', $current_category_slug, 'works_category') : null;
		$archive_current_parent = null;
		if ($archive_current_term && ! is_wp_error($archive_current_term)) {
			$archive_current_parent = $archive_current_term->parent ? get_term($archive_current_term->parent, 'works_category') : $archive_current_term;
		}

		$selected_directions = isset($_GET['direction']) ? (array) $_GET['direction'] : array();
		$selected_directions = array_filter(array_map('sanitize_text_field', $selected_directions));
		$direction_other     = ! empty($_GET['direction_other']);

		$direction_clauses = array();
		if (! empty($selected_directions)) {
			$direction_clauses[] = array(
				'taxonomy' => 'works_directions',
				'field'    => 'slug',
				'terms'    => $selected_directions,
			);
		}
		if ($direction_other && $archive_current_parent) {
			$other_ids = sws_get_other_direction_ids($archive_current_parent->term_id);
			if (! empty($other_ids)) {
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

		if (! empty($tax_query)) {
			if (count($tax_query) > 1) {
				$tax_query['relation'] = 'AND';
			}
			$params['tax_query'] = $tax_query;
		}

		// Фильтры по числовым полям (диапазоны)
		$meta_query = array();

		$plot_range      = isset($plot_range) ? $plot_range : sws_get_works_meta_range('plot_area');
		$plot_global_min = $plot_range['min'];
		$plot_global_max = $plot_range['max'] ? $plot_range['max'] : $plot_global_min;
		$plot_area_min   = isset($_GET['plot_area_min']) ? (int) $_GET['plot_area_min'] : $plot_global_min;
		$plot_area_max   = isset($_GET['plot_area_max']) ? (int) $_GET['plot_area_max'] : $plot_global_max;

		if (! ($plot_area_min === $plot_global_min && $plot_area_max === $plot_global_max) && ($plot_area_min > 0 || $plot_area_max > 0)) {
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

		$home_range      = isset($home_range) ? $home_range : sws_get_works_meta_range('home_area');
		$home_global_min = $home_range['min'];
		$home_global_max = $home_range['max'] ? $home_range['max'] : $home_global_min;
		$home_area_min   = isset($_GET['home_area_min']) ? (int) $_GET['home_area_min'] : $home_global_min;
		$home_area_max   = isset($_GET['home_area_max']) ? (int) $_GET['home_area_max'] : $home_global_max;

		if (! ($home_area_min === $home_global_min && $home_area_max === $home_global_max) && ($home_area_min > 0 || $home_area_max > 0)) {
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

		$rem_range       = isset($rem_range) ? $rem_range : sws_get_works_meta_range('remoteness');
		$rem_global_min  = $rem_range['min'];
		$rem_global_max  = $rem_range['max'] ? $rem_range['max'] : $rem_global_min;
		$remoteness_min  = isset($_GET['remoteness_min']) ? (int) $_GET['remoteness_min'] : $rem_global_min;
		$remoteness_max  = isset($_GET['remoteness_max']) ? (int) $_GET['remoteness_max'] : $rem_global_max;

		if (! ($remoteness_min === $rem_global_min && $remoteness_max === $rem_global_max) && ($remoteness_min > 0 || $remoteness_max > 0)) {
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

		if (! empty($meta_query)) {
			if (count($meta_query) > 1) {
				$meta_query['relation'] = 'AND';
			}
			$params['meta_query'] = $meta_query;
		}

		$works_query = new WP_Query($params);
		?>
		<div class="works__list" id="works-list">
			<?php while ($works_query->have_posts()) : $works_query->the_post(); ?>
				<?php get_template_part('template-parts/content', 'work'); ?>
			<?php endwhile; ?>
		</div>
		<?php if ($works_query->max_num_pages > $current_page) : ?>
			<button class="works__loadMore btn"
				data-current-page="<?php echo esc_attr($current_page); ?>"
				data-max-pages="<?php echo esc_attr($works_query->max_num_pages); ?>">
				показать еще
			</button>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</section>

	<?php
	// Блок описания таксономии в виде composition
	if ($term && ! is_wp_error($term)) {
		$term_description = term_description($term);

		// Картинка таксономии из SCF (attachment ID)
		$image_html = '';
		if (class_exists('SCF')) {
			$image_id = null;

			// Если в плагине есть term-API, используем его
			if (method_exists('SCF', 'get_term_meta')) {
				$image_id = SCF::get_term_meta($term->term_id, $taxonomy, 'works_term_image');
			} else {
				// Fallback: старый стиль через SCF::get
				$image_id = SCF::get('works_term_image', $term->term_id);
				if (! $image_id) {
					$image_id = SCF::get('works_term_image', 'term_' . $term->term_id);
				}
			}

			if ($image_id) {
				$image_html = wp_get_attachment_image($image_id, 'medium', false, array('class' => 'composition__image'));
			}
		}

		if (! empty($term_description) || $image_html) :
	?>
			<section class="wp-block-group composition is-layout-constrained wp-block-group-is-layout-constrained">
				<div class="wp-block-columns composition__wrap wp-block-columns-is-layout-flex">
					<?php if ($image_html) : ?>
						<div class="wp-block-column composition__img is-layout-flow wp-block-column-is-layout-flow" style="flex-basis:33.33%">
							<figure class="wp-block-image size-full">
								<?php echo $image_html; ?>
							</figure>
						</div>
					<?php endif; ?>

					<div class="wp-block-column is-vertically-aligned-top is-layout-flow wp-block-column-is-layout-flow" style="flex-basis:66.66%">
						<div class="wp-block-columns composition__body wp-block-columns-is-layout-flex">
							<div class="wp-block-column composition__head is-layout-flow wp-block-column-is-layout-flow">
								<h2 class="wp-block-heading section__title">
									<?php echo esc_html($term->name); ?>
								</h2>

								<div class="wp-block-group composition__notif is-layout-constrained wp-block-group-is-layout-constrained">
									<?php echo wp_kses_post($term_description); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
	<?php
		endif;
	}
	?>
</div>
<!-- End container_center -->