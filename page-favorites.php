<?php
/**
 * Template Name: Избранное
 * Шаблон страницы «Избранное» — карточки подгружаются по ID из localStorage.
 *
 * @package sws
 */
get_header();

$favorites_page_id = (int) get_theme_mod('favorites_page');
?>
<!-- Begin container_center -->
<div class="container_center">
	<?php sws_breadcrumbs(); ?>
	<section id="works" class="works works--favorites">
		<h1 class="section__title"><?php esc_html_e('Избранное', 'sws'); ?></h1>
		<div class="works__list" id="works-list">
			<!-- Карточки подгружаются JS из localStorage -->
		</div>
		<div class="works__empty works__empty--favorites" id="works-favorites-empty" style="display: none;">
			<p><?php esc_html_e('В избранном пока ничего нет.', 'sws'); ?></p>
		</div>
	</section>
	<?php echo get_the_content(null, null, $favorites_page_id) ?>
</div>
<!-- End container_center -->
<?php
get_footer();
