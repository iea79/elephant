<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sws
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'sws'); ?></a>
	<?php
	$sticked = get_theme_mod('sticked', HEADER_STICKED);
	$showTop = get_theme_mod('top', HEADER_TOP);
	$showAddress = get_theme_mod('show_address', SHOW_ADDRESS);
	$address = get_theme_mod('site_address', SITE_ADDRESS);
	$showPhone = get_theme_mod('show_phone', SHOW_PHONE);
	$phone = get_theme_mod('site_phone', SITE_PHONE);
	$showEmail = get_theme_mod('show_email', SHOW_EMAIL);
	$email = get_theme_mod('site_email', SITE_EMAIL);
	$showJobTime = get_theme_mod('show_job_time', SHOW_JOB_TIME);
	$jobTime = get_theme_mod('site_job_time');
	$showCallbackBtn = get_theme_mod('show_callback_bth', SHOW_CALLBACK_BTH);
	$headerCallbackStyle = get_theme_mod('header_callback_style', 'btn_primary');
	$addressTime = '';
	if ($showAddress && $address) {
		$addressTime = $address;
	}
	if ($showJobTime && $jobTime) {
		$addressTime .= ' ' . $jobTime;
	}
	if (theme_check_required_plugins(theme_get_required_plugins())) {
	?>

		<header id="masthead" class="header<?php echo $sticked ? ' header_sticked' : '' ?>">
			<div class="header__content">
				<div class="header__logo">
					<?php
					renderLogo()
					?>
				</div><!-- .logo-->

				<nav id="site-navigation" class="nav">
					<div class="nav__head mobile">
						<div class="nav__logo">
							<?php
							renderLogo()
							?>
						</div>
						<button class="nav__toggle active" aria-controls="primary-menu" aria-expanded="false"></button>
					</div>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'main-menu',
							'menu_id'        => 'primary-menu',
							'container'  	 => false,
						)
					);
					?>
					<?php if ($showCallbackBtn) { ?>
						<div class="nav__callback mobile">
							<button class="btn <?php echo $headerCallbackStyle ?>" data-toggle="modal" data-target="#callback">Обратный звонок</button>
						</div>
					<?php } ?>
				</nav><!-- #site-navigation -->
				<div class="header__actions">
					<div class="header__search">
						<i class="ie-icon_search search__toggle"></i>
					</div>
					<div class="header__favor">
						<?php
						$favorites_page_id = (int) get_theme_mod('favorites_page');
						$favorites_url = $favorites_page_id ? get_permalink($favorites_page_id) : '#';
						?>
						<a href="<?php echo esc_url($favorites_url); ?>" class="header__favorLink" aria-label="<?php esc_attr_e('Избранное', 'sws'); ?>">
							<i class="ie-icon_heart"></i>
						</a>
					</div>
					<div class="header__phone">
						<a href="tel:<?php echo $phone ?>">
							<?php echo $phone ?>
						</a>
					</div>
					<?php if ($showCallbackBtn) { ?>
						<div class="header__callback desktop">
							<button class="btn btn_small <?php echo $headerCallbackStyle ?>" data-toggle="modal" data-target="#callback">Обратный звонок</button>
						</div>
					<?php } ?>
					<button class="nav__toggle" aria-controls="primary-menu" aria-expanded="false"></button>
				</div>
			</div>
			<div class="header__fullnav">
				<div class="header__fullnavContent">
					<div class="header__fullnavImg">
						<?php echo wp_get_attachment_image(get_theme_mod('nav_menu_img'), 'full') ?>
					</div>
					<div class="header__fullnavBody">
						<div class="header__fullnavMenu">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'full-menu',
									'menu_id'        => 'full-menu',
									'container'  	 => false,
								)
							);
							?>

						</div>
						<div class="header__fullnavSearch">
							<?php echo do_shortcode('[works_search]'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="header__navsearch">
				<?php echo do_shortcode('[works_search]'); ?>
			</div>
		</header><!-- #masthead -->

	<?php } ?>

	<main id="primary" class="main">