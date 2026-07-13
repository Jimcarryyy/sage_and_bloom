<?php
/**
 * Services archive template.
 *
 * @package starter_theme
 */

get_header();

$current_term = get_queried_object();
$terms        = get_terms(
	array(
		'taxonomy'   => 'service_category',
		'hide_empty' => true,
	)
);

$hero_title = __( 'Our Services', 'starter_theme' );
$hero_desc  = __( 'Browse our full menu of salon services. Book online or call to schedule.', 'starter_theme' );

if ( is_tax( 'service_category' ) && $current_term ) {
	$hero_title = $current_term->name;
	if ( ! empty( $current_term->description ) ) {
		$hero_desc = wp_strip_all_tags( $current_term->description );
	}
}

starter_theme_render_page_hero(
	array(
		'eyebrow'  => __( 'Salon Menu', 'starter_theme' ),
		'title'    => $hero_title,
		'desc'     => $hero_desc,
		'image'    => starter_theme_page_hero_image_url(),
		'benefits' => array(
			__( 'Licensed stylists & technicians', 'starter_theme' ),
			__( 'Premium products used in every service', 'starter_theme' ),
			__( 'Bridal & special event packages', 'starter_theme' ),
			__( 'Book online in minutes', 'starter_theme' ),
		),
		'primary_cta' => array(
			'text' => __( 'Book Appointment', 'starter_theme' ),
			'url'  => starter_theme_booking_url(),
		),
	)
);
?>

<main id="primary" class="site-main services-archive">

	<section class="page-section reveal" data-archive-scroll-target>
		<div class="site-container">

			<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
				<div class="services-toolbar">
					<nav class="services-filter" data-archive-filter aria-label="<?php esc_attr_e( 'Filter services', 'starter_theme' ); ?>">
						<a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>" class="<?php echo is_post_type_archive( 'service' ) ? 'is-active' : ''; ?>" data-no-loader="true">
							<?php esc_html_e( 'All', 'starter_theme' ); ?>
						</a>
						<?php foreach ( $terms as $term ) : ?>
							<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="<?php echo ( is_tax( 'service_category', $term->slug ) ) ? 'is-active' : ''; ?>" data-no-loader="true">
								<?php echo esc_html( $term->name ); ?>
							</a>
						<?php endforeach; ?>
					</nav>
				</div>
			<?php endif; ?>

			<div class="archive-results" data-archive-results>
				<?php if ( have_posts() ) : ?>
					<ul class="services-grid">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/services/card' );
						endwhile;
						?>
					</ul>
					<?php the_posts_pagination(); ?>
				<?php else : ?>
					<p class="page-empty"><?php esc_html_e( 'No services found.', 'starter_theme' ); ?></p>
				<?php endif; ?>
			</div>

		</div>
	</section>

	<?php starter_theme_render_page_cta(); ?>

</main>

<?php
get_footer();
