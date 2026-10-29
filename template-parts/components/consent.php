<?php
defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args,
	array(
		'name'    => 'consent',
		'purpose' => '',
		'dark'    => false,
		'class'   => '',
	)
);

$classes = trim( 'check ' . ( $args['dark'] ? 'check--dark ' : '' ) . $args['class'] );
?>
<label class="<?php echo esc_attr( $classes ); ?>">
	<input class="check__input" type="checkbox" name="<?php echo esc_attr( $args['name'] ); ?>" value="1" required>
	<span class="check__box"><?php echo theme_icon( 'icon-tick', 'check__icon' ); ?></span>
	<span class="check__text">Я даю согласие на обработку персональных данных в соответствии с <a class="check__link" href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">политикой конфиденциальности</a> (ст. 9 ФЗ-152) <?php echo esc_html( $args['purpose'] ); ?></span>
</label>
