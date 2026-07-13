<?php
/**
 * Single service template.
 *
 * @package starter_theme
 */

get_header();

while ( have_posts() ) :
	the_post();

	$price    = get_field( 'service_price' );
	$duration = get_field( 'service_duration' );
	$image    = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : starter_theme_page_hero_image_url();
	$desc     = get_field( 'service_short_desc' );
	$desc     = $desc ? wp_strip_all_tags( (string) $desc ) : '';
	$terms    = get_the_terms( get_the_ID(), 'service_category' );
	$eyebrow  = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0]->name : __( 'Salon Service', 'starter_theme' );

	$hero_benefits = starter_theme_service_includes( get_the_ID() );
	if ( count( $hero_benefits ) > 4 ) {
		$hero_benefits = array_slice( $hero_benefits, 0, 4 );
	}

	starter_theme_render_page_hero(
		array(
			'eyebrow'  => $eyebrow,
			'title'    => get_the_title(),
			'desc'     => $desc,
			'image'    => $image,
			'benefits' => $hero_benefits,
			'primary_cta'   => array(
				'text' => __( 'Book This Service', 'starter_theme' ),
				'url'  => starter_theme_booking_url(),
			),
			'secondary_cta' => array(
				'text' => __( 'All Services', 'starter_theme' ),
				'url'  => starter_theme_services_url(),
			),
		)
	);
	?>

	<main id="primary" class="site-main service-single">
		<?php get_template_part( 'template-parts/services/single-detail' ); ?>
		<?php starter_theme_render_page_cta(); ?>
	</main>

	<?php
endwhile;

get_footer();
