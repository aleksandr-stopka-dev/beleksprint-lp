<?php
defined( 'ABSPATH' ) || exit;

$items = get_field( 'catalog_items' ) ?: array();
$tabs  = array(
	'box'  => get_field( 'catalog_box_label' ),
	'tube' => get_field( 'catalog_tube_label' ),
);
$count = array_count_values( array_column( $items, 'category' ) );
?>
<section class="section-padding catalog" id="cat" aria-labelledby="catalog-title">
	<div class="container catalog__inner">
		<div class="catalog__head">
			<div class="catalog__heading">
				<?php get_template_part( 'template-parts/components/eyebrow', null, array( 'text' => get_field( 'catalog_eyebrow' ) ) ); ?>
				<h2 class="text-h2" id="catalog-title"><?php echo theme_accent_text( get_field( 'catalog_title' ) ); ?></h2>
			</div>

			<div class="tabs" role="tablist" aria-label="Категории" data-tabs>
				<?php
				$first = true;
				foreach ( $tabs as $key => $label ) :
					$panel_id = 'catalog-panel-' . $key;
					?>
					<button class="tabs__tab" type="button" role="tab" id="catalog-tab-<?php echo esc_attr( $key ); ?>" aria-selected="<?php echo $first ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $panel_id ); ?>" data-tab="<?php echo esc_attr( $key ); ?>">
						<?php echo esc_html( $label ); ?>
						<span class="tabs__count"><?php echo esc_html( $count[ $key ] ?? 0 ); ?></span>
					</button>
					<?php
					$first = false;
				endforeach;
				?>
			</div>
		</div>

		<div class="catalog__panels">
		<?php
		$first = true;
		foreach ( $tabs as $key => $label ) :
			$panel_id = 'catalog-panel-' . $key;
			?>
			<div class="catalog__panel hs" role="tabpanel" id="<?php echo esc_attr( $panel_id ); ?>" aria-labelledby="catalog-tab-<?php echo esc_attr( $key ); ?>" data-tab-panel="<?php echo esc_attr( $key ); ?>"<?php echo $first ? '' : ' hidden'; ?>>
				<?php
				foreach ( $items as $index => $item ) :
					if ( $item['category'] !== $key ) {
						continue;
					}
					?>
					<article class="catalog__card">
						<div class="catalog__media">
							<?php echo wp_get_attachment_image( $item['image'], 'full', false, array( 'class' => 'catalog__img', 'alt' => '', 'loading' => $index < 3 ? 'eager' : 'lazy' ) ); ?>
							<span class="chip chip--tag"><?php echo esc_html( $item['tag'] ); ?></span>
						</div>
						<div class="catalog__body">
							<h3 class="text-h3 catalog__title"><?php echo esc_html( $item['title'] ); ?></h3>
							<p class="text-sm catalog__text"><?php echo esc_html( $item['description'] ); ?></p>
							<?php if ( ! empty( $item['uses'] ) ) : ?>
								<ul class="catalog__uses">
									<?php foreach ( $item['uses'] as $use ) : ?>
										<li class="chip chip--use"><?php echo esc_html( $use['text'] ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<button class="btn btn--dark btn--sm catalog__calc" type="button" data-quiz-product="<?php echo esc_attr( $index ); ?>">
								Рассчитать
								<?php echo theme_icon( 'icon-arrow-small' ); ?>
							</button>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
			<?php
			$first = false;
		endforeach;
		?>
		</div>

		<div class="catalog__note">
			<span class="catalog__note-text">
				<strong class="catalog__note-title"><?php echo esc_html( get_field( 'catalog_note_title' ) ); ?></strong>
				<span><?php echo esc_html( get_field( 'catalog_note_text' ) ); ?></span>
			</span>
			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'link'    => get_field( 'catalog_note_link' ),
					'variant' => 'primary',
					'class'   => 'btn--sm',
				)
			);
			?>
		</div>
	</div>
</section>
