<?php
/**
 * Homepage call-to-action section.
 *
 * @package starter_theme
 */

$cta_heading     = starter_theme_home_field( 'cta_heading', 'Ready for your appointment?' );
$cta_text        = starter_theme_home_field( 'cta_text', 'Book online or call us today. Walk-ins welcome when available.' );
$cta_button_text = starter_theme_home_field( 'cta_button_text', 'Book Now' );
$cta_button_url  = starter_theme_home_field( 'cta_button_url', starter_theme_booking_url() );
$business_phone  = starter_theme_business_option( 'business_phone', '' );
?>

<section id="contact" class="home-cta home-section reveal">
	<div class="site-container">
		<div class="home-cta__inner">
			<?php
			get_template_part(
				'template-parts/shared/section-header',
				null,
				array(
					'eyebrow' => __( 'Visit Us', 'starter_theme' ),
					'title'   => $cta_heading,
					'desc'    => $cta_text,
				)
			);
			?>

			<div class="home-cta__actions">
				<?php if ( $cta_button_text && $cta_button_url ) : ?>
					<a class="btn btn--primary btn--lg home-cta__button" href="<?php echo esc_url( $cta_button_url ); ?>">
						<?php echo esc_html( $cta_button_text ); ?>
					</a>
				<?php endif; ?>

				<?php if ( $business_phone ) : ?>
					<a class="btn btn--ghost home-cta__phone" href="tel:<?php echo esc_attr( starter_theme_business_phone_link() ); ?>">
						<?php echo esc_html( $business_phone ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
