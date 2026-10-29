<?php
defined( 'ABSPATH' ) || exit;

$steps = get_field( 'mark_steps' ) ?: array();
?>
<section class="section-padding mark dark-bg dark-bg--dots is-dark" aria-labelledby="mark-title">
	<div class="container mark__inner">
		<div class="mark__content">
			<?php get_template_part( 'template-parts/components/eyebrow', null, array( 'text' => get_field( 'mark_eyebrow' ), 'variant' => 'dark' ) ); ?>
			<h2 class="text-h2 mark__title" id="mark-title"><?php echo theme_accent_text( get_field( 'mark_title' ) ); ?></h2>
			<p class="mark__text"><?php echo esc_html( get_field( 'mark_text' ) ); ?></p>

			<ol class="mark__steps">
				<?php foreach ( $steps as $index => $step ) : ?>
					<li class="mark__step">
						<span class="mark__num" aria-hidden="true"><?php echo esc_html( $index + 1 ); ?></span>
						<span class="mark__step-body">
							<strong class="mark__step-title"><?php echo esc_html( $step['title'] ); ?></strong>
							<span class="mark__step-text"><?php echo esc_html( $step['text'] ); ?></span>
						</span>
					</li>
				<?php endforeach; ?>
			</ol>

			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'link'    => get_field( 'mark_link' ),
					'variant' => 'primary',
					'class'   => 'mark__button',
				)
			);
			?>
		</div>

		<div class="mark__visual">
			<div class="mark__card">
				<div class="mark__card-head">
					<span class="mark__brand"><?php echo theme_mosaic(); ?>Beleksprint</span>
					<span class="mark__tag">Упаковка</span>
				</div>
				<div class="mark__code">
					<img class="mark__datamatrix" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/graphics/datamatrix.svg' ); ?>" alt="Код маркировки DataMatrix" width="180" height="180" loading="lazy" decoding="async">
				</div>
				<span class="mark__line mark__line--title" aria-hidden="true"></span>
				<span class="mark__line" aria-hidden="true"></span>
				<span class="mark__line mark__line--short" aria-hidden="true"></span>
				<span class="mark__line mark__line--accent" aria-hidden="true"></span>
			</div>
			<span class="mark__badge mark__badge--ok">
				<?php echo theme_icon( 'icon-tick' ); ?>
				<?php echo esc_html( get_field( 'mark_badge_ok' ) ); ?>
			</span>
			<span class="mark__badge mark__badge--info"><?php echo esc_html( get_field( 'mark_badge_info' ) ); ?></span>
		</div>
	</div>
</section>
