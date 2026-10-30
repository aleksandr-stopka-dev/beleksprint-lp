<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php wp_head(); ?>
</head>
<body>

<a class="skip-link" href="#main">Перейти к содержимому</a>

<?php
$theme_location = 'Производство: ' . theme_opt( 'address' ) . ' · поставки по всей России и в страны ЕАЭС';
$theme_terms    = 'Договор и безнал для юрлиц РФ · ' . theme_opt( 'header_topbar_hours' ) . ' · заявки онлайн 24/7';
$theme_phone    = theme_opt( 'phone' );
$theme_tel      = $theme_phone ? 'tel:' . preg_replace( '/[^\d+]/', '', $theme_phone ) : '';
$theme_email    = theme_opt( 'email' );
?>
<div class="header__topbar">
	<div class="container header__topbar-inner">
		<p class="header__topbar-location"><?php echo esc_html( $theme_location ); ?></p>
		<p class="header__topbar-terms"><?php echo esc_html( $theme_terms ); ?></p>
	</div>
</div>

<header class="header" data-header>
	<div class="header__top">
		<div class="container header__inner">
			<a class="header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Beleksprint — на главную">
				<?php echo theme_logo( 'logo' ); ?>
			</a>

			<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="Открыть меню" data-nav-toggle>
				<span></span>
				<span></span>
				<span></span>
			</button>

			<?php if ( theme_opt( 'header_pill' ) ) : ?>
				<span class="header__pill"><?php echo esc_html( theme_opt( 'header_pill' ) ); ?></span>
			<?php endif; ?>

			<?php if ( theme_opt( 'email' ) ) : ?>
				<a class="header__email" href="<?php echo esc_attr( 'mailto:' . theme_opt( 'email' ) ); ?>">
					<span class="header__icon">
						<?php echo theme_icon( 'icon-email' ); ?>
					</span>
					<span class="header__email-body">
						<strong class="header__email-value"><?php echo esc_html( theme_opt( 'email' ) ); ?></strong>
						<?php if ( theme_opt( 'header_email_note' ) ) : ?>
							<span class="header__email-note"><?php echo esc_html( theme_opt( 'header_email_note' ) ); ?></span>
						<?php endif; ?>
					</span>
				</a>
			<?php endif; ?>

			<?php if ( theme_opt( 'phone' ) ) : ?>
				<div class="manager">
					<?php if ( theme_opt( 'manager_photo' ) ) : ?>
						<span class="manager__avatar">
							<?php echo theme_image( theme_opt( 'manager_photo' ), 'manager__photo', '46px', false ); ?>
						</span>
					<?php endif; ?>
					<div class="manager__info">
						<a class="manager__phone" href="<?php echo esc_attr( 'tel:' . preg_replace( '/[^\d+]/', '', theme_opt( 'phone' ) ) ); ?>"><?php echo esc_html( theme_opt( 'phone' ) ); ?></a>
						<p class="manager__status">
							<?php echo esc_html( theme_opt( 'manager_status' ) ); ?>
							<?php if ( theme_opt( 'manager_callback' ) ) : ?>
								· <a class="manager__callback" href="<?php echo esc_url( home_url( '/#contacts' ) ); ?>"><?php echo esc_html( theme_opt( 'manager_callback' ) ); ?></a>
							<?php endif; ?>
						</p>
					</div>
				</div>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/components/messengers', null, array( 'class' => 'header__messengers' ) ); ?>
		</div>
	</div>

	<nav class="nav" id="site-nav" aria-label="Основная навигация" data-nav>
		<div class="container">
			<?php
			wp_nav_menu( [
				'theme_location' => 'header_menu',
				'container'      => false,
				'menu_class'     => 'nav__list',
				'fallback_cb'    => false,
			] );
			?>

			<div class="nav__contacts">
				<?php if ( $theme_tel ) : ?>
					<div class="manager manager--nav">
						<?php if ( theme_opt( 'manager_photo' ) ) : ?>
							<span class="manager__avatar">
								<?php echo theme_image( theme_opt( 'manager_photo' ), 'manager__photo', '46px', false ); ?>
							</span>
						<?php endif; ?>
						<div class="manager__info">
							<a class="manager__phone" href="<?php echo esc_attr( $theme_tel ); ?>"><?php echo esc_html( $theme_phone ); ?></a>
							<p class="manager__status">
								<?php echo esc_html( theme_opt( 'manager_status' ) ); ?>
								<?php if ( theme_opt( 'manager_callback' ) ) : ?>
									· <a class="manager__callback" href="<?php echo esc_url( home_url( '/#contacts' ) ); ?>"><?php echo esc_html( theme_opt( 'manager_callback' ) ); ?></a>
								<?php endif; ?>
							</p>
						</div>
					</div>
				<?php endif; ?>
				<?php if ( $theme_email ) : ?>
					<a class="nav__email" href="<?php echo esc_attr( 'mailto:' . $theme_email ); ?>">
						<span class="header__icon"><?php echo theme_icon( 'icon-email' ); ?></span>
						<?php echo esc_html( $theme_email ); ?>
					</a>
				<?php endif; ?>
				<?php get_template_part( 'template-parts/components/messengers', null, array( 'class' => 'nav__messengers' ) ); ?>
				<p class="nav__info"><?php echo esc_html( $theme_location ); ?></p>
				<p class="nav__info"><?php echo esc_html( $theme_terms ); ?></p>
			</div>
		</div>
	</nav>
</header>
