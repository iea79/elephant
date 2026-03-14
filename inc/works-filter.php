<?php

/**
 * Фильтр для архива works
 *
 * @package sws
 */

if (!defined('ABSPATH')) {
	exit;
}

// Родительские категории для фильтра
$parent_categories = get_terms(array(
	'taxonomy'   => 'works_category',
	'hide_empty' => 0,
	'parent'     => 0,
));

$paged_query   = get_query_var('paged') ? (int) get_query_var('paged') : 0;
$page_query    = get_query_var('page') ? (int) get_query_var('page') : 0;
$current_page  = $paged_query ? $paged_query : ($page_query ? $page_query : 1);

$category_slug = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';

// Текущий термин и родительская категория для вывода дочерних
$current_term   = null;
$current_parent = null;

// Если мы на архиве таксономии works_category — используем реально запрошенный термин
if (is_tax('works_category')) {
	$queried = get_queried_object();
	if ($queried && !is_wp_error($queried) && !empty($queried->slug)) {
		$current_term  = $queried;
		$category_slug = $queried->slug;
	}
}

// Если термина ещё нет, но есть slug из GET — берём его
if (!$current_term && $category_slug) {
	$current_term = get_term_by('slug', $category_slug, 'works_category');
}

// По умолчанию, если ничего не выбрано, применяем первую родительскую категорию
if (!$current_term && !$category_slug && !empty($parent_categories) && !is_wp_error($parent_categories)) {
	$category_slug = $parent_categories[0]->slug;
	$current_term  = get_term_by('slug', $category_slug, 'works_category');
}

if ($current_term && !is_wp_error($current_term)) {
	if ($current_term->parent) {
		$current_parent = get_term($current_term->parent, 'works_category');
	} else {
		$current_parent = $current_term;
	}
} elseif (!empty($parent_categories) && !is_wp_error($parent_categories)) {
	$current_parent = $parent_categories[0];
}

// Дочерние категории активной родительской
$child_categories = array();
if ($current_parent) {
	$child_categories = get_terms(array(
		'taxonomy'   => 'works_category',
		'hide_empty' => 1,
		'parent'     => $current_parent->term_id,
	));
}

?>

<?php if (!empty($child_categories) && !is_wp_error($child_categories)) : ?>
	<div class="worksFilter__subcategories mobile">
		<div class="worksFilter__toggle btn btn_small">Фильтры</div>
		<?php foreach ($child_categories as $child) : ?>
			<?php
			$child_url       = get_term_link($child, 'works_category');
			$is_child_active = ($current_term && !is_wp_error($current_term) && $current_term->slug === $child->slug);
			?>
			<a href="<?php echo esc_url($child_url); ?>" class="btn btn_success btn_small worksFilter__subcategory<?php echo $is_child_active ? ' worksFilter__subcategory--active' : ''; ?>">
				<?php echo esc_html($child->name); ?>
			</a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<div class="worksFilter__container">
	<?php if (!empty($parent_categories) && !is_wp_error($parent_categories)) : ?>
		<div class="worksFilter__head">
			<div class="worksFilter__tabs">
				<?php foreach ($parent_categories as $parent_cat) : ?>
					<?php
					$active_parent_slug = $current_parent ? $current_parent->slug : $category_slug;
					$is_active          = ($active_parent_slug === $parent_cat->slug);
					$tab_url            = get_term_link($parent_cat, 'works_category');
					?>
					<a class="worksFilter__tab<?php echo $is_active ? ' worksFilter__tab--active' : ''; ?>" href="<?php echo esc_url($tab_url); ?>">
						<?php echo esc_html($parent_cat->name); ?>
					</a>
				<?php endforeach; ?>
			</div>
			<?php if (!empty($child_categories) && !is_wp_error($child_categories)) : ?>
				<div class="worksFilter__subcategories desktop">
					<?php foreach ($child_categories as $child) : ?>
						<?php
						$child_url       = get_term_link($child, 'works_category');
						$is_child_active = ($current_term && !is_wp_error($current_term) && $current_term->slug === $child->slug);
						?>
						<a href="<?php echo esc_url($child_url); ?>" class="btn btn_success btn_small worksFilter__subcategory<?php echo $is_child_active ? ' worksFilter__subcategory--active' : ''; ?>">
							<?php echo esc_html($child->name); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<form class="worksFilter" method="get" action="<?php echo esc_url(get_permalink(WORKS_PAGE_ID)); ?>" data-reset-url="<?php echo esc_url(get_permalink(WORKS_PAGE_ID)); ?>">
		<input type="hidden" name="category" value="<?php echo esc_attr($category_slug); ?>">

		<div class="worksFilter__row">
			<div class="worksFilter__col worksFilter__col--directions">
				<?php
				// Направления: привязаны к текущей родительской категории и отмечены «Показывать в фильтре»
				$directions = array();
				if ($current_parent) {
					$filter_ids = sws_get_filter_direction_ids($current_parent->term_id);
					if (!empty($filter_ids)) {
						$directions = get_terms(array(
							'taxonomy' => 'works_directions',
							'include'  => $filter_ids,
							'orderby'  => 'name',
							'order'    => 'ASC',
						));
						$directions = is_array($directions) && !is_wp_error($directions) ? $directions : array();
					}
				}
				$selected_directions      = isset($_GET['direction']) ? (array) $_GET['direction'] : array();
				$selected_direction_other = !empty($_GET['direction_other']);
				$other_direction_ids      = $current_parent ? sws_get_other_direction_ids($current_parent->term_id) : array();

				// Всегда показываем чекбокс «Другие направления»
				$show_other_checkbox = true;

				// Ограничиваем общее количество чекбоксов (направления + «Другие направления») до 4
				$max_total_checkboxes = 4;
				$directions_to_show   = $directions;
				$max_directions       = $max_total_checkboxes - ($show_other_checkbox ? 1 : 0);
				if ($max_directions < 0) {
					$max_directions = 0;
				}
				if (!empty($directions_to_show) && count($directions_to_show) > $max_directions) {
					$directions_to_show = array_slice($directions_to_show, 0, $max_directions);
				}
				?>
				<?php if (!empty($directions_to_show) || $show_other_checkbox) : ?>
					<div class="worksFilter__directions">
						<?php foreach ($directions_to_show as $direction) : ?>
							<?php
							$checked = in_array($direction->slug, $selected_directions, true);
							?>
							<label class="worksFilter__checkbox">
								<input type="checkbox" name="direction[]" value="<?php echo esc_attr($direction->slug); ?>" <?php checked($checked); ?>>
								<span class="worksFilter__checkboxLabel"><?php echo esc_html($direction->name); ?></span>
							</label>
						<?php endforeach; ?>
						<?php if ($show_other_checkbox) : ?>
							<label class="worksFilter__checkbox">
								<input type="checkbox" name="direction_other" value="1" <?php checked($selected_direction_other); ?>>
								<span class="worksFilter__checkboxLabel"><?php esc_html_e('Другие направления', 'sws'); ?></span>
							</label>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="worksFilter__col worksFilter__col--ranges">
				<div class="worksFilter__range">
					<label class="worksFilter__rangeLabel">
						Площадь участка
						<?php
						$plot_range      = sws_get_works_meta_range('plot_area');
						$plot_global_min = $plot_range['min'];
						$plot_global_max = $plot_range['max'] ?: $plot_global_min;
						$plot_min        = isset($_GET['plot_area_min']) ? (int) $_GET['plot_area_min'] : $plot_global_min;
						$plot_max        = isset($_GET['plot_area_max']) ? (int) $_GET['plot_area_max'] : $plot_global_max;
						?>
						<div class="worksFilter__rangeSlider"
							data-min="<?php echo esc_attr($plot_global_min); ?>"
							data-max="<?php echo esc_attr($plot_global_max); ?>"
							data-min-input="plot_area_min"
							data-max-input="plot_area_max"
							data-current-min="<?php echo esc_attr($plot_min); ?>"
							data-current-max="<?php echo esc_attr($plot_max); ?>">
							<div class="worksFilter__rangeValues">
								<span class="worksFilter__rangeValue worksFilter__rangeValue--min">от <span><?php echo esc_html($plot_min); ?> </span> сот.</span>
								<span class="worksFilter__rangeValue worksFilter__rangeValue--max">до <span><?php echo esc_html($plot_max); ?></span> сот.</span>
							</div>
							<div class="worksFilter__rangeSliderTrack"></div>
						</div>
						<input type="hidden" name="plot_area_min" value="<?php echo esc_attr($plot_min); ?>">
						<input type="hidden" name="plot_area_max" value="<?php echo esc_attr($plot_max); ?>">
					</label>
				</div>

				<div class="worksFilter__range">
					<label class="worksFilter__rangeLabel">
						Площадь дома
						<?php
						$home_range      = sws_get_works_meta_range('home_area');
						$home_global_min = $home_range['min'];
						$home_global_max = $home_range['max'] ?: $home_global_min;
						$home_min        = isset($_GET['home_area_min']) ? (int) $_GET['home_area_min'] : $home_global_min;
						$home_max        = isset($_GET['home_area_max']) ? (int) $_GET['home_area_max'] : $home_global_max;
						?>
						<div class="worksFilter__rangeSlider"
							data-min="<?php echo esc_attr($home_global_min); ?>"
							data-max="<?php echo esc_attr($home_global_max); ?>"
							data-min-input="home_area_min"
							data-max-input="home_area_max"
							data-current-min="<?php echo esc_attr($home_min); ?>"
							data-current-max="<?php echo esc_attr($home_max); ?>">
							<div class="worksFilter__rangeValues">
								<span class="worksFilter__rangeValue worksFilter__rangeValue--min">от <span><?php echo esc_html($home_min); ?></span> м²</span>
								<span class="worksFilter__rangeValue worksFilter__rangeValue--max">до <span><?php echo esc_html($home_max); ?></span> м²</span>
							</div>
							<div class="worksFilter__rangeSliderTrack"></div>
						</div>
						<input type="hidden" name="home_area_min" value="<?php echo esc_attr($home_min); ?>">
						<input type="hidden" name="home_area_max" value="<?php echo esc_attr($home_max); ?>">
					</label>
				</div>

				<div class="worksFilter__range">
					<label class="worksFilter__rangeLabel">
						Удалённость
						<?php
						$rem_range      = sws_get_works_meta_range('remoteness');
						$rem_global_min = $rem_range['min'];
						$rem_global_max = $rem_range['max'] ?: $rem_global_min;
						$rem_min        = isset($_GET['remoteness_min']) ? (int) $_GET['remoteness_min'] : $rem_global_min;
						$rem_max        = isset($_GET['remoteness_max']) ? (int) $_GET['remoteness_max'] : $rem_global_max;
						?>
						<div class="worksFilter__rangeSlider"
							data-min="<?php echo esc_attr($rem_global_min); ?>"
							data-max="<?php echo esc_attr($rem_global_max); ?>"
							data-min-input="remoteness_min"
							data-max-input="remoteness_max"
							data-current-min="<?php echo esc_attr($rem_min); ?>"
							data-current-max="<?php echo esc_attr($rem_max); ?>">
							<div class="worksFilter__rangeValues">
								<span class="worksFilter__rangeValue worksFilter__rangeValue--min">от <span><?php echo esc_html($rem_min); ?></span> км</span>
								<span class="worksFilter__rangeValue worksFilter__rangeValue--max">до <span><?php echo esc_html($rem_max); ?></span> км</span>
							</div>
							<div class="worksFilter__rangeSliderTrack"></div>
						</div>
						<input type="hidden" name="remoteness_min" value="<?php echo esc_attr($rem_min); ?>">
						<input type="hidden" name="remoteness_max" value="<?php echo esc_attr($rem_max); ?>">
					</label>
				</div>
			</div>

			<div class="worksFilter__col worksFilter__col--actions">
				<button type="submit" class="worksFilter__button worksFilter__button--apply btn btn_secondary">
					Применить
				</button>
				<button type="reset" class="worksFilter__button worksFilter__button--reset worksFilter__reset" aria-label="Сбросить фильтры">
					<span class="ie-icon_filter-off"></span>
				</button>
			</div>
		</div>

	</form>
</div>
<script>
	(function () {
		try {
			if (window.innerWidth >= 768) return;
			var container = document.querySelector('.worksFilter__container');
			if (!container) return;
			var saved = window.localStorage ? localStorage.getItem('worksFilterIsOpen') : null;
			if (saved === '1') {
				container.classList.add('is-open');
			} else {
				container.classList.remove('is-open');
			}
			// Мгновенно применяем высоту без анимации
			container.style.overflow = 'hidden';
			container.style.transition = 'none';
			container.style.height = container.classList.contains('is-open') ? container.scrollHeight + 'px' : '0px';
			// Разрешим анимацию для последующих кликов
			setTimeout(function () {
				container.style.transition = '';
			}, 0);
		} catch (e) {}
	})();
</script>