<?php
$phone = get_theme_mod('site_phone');
$email = get_theme_mod('site_email');
$address = get_theme_mod('site_address');
$job_time = get_theme_mod('site_job_time');
$map = get_theme_mod('site_map');
$form = get_theme_mod('form__contact');
?>
<!-- begin contacts -->
<section id="contacts" class="contacts section">
	<h1 class="page__title"><?php the_title() ?></h1>
	<div class="contacts__content">
		<div class="contacts__left">
			<?php if ($phone): ?>
				<p><b>Телефон:</b> <a href="tel: <?php echo $phone; ?>"><?php echo $phone; ?></a></p>
			<?php endif; ?>
			<?php if ($email): ?>
				<p><b>Email:</b> <a href="mailto: <?php echo $email; ?>"><?php echo $email; ?></a></p>
			<?php endif; ?>
			<?php if ($address): ?>
				<p><b>Адрес:</b> <?php echo $address ?></p>
			<?php endif; ?>
			<?php if ($job_time): ?>
				<p><b>Время работы:</b> <?php echo $job_time ?></p>
			<?php endif; ?>
		</div>
		<?php if ($form): ?>
			<div class="contacts__right">
				<?php echo do_shortcode($form); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php if ($map): ?>
		<div class="contacts__map">
			<?php echo $map ?>
		</div>
	<?php endif; ?>
</section>
<!-- end contacts -->