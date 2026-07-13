<?php
/**
 * Interactive contact map (Leaflet + OpenStreetMap).
 *
 * @package starter_theme
 */

$map = starter_theme_contact_map_data();

if ( empty( $map['show'] ) ) {
	return;
}
?>

<section class="contact-map-section page-section reveal" aria-labelledby="contact-map-heading">
	<div class="site-container">
		<?php
		get_template_part(
			'template-parts/shared/section-header',
			null,
			array(
				'eyebrow' => __( 'Find Us', 'starter_theme' ),
				'title'   => __( 'Visit our studio', 'starter_theme' ),
				'desc'    => __( 'Downtown location with free parking. Tap the marker for directions.', 'starter_theme' ),
			)
		);
		?>

		<div class="contact-map-wrap">
			<div
				id="contact-map"
				class="contact-map contact-map--interactive"
				data-lat="<?php echo esc_attr( $map['lat'] ); ?>"
				data-lng="<?php echo esc_attr( $map['lng'] ); ?>"
				data-zoom="<?php echo esc_attr( $map['zoom'] ); ?>"
				data-address="<?php echo esc_attr( $map['address'] ); ?>"
				data-label="<?php echo esc_attr( $map['label'] ); ?>"
				role="region"
				aria-label="<?php esc_attr_e( 'Interactive map', 'starter_theme' ); ?>"
			></div>

			<?php if ( ! empty( $map['directions_url'] ) ) : ?>
				<p class="contact-map__actions">
					<a class="btn btn--outline" href="<?php echo esc_url( $map['directions_url'] ); ?>" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'Get Directions', 'starter_theme' ); ?>
					</a>
				</p>
			<?php endif; ?>
		</div>
	</div>
</section>
