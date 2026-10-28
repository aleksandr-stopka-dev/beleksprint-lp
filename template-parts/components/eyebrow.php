<?php
defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args,
	array(
		'text'    => '',
		'variant' => 'light',
	)
);

if ( '' === $args['text'] ) {
	return;
}
?>
<p class="eyebrow eyebrow--<?php echo esc_attr( $args['variant'] ); ?>">
	<?php echo theme_mosaic(); ?>
	<?php echo esc_html( $args['text'] ); ?>
</p>
