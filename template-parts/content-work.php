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
			$image_ids = array();
			foreach ($work_gallery as $gallery_item) {
				if (empty($gallery_item['work_galery_item'])) {
					continue;
				}
				$image_id = (int) $gallery_item['work_galery_item'];
				if ($image_id > 0) {
					$image_ids[] = $image_id;
				}
			}
			?>
			<?php if (!empty($image_ids)) : ?>
				<div class="homeSlider works__itemGallery" data-autoplay="true" data-autoplay-speed="3000" data-dots="true" data-arrows="false" data-fade="false" data-speed="500">
					<?php foreach ($image_ids as $image_id) : ?>
						<?php
						$image_html = wp_get_attachment_image(
							$image_id,
							'large',
							false,
							array(
								'class'   => 'homeSlider__image',
								'loading' => 'lazy',
							)
						);
						if (!$image_html) {
							continue;
						}
						?>
						<div class="homeSlider__slide">
							<?php echo $image_html; ?>
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
				<?php
				if ($work_price !== '' && $work_price !== null) :
					echo esc_html(number_format_i18n((float) $work_price, 0));
				else :
					echo esc_html('цена');
				endif;
				?>
				<svg width="15" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="rub">
					<path d="M1.675 17.85V0H7.85C10.1167 0 11.8083 0.491667 12.925 1.475C14.0583 2.45833 14.625 3.85833 14.625 5.675C14.625 6.70833 14.4083 7.68333 13.975 8.6C13.5583 9.5 12.8583 10.2333 11.875 10.8C10.8917 11.3667 9.55 11.6583 7.85 11.675H6.5V17.85H1.675ZM0 16.25V13.325H9.625V16.25H0ZM0 11.675V7.75H7.325V11.675H0ZM7.375 7.75C7.79167 7.75 8.175 7.675 8.525 7.525C8.89167 7.375 9.18333 7.15 9.4 6.85C9.63333 6.53333 9.75 6.15 9.75 5.7C9.75 5.13333 9.59167 4.69167 9.275 4.375C8.95833 4.05833 8.45 3.9 7.75 3.9H6.5V7.75H7.375Z" fill="#284A42"/>
				</svg>
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