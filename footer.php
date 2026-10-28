<footer class="footer">
	<div class="container footer__grid">
		<div class="footer__brand">
			<?php echo theme_logo( 'logo' ); ?>
			<?php if ( theme_opt( 'footer_about' ) ) : ?>
				<p><?php echo esc_html( theme_opt( 'footer_about' ) ); ?></p>
			<?php endif; ?>
		</div>

		<nav class="footer__nav" aria-label="Разделы">
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
				<strong class="footer__legal-title"><?php echo esc_html( theme_opt( 'footer_legal_title' ) ); ?></strong>
			<?php endif; ?>
			<?php if ( theme_opt( 'footer_legal_text' ) ) : ?>
				<p><?php echo esc_html( theme_opt( 'footer_legal_text' ) ); ?></p>
			<?php endif; ?>
			<p>
				<?php echo esc_html( theme_opt( 'address' ) ); ?>
				<?php if ( theme_opt( 'address' ) && theme_opt( 'email' ) ) echo ' · '; ?>
				<?php echo esc_html( theme_opt( 'email' ) ); ?>
			</p>
		</div>
	</div>

	<div class="container footer__bottom">
		<span>© 2011–<?php echo esc_html( date( 'Y' ) ); ?> Beleksprint · upak-bel.ru</span>
		<?php $theme_policy = theme_link_parts( theme_opt( 'footer_policy' ) ); ?>
		<?php if ( $theme_policy ) : ?>
			<a href="<?php echo esc_url( $theme_policy['url'] ); ?>"<?php echo '_blank' === $theme_policy['target'] ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $theme_policy['title'] ); ?></a>
		<?php endif; ?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
