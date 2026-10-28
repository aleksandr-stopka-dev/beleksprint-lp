<?php get_header(); ?>

<main>
	<section class="page section-padding">
		<div class="container">
			<div class="page__inner">
				<h1 class="text-h1">404</h1>
				<div class="page__body">
					<p>Страница не найдена.</p>
					<a href="<?php echo esc_url( home_url('/') ); ?>">На главную</a>
				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
