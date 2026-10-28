<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
	<header class="header">
		<div class="container">
			<div class="header__inner">
				<div class="header__logo">
					<?php if ( !is_front_page() ) : ?>
						<a href="<?php echo esc_url( home_url('/') ); ?>">
					<?php endif; ?>
						<?php echo theme_logo('logo'); ?>
					<?php if ( !is_front_page() ) : ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</header>