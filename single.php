<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package sws
 */

get_header();
?>

<div class="container_center">
	<?php
	sws_breadcrumbs();

	while (have_posts()) :
		the_post();

		get_template_part('template-parts/content', get_post_type());

	// the_post_navigation(
	// 	array(
	// 		'prev_text' => '<span class="nav-subtitle">' . esc_html__('Предыдущая:', 'sws') . '</span> <span class="nav-title">%title</span>',
	// 		'next_text' => '<span class="nav-subtitle">' . esc_html__('Следующая:', 'sws') . '</span> <span class="nav-title">%title</span>',
	// 	)
	// );

	// If comments are open or we have at least one comment, load up the comment template.
	// if ( comments_open() || get_comments_number() ) :
	// 	comments_template();
	// endif;

	endwhile; // End of the loop.
	?>
</div>


<?php
// get_sidebar();
get_footer();
