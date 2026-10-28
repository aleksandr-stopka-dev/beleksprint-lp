<?php
defined( 'ABSPATH' ) || exit;

$items = get_field( 'why_items' ) ?: array();
?>
<section class="section-padding why" id="why" aria-labelledby="why-title">
	<div class="container why__inner">
		<div class="why__heading">
			<?php get_template_part( 'template-parts/components/eyebrow', null, array( 'text' => get_field( 'why_eyebrow' ) ) ); ?>
			<h2 class="text-h2" id="why-title"><?php echo theme_accent_text( get_field( 'why_title' ) ); ?></h2>
		</div>

		<?php if ( $items ) : ?>
			<ul class="why__list hs">
				<?php foreach ( $items as $item ) : ?>
					<li class="why__item why__item--<?php echo esc_attr( $item['style'] ); ?>">
						<span class="why__icon"><?php echo theme_icon( 'icon-' . $item['icon'] ); ?></span>
						<strong class="why__title"><?php echo esc_html( $item['title'] ); ?></strong>
						<span class="why__text"><?php echo esc_html( $item['text'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
