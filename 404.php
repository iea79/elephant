<?php

/**
 * Шаблон страницы 404 (страница не найдена).
 *
 * @package sws
 */

get_header();

$page_404_title   = get_theme_mod('page_404_title', 'Страница не найдена');
$page_404_content = get_theme_mod('page_404_content', '');
$page_404_image   = get_theme_mod('page_404_image', '');
?>

<div class="container_center">
	<?php sws_breadcrumbs() ?>
	<section class="error-404 not-found notFound">
		<div class="notFound__content">
			<div class="notFound__left">
				<?php if ($page_404_title) : ?>
					<h1 class="page__title"><?php echo esc_html($page_404_title); ?></h1>
				<?php endif; ?>

				<?php if ($page_404_content) : ?>
					<div class="notFound__text">
						<?php echo wp_kses_post(wpautop($page_404_content)); ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="notFound__right">
				<?php
				if ($page_404_image) {
					echo wp_get_attachment_image($page_404_image, 'large', false, array(
						'class' => 'notFound__image',
					));
				}
				?>
			</div>
		</div>
	</section>
</div>

<?php
get_footer();
