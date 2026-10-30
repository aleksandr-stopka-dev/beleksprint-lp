<?php
get_header();
?>

<main id="main">
	<?php
	get_template_part( 'template-parts/sections/hero' );
	get_template_part( 'template-parts/sections/about' );
	get_template_part( 'template-parts/sections/catalog' );
	get_template_part( 'template-parts/sections/rf' );
	get_template_part( 'template-parts/sections/why' );
	get_template_part( 'template-parts/sections/reviews' );
	get_template_part( 'template-parts/sections/contacts' );
	?>
</main>

<?php get_footer(); ?>
