<?php
/**
 * Shared closing CTA band for inner pages.
 *
 * @package starter_theme
 *
 * @var array $args Optional overrides for eyebrow, title, desc, button.
 */

$eyebrow     = isset( $args['eyebrow'] ) ? $args['eyebrow'] : __( 'Visit Us', 'starter_theme' );
$title       = isset( $args['title'] ) ? $args['title'] : __( 'Ready for your appointment?', 'starter_theme' );
$desc        = isset( $args['desc'] ) ? $args['desc'] : __( 'Book online or call us today. Walk-ins welcome when available.', 'starter_theme' );
$button_text = isset( $args['button_text'] ) ? $args['button_text'] : __( 'Book Now', 'starter_theme' );
$button_url  = isset( $args['button_url'] ) ? $args['button_url'] : starter_theme_booking_url();
$show_phone  = isset( $args['show_phone'] ) ? (bool) $args['show_phone'] : true;
$phone       = starter_theme_business_option( 'business_phone', '' );
?>

<section class="page-cta reveal" aria-label="<?php esc_attr_e( 'Book an appointment', 'starter_theme' ); ?>">
	<div class="site-container">
		<div class="page-cta__inner">
			<?php
			get_template_part(
				'template-parts/shared/section-header',
				null,
				array(
					'eyebrow' => $eyebrow,
					'title'   => $title,
					'desc'    => $desc,
				)
			);
			?>

			<div class="page-cta__actions">
				<?php if ( $button_text && $button_url ) : ?>
					<a class="btn btn--primary btn--lg" href="<?php echo esc_url( $button_url ); ?>">
						<?php echo esc_html( $button_text ); ?>
					</a>
				<?php endif; ?>

				<?php if ( $show_phone && $phone ) : ?>
					<a class="btn btn--ghost page-cta__phone" href="tel:<?php echo esc_attr( starter_theme_business_phone_link() ); ?>">
						<?php echo esc_html( $phone ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
