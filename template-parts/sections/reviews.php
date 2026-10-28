<?php
defined( 'ABSPATH' ) || exit;

$reviews = get_field( 'reviews' ) ?: array();
?>
<section class="section-padding reviews dark-bg is-dark" id="reviews" aria-labelledby="reviews-title">
	<div class="container reviews__inner">
		<div class="reviews__head">
			<div class="reviews__heading">
				<?php get_template_part( 'template-parts/components/eyebrow', null, array( 'text' => get_field( 'reviews_eyebrow' ), 'variant' => 'dark' ) ); ?>
				<h2 class="text-h2" id="reviews-title"><?php echo theme_accent_text( get_field( 'reviews_title' ) ); ?></h2>
			</div>
			<p class="reviews__text"><?php echo esc_html( get_field( 'reviews_text' ) ); ?></p>
		</div>

		<?php if ( $reviews ) : ?>
			<div class="reviews__body" data-reviews>
				<div class="reviews__scans">
					<?php foreach ( $reviews as $index => $review ) : ?>
						<div class="reviews__scan" data-review-panel="<?php echo esc_attr( $index ); ?>"<?php echo 0 === $index ? '' : ' hidden'; ?>>
							<?php echo wp_get_attachment_image( $review['image'], 'review-2x', false, array( 'class' => 'reviews__scan-img', 'alt' => 'Благодарственное письмо: ' . $review['company'], 'sizes' => '(max-width: 860px) 250px, 400px', 'loading' => 0 === $index ? 'eager' : 'lazy' ) ); ?>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="reviews__info">
					<?php foreach ( $reviews as $index => $review ) : ?>
						<div class="reviews__panel" data-review-panel="<?php echo esc_attr( $index ); ?>"<?php echo 0 === $index ? '' : ' hidden'; ?>>
							<?php echo theme_icon( 'icon-quote', 'reviews__quote-icon' ); ?>
							<p class="reviews__quote"><?php echo esc_html( $review['quote'] ); ?></p>
							<div class="reviews__author">
								<span class="reviews__initial" aria-hidden="true"><?php echo esc_html( $review['initial'] ); ?></span>
								<span class="reviews__author-body">
									<strong class="reviews__company"><?php echo esc_html( $review['company'] ); ?></strong>
									<span class="reviews__meta"><?php echo esc_html( $review['meta'] ); ?></span>
								</span>
							</div>
						</div>
					<?php endforeach; ?>

					<div class="reviews__thumbs" role="group" aria-label="Письма">
						<?php foreach ( $reviews as $index => $review ) : ?>
							<button class="reviews__thumb" type="button" aria-label="<?php echo esc_attr( $review['company'] ); ?>" aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>" data-review-thumb="<?php echo esc_attr( $index ); ?>">
								<?php echo wp_get_attachment_image( $review['image'], 'review-thumb-2x', false, array( 'class' => 'reviews__thumb-img', 'alt' => '', 'loading' => 'lazy' ) ); ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
