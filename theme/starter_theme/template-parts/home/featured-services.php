<?php
/**
 * Homepage featured services section.
 *
 * @package starter_theme
 */

$heading  = starter_theme_home_field( 'services_heading', 'Our Services' );
$services = starter_theme_home_field( 'featured_services', null );

if ( empty( $services ) ) {
	$services = get_posts(
		array(
			'post_type'      => 'service',
			'posts_per_page' => 6,
			'meta_key'       => 'service_featured',
			'meta_value'     => '1',
		)
	);
}

if ( empty( $services ) ) {
	$services = get_posts(
		array(
			'post_type'      => 'service',
			'posts_per_page' => 6,
		)
	);
}

if ( empty( $services ) ) {
	return;
}
?>

<section class="home-services home-section reveal">
	<div class="site-container">
		<?php
		get_template_part(
			'template-parts/shared/section-header',
			null,
			array(
				'eyebrow' => __( 'Services', 'starter_theme' ),
				'title'   => $heading,
				'desc'    => __( 'Expert care tailored to you.', 'starter_theme' ),
			)
		);
		?>

		<ul class="services-grid">
			<?php
			foreach ( $services as $post ) :
				setup_postdata( $post );
				get_template_part( 'template-parts/services/card' );
			endforeach;
			wp_reset_postdata();
			?>
		</ul>

		<p class="section-cta">
			<a class="btn btn--primary" href="<?php echo esc_url( starter_theme_services_url() ); ?>">
				<?php esc_html_e( 'View All Services', 'starter_theme' ); ?>
			</a>
		</p>
	</div>
</section>
