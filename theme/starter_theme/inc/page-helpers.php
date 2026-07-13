<?php
/**
 * Shared helpers for inner pages.
 *
 * @package starter_theme
 */

/**
 * Fallback hero background URL for inner pages.
 *
 * @param int $post_id Optional post ID for featured image.
 * @return string
 */
function starter_theme_page_hero_image_url( $post_id = 0 ) {
	if ( $post_id && has_post_thumbnail( $post_id ) ) {
		$url = get_the_post_thumbnail_url( $post_id, 'large' );
		if ( $url ) {
			return $url;
		}
	}

	if ( is_front_page() && function_exists( 'get_field' ) ) {
		$front_page_id = (int) get_option( 'page_on_front' );
		if ( $front_page_id ) {
			$hero = get_field( 'hero_image', $front_page_id );
			if ( ! empty( $hero['url'] ) ) {
				return $hero['url'];
			}
		}
	}

	if ( function_exists( 'starter_theme_page_hero_default_url' ) ) {
		return starter_theme_page_hero_default_url( starter_theme_page_hero_context( $post_id ) );
	}

	return '';
}

/**
 * Default highlight bullets for About page hero / story sidebar.
 *
 * @return array<int, string>
 */
function starter_theme_about_highlights() {
	if ( function_exists( 'get_field' ) ) {
		$raw = get_field( 'about_highlights' );
		if ( is_string( $raw ) && '' !== trim( $raw ) && function_exists( 'starter_theme_parse_lines' ) ) {
			return starter_theme_parse_lines( $raw );
		}
	}

	return array(
		__( 'Expert cuts, color, and nail care', 'starter_theme' ),
		__( 'Curated self-care products in our boutique', 'starter_theme' ),
		__( 'Warm hospitality for walk-ins and appointments', 'starter_theme' ),
		__( 'Downtown location with free parking', 'starter_theme' ),
	);
}

/**
 * Stats for inner pages (reuses homepage stats when available).
 *
 * @return array<int, array{value: string, label: string}>
 */
function starter_theme_page_stats() {
	if ( function_exists( 'starter_theme_home_stats' ) ) {
		return starter_theme_home_stats();
	}

	return array(
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
}

/**
 * Render the shared page closing CTA with optional overrides.
 *
 * @param array<string, mixed> $args CTA overrides.
 * @return void
 */
function starter_theme_render_page_cta( $args = array() ) {
	get_template_part( 'template-parts/shared/page-cta', null, $args );
}

/**
 * Render the shared dark page hero.
 *
 * @param array<string, mixed> $args Hero overrides.
 * @return void
 */
function starter_theme_render_page_hero( $args = array() ) {
	get_template_part( 'template-parts/shared/page-hero', null, $args );
}

/**
 * Contact map configuration.
 *
 * @return array{show: bool, lat: string, lng: string, zoom: string, address: string, label: string, directions_url: string}
 */
function starter_theme_contact_map_data() {
	$show = true;

	if ( function_exists( 'get_field' ) && is_page() ) {
		$field_show = get_field( 'contact_show_map' );
		if ( false === $field_show || '0' === (string) $field_show ) {
			$show = false;
		}
	}

	$address = starter_theme_business_option( 'business_address', '' );
	$label   = function_exists( 'starter_theme_brand_name' ) ? starter_theme_brand_name() : get_bloginfo( 'name' );
	$lat     = starter_theme_business_option( 'business_map_lat', '14.5995' );
	$lng     = starter_theme_business_option( 'business_map_lng', '120.9842' );
	$zoom    = starter_theme_business_option( 'business_map_zoom', '15' );

	$directions_url = '';
	if ( $address ) {
		$directions_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( wp_strip_all_tags( $address ) );
	}

	return array(
		'show'           => $show && ( $address || ( $lat && $lng ) ),
		'lat'            => (string) $lat,
		'lng'            => (string) $lng,
		'zoom'           => (string) $zoom,
		'address'        => wp_strip_all_tags( (string) $address ),
		'label'          => (string) $label,
		'directions_url' => $directions_url,
	);
}
