<?php
get_header();

$phone      = theme_opt( 'phone' );
$messengers = array(
	'viber'    => array( 'Viber', 'viber.svg' ),
	'whatsapp' => array( 'WhatsApp', 'whatsapp.svg' ),
	'telegram' => array( 'Telegram', 'tg.svg' ),
);
?>

<main id="main">
	<section class="section-padding thanks" aria-labelledby="thanks-title">
		<div class="container thanks__inner">
			<span class="thanks__icon"><?php echo theme_icon( 'icon-tick' ); ?></span>
			<h1 class="text-h1 thanks__title" id="thanks-title"><?php the_title(); ?></h1>
			<div class="thanks__text"><?php the_content(); ?></div>

			<div class="thanks__actions">
				<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">На главную</a>
				<?php if ( $phone ) : ?>
					<a class="btn btn--outline" href="<?php echo esc_attr( 'tel:' . preg_replace( '/[^\d+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
				<?php endif; ?>
			</div>

			<div class="thanks__messengers">
				<?php foreach ( $messengers as $field => $messenger ) : ?>
					<?php $url = theme_opt( $field ); ?>
					<?php if ( $url ) : ?>
						<a class="thanks__messenger" href="<?php echo esc_url( $url, array( 'http', 'https', 'viber', 'whatsapp' ) ); ?>" aria-label="<?php echo esc_attr( $messenger[0] ); ?>" target="_blank" rel="noopener noreferrer">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand/' . $messenger[1] ); ?>" alt="" width="44" height="44" decoding="async">
						</a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
