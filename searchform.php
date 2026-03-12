<?php
/**
 * Custom search form.
 *
 * @package sws
 */

$unique_id = esc_attr(uniqid('search-form-'));
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
	<label for="<?php echo $unique_id; ?>" class="screen-reader-text">
		<?php esc_html_e('Поиск', 'sws'); ?>
	</label>
	<input
		type="search"
		id="<?php echo $unique_id; ?>"
		class="search-field"
		placeholder="<?php esc_attr_e('Поиск…', 'sws'); ?>"
		value="<?php echo esc_attr(get_search_query()); ?>"
		name="s"
	/>
	<button type="submit" class="search-submit btn">
		<?php esc_html_e('Найти', 'sws'); ?>
	</button>
</form>

