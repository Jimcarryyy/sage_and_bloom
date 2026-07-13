<?php
/**
 * Homepage setup: assign static front page and ACF JSON paths.
 *
 * @package starter_theme
 */

/**
 * Save and load ACF field groups from the theme acf-json directory.
 */
function starter_theme_acf_json_paths() {
	if ( ! function_exists( 'acf_update_setting' ) ) {
		return;
	}

	acf_update_setting( 'save_json', get_template_directory() . '/acf-json' );
	acf_append_setting( 'load_json', get_template_directory() . '/acf-json' );
}
add_action( 'acf/init', 'starter_theme_acf_json_paths' );

/**
 * Assign the "Home page" WordPress page as the static front page.
 */
function starter_theme_assign_front_page() {
	if ( get_option( 'starter_theme_front_page_assigned' ) ) {
		return;
	}

	$home = get_page_by_title( 'Home page', OBJECT, 'page' );

	if ( ! $home ) {
		$home = get_page_by_path( 'home-page', OBJECT, 'page' );
	}

	if ( ! $home ) {
		return;
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home->ID );
	update_option( 'starter_theme_front_page_assigned', 1 );
}
add_action( 'after_setup_theme', 'starter_theme_assign_front_page', 20 );
add_action( 'admin_init', 'starter_theme_assign_front_page' );

/**
 * Admin notice when no static front page is configured.
 */
function starter_theme_front_page_admin_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ) ) {
		return;
	}

	$home = get_page_by_title( 'Home page', OBJECT, 'page' );

	if ( ! $home ) {
		return;
	}

	$reading_url = admin_url( 'options-reading.php' );

	printf(
		'<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
		wp_kses_post(
			sprintf(
				/* translators: 1: page title, 2: settings URL */
				__( 'Set <strong>%1$s</strong> as your static homepage under <a href="%2$s">Settings &rarr; Reading</a>.', 'starter_theme' ),
				esc_html( $home->post_title ),
				esc_url( $reading_url )
			)
		)
	);
}
add_action( 'admin_notices', 'starter_theme_front_page_admin_notice' );

/**
 * Get a homepage ACF field with an optional fallback default.
 *
 * @param string $name    Field name.
 * @param mixed  $default Default value when field is empty.
 * @return mixed
 */
function starter_theme_home_field( $name, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}

	$post_id = (int) get_option( 'page_on_front' );

	if ( ! $post_id && is_front_page() ) {
		$post_id = get_queried_object_id();
	}

	$value = $post_id ? get_field( $name, $post_id ) : get_field( $name );

	if ( null === $value || '' === $value || ( is_array( $value ) && empty( $value ) ) ) {
		return $default;
	}

	return $value;
}

/**
 * Parse multiline ACF text into a trimmed list of strings.
 *
 * @param string $text Raw textarea value.
 * @return array<int, string>
 */
function starter_theme_parse_lines( $text ) {
	if ( ! is_string( $text ) || '' === trim( $text ) ) {
		return array();
	}

	$lines = preg_split( '/\r\n|\r|\n/', $text );
	$lines = array_map( 'trim', $lines );

	return array_values( array_filter( $lines ) );
}

/**
 * Hero benefit bullets for the homepage.
 *
 * @return array<int, string>
 */
function starter_theme_home_hero_benefits() {
	$raw = starter_theme_home_field( 'hero_benefits', '' );

	if ( is_string( $raw ) && '' !== trim( $raw ) ) {
		return starter_theme_parse_lines( $raw );
	}

	$info = starter_theme_home_field( 'info_bar_text', '' );

	if ( is_string( $info ) && '' !== trim( $info ) ) {
		$parts = array_map( 'trim', explode( '·', $info ) );
		$parts = array_filter( $parts );

		if ( ! empty( $parts ) ) {
			return array_values( $parts );
		}
	}

	return array(
		__( 'Walk-ins welcome', 'starter_theme' ),
		__( 'Free parking', 'starter_theme' ),
		__( 'Gift cards available', 'starter_theme' ),
		__( 'Expert stylists & curated products', 'starter_theme' ),
	);
}

/**
 * Trust badges for the homepage strip.
 *
 * @return array<int, string>
 */
function starter_theme_home_trust_badges() {
	$raw = starter_theme_home_field( 'trust_badges', '' );

	if ( is_string( $raw ) && '' !== trim( $raw ) ) {
		return starter_theme_parse_lines( $raw );
	}

	return array(
		__( 'Licensed professionals', 'starter_theme' ),
		__( 'Premium products', 'starter_theme' ),
		__( 'Bridal & events', 'starter_theme' ),
		__( 'Neighborhood favorite', 'starter_theme' ),
	);
}

/**
 * Stats for the homepage stats band.
 *
 * @return array<int, array{value: string, label: string}>
 */
function starter_theme_home_stats() {
	$defaults = array(
		array(
			'value' => '10+',
			'label' => __( 'Years serving our community', 'starter_theme' ),
		),
		array(
			'value' => '5',
			'label' => __( 'Expert stylists on staff', 'starter_theme' ),
		),
		array(
			'value' => '500+',
			'label' => __( 'Happy clients each year', 'starter_theme' ),
		),
		array(
			'value' => '4.9★',
			'label' => __( 'Average client rating', 'starter_theme' ),
		),
	);

	$stats = array();

	for ( $i = 1; $i <= 4; $i++ ) {
		$value = starter_theme_home_field( "stat_{$i}_value", '' );
		$label = starter_theme_home_field( "stat_{$i}_label", '' );

		if ( $value && $label ) {
			$stats[] = array(
				'value' => $value,
				'label' => $label,
			);
		}
	}

	return ! empty( $stats ) ? $stats : $defaults;
}

/**
 * Build initials from a client name.
 *
 * @param string $client Client name.
 * @return string
 */
function starter_theme_testimonial_initials( $client ) {
	if ( ! $client ) {
		return '';
	}

	$parts = preg_split( '/\s+/', trim( $client ) );

	return strtoupper(
		mb_substr( $parts[0], 0, 1 ) . ( isset( $parts[1] ) ? mb_substr( $parts[1], 0, 1 ) : '' )
	);
}
