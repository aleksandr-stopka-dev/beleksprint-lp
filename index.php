<?php get_header(); ?>

<main>
	<section class="page section-padding">
		<div class="container">
			<div class="page__inner">
				<?php if ( have_posts() ) : ?>
					<?php if ( is_home() || is_archive() || is_search() ) : ?>
						<h1 class="text-h1">
							<?php
							if ( is_search() ) {
								printf( 'Поиск: %s', esc_html( get_search_query() ) );
							} elseif ( is_archive() ) {
								echo wp_kses_post( get_the_archive_title() );
							} else {
								echo esc_html( get_the_title( (int) get_option( 'page_for_posts' ) ) ?: 'Блог' );
							}
							?>
						</h1>
					<?php endif; ?>

					<?php while ( have_posts() ) : the_post(); ?>
						<article class="page__body">
							<?php if ( is_singular() ) : ?>
								<h1 class="text-h1"><?php the_title(); ?></h1>
								<?php the_content(); ?>
							<?php else : ?>
								<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<?php the_excerpt(); ?>
							<?php endif; ?>
						</article>
					<?php endwhile; ?>

					<?php the_posts_pagination( [ 'mid_size' => 1, 'prev_text' => '←', 'next_text' => '→' ] ); ?>
				<?php else : ?>
					<h1 class="text-h1">Ничего не найдено</h1>
					<div class="page__body">
						<a href="<?php echo esc_url( home_url('/') ); ?>">На главную</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
