<?php
/**
 * Front page template — local business homepage with ACF sections.
 *
 * @package starter_theme
 */

get_header();
?>

<main id="primary" class="site-main site-main--front-page">

	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/home/hero' );
		get_template_part( 'template-parts/home/trust-strip' );
		get_template_part( 'template-parts/home/hours-bar' );
		get_template_part( 'template-parts/home/about' );
		get_template_part( 'template-parts/home/featured-services' );
		get_template_part( 'template-parts/home/stats-bar' );
		get_template_part( 'template-parts/home/featured-products' );
		get_template_part( 'template-parts/home/testimonials' );
		get_template_part( 'template-parts/home/cta' );

	endwhile;
	?>

</main>

<?php
get_footer();
