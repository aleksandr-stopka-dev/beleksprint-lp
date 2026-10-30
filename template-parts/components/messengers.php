<?php
defined( 'ABSPATH' ) || exit;

$args = wp_parse_args( $args, array( 'class' => '' ) );

$theme_messengers = array(
	'viber'    => array( 'Viber', 'viber.svg' ),
	'whatsapp' => array( 'WhatsApp', 'whatsapp.svg' ),
	'telegram' => array( 'Telegram', 'tg.svg' ),
);
?>
<div class="<?php echo esc_attr( $args['class'] ); ?>">
	<?php
	foreach ( $theme_messengers as $theme_field => $theme_item ) :
		$theme_url = theme_opt( $theme_field );
		if ( ! $theme_url ) continue;
		?>
		<a href="<?php echo esc_url( $theme_url, array( 'http', 'https', 'viber', 'whatsapp' ) ); ?>" aria-label="<?php echo esc_attr( $theme_item[0] ); ?>" target="_blank" rel="noopener noreferrer">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand/' . $theme_item[1] ); ?>" alt="" width="38" height="38" decoding="async">
		</a>
	<?php endforeach; ?>
</div>
