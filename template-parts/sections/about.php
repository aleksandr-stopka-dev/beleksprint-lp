<?php
defined( 'ABSPATH' ) || exit;

$metrics = get_field( 'about_metrics' ) ?: array();
?>
<section class="about" aria-labelledby="about-title">
	<div class="container">
		<div class="about__head">
			<div class="about__heading">
				<?php get_template_part( 'template-parts/components/eyebrow', null, array( 'text' => get_field( 'about_eyebrow' ) ) ); ?>
				<h2 class="text-h2" id="about-title"><?php echo theme_accent_text( get_field( 'about_title' ) ); ?></h2>
			</div>
			<p class="about__text"><?php echo esc_html( get_field( 'about_text' ) ); ?></p>
		</div>

		<?php if ( $metrics ) : ?>
			<ul class="about__metrics">
				<?php foreach ( $metrics as $metric ) : ?>
					<li class="metric">
						<span class="metric__icon"><?php echo theme_icon( 'icon-' . $metric['icon'] ); ?></span>
						<strong class="metric__value"><?php echo esc_html( $metric['value'] ); ?></strong>
						<span class="metric__label"><?php echo esc_html( $metric['label'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
