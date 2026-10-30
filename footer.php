<footer class="footer">
	<div class="container footer__grid">
		<div class="footer__brand">
			<?php echo theme_logo( 'logo' ); ?>
			<?php if ( theme_opt( 'footer_about' ) ) : ?>
				<p><?php echo esc_html( theme_opt( 'footer_about' ) ); ?></p>
			<?php endif; ?>
		</div>

		<nav class="footer__nav" aria-label="Навигация в подвале">
			<strong class="footer__nav-title">Разделы</strong>
			<?php
			wp_nav_menu( [
				'theme_location' => 'footer_menu',
				'container'      => false,
				'menu_class'     => 'footer__nav-list',
				'fallback_cb'    => false,
			] );
			?>
		</nav>

		<div class="footer__legal">
			<?php if ( theme_opt( 'footer_legal_title' ) ) : ?>
				<strong class="footer__legal-title"><?php echo str_replace( ' — ', ' —<br class="footer__br"> ', esc_html( theme_opt( 'footer_legal_title' ) ) ); ?></strong>
			<?php endif; ?>
			<?php if ( theme_opt( 'footer_legal_text' ) ) : ?>
				<p><?php echo esc_html( theme_opt( 'footer_legal_text' ) ); ?></p>
			<?php endif; ?>
			<p>
				<?php echo esc_html( theme_opt( 'address' ) ); ?>
				<?php if ( theme_opt( 'address' ) && theme_opt( 'email' ) ) echo ' · '; ?>
				<a class="footer__legal-link" href="<?php echo esc_attr( 'mailto:' . theme_opt( 'email' ) ); ?>"><?php echo esc_html( theme_opt( 'email' ) ); ?></a>
			</p>
		</div>
	</div>

	<div class="container footer__bottom">
		<span class="footer__copy">© 2011–<?php echo esc_html( date( 'Y' ) ); ?> Beleksprint · upak-bel.ru</span>
		<a class="footer__dev" href="https://t.me/aleksandr_stopka" target="_blank" rel="nofollow noopener noreferrer">Разработка сайта — @aleksandr_stopka</a>
		<?php $theme_policy = theme_link_parts( theme_opt( 'footer_policy' ) ); ?>
		<?php if ( $theme_policy ) : ?>
			<a class="footer__policy" href="<?php echo esc_url( $theme_policy['url'] ); ?>"<?php echo '_blank' === $theme_policy['target'] ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $theme_policy['title'] ); ?></a>
		<?php endif; ?>
	</div>
</footer>

<?php
$theme_phone = theme_opt( 'phone' );
if ( $theme_phone ) :
	$theme_email = theme_opt( 'email' );
	?>
	<div class="fab" data-fab>
		<div class="fab__panel" id="fab-panel" role="dialog" aria-label="Связаться с менеджером" data-fab-panel>
			<p class="fab__status">
				<strong class="fab__name">Ваш менеджер</strong>
				<?php echo esc_html( theme_opt( 'manager_status' ) ); ?>
			</p>
			<a class="fab__phone" href="<?php echo esc_attr( 'tel:' . preg_replace( '/[^\d+]/', '', $theme_phone ) ); ?>"><?php echo esc_html( $theme_phone ); ?></a>
			<?php if ( $theme_email ) : ?>
				<a class="fab__email" href="<?php echo esc_attr( 'mailto:' . $theme_email ); ?>">
					<span class="header__icon"><?php echo theme_icon( 'icon-email' ); ?></span>
					<?php echo esc_html( $theme_email ); ?>
				</a>
			<?php endif; ?>
			<?php get_template_part( 'template-parts/components/messengers', null, array( 'class' => 'fab__messengers' ) ); ?>
		</div>
		<button class="fab__toggle" type="button" aria-expanded="false" aria-controls="fab-panel" aria-label="Связаться с менеджером" data-fab-toggle>
			<?php if ( theme_opt( 'manager_photo' ) ) : ?>
				<?php echo theme_image( theme_opt( 'manager_photo' ), 'fab__photo', '60px', false ); ?>
			<?php else : ?>
				<?php echo theme_icon( 'icon-user', 'fab__icon' ); ?>
			<?php endif; ?>
		</button>
	</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
