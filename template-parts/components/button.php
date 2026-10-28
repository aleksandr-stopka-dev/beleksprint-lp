<?php
defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args,
	array(
		'link'        => array(),
		'variant'     => 'primary',
		'icon'        => '',
		'icon_before' => false,
		'class'       => '',
	)
);

if ( empty( $args['link']['url'] ) || empty( $args['link']['title'] ) ) {
	return;
}

$is_external = ! empty( $args['link']['target'] ) && '_blank' === $args['link']['target'];
$classes     = trim( 'btn btn--' . $args['variant'] . ' ' . $args['class'] );
$icon        = $args['icon'] ? theme_icon( $args['icon'], 'btn__icon' ) : '';
?>
<a class="<?php echo esc_attr( $classes ); ?>" href="<?php echo esc_url( $args['link']['url'] ); ?>"<?php echo $is_external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
	<?php echo $args['icon_before'] ? $icon : ''; ?>
	<?php echo esc_html( $args['link']['title'] ); ?>
	<?php echo $args['icon_before'] ? '' : $icon; ?>
</a>
