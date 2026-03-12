<?php
get_header();
if (theme_check_required_plugins(theme_get_required_plugins())) {
	define('BLOG_ID', get_option('page_for_posts'));

	if (!BLOG_ID) return;
?>
	<div class="container_center">
		<?php sws_breadcrumbs(); ?>
		<h1 class="page__title"><?php echo get_the_title(BLOG_ID); ?></h1>
		<div class="posts">
			<?php
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

			$params = array(
				'posts_per_page'  => get_option('posts_per_page'), // количество постов на странице
				'post_type'       => 'post', // тип постов
				'paged'           => $paged // текущая страница
			);
			query_posts($params);

			$wp_query->is_archive = true;
			$wp_query->is_home = false;
			echo '<div class="posts__list">';
			while (have_posts()): the_post();
				$cats = get_the_category();
			?>
				<div class="posts__item">
					<a href="<?php echo get_permalink(); ?>" class="posts__img">
						<?php echo get_the_post_thumbnail(); ?>
					</a>
					<div class="posts__title"><?php the_title() ?></div>
					<div class="posts__text"><?php echo wp_trim_words(get_the_content(), 60); ?></div>
					<div class="wp-block-post-excerpt__more-text">
						<a href="<?php echo get_the_permalink(); ?>">Читать далее</a>
					</div>
				</div>
			<?php
			endwhile;
			echo '</div>';
			?>
			<div class="pagination"><?php echo paginate_links(
										array(
											'prev_next' => true,
											'prev_text' => __(''),
											'next_text' => __(''),
											'total' => $wp_query->max_num_pages
										)
									); ?></div>
			<?php
			wp_reset_query();

			?>
		</div>
		<?php echo get_the_content(null, null, BLOG_ID); ?>
	</div>
<?php
} else {
	include get_template_directory() . '/inc/section-plugin-required.php';
}
get_footer();
