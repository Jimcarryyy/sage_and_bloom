<?php
/**
 * Template Name: Booking
 * Template for the Book Now page.
 *
 * @package starter_theme
 */

get_header();

while ( have_posts() ) :
	the_post();

	$intro = get_field( 'booking_intro' );
	$intro = $intro ? wp_strip_all_tags( (string) $intro ) : __( 'Request an appointment and we\'ll confirm your booking shortly.', 'starter_theme' );

	starter_theme_render_page_hero(
		array(
			'eyebrow'  => __( 'Appointments', 'starter_theme' ),
			'title'    => get_the_title(),
			'desc'     => $intro,
			'image'    => starter_theme_page_hero_image_url( get_the_ID() ),
			'benefits' => array(
				__( 'Easy online booking', 'starter_theme' ),
				__( 'Confirmation by email or phone', 'starter_theme' ),
				__( 'Walk-ins welcome when available', 'starter_theme' ),
				__( 'Bridal & group appointments', 'starter_theme' ),
			),
		)
	);
	?>

	<main id="primary" class="site-main booking-page">

		<section class="page-section page-section--cream reveal">
			<div class="site-container">
				<div class="booking-layout">
					<aside class="booking-sidebar">
						<article class="info-card info-card--accent">
							<span class="info-card__label"><?php esc_html_e( 'Need help?', 'starter_theme' ); ?></span>
							<p class="info-card__value"><?php esc_html_e( 'Call us and our front desk will find the best time for you.', 'starter_theme' ); ?></p>
							<?php
							$phone = starter_theme_business_option( 'business_phone', '' );
							if ( $phone ) :
								?>
								<a class="btn btn--primary btn--sm booking-sidebar__phone" href="tel:<?php echo esc_attr( starter_theme_business_phone_link() ); ?>">
									<?php echo esc_html( $phone ); ?>
								</a>
							<?php endif; ?>
						</article>

						<article class="info-card">
							<span class="info-card__label"><?php esc_html_e( 'Studio Hours', 'starter_theme' ); ?></span>
							<div class="info-card__value"><?php starter_theme_render_hours(); ?></div>
						</article>
					</aside>

					<div class="booking-form-panel">
						<?php
						get_template_part(
							'template-parts/shared/section-header',
							null,
							array(
								'eyebrow' => __( 'Request a Time', 'starter_theme' ),
								'title'   => __( 'Tell us what you need', 'starter_theme' ),
								'desc'    => __( 'Share your preferred service and availability — we\'ll follow up to confirm.', 'starter_theme' ),
								'align'   => 'left',
							)
						);

						if ( get_the_content() ) {
							echo '<div class="entry-content booking-form-panel__intro">';
							the_content();
							echo '</div>';
						}

						$form_shortcode = get_field( 'booking_form_shortcode' );
						if ( $form_shortcode ) {
							echo do_shortcode( $form_shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} elseif ( class_exists( 'SBC_Booking_Service' ) ) {
							get_template_part( 'template-parts/forms/booking-api' );
						} else {
							get_template_part( 'template-parts/forms/booking-fallback' );
						}
						?>
					</div>
				</div>
			</div>
		</section>

	</main>

	<?php
endwhile;

get_footer();
