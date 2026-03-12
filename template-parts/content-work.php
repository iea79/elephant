<?php
$work_gallery = class_exists('SCF') ? SCF::get('work-galery', get_the_ID()) : array();
$has_gallery  = false;

if (is_array($work_gallery)) {
	foreach ($work_gallery as $gallery_item) {
		if (!empty($gallery_item['work_galery_item'])) {
			$has_gallery = true;
			break;
		}
	}
}
?>

<div class="works__item">
	<a href="<?php echo get_permalink(); ?>" class="works__itemImage">
		<?php if ($has_gallery) : ?>
			<?php
			$image_urls = array();
			foreach ($work_gallery as $gallery_item) {
				if (empty($gallery_item['work_galery_item'])) {
					continue;
				}
				$img_url = wp_get_attachment_image_url($gallery_item['work_galery_item'], 'large');
				if ($img_url) {
					$image_urls[] = $img_url;
				}
			}
			?>
			<?php if (!empty($image_urls)) : ?>
				<div class="homeSlider works__itemGallery" data-autoplay="true" data-autoplay-speed="3000" data-dots="true" data-arrows="false" data-fade="false" data-speed="500">
					<?php foreach ($image_urls as $url) : ?>
						<div class="homeSlider__slide">
							<img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="homeSlider__image" />
						</div>
					<?php endforeach; ?>
				</div>
				<style>
					:root {
						--home-slider-autoplay-speed: <?php echo (3000 + 500) / 1000; ?>s;
					}
				</style>
			<?php else : ?>
				<?php the_post_thumbnail(); ?>
			<?php endif; ?>
		<?php else : ?>
			<?php the_post_thumbnail(); ?>
		<?php endif; ?>
	</a>

	<div class="works__itemContent">
		<?php
		$work_id    = class_exists('SCF') ? SCF::get('work_id', get_the_ID()) : '';
		$work_price = '';
		if (class_exists('SCF')) {
			$work_price = SCF::get('work_price', get_the_ID());
		}

		$socials = get_theme_mod('socials_list', array());
		if (is_string($socials)) {
			$socials = json_decode($socials, true);
		}
		$socials = is_array($socials) ? $socials : array();

		$whatsapp = '';
		$tg       = '';
		foreach ($socials as $item) {
			if (!empty($item['key']) && !empty($item['url'])) {
				if ($item['key'] === 'whatsapp') {
					$whatsapp = $item['url'];
				}
				if ($item['key'] === 'tg') {
					$tg = $item['url'];
				}
			}
		}

		$phone = get_theme_mod('site_phone', defined('SITE_PHONE') ? SITE_PHONE : '');

		if (empty($work_id)) {
			$work_id = get_the_ID();
		}
		?>

		<div class="works__itemMeta">
			<div class="works__itemId">
				<span class="works__itemIdLabel">ID</span>
				<span class="works__itemIdValue"><?php echo esc_html($work_id); ?></span>
			</div>
			<div class="works__itemIcons">
				<button type="button" class="works__itemShare" aria-label="Поделиться ссылкой" data-share-url="<?php echo esc_url(get_permalink()); ?>" data-share-title="<?php echo esc_attr(get_the_title()); ?>" data-work-id="<?php echo esc_attr($work_id); ?>">
					<span class="ie-icon_share"></span>
				</button>
				<button type="button" class="works__itemFavorite" data-work-id="<?php echo esc_attr(get_the_ID()); ?>" aria-pressed="false" aria-label="Добавить в избранное">
					<span class="ie-icon_heart"></span>
				</button>
			</div>
		</div>

		<a href="<?php echo get_permalink(); ?>" class="works__itemTitle">
			<?php the_title(); ?>
		</a>

		<?php
		$directions = get_the_terms(get_the_ID(), 'works_directions');
		if (!empty($directions) && !is_wp_error($directions)) :
			$main_direction = reset($directions);
		?>
			<div class="works__itemDirection">
				<?php echo esc_html($main_direction->name); ?>
			</div>
		<?php endif; ?>

		<?php
		$works_tags = get_the_terms(get_the_ID(), 'works_tags');
		if (!empty($works_tags) && !is_wp_error($works_tags)) :
		?>
			<ul class="works__itemTags">
				<?php foreach ($works_tags as $tag) : ?>
					<li class="works__itemTag"><span><?php echo esc_html($tag->name); ?></span></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<div class="works__itemFoot">
			<div class="works__itemPrice">
				<?php echo esc_html($work_price !== '' ? $work_price  : 'цена'); ?> ₽
			</div>

			<?php if ($whatsapp || $tg || $phone) : ?>
				<div class="works__itemSocials">
					<?php if ($whatsapp) : ?>
						<a href="<?php echo esc_url($whatsapp); ?>" class="works__itemSocial works__itemSocial--whatsapp" target="_blank" rel="noopener">
							<span class="ie-icon_whatsapp"></span>
						</a>
					<?php endif; ?>

					<?php if ($tg) : ?>
						<a href="<?php echo esc_url($tg); ?>" class="works__itemSocial works__itemSocial--tg" target="_blank" rel="noopener">
							<span class="ie-icon_tg"></span>
						</a>
					<?php endif; ?>

					<?php if ($phone) : ?>
						<a href="tel:<?php echo esc_attr(preg_replace('/\D+/', '', $phone)); ?>" class="works__itemSocial works__itemSocial--phone">
							<span class="ie-icon_phone"></span>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>