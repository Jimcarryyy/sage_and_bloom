<?php
/**
 * Homepage info bar section.
 *
 * @package starter_theme
 */

$info_text = starter_theme_home_field( 'info_bar_text', 'Walk-ins welcome · Free parking · Gift cards available' );
$phone     = starter_theme_business_option( 'business_phone', '' );
$address   = starter_theme_business_option( 'business_address', '' );
?>

<section class="home-info-bar reveal">
	<div class="site-container">
		<p class="home-info-bar__text"><?php echo esc_html( $info_text ); ?></p>
		<div class="home-info-bar__actions">
			<?php if ( $phone ) : ?>
				<a href="tel:<?php echo esc_attr( starter_theme_business_phone_link() ); ?>" class="home-info-bar__link">
					<?php echo esc_html( $phone ); ?>
				</a>
			<?php endif; ?>
			<?php if ( $address ) : ?>
				<a href="<?php echo esc_url( starter_theme_booking_url() ); ?>" class="home-info-bar__link home-info-bar__link--directions">
					<?php esc_html_e( 'Get Directions', 'starter_theme' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
