<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sws
 */

// Страница избранного определяется по ID из кастомайзера.
$favorites_page_id = (int) get_theme_mod('favorites_page');
if ($favorites_page_id && is_page($favorites_page_id)) {
	// Используем отдельный шаблон page-favorites.php.
	include get_template_directory() . '/page-favorites.php';
	return;
}

get_header();
?>

<div class="container_center">
	<?php
	sws_breadcrumbs();

	if (is_page_contacts()) {
		get_template_part('template-parts/content', 'contact');
	} else if (is_page_works()) {
		get_template_part('archive', 'works');
	} else {
		while (have_posts()) :
			the_post();
			get_template_part('template-parts/content', 'page');
		// If comments are open or we have at least one comment, load up the comment template.
		// if (comments_open() || get_comments_number()) :
		// 	comments_template();
		// endif;

		endwhile; // End of the loop.
	}

	?>

</div>

<?php
// get_sidebar();
get_footer();
