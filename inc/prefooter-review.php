<?php

$exclude = [];
// is_front_page() ? $single_reviwe = SCF::get('second_single_reviwe') ? SCF::get('second_single_reviwe')[0] : get_the_ID() : get_the_ID();
if (is_front_page()) {
	$single_reviwe = SCF::get('second_single_reviwe');
	$exclude = $single_reviwe;
}

$args = array(
	'post_type' => 'reviews',
	'posts_per_page' => 1,
	'orderby' => 'rand',
	'exclude' => $exclude,
);

$query = new WP_Query($args);

if ($query->have_posts()) {
	while ($query->have_posts()) {
		$query->the_post();
		$content = get_the_content();
		$excerpt = get_the_excerpt();
?>
		<div class="review">
			<h2 class="section__title section__title--center"><?php echo wp_filter_nohtml_kses($content); ?></h2>
			<div class="review__photo"><?php the_post_thumbnail() ?></div>
			<div class="review__author"><?php the_title() ?></div>
			<div class="review__excerpt"><?php echo wp_filter_nohtml_kses($excerpt); ?></div>
		</div>
<?php
	}
} else {
	// no posts found
}

wp_reset_postdata();
