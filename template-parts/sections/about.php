<?php
defined( 'ABSPATH' ) || exit;

$metrics = get_field( 'about_metrics' ) ?: array();
?>
<section class="about" aria-labelledby="about-title">
	<div class="container about__inner">
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
					<?php $value = preg_match( '/^([\d\s.,+]+?)\s+(\D.*)$/u', $metric['value'], $parts ) ? $parts : array( $metric['value'], $metric['value'], '' ); ?>
					<li class="metric">
						<span class="metric__icon"><?php echo theme_icon( 'icon-' . $metric['icon'] ); ?></span>
						<strong class="metric__value"><?php echo esc_html( $value[1] ); ?><?php echo $value[2] ? ' <span class="metric__unit">' . esc_html( $value[2] ) . '</span>' : ''; ?></strong>
						<span class="metric__label"><?php echo esc_html( $metric['label'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
