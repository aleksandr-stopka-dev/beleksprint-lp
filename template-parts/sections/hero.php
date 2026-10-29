<?php
defined( 'ABSPATH' ) || exit;

$facts = get_field( 'hero_facts' ) ?: array();
?>
<section class="hero dark-bg dark-bg--dots is-dark" aria-labelledby="hero-title">
	<div class="container hero__inner">
		<div class="hero__content">
			<p class="chip chip--outline hero__chip">
				<?php echo theme_mosaic(); ?>
				<span><?php echo esc_html( get_field( 'hero_chip' ) ); ?></span>
			</p>

			<h1 class="text-h1 hero__title" id="hero-title"><?php echo theme_accent_text( get_field( 'hero_title' ) ); ?></h1>

			<p class="hero__text"><?php echo esc_html( get_field( 'hero_text' ) ); ?></p>

			<div class="hero__actions">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'link'    => get_field( 'hero_btn_catalog' ),
						'variant' => 'primary',
						'icon'    => 'icon-arrow-right',
					)
				);
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'link'        => get_field( 'hero_btn_price' ),
						'variant'     => 'ghost',
						'icon'        => 'icon-download',
						'icon_before' => true,
						'class'       => 'hero__button',
					)
				);
				?>
			</div>

			<?php if ( $facts ) : ?>
				<ul class="hero__facts">
					<?php foreach ( $facts as $fact ) : ?>
						<li class="hero__fact">
							<strong class="hero__fact-value"><?php echo esc_html( $fact['value'] ); ?></strong>
							<span class="hero__fact-label"><?php echo esc_html( $fact['label'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<?php get_template_part( 'template-parts/sections/quiz' ); ?>
	</div>
</section>
