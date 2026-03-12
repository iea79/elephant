<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package sws
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function sws_body_classes($classes)
{
	// Adds a class of hfeed to non-singular pages.
	if (! is_singular()) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if (! is_active_sidebar('sidebar-1')) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter('body_class', 'sws_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function sws_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}
add_action('wp_head', 'sws_pingback_header');


/**
 * Render the logo on frontend
 */
function renderLogo($media_id = null)
{
	if ($media_id) {
		echo "<a href=" . home_url() . ">";
		echo wp_get_attachment_image($media_id, 'full');
		echo "</a>";
	} else {
		if (has_custom_logo()) {
			the_custom_logo();
		} else {
			echo '<a href="' . esc_url(home_url('/')) . '" rel="home" aria-label="Home link">' . 'Лого' . '</a>';
		}
	}
}


/**
 * Include custom post types and taxonomies
 */
include_once get_template_directory() . '/inc/custom-post-types.php';


/**
 * Проверяем, является ли текущая страница страницей контактов
 */
function is_page_contacts()
{
	return get_the_ID() == get_theme_mod('contacts_page');
}

/**
 * Проверяем, является ли текущая страницей работ
 */
function is_page_works()
{
	return is_archive(WORKS_PAGE_SLUG);
}

/**
 * Получить минимальное и максимальное значение числового мета-поля для записей works
 *
 * @param string $meta_key
 * @return array{min:int,max:int}
 */
function sws_get_works_meta_range($meta_key)
{
	static $cache = array();

	if (isset($cache[$meta_key])) {
		return $cache[$meta_key];
	}

	$range = array(
		'min' => 0,
		'max' => 0,
	);

	// Минимальное значение
	$min_query = new WP_Query(
		array(
			'post_type'      => 'works',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'meta_key'       => $meta_key,
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => $meta_key,
					'compare' => 'EXISTS',
				),
			),
		)
	);

	if ($min_query->have_posts()) {
		$min_query->the_post();
		$range['min'] = (int) get_post_meta(get_the_ID(), $meta_key, true);
		wp_reset_postdata();
	}

	// Максимальное значение
	$max_query = new WP_Query(
		array(
			'post_type'      => 'works',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'meta_key'       => $meta_key,
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
			'meta_query'     => array(
				array(
					'key'     => $meta_key,
					'compare' => 'EXISTS',
				),
			),
		)
	);

	if ($max_query->have_posts()) {
		$max_query->the_post();
		$range['max'] = (int) get_post_meta(get_the_ID(), $meta_key, true);
		wp_reset_postdata();
	}

	// Фолбэк, если нет значений
	if ($range['max'] < $range['min']) {
		$range['max'] = $range['min'];
	}

	$cache[$meta_key] = $range;

	return $range;
}

/**	
 * Функция для вывода хлебных крошек 
 */
function sws_breadcrumbs()
{
	// получаем номер текущей страницы
	$page_num = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$home_txt = 'The best only';
	$blog_id  = defined('BLOG_ID') ? BLOG_ID : (int) get_option('page_for_posts');

	//  разделитель
	$separator = '<svg width="4" height="10" viewBox="0 0 4 10" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M2 0H4L2 10H0L2 0Z" fill="#284A42"/>
	</svg>
	';

	// если главная страница сайта
	echo '<div class="breadcrumbs">';
	if (is_front_page()) {
		// Если функция выводится на странице
		if ($page_num > 1) {
			echo '<a href="' . site_url() . '">' . $home_txt . '</a>' . $separator . $page_num . '-я страница';
		} else {
			echo 'Вы находитесь на главной странице';
		}
	} elseif (is_home()) { // страница блога
		echo '<a href="' . site_url() . '">' . $home_txt . '</a>' . $separator;
		echo '<span>' . ($blog_id ? get_the_title($blog_id) : '') . '</span>';
	} elseif (is_archive()) { // архивы
		echo '<a href="' . site_url() . '">' . $home_txt . '</a>' . $separator;
		if (is_page_works()) {
			echo '<span>' . get_the_title(get_theme_mod('portfolio_page')) . '</span>';
		} else {
			if ($blog_id) {
				echo '<a href="' . esc_url(get_permalink($blog_id)) . '">' . esc_html(get_the_title($blog_id)) . '</a>' . $separator;
			}
			echo '<span>';
			the_archive_title('', '');
			echo '</span>';
		}
	} else { // не главная
		echo '<a href="' . site_url() . '">' . $home_txt . '</a>' . $separator;

		if (is_search()) {
			echo '<span>Поиск</span>';
		} elseif (is_single()) { // записи и custom post types
			$portfolio_id = get_theme_mod('portfolio_page');
			if (is_singular('works') && $portfolio_id) {
				echo '<a href="' . get_permalink($portfolio_id) . '">' . get_the_title($portfolio_id) . '</a>';
				echo $separator;
			} elseif ($blog_id && is_singular('post')) {
				echo '<a href="' . esc_url(get_permalink($blog_id)) . '">' . esc_html(get_the_title($blog_id)) . '</a>' . $separator;
			}
			echo '<span>' . get_the_title('', '') . '</span>';
		} elseif (is_page()) { // страницы WordPress 
			echo '<span>' . get_the_title('', '') . '</span>';
		} elseif (is_page_works()) { // страницы WordPress 
			echo '<span>' . get_the_title('', '') . '</span>';
		} elseif (is_category()) {
			if ($blog_id) {
				echo '<a href="' . esc_url(get_permalink($blog_id)) . '">' . esc_html(get_the_title($blog_id)) . '</a>' . $separator;
			}
			echo '<span>' . single_cat_title('', '') . '</span>';
		} elseif (is_tag()) {
			if ($blog_id) {
				echo '<a href="' . esc_url(get_permalink($blog_id)) . '">' . esc_html(get_the_title($blog_id)) . '</a>' . $separator;
			}
			echo '<span>' . single_tag_title('', '') . '</span>';
		} elseif (is_day()) { // архивы (по дням)
			if ($blog_id) {
				echo '<a href="' . esc_url(get_permalink($blog_id)) . '">' . esc_html(get_the_title($blog_id)) . '</a>' . $separator;
			}
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $separator;
			echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $separator;
			echo get_the_time('d');
		} elseif (is_month()) { // архивы (по месяцам)
			if ($blog_id) {
				echo '<a href="' . esc_url(get_permalink($blog_id)) . '">' . esc_html(get_the_title($blog_id)) . '</a>' . $separator;
			}
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $separator;
			echo get_the_time('F');
		} elseif (is_year()) { // архивы (по годам)
			if ($blog_id) {
				echo '<a href="' . esc_url(get_permalink($blog_id)) . '">' . esc_html(get_the_title($blog_id)) . '</a>' . $separator;
			}
			echo get_the_time('Y');
		} elseif (is_author()) { // архивы по авторам
			if ($blog_id) {
				echo '<a href="' . esc_url(get_permalink($blog_id)) . '">' . esc_html(get_the_title($blog_id)) . '</a>' . $separator;
			}
			global $author;
			$userdata = get_userdata($author);
			echo 'Опубликовал(а) ' . $userdata->display_name;
		} elseif (is_404()) { // если страницы не существует
			echo '<span>Ошибка 404</span>';
		}

		if ($page_num > 1) { // номер текущей страницы
			// echo ' (' . $page_num . '-я страница)';
		}
	}
	echo '</div>';
}

/**
 * Отдает фрагмент текста из контента, содержащий поисковый запрос,
 * без заголовков и изображений, с подсветкой запроса через <strong>.
 *
 * @param int|\WP_Post $post
 * @param string       $search_query
 * @param int          $length
 *
 * @return string
 */
function sws_get_search_snippet($post, $search_query, $length = 240)
{
	$post = get_post($post);
	if (!$post) {
		return '';
	}

	$content = $post->post_content;
	if (empty($content)) {
		$content = $post->post_excerpt;
	}

	$content = strip_shortcodes($content);
	$content = wp_strip_all_tags($content);
	$content = trim(preg_replace('/\s+/u', ' ', $content));

	if ($content === '') {
		return '';
	}

	$search_query = trim((string) $search_query);

	$has_mb = function_exists('mb_strlen');
	$strlen = $has_mb ? 'mb_strlen' : 'strlen';
	$substr = $has_mb ? 'mb_substr' : 'substr';
	$stripos = $has_mb ? 'mb_stripos' : 'stripos';

	if ($search_query === '') {
		// Если поисковая фраза пустая — просто первые 10 слов
		$words = preg_split('/\s+/u', $content);
		if (!is_array($words)) {
			return '';
		}
		$snippet_words = array_slice($words, 0, 10);
		$snippet = implode(' ', $snippet_words);
	} else {
		$words = preg_split('/\s+/u', $content);
		if (!is_array($words) || empty($words)) {
			return '';
		}

		$match_index = null;
		foreach ($words as $index => $word) {
			if ($stripos($word, $search_query) !== false) {
				$match_index = $index;
				break;
			}
		}

		if ($match_index === null) {
			// Если точного вхождения нет – fallback к первым 10 словам
			$snippet_words = array_slice($words, 0, 10);
		} else {
			// 2–4 слова до и 3–5 после, берем 3 до и 4 после
			$before = 3;
			$after  = 4;

			$start = max(0, $match_index - $before);
			$end   = min(count($words) - 1, $match_index + $after);

			$snippet_words = array_slice($words, $start, $end - $start + 1);
		}

		$snippet = implode(' ', $snippet_words);
	}

	if ($search_query !== '' && $snippet !== '') {
		$pattern = '/' . preg_quote($search_query, '/') . '/iu';
		$snippet = preg_replace($pattern, '<strong>$0</strong>', $snippet);
	}
	$snippet = trim($snippet);
	if ($snippet === '') {
		return '';
	}

	return '… ' . $snippet . ' …';
}
