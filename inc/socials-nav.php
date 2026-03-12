<?php
// Получаем список социальных сетей из настроек
$socials_list = get_theme_mod('socials_list', array());

// Убедимся, что это массив
if (!is_array($socials_list)) {
	$socials_list = json_decode($socials_list, true);
}

// Если все еще не массив, используем пустой массив
if (is_array($socials_list)) {
?>
	<div class="soc">
		<?php foreach ($socials_list as $social) : ?>
			<?php
			$key = isset($social['key']) ? $social['key'] : '';
			$label = isset($social['label']) ? $social['label'] : '';
			$url = isset($social['url']) ? $social['url'] : '';
			?>
			<?php if ($key && $url) : ?>
				<a href="<?php echo esc_url($url); ?>" class="ie-icon_<?php echo esc_attr($key); ?>" target="_blank" aria-label="Ссылка <?php echo esc_attr($label); ?>"></a>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
<?php } ?>