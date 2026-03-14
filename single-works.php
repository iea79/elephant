<?php
get_header();
the_post();
$cat = get_the_category();
$porfolioPageId = (int)get_theme_mod('portfolio_page');
?>
<article class="">
	<!-- Begin container_center -->
	<div class="container_center">
		<?php sws_breadcrumbs(); ?>
		<!-- End container_center -->
		<?php
		$breadcrumbs_show = get_theme_mod('breadcrumbs_show');
		get_template_part('template-parts/content', 'works');
		the_content();
		?>
	</div>
</article>
<div class="container_center">
	<div class="works__featured">
		<?php
		$posts = get_posts(array(
			'posts_per_page' => 3,
			'post_type'	  => 'works',
			'orderby'		=> 'rand',
			'post__not_in'   => array(get_the_ID()),
		));
		if ($posts) {
		?>
			<div class="works__featuredHead">
				<?php
				echo '<h2 class="section__title">Другие объекты</h2>';

				?>
				<div class="works__featuredSub">
					<!-- Выводим подкатегории для страницы -->
					<?php
					// Получаем категории текущего объекта
					$terms = get_the_terms(get_the_ID(), 'works_category');
					$current_category_id = $terms && !is_wp_error($terms) && isset($terms[0]) ? $terms[0]->term_id : 0;

					// Получаем подкатегории, если есть основная категория
					if ($current_category_id) {
						$subcategories = get_terms(array(
							'taxonomy' => 'works_category',
							'parent' => $current_category_id,
							'hide_empty' => true,
						));

						// Если нет подкатегорий, пробуем получить "соседние" (дети родительской категории)
						if (empty($subcategories)) {
							$parent_term = get_term($current_category_id, 'works_category');
							if ($parent_term && $parent_term->parent) {
								$subcategories = get_terms(array(
									'taxonomy' => 'works_category',
									'parent' => $parent_term->parent,
									'hide_empty' => true,
								));
							}
						}

						if (!empty($subcategories) && !is_wp_error($subcategories)) {
							foreach ($subcategories as $subcategory) {
								// Прямая ссылка на архив таксономии works_category.
								$url = get_term_link($subcategory, 'works_category');
								echo '<a href="' . esc_url($url) . '" class="works__subcategory btn btn_success btn_small' . ($subcategory->term_id == $current_category_id ? ' is-active' : '') . '">';
								echo esc_html($subcategory->name);
								echo '</a>';
							}
						}
					}
					?>
				</div>
			</div>
		<?php
			echo '<div class="works__list">';
			foreach ($posts as $post) {
				setup_postdata($post);
				get_template_part('template-parts/content', 'work');
			}
			echo '</div>';
			wp_reset_postdata();
		}

		?>
	</div>
</div>
<?php
get_footer();
