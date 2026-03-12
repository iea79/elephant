<?php

/**
 * Элемент поиска по записям типа works
 *
 * @package sws
 */

/**
 * Выводит HTML разметку поиска по works.
 */
function sws_works_search_element()
{
?>
	<div class="worksSearch">
		<div class="worksSearch__form">
			<input type="text" class="worksSearch__input" placeholder="<?php esc_attr_e('Поиск объектов...', 'sws'); ?>" />
			<button type="button" class="worksSearch__button btn btn_secondary"><?php esc_html_e('Найти', 'sws'); ?></button>
		</div>
		<div class="worksSearch__results" style="display: none;"></div>
	</div>
	<?php
}

/**
 * Подключает скрипт для AJAX поиска.
 */
function sws_works_search_scripts()
{
	wp_enqueue_script('sws-works-search', get_template_directory_uri() . '/inc/works-search.js', array('jquery'), _S_VERSION, true);
	wp_localize_script('sws-works-search', 'swsWorksSearch', array(
		'ajaxurl'     => admin_url('admin-ajax.php'),
		'nonce'       => wp_create_nonce('works_search_nonce'),
		'loadingText' => __('Загрузка...', 'sws'),
		'errorText'   => __('Произошла ошибка.', 'sws'),
		'searchUrl'   => home_url('/'),
		'postType'    => 'works',
	));
}
add_action('wp_enqueue_scripts', 'sws_works_search_scripts');

/**
 * Обработчик AJAX поиска.
 */
function sws_ajax_works_search()
{
	check_ajax_referer('works_search_nonce', 'nonce');

	$search = sanitize_text_field($_POST['search'] ?? '');
	$args   = array(
		'post_type'      => 'works',
		'posts_per_page' => 10,
		's'              => $search,
		'post_status'    => 'publish',
	);

	$query = new WP_Query($args);
	ob_start();
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
	?>
			<a href="<?php the_permalink(); ?>" class="worksSearch__item">
				<?php if (has_post_thumbnail()) : ?>
					<div class="worksSearch__thumbnail">
						<?php the_post_thumbnail('thumbnail'); ?>
					</div>
				<?php endif; ?>
				<div class="worksSearch__content">
					<h4 class="worksSearch__title"><?php the_title(); ?></h4>
					<div class="worksSearch__excerpt"><?php the_excerpt(); ?></div>
				</div>
			</a>
		<?php
		}
		wp_reset_postdata();
	} else {
		?>
		<div class="worksSearch__noResults"><?php esc_html_e('Ничего не найдено.', 'sws'); ?></div>
<?php
	}
	$html = ob_get_clean();
	wp_send_json_success(array('html' => $html));
}
add_action('wp_ajax_works_search', 'sws_ajax_works_search');
add_action('wp_ajax_nopriv_works_search', 'sws_ajax_works_search');

/**
 * Шорткод для вывода элемента поиска.
 */
function sws_works_search_shortcode()
{
	ob_start();
	sws_works_search_element();
	return ob_get_clean();
}
add_shortcode('works_search', 'sws_works_search_shortcode');
