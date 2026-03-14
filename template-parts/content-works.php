<?php
if (!function_exists('sws_work_sotki_label')) {
	function sws_work_sotki_label($number)
	{
		$number = abs((int) $number);
		$mod10  = $number % 10;
		$mod100 = $number % 100;

		if ($mod10 === 1 && $mod100 !== 11) {
			return 'сотка';
		}

		if ($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 12 || $mod100 > 14)) {
			return 'сотки';
		}

		return 'соток';
	}
}
?>

<?php if (is_singular('works')) : ?>
	<?php
	$work_gallery = class_exists('SCF') ? SCF::get('work-galery', get_the_ID()) : array();
	$gallery_items = array();

	if (is_array($work_gallery)) {
		foreach ($work_gallery as $gallery_item) {
			if (empty($gallery_item['work_galery_item'])) {
				continue;
			}

			$image_id = (int) $gallery_item['work_galery_item'];
			$full_url = wp_get_attachment_image_url($image_id, 'large');
			$thumb_url = wp_get_attachment_image_url($image_id, 'thumbnail');

			if ($full_url) {
				$gallery_items[] = array(
					'full'  => $full_url,
					'thumb' => $thumb_url ? $thumb_url : $full_url,
				);
			}
		}
	}

	$work_id           = class_exists('SCF') ? SCF::get('work_id', get_the_ID()) : '';
	$work_price        = class_exists('SCF') ? SCF::get('work_price', get_the_ID()) : '';
	$work_address      = class_exists('SCF') ? SCF::get('work_address', get_the_ID()) : '';
	$work_market_type  = class_exists('SCF') ? SCF::get('work_market_type', get_the_ID()) : '';
	$work_object_type  = class_exists('SCF') ? SCF::get('work_object_type', get_the_ID()) : '';
	$work_house_area   = class_exists('SCF') ? SCF::get('work_house_area', get_the_ID()) : '';
	$work_land_area    = class_exists('SCF') ? SCF::get('work_land_area', get_the_ID()) : '';
	$work_rooms        = class_exists('SCF') ? SCF::get('work_rooms', get_the_ID()) : '';
	$work_bedrooms     = class_exists('SCF') ? SCF::get('work_bedrooms', get_the_ID()) : '';
	$work_bathrooms    = class_exists('SCF') ? SCF::get('work_bathrooms', get_the_ID()) : '';
	$work_security     = class_exists('SCF') ? SCF::get('work_security', get_the_ID()) : '';
	$work_water_supply = class_exists('SCF') ? SCF::get('work_water_supply', get_the_ID()) : '';
	$work_gas_supply   = class_exists('SCF') ? SCF::get('work_gas_supply', get_the_ID()) : '';
	$work_garage_type  = class_exists('SCF') ? SCF::get('work_garage_type', get_the_ID()) : '';
	$work_properties   = class_exists('SCF') ? SCF::get('work-properties', get_the_ID()) : array();

	if (empty($work_id)) {
		$work_id = get_the_ID();
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

	$land_area_display = '';
	if ($work_land_area !== '' && $work_land_area !== null) {
		if (is_numeric($work_land_area)) {
			$number = (int) $work_land_area;
			$land_area_display = $number . ' ' . sws_work_sotki_label($number);
		} else {
			$land_area_display = $work_land_area;
		}
	}
	?>

	<div class="workSingle">
		<div class="workSingle__header">
			<h1 class="page__title"><?php the_title(); ?></h1>
			<?php
			$work_categories = get_the_terms(get_the_ID(), 'works_category');
			if (!empty($work_categories) && !is_wp_error($work_categories)) :
				$main_category = reset($work_categories);
				$category_url  = get_term_link($main_category, 'works_category');
			?>
				<a href="<?php echo esc_url($category_url); ?>" class="btn btn_success btn_small">
					<?php echo esc_html($main_category->name); ?>
				</a>
			<?php endif; ?>
		</div>

		<div class="workSingle__main">
			<div class="workSingle__left">
				<?php if (!empty($gallery_items)) : ?>
					<div class="workSingle__gallery">
						<div class="workSingle__sliderWrapper">
							<div class="workSingle__slider js-workSingle-main">
								<?php foreach ($gallery_items as $image) : ?>
									<div class="workSingle__slide">
										<div class="workSingle__slideLink">
											<img src="<?php echo esc_url($image['full']); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
											<span class="workSingle__zoom" role="button" tabindex="0">
												<span class="ie-icon_zoom"></span>
											</span>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>

						<?php if (count($gallery_items) > 1) : ?>
							<div class="workSingle__thumbs js-workSingle-thumbs">
								<?php foreach ($gallery_items as $index => $image) : ?>
									<button type="button" class="workSingle__thumb" data-slide="<?php echo esc_attr($index); ?>">
										<img src="<?php echo esc_url($image['thumb']); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
									</button>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php else : ?>
					<div class="workSingle__image">
						<?php the_post_thumbnail('large'); ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="workSingle__right">
				<div class="workSingle__top">
					<div class="workSingle__row workSingle__row--id">
						<div class="workSingle__id">ID <?php echo esc_html($work_id); ?></div>
						<div class="works__itemIcons workSingle__share">
							<button type="button"
								class="works__itemShare"
								aria-label="Поделиться ссылкой"
								data-share-url="<?php echo esc_url(get_permalink()); ?>"
								data-share-title="<?php echo esc_attr(get_the_title()); ?>"
								data-work-id="<?php echo esc_attr($work_id); ?>">
								<span class="ie-icon_share"></span>
							</button>
							<button type="button"
								class="works__itemFavorite"
								data-work-id="<?php echo esc_attr(get_the_ID()); ?>"
								aria-pressed="false"
								aria-label="Добавить в избранное">
								<span class="ie-icon_heart"></span>
							</button>
						</div>
					</div>

					<h2 class="section__title">Основные характеристики</h2>

					<?php if ($work_address) : ?>
						<div class="workSingle__row workSingle__row--address">
							<div class="workSingle__value"><?php echo $work_address; ?></div>
						</div>
					<?php endif; ?>

					<div class="workSingle__params">
						<?php if ($work_market_type) : ?>
							<div class="workSingle__param workSingle__param--market">
								<div class="workSingle__paramName">Тип недвижимости</div>
								<div class="workSingle__paramValue"><?php echo esc_html($work_market_type); ?></div>
							</div>
						<?php endif; ?>

						<?php if ($work_object_type) : ?>
							<div class="workSingle__param workSingle__param--object">
								<div class="workSingle__paramName">Тип объекта</div>
								<div class="workSingle__paramValue"><?php echo esc_html($work_object_type); ?></div>
							</div>
						<?php endif; ?>

						<?php if ($work_house_area) : ?>
							<div class="workSingle__param workSingle__param--house-area">
								<div class="workSingle__paramName">Площадь дома</div>
								<div class="workSingle__paramValue"><?php echo esc_html($work_house_area); ?> м²</div>
							</div>
						<?php endif; ?>

						<?php if ($land_area_display) : ?>
							<div class="workSingle__param workSingle__param--land-area">
								<div class="workSingle__paramName">Площадь участка</div>
								<div class="workSingle__paramValue"><?php echo esc_html($land_area_display); ?></div>
							</div>
						<?php endif; ?>

						<?php if ($work_rooms) : ?>
							<div class="workSingle__param workSingle__param--rooms">
								<div class="workSingle__paramName">Комнаты</div>
								<div class="workSingle__paramValue"><?php echo esc_html($work_rooms); ?></div>
							</div>
						<?php endif; ?>

						<?php if ($work_bedrooms) : ?>
							<div class="workSingle__param workSingle__param--bedrooms">
								<div class="workSingle__paramName">Спальни</div>
								<div class="workSingle__paramValue"><?php echo esc_html($work_bedrooms); ?></div>
							</div>
						<?php endif; ?>

						<?php if ($work_bathrooms) : ?>
							<div class="workSingle__param workSingle__param--bathrooms">
								<div class="workSingle__paramName">Санузлы</div>
								<div class="workSingle__paramValue"><?php echo esc_html($work_bathrooms); ?></div>
							</div>
						<?php endif; ?>

						<?php if ($work_security) : ?>
							<div class="workSingle__param workSingle__param--security">
								<div class="workSingle__paramName">Охрана</div>
								<div class="workSingle__paramValue"><?php echo esc_html($work_security); ?></div>
							</div>
						<?php endif; ?>

						<?php if ($work_water_supply) : ?>
							<div class="workSingle__param workSingle__param--water">
								<div class="workSingle__paramName">Водоснабжение</div>
								<div class="workSingle__paramValue"><?php echo esc_html($work_water_supply); ?></div>
							</div>
						<?php endif; ?>

						<?php if ($work_gas_supply) : ?>
							<div class="workSingle__param workSingle__param--gas">
								<div class="workSingle__paramName">Газоснабжение</div>
								<div class="workSingle__paramValue"><?php echo esc_html($work_gas_supply); ?></div>
							</div>
						<?php endif; ?>

						<?php if ($work_garage_type) : ?>
							<div class="workSingle__param workSingle__param--garage">
								<div class="workSingle__paramName">Тип гаража</div>
								<div class="workSingle__paramValue"><?php echo esc_html($work_garage_type); ?></div>
							</div>
						<?php endif; ?>

						<?php if (!empty($work_properties) && is_array($work_properties)) : ?>
							<?php foreach ($work_properties as $property) : ?>
								<?php
								$title = isset($property['work_property_title']) ? trim($property['work_property_title']) : '';
								$value = isset($property['work_property_value']) ? trim($property['work_property_value']) : '';

								if ($title === '' && $value === '') {
									continue;
								}
								?>
								<div class="workSingle__param">
									<?php if ($title !== '') : ?>
										<div class="workSingle__paramName"><?php echo esc_html($title); ?></div>
									<?php endif; ?>
									<?php if ($value !== '') : ?>
										<div class="workSingle__paramValue"><?php echo esc_html($value); ?></div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="workSingle__bottom">
					<?php if ($work_price !== '' && $work_price !== null) : ?>
						<div class="workSingle__row workSingle__row--price">
							<div class="workSingle__value">
								<?php echo esc_html(number_format_i18n($work_price, 0)); ?>
								<svg width="15" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="rub">
									<path d="M1.675 17.85V0H7.85C10.1167 0 11.8083 0.491667 12.925 1.475C14.0583 2.45833 14.625 3.85833 14.625 5.675C14.625 6.70833 14.4083 7.68333 13.975 8.6C13.5583 9.5 12.8583 10.2333 11.875 10.8C10.8917 11.3667 9.55 11.6583 7.85 11.675H6.5V17.85H1.675ZM0 16.25V13.325H9.625V16.25H0ZM0 11.675V7.75H7.325V11.675H0ZM7.375 7.75C7.79167 7.75 8.175 7.675 8.525 7.525C8.89167 7.375 9.18333 7.15 9.4 6.85C9.63333 6.53333 9.75 6.15 9.75 5.7C9.75 5.13333 9.59167 4.69167 9.275 4.375C8.95833 4.05833 8.45 3.9 7.75 3.9H6.5V7.75H7.375Z" fill="#284A42" />
								</svg>
							</div>
						</div>
					<?php endif; ?>

					<div class="workSingle__actions">
						<button type="button" class="btn workSingle__btn" data-toggle="modal" data-target="#getConsult">
							Получить консультацию
						</button>

						<?php if ($whatsapp) : ?>
							<a href="<?php echo esc_url($whatsapp); ?>" class="workSingle__icon workSingle__icon--whatsapp" target="_blank" rel="noopener">
								<span class="ie-icon_whatsapp"></span>
							</a>
						<?php endif; ?>

						<?php if ($tg) : ?>
							<a href="<?php echo esc_url($tg); ?>" class="workSingle__icon workSingle__icon--telegram" target="_blank" rel="noopener">
								<span class="ie-icon_tg"></span>
							</a>
						<?php endif; ?>

						<button type="button" class="btn btn_secondary workSingle__btn workSingle__btn--secondary" data-toggle="modal" data-target="#getConsult">
							Записаться на просмотр
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>