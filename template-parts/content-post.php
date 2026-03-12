<?php
$cats = get_the_category();
$cats = array_filter($cats, function ($cat) {
	return $cat->cat_name !== 'Без рубрики';
});
?>
<article class="article">
	<?php the_content(); ?>
</article>