<?php
define('WORKS_PAGE_ID', get_theme_mod('portfolio_page'));

if (WORKS_PAGE_ID) {
	// get slug
	define('WORKS_PAGE_SLUG', get_post(WORKS_PAGE_ID)->post_name);
} else {
	define('WORKS_PAGE_SLUG', 'works');
}

/**
 * Custom post works
 */

add_action('init', 'custom_post_works');

function custom_post_works()
{
	$labels = array(
		'name' => _x('Объекты', 'post type general name'),
		'singular_name' => _x('Объект', 'post type singular name'),
		'add_new' => _x('Добавить новую', 'works'),
		'add_new_item' => __('Добавить новый объект'),
		'edit_item' => __('Редактировать объект'),
		'new_item' => __('Новый объект'),
		'view_item' => __('Посмотреть объект'),
		'search_items' => __('Найти объект'),
		'not_found' =>  __('Объекты не найдены'),
		'not_found_in_trash' => __('В корзине нет объектов'),
		'parent_item_colon' => ''
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_icon' => 'dashicons-portfolio',
		'show_in_rest' => true,
		'query_var'    => true,
		// CPT архив по адресу /{WORKS_PAGE_SLUG}
		'has_archive'  => WORKS_PAGE_SLUG,
		// Синглы под /{WORKS_PAGE_SLUG}/{post_name}
		'rewrite'      => array(
			'slug'       => WORKS_PAGE_SLUG,
			'with_front' => false,
		),
		'capability_type' => 'post',
		'hierarchical' => false,
		'exclude_from_search' => false,
		'menu_position' => 5,
		'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'taxonomies'),
		'taxonomies' => array('works_category'),
	);

	register_post_type('works', $args);
}

/**
 * Custom post type "Информация"
 */
add_action('init', 'custom_post_information');

function custom_post_information()
{
	$labels = array(
		'name'               => _x('Информация', 'post type general name', 'sws'),
		'singular_name'      => _x('Информационная страница', 'post type singular name', 'sws'),
		'add_new'            => _x('Добавить новую', 'information', 'sws'),
		'add_new_item'       => __('Добавить информационную страницу', 'sws'),
		'edit_item'          => __('Редактировать страницу', 'sws'),
		'new_item'           => __('Новая страница', 'sws'),
		'view_item'          => __('Просмотр страницы', 'sws'),
		'search_items'       => __('Найти страницу', 'sws'),
		'not_found'          => __('Страницы не найдены', 'sws'),
		'not_found_in_trash' => __('В корзине нет страниц', 'sws'),
		'parent_item_colon'  => '',
		'menu_name'          => __('Информация', 'sws'),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'menu_icon'          => 'dashicons-media-text',
		'show_in_rest'       => true, // Гутенберг
		'query_var'          => true,
		'has_archive'        => false,
		'rewrite'            => array('slug' => 'information', 'with_front' => false),
		'capability_type'    => 'page',
		'hierarchical'       => false,
		'exclude_from_search' => false,
		'menu_position'      => 6,
		'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
		'taxonomies'         => array(),
	);

	register_post_type('information', $args);
}

/**
 * Custom taxonomy categegories
 */
add_action('init', 'custom_post_works_category');

function custom_post_works_category()
{
	$labels = array(
		'name' => _x('Категории', 'taxonomy general name'),
		'singular_name' => _x('Категория', 'taxonomy singular name'),
		'search_items' =>  __('Поиск категорий'),
		'all_items' => __('Все категории'),
		'parent_item' => __('Родительская категория'),
		'parent_item_colon' => __('Родительская категория:'),
		'edit_item' => __('Редактировать категорию'),
		'update_item' => __('Обновить категорию'),
		'add_new_item' => __('Добавить категорию'),
		'new_item_name' => __('Новая категория'),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'show_ui' => true,
		'query_var' => true,
		// Отдельная база таксономии, не под /{WORKS_PAGE_SLUG}, чтобы исключить конфликты
		// URL: /{WORKS_PAGE_SLUG}-category/parent/child
		'rewrite' => array(
			'slug'         => 'real-property',
			'hierarchical' => true,
			'with_front'   => false,
		),
		'show_admin_column' => true,
		'show_in_rest' => true
	);

	register_taxonomy('works_category', 'works', $args);
}

/**
 * Custom taxonomy categegories
 */
add_action('init', 'custom_post_works_tags');

function custom_post_works_tags()
{
	$labels = array(
		'name' => _x('Свойства', 'taxonomy general name'),
		'singular_name' => _x('Свойство', 'taxonomy singular name'),
		'search_items' =>  __('Поиск свойств'),
		'all_items' => __('Все свойства'),
		'edit_item' => __('Редактировать свойство'),
		'update_item' => __('Обновить свойство'),
		'add_new_item' => __('Добавить свойство'),
		'new_item_name' => __('Новое свойство'),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		'show_ui' => true,
		'query_var' => true,
		// /{WORKS_PAGE_SLUG}/svoistvo/term
		'rewrite' => false,
		'show_admin_column' => true,
		'show_in_rest' => true
	);

	register_taxonomy('works_tags', 'works', $args);
}


/**
 * Custom taxonomy directions (Направления)
 */
add_action('init', 'custom_post_works_directions');

function custom_post_works_directions()
{
	$labels = array(
		'name' => _x('Направления', 'taxonomy general name'),
		'singular_name' => _x('Направление', 'taxonomy singular name'),
		'search_items' =>  __('Поиск направлений'),
		'all_items' => __('Все направления'),
		'edit_item' => __('Редактировать направление'),
		'update_item' => __('Обновить направление'),
		'add_new_item' => __('Добавить направление'),
		'new_item_name' => __('Новое направление'),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		'show_ui' => true,
		'query_var' => true,
		// /{WORKS_PAGE_SLUG}/napravlenie/term
		'rewrite' => array(
			'slug'       => 'directions',
			'with_front' => false,
		),
		'show_admin_column' => true,
		'show_in_rest' => true
	);

	register_taxonomy('works_directions', 'works', $args);
}

/**
 * Meta-настройка: привязка направления к родительской категории works_category
 */
add_action('works_directions_add_form_fields', 'works_directions_add_parent_category_field');
add_action('works_directions_edit_form_fields', 'works_directions_edit_parent_category_field', 10, 2);
add_action('created_works_directions', 'works_directions_save_parent_category_field', 10, 2);
add_action('edited_works_directions', 'works_directions_save_parent_category_field', 10, 2);

function works_directions_add_parent_category_field($taxonomy)
{
	$categories = get_terms(array(
		'taxonomy'   => 'works_category',
		'hide_empty' => 0,
		'parent'     => 0,
	));
?>
	<div class="form-field term-parent-category-wrap">
		<label for="works_parent_category"><?php esc_html_e('Родительская категория', 'sws'); ?></label>
		<select name="works_parent_category" id="works_parent_category" class="postform">
			<option value="0"><?php esc_html_e('— Не выбрано —', 'sws'); ?></option>
			<?php if (!empty($categories) && !is_wp_error($categories)) : ?>
				<?php foreach ($categories as $cat) : ?>
					<option value="<?php echo esc_attr($cat->term_id); ?>">
						<?php echo esc_html($cat->name); ?>
					</option>
				<?php endforeach; ?>
			<?php endif; ?>
		</select>
		<p class="description"><?php esc_html_e('Выберите родительскую категорию, к которой относится это направление.', 'sws'); ?></p>
	</div>
	<div class="form-field term-show-in-filter-wrap">
		<label for="works_show_in_filter">
			<input type="checkbox" name="works_show_in_filter" id="works_show_in_filter" value="1">
			<?php esc_html_e('Показывать в фильтре', 'sws'); ?>
		</label>
		<p class="description"><?php esc_html_e('Показывать это направление в блоке чекбоксов фильтра для выбранной родительской категории.', 'sws'); ?></p>
	</div>
<?php
}

function works_directions_edit_parent_category_field($term, $taxonomy)
{
	$selected_id = (int) get_term_meta($term->term_id, 'works_parent_category', true);
	$show_in_filter = (string) get_term_meta($term->term_id, 'works_show_in_filter', true);

	$categories = get_terms(array(
		'taxonomy'   => 'works_category',
		'hide_empty' => 0,
		'parent'     => 0,
	));
?>
	<tr class="form-field term-parent-category-wrap">
		<th scope="row">
			<label for="works_parent_category"><?php esc_html_e('Родительская категория', 'sws'); ?></label>
		</th>
		<td>
			<select name="works_parent_category" id="works_parent_category" class="postform">
				<option value="0"><?php esc_html_e('— Не выбрано —', 'sws'); ?></option>
				<?php if (!empty($categories) && !is_wp_error($categories)) : ?>
					<?php foreach ($categories as $cat) : ?>
						<option value="<?php echo esc_attr($cat->term_id); ?>" <?php selected($selected_id, $cat->term_id); ?>>
							<?php echo esc_html($cat->name); ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<p class="description"><?php esc_html_e('Выберите родительскую категорию, к которой относится это направление.', 'sws'); ?></p>
		</td>
	</tr>
	<tr class="form-field term-show-in-filter-wrap">
		<th scope="row"><?php esc_html_e('Показывать в фильтре', 'sws'); ?></th>
		<td>
			<label for="works_show_in_filter">
				<input type="checkbox" name="works_show_in_filter" id="works_show_in_filter" value="1" <?php checked($show_in_filter, '1'); ?>>
				<?php esc_html_e('Показывать в блоке чекбоксов фильтра для этой родительской категории', 'sws'); ?>
			</label>
		</td>
	</tr>
<?php
}

function works_directions_save_parent_category_field($term_id, $tt_id)
{
	if (isset($_POST['works_parent_category'])) {
		$parent_id = (int) $_POST['works_parent_category'];
		if ($parent_id > 0) {
			update_term_meta($term_id, 'works_parent_category', $parent_id);
		} else {
			delete_term_meta($term_id, 'works_parent_category');
		}
	}

	if (isset($_POST['works_show_in_filter'])) {
		update_term_meta($term_id, 'works_show_in_filter', '1');
	} else {
		update_term_meta($term_id, 'works_show_in_filter', '0');
	}
}

// Доп. поля таксономий works_* теперь задаются через SCF (см. scf/pages/works-taxonomies.php)

/**
 * ID направлений, которые показываются в фильтре для данной родительской категории.
 *
 * @param int $parent_term_id ID термина works_category (родительская).
 * @return int[]
 */
function sws_get_filter_direction_ids($parent_term_id)
{
	if (!$parent_term_id) {
		return array();
	}
	$terms = get_terms(array(
		'taxonomy'   => 'works_directions',
		'hide_empty' => 0,
		'fields'     => 'ids',
		'meta_query' => array(
			array(
				'key'   => 'works_parent_category',
				'value' => (int) $parent_term_id,
				'type'  => 'NUMERIC',
			),
			array(
				'key'   => 'works_show_in_filter',
				'value' => '1',
			),
		),
	));
	return is_array($terms) ? $terms : array();
}

/**
 * ID направлений, которые не показываются в фильтре (для чекбокса «Другие направления»).
 *
 * @param int $parent_term_id ID родительской категории works_category.
 * @return int[]
 */
function sws_get_other_direction_ids($parent_term_id)
{
	$filter_ids = sws_get_filter_direction_ids($parent_term_id);
	$all = get_terms(array(
		'taxonomy'   => 'works_directions',
		'hide_empty' => 0,
		'fields'     => 'ids',
	));
	if (!is_array($all)) {
		return array();
	}
	return array_values(array_diff($all, $filter_ids));
}


function custom_posts_per_page($query)
{
	if (!is_admin() && $query->is_main_query() && is_post_type_archive('works')) { //количество записей в пользовательстком типе записей
		$query->set('posts_per_page', get_theme_mod('portfolio_per_page'));
	}
	if (!is_admin() && $query->is_main_query() && is_tax('works_category')) { //количество записей в пользовательстком типе записей в таксономиях
		$query->set('posts_per_page', get_theme_mod('portfolio_per_page'));
	}
}
add_action('pre_get_posts', 'custom_posts_per_page');
