<?php
/**
 * Homepage hero section.
 *
 * @package starter_theme
 */

$hero_heading     = starter_theme_home_field( 'hero_heading', 'Welcome to Our Salon & Boutique' );
$hero_subheading  = starter_theme_home_field( 'hero_subheading', 'Quality services, curated products, and a warm local experience.' );
$hero_eyebrow     = starter_theme_home_field( 'hero_eyebrow', '' );
$hero_button_text = starter_theme_home_field( 'hero_button_text', 'Book Appointment' );
$hero_button_url  = starter_theme_home_field( 'hero_button_url', starter_theme_booking_url() );
$hero_image       = starter_theme_home_field( 'hero_image', null );
$hero_benefits    = starter_theme_home_hero_benefits();

if ( ! $hero_eyebrow ) {
	$hero_eyebrow = starter_theme_business_option( 'business_status', __( 'Open today — Walk-ins welcome', 'starter_theme' ) );
}

$media_style = '';
if ( ! empty( $hero_image['url'] ) ) {
	$media_style = sprintf(
		' style="background-image: url(%s);"',
		esc_url( $hero_image['url'] )
	);
}
?>

<section class="home-hero home-section reveal">
	<?php if ( ! empty( $hero_image['url'] ) ) : ?>
		<div class="home-hero__media"<?php echo $media_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-hidden="true"></div>
	<?php endif; ?>
	<div class="home-hero__overlay" aria-hidden="true"></div>

	<div class="site-container">
		<div class="home-hero__content">
			<?php if ( $hero_eyebrow ) : ?>
				<span class="home-hero__eyebrow"><?php echo esc_html( $hero_eyebrow ); ?></span>
			<?php endif; ?>

			<h1 class="home-hero__heading"><?php echo esc_html( $hero_heading ); ?></h1>
			<p class="home-hero__subheading"><?php echo esc_html( $hero_subheading ); ?></p>

			<?php
			get_template_part(
				'template-parts/shared/benefit-list',
				null,
				array(
					'items'    => $hero_benefits,
					'modifier' => 'hero',
				)
			);
			?>

			<div class="home-hero__actions">
				<?php if ( $hero_button_text && $hero_button_url ) : ?>
					<a class="btn btn--primary btn--lg home-hero__cta" href="<?php echo esc_url( $hero_button_url ); ?>">
						<?php echo esc_html( $hero_button_text ); ?>
					</a>
				<?php endif; ?>
				<a class="btn btn--ghost" href="<?php echo esc_url( starter_theme_services_url() ); ?>">
					<?php esc_html_e( 'View Services', 'starter_theme' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
