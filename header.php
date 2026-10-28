<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php wp_head(); ?>
</head>
<body>

<a class="skip-link" href="#main">Перейти к содержимому</a>

<header class="header" data-header>
	<div class="header__topbar">
		<div class="container header__topbar-inner">
			<p class="header__topbar-location">Производство: <?php echo esc_html( theme_opt( 'address' ) ); ?> · поставки по всей России и в страны ЕАЭС</p>
			<p class="header__topbar-terms">Договор и безнал для юрлиц РФ · <?php echo esc_html( theme_opt( 'header_topbar_hours' ) ); ?> · заявки онлайн 24/7</p>
		</div>
	</div>

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

			<div class="header__messengers">
				<?php
				$theme_messengers = [
					'viber'    => [ 'Viber', 'icon-viber' ],
					'whatsapp' => [ 'WhatsApp', 'icon-whatsapp' ],
					'telegram' => [ 'Telegram', 'icon-telegram' ],
				];
				foreach ( $theme_messengers as $theme_field => $theme_item ) :
					$theme_url = theme_opt( $theme_field );
					if ( ! $theme_url ) continue;
					?>
					<a href="<?php echo esc_url( $theme_url, [ 'http', 'https', 'viber', 'whatsapp' ] ); ?>" aria-label="<?php echo esc_attr( $theme_item[0] ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo theme_icon( $theme_item[1] ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<nav class="nav" id="site-nav" aria-label="Разделы" data-nav>
		<div class="container">
			<?php
			wp_nav_menu( [
				'theme_location' => 'header_menu',
				'container'      => false,
				'menu_class'     => 'nav__list',
				'fallback_cb'    => false,
			] );
			?>
		</div>
	</nav>
</header>
