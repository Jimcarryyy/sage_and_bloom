<?php
/**
 * Homepage testimonials section.
 *
 * @package starter_theme
 */

$heading      = starter_theme_home_field( 'testimonials_heading', 'What Clients Say' );
$testimonials = starter_theme_home_field( 'featured_testimonials', null );

if ( empty( $testimonials ) ) {
	$testimonials = get_posts(
		array(
			'post_type'      => 'testimonial',
			'posts_per_page' => 6,
		)
	);
}

if ( empty( $testimonials ) ) {
	$testimonials = array(
		(object) array(
			'fallback' => true,
			'quote'    => 'The bridal package was perfect — stress-free and beautiful results.',
			'client'   => 'Elena R.',
			'role'     => __( 'Bridal client', 'starter_theme' ),
			'rating'   => 5,
		),
		(object) array(
			'fallback' => true,
			'quote'    => 'Absolutely love this place! Professional service and a beautiful shop.',
			'client'   => 'Sarah M.',
			'role'     => __( 'Color & cut regular', 'starter_theme' ),
			'rating'   => 5,
		),
		(object) array(
			'fallback' => true,
			'quote'    => 'Best salon experience in town. I always leave feeling refreshed.',
			'client'   => 'James L.',
			'role'     => __( 'Walk-in guest', 'starter_theme' ),
			'rating'   => 5,
		),
		(object) array(
			'fallback' => true,
			'quote'    => 'The team is so talented and friendly. Highly recommend the spa pedicure!',
			'client'   => 'Priya K.',
			'role'     => __( 'Spa pedicure guest', 'starter_theme' ),
			'rating'   => 5,
		),
		(object) array(
			'fallback' => true,
			'quote'    => 'Great products in the shop and amazing hair color results every time.',
			'client'   => 'Maria G.',
			'role'     => __( 'Boutique shopper', 'starter_theme' ),
			'rating'   => 5,
		),
		(object) array(
			'fallback' => true,
			'quote'    => 'Easy booking and always on time. My go-to neighborhood spot.',
			'client'   => 'Tom H.',
			'role'     => __( 'Monthly member', 'starter_theme' ),
			'rating'   => 4,
		),
	);
}

$home_stats   = function_exists( 'starter_theme_home_stats' ) ? starter_theme_home_stats() : array();
$rating_stat  = ! empty( $home_stats[3]['value'] ) ? $home_stats[3]['value'] : '4.9★';
$clients_stat = ! empty( $home_stats[2]['value'] ) ? $home_stats[2]['value'] : '500+';
$index        = 0;
?>

<section class="home-testimonials home-section reveal">
	<div class="site-container">
		<?php
		get_template_part(
			'template-parts/shared/section-header',
			null,
			array(
				'eyebrow' => __( 'Testimonials', 'starter_theme' ),
				'title'   => $heading,
				'desc'    => __( 'Trusted by our community for quality care and warm hospitality.', 'starter_theme' ),
			)
		);
		?>

		<div class="testimonials-summary" aria-label="<?php esc_attr_e( 'Client satisfaction highlights', 'starter_theme' ); ?>">
			<div class="testimonials-summary__item">
				<span class="testimonials-summary__value"><?php echo esc_html( $rating_stat ); ?></span>
				<span class="testimonials-summary__label"><?php esc_html_e( 'Average rating', 'starter_theme' ); ?></span>
			</div>
			<div class="testimonials-summary__item">
				<span class="testimonials-summary__value"><?php echo esc_html( $clients_stat ); ?></span>
				<span class="testimonials-summary__label"><?php esc_html_e( 'Happy clients yearly', 'starter_theme' ); ?></span>
			</div>
		</div>

		<ul class="testimonials-grid">
			<?php foreach ( $testimonials as $item ) : ?>
				<?php
				$is_featured = ( 0 === $index );
				++$index;

				if ( ! empty( $item->fallback ) ) {
					$quote  = $item->quote;
					$client = $item->client;
					$role   = $item->role;
					$rating = (int) $item->rating;
				} else {
					$quote  = get_field( 'testimonial_quote', $item->ID );
					$client = get_field( 'testimonial_client', $item->ID );
					$role   = get_field( 'testimonial_role', $item->ID );
					$rating = (int) get_field( 'testimonial_rating', $item->ID );
				}

				if ( ! $role ) {
					$role = __( 'Verified client', 'starter_theme' );
				}

				$initials = starter_theme_testimonial_initials( $client );
				?>
				<li class="testimonial-card<?php echo $is_featured ? ' testimonial-card--featured' : ''; ?>">
					<span class="testimonial-card__quote-mark" aria-hidden="true">&ldquo;</span>

					<?php if ( $rating ) : ?>
						<div class="testimonial-card__stars" aria-label="<?php echo esc_attr( sprintf( __( '%d out of 5 stars', 'starter_theme' ), $rating ) ); ?>">
							<?php echo esc_html( str_repeat( '★', min( 5, $rating ) ) ); ?>
						</div>
					<?php endif; ?>

					<blockquote class="testimonial-card__quote">
						<p><?php echo esc_html( $quote ); ?></p>
					</blockquote>

					<div class="testimonial-card__footer">
						<?php if ( $initials ) : ?>
							<span class="testimonial-card__initials" aria-hidden="true"><?php echo esc_html( $initials ); ?></span>
						<?php endif; ?>
						<div class="testimonial-card__meta">
							<cite class="testimonial-card__author"><?php echo esc_html( $client ); ?></cite>
							<span class="testimonial-card__role"><?php echo esc_html( $role ); ?></span>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
