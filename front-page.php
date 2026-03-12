<?php
get_header();
if (theme_check_required_plugins(theme_get_required_plugins())) {
?>
	<!-- Front page -->
	<!-- Begin container_center -->
	<div class="container_center">
		<!-- Titles -->
		<!-- <h1 class="page__title"><?php the_title() ?></h1> -->
		<?php the_content() ?>
	</div>
	<!-- End container_center -->
<?php
} else {
	include get_template_directory() . '/inc/section-plugin-required.php';
}
get_footer();
