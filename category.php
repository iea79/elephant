<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sws
 */

get_header();
?>

<div class="container_center">
	<?php if (have_posts()) : ?>

		<header class="page-header">
			<h1 class="page__title"><?php single_cat_title(); ?></h1>
			<?php

			the_archive_description('<div class="section__sub">', '</div>');
			?>
		</header><!-- .page-header -->

	<?php
		/* Start the Loop */
		while (have_posts()) :
			the_post();

		/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
		// get_template_part('template-parts/content', get_post_type());

		endwhile;

		the_posts_navigation();

	else :

	// get_template_part('template-parts/content', 'none');

	endif;
	?>

</div>


<?php
// get_sidebar();
get_footer();
