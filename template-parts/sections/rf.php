<?php
defined( 'ABSPATH' ) || exit;

$steps  = get_field( 'rf_steps' ) ?: array();
$fears  = get_field( 'rf_fears' ) ?: array();
$docs   = get_field( 'rf_docs' ) ?: array();
$manager_photo = theme_opt( 'manager_photo' );
?>
<section class="section-padding rf" id="rf" aria-labelledby="rf-title">
	<div class="container rf__inner">
		<div class="rf__head">
			<div class="rf__heading">
				<?php get_template_part( 'template-parts/components/eyebrow', null, array( 'text' => get_field( 'rf_eyebrow' ) ) ); ?>
				<h2 class="text-h2" id="rf-title"><?php echo theme_accent_text( get_field( 'rf_title' ) ); ?></h2>
			</div>
			<p class="rf__text"><?php echo esc_html( get_field( 'rf_text' ) ); ?></p>
		</div>

		<?php if ( $steps ) : ?>
			<ol class="rf__steps hs">
				<?php foreach ( $steps as $index => $step ) : ?>
					<li class="rf__step<?php echo $index === count( $steps ) - 1 ? ' rf__step--accent' : ''; ?>">
						<span class="rf__step-head">
							<span class="rf__num<?php echo $index === count( $steps ) - 1 ? ' rf__num--accent' : ''; ?>" aria-hidden="true"><?php echo esc_html( $index + 1 ); ?></span>
							<span class="rf__step-tag"><?php echo esc_html( $step['tag'] ); ?></span>
						</span>
						<strong class="rf__step-title"><?php echo esc_html( $step['title'] ); ?></strong>
						<span class="rf__step-text"><?php echo esc_html( $step['text'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ol>
		<?php endif; ?>

		<div class="rf__bottom">
			<div class="rf__fears">
				<?php $fears_title = explode( ' — ', (string) get_field( 'rf_fears_title' ), 2 ); ?>
				<strong class="rf__fears-title"><?php echo esc_html( $fears_title[0] ); ?><?php echo isset( $fears_title[1] ) ? ' <span class="rf__fears-tail">— ' . esc_html( $fears_title[1] ) . '</span>' : ''; ?></strong>
				<?php foreach ( $fears as $fear ) : ?>
					<div class="rf__fear">
						<span class="rf__fear-icon"><?php echo theme_icon( 'icon-tick' ); ?></span>
						<span class="rf__fear-body">
							<strong class="rf__fear-question"><?php echo esc_html( $fear['question'] ); ?></strong>
							<span class="rf__fear-answer"><?php echo esc_html( $fear['answer'] ); ?></span>
						</span>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="rf__docs">
				<strong class="rf__docs-title"><?php echo esc_html( get_field( 'rf_docs_title' ) ); ?></strong>
				<ul class="rf__doc-list">
					<?php foreach ( $docs as $doc ) : ?>
						<li class="rf__doc">
							<?php echo theme_icon( 'icon-doc', 'rf__doc-icon' ); ?>
							<span><?php echo esc_html( $doc['text'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>

				<div class="rf__callout">
					<strong class="rf__callout-title"><?php echo esc_html( get_field( 'rf_callout_title' ) ); ?></strong>
					<span class="rf__callout-text"><?php echo esc_html( get_field( 'rf_callout_text' ) ); ?></span>
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'link'    => get_field( 'rf_callout_link' ),
							'variant' => 'primary',
							'icon'    => 'icon-download',
							'icon_before' => true,
							'class'   => 'btn--sm',
						)
					);
					?>
				</div>

				<div class="rf__manager">
					<?php if ( is_array( $manager_photo ) || is_numeric( $manager_photo ) ) : ?>
						<span class="rf__manager-photo">
							<?php echo wp_get_attachment_image( is_array( $manager_photo ) ? $manager_photo['ID'] : $manager_photo, 'avatar', false, array( 'class' => 'rf__manager-img', 'alt' => 'Менеджер Beleksprint', 'loading' => 'lazy' ) ); ?>
						</span>
					<?php endif; ?>
					<span class="rf__manager-text">
						<strong><?php echo esc_html( get_field( 'rf_manager_title' ) ); ?></strong>
						<?php echo esc_html( get_field( 'rf_manager_text' ) ); ?>
					</span>
				</div>
			</div>
		</div>
	</div>
</section>
