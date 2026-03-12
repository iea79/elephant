<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package sws
 */

get_header();
?>

<div class="container_center">

	<?php sws_breadcrumbs() ?>

	<?php
	$search_query = get_search_query();

	$sections = array(
		'works'       => array(
			'label'     => __('Каталог', 'sws'),
			'post_type' => 'works',
		),
		'information' => array(
			'label'     => __('Информация', 'sws'),
			'post_type' => 'information',
		),
		'posts'       => array(
			'label'     => __('Статьи', 'sws'),
			'post_type' => 'post',
		),
		'pages'       => array(
			'label'     => __('Страницы', 'sws'),
			'post_type' => 'page',
		),
	);

	$total_found     = 0;
	$section_queries = array();

	foreach ($sections as $key => $section) {
		$query = new WP_Query(
			array(
				'post_type'      => $section['post_type'],
				'post_status'    => 'publish',
				's'              => $search_query,
				'posts_per_page' => 10,
			)
		);

		$section_queries[$key] = $query;
		$total_found          += (int) $query->found_posts;
	}

	$has_results = $total_found > 0;
	?>

	<header class="searchPage__header">
		<?php get_search_form(); ?>
		<h1 class="page__title">
			<?php
			printf(
				esc_html__('Результаты поиска по запросу %s', 'sws'),
				'«' . $search_query . '»'
			);
			?>
		</h1>

		<p class="searchPage__count">
			<?php printf(esc_html__('Найдено: %d', 'sws'), (int) $total_found); ?>
		</p>
	</header>

	<div class="searchPage">
		<?php foreach ($sections as $key => $section) : ?>
			<?php
			$query = isset($section_queries[$key]) ? $section_queries[$key] : null;

			if (!$query || !$query->have_posts()) {
				wp_reset_postdata();
				continue;
			}

			$has_results = true;
			?>

			<section class="searchPage__section searchPage__section--<?php echo esc_attr($key); ?>">
				<h2 class="searchPage__sectionTitle"><?php echo esc_html($section['label']); ?></h2>

				<div class="worksSearch worksSearch--page">
					<div class="worksSearch__results">
						<?php
						while ($query->have_posts()) :
							$query->the_post();
						?>
							<a href="<?php the_permalink(); ?>" class="worksSearch__item">
								<?php if (has_post_thumbnail()) : ?>
									<div class="worksSearch__thumbnail">
										<?php the_post_thumbnail('thumbnail'); ?>
									</div>
								<?php endif; ?>
								<div class="worksSearch__content">
									<h3 class="worksSearch__title"><?php the_title(); ?></h3>
									<div class="worksSearch__excerpt">
										<?php echo wp_kses_post(sws_get_search_snippet(get_the_ID(), $search_query)); ?>
									</div>
								</div>
							</a>
						<?php endwhile; ?>
					</div>
				</div>

			</section>

		<?php
			wp_reset_postdata();
		endforeach;
		?>

		<button type="button" class="btn searchPage__more">
			<?php esc_html_e('показать еще ↓', 'sws'); ?>
		</button>

		<?php if (!$has_results) : ?>
			<div class="searchPage__empty">
				<?php esc_html_e('Ничего не найдено.', 'sws'); ?>
			</div>
		<?php endif; ?>
	</div>

</div><!-- #main -->

<?php
// get_sidebar();
get_footer();
