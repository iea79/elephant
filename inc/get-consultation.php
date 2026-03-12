<!-- begin getConsultation -->
<section id="getConsultation" class="getConsultation section">
	<div class="container_center">
		<div class="getConsultation__content">
			<div class="getConsultation__left">
				<div class="getConsultation__top">
					<h2 class="section__title"><?php echo get_theme_mod('consult_title') ?></h2>
					<div class="getConsultation__text"><?php echo get_theme_mod('consult_description') ?></div>
				</div>
				<div class="getConsultation__action">
					<button class="btn btn_secondary" data-toggle="modal" data-target="#getConsult"><?php echo get_theme_mod('consult_button_text') ?></button>
					<button class="btn btn_border btn_border--contrast" data-toggle="modal" data-target="#getQuestion"><?php echo get_theme_mod('consult_button2_text') ?></button>
				</div>
			</div>
			<div class="getConsultation__right">
				<?php echo wp_get_attachment_image(get_theme_mod('consult_img'), 'full') ?>
			</div>
		</div>
	</div>
</section>
<!-- end getConsultation -->