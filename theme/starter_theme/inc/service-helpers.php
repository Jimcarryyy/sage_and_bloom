<?php
/**
 * Service helpers — detail content, fallbacks, related services.
 *
 * @package starter_theme
 */

/**
 * Parse multiline service field into a list.
 *
 * @param string $raw Raw field value.
 * @return array<int, string>
 */
function starter_theme_service_lines( $raw ) {
	if ( ! is_string( $raw ) || '' === trim( $raw ) ) {
		return array();
	}

	if ( function_exists( 'starter_theme_parse_lines' ) ) {
		return starter_theme_parse_lines( $raw );
	}

	$lines = preg_split( '/\r\n|\r|\n/', $raw );
	$lines = array_map( 'trim', $lines );

	return array_values( array_filter( $lines ) );
}

/**
 * What's included bullets for a service.
 *
 * @param int $post_id Service post ID.
 * @return array<int, string>
 */
function starter_theme_service_includes( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	if ( function_exists( 'get_field' ) ) {
		$raw = get_field( 'service_includes', $post_id );
		$lines = starter_theme_service_lines( (string) $raw );
		if ( ! empty( $lines ) ) {
			return $lines;
		}
	}

	$terms = wp_get_post_terms( $post_id, 'service_category', array( 'fields' => 'slugs' ) );
	$slug  = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0] : '';

	if ( 'nails' === $slug ) {
		return array(
			__( 'Consultation and shape assessment', 'starter_theme' ),
			__( 'Premium products and sanitized tools', 'starter_theme' ),
			__( 'Cuticle care and finishing polish', 'starter_theme' ),
			__( 'Aftercare tips from your technician', 'starter_theme' ),
		);
	}

	if ( 'packages' === $slug ) {
		return array(
			__( 'Personalized planning session', 'starter_theme' ),
			__( 'Dedicated stylist for your visit', 'starter_theme' ),
			__( 'Premium products included', 'starter_theme' ),
			__( 'Complimentary beverage during service', 'starter_theme' ),
		);
	}

	return array(
		__( 'One-on-one consultation with your stylist', 'starter_theme' ),
		__( 'Shampoo and scalp treatment', 'starter_theme' ),
		__( 'Professional cut, color, or treatment', 'starter_theme' ),
		__( 'Blow-dry style and finishing products', 'starter_theme' ),
	);
}

/**
 * Highlights / what to expect for a service.
 *
 * @param int $post_id Service post ID.
 * @return array<int, string>
 */
function starter_theme_service_highlights( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	if ( function_exists( 'get_field' ) ) {
		$raw = get_field( 'service_highlights', $post_id );
		$lines = starter_theme_service_lines( (string) $raw );
		if ( ! empty( $lines ) ) {
			return $lines;
		}
	}

	return array(
		__( 'Arrive 5 minutes early for a relaxed start', 'starter_theme' ),
		__( 'Let us know about allergies or sensitivities', 'starter_theme' ),
		__( 'Easy rescheduling by phone or online', 'starter_theme' ),
		__( 'Ask about add-ons and product recommendations', 'starter_theme' ),
	);
}

/**
 * Booking note for a service.
 *
 * @param int $post_id Service post ID.
 * @return string
 */
function starter_theme_service_booking_note( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	if ( function_exists( 'get_field' ) ) {
		$note = get_field( 'service_booking_note', $post_id );
		if ( is_string( $note ) && '' !== trim( $note ) ) {
			return trim( $note );
		}
	}

	return __( 'New clients welcome. A card on file may be required for color services and packages.', 'starter_theme' );
}

/**
 * Related services in the same category.
 *
 * @param int $post_id Service post ID.
 * @param int $limit   Max results.
 * @return array<int, WP_Post>
 */
function starter_theme_related_services( $post_id = 0, $limit = 3 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$terms   = wp_get_post_terms( $post_id, 'service_category', array( 'fields' => 'ids' ) );

	$args = array(
		'post_type'      => 'service',
		'posts_per_page' => $limit,
		'post__not_in'   => array( $post_id ),
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	);

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'service_category',
				'field'    => 'term_id',
				'terms'    => $terms,
			),
		);
	}

	$posts = get_posts( $args );

	if ( count( $posts ) < $limit ) {
		$fallback = get_posts(
			array(
				'post_type'      => 'service',
				'posts_per_page' => $limit,
				'post__not_in'   => array_merge( array( $post_id ), wp_list_pluck( $posts, 'ID' ) ),
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			)
		);
		$posts    = array_merge( $posts, $fallback );
		$posts    = array_slice( $posts, 0, $limit );
	}

	return $posts;
}

/**
 * Enqueue AJAX service archive filtering.
 */
function starter_theme_enqueue_archive_filtering() {
	if ( ! is_post_type_archive( 'service' ) && ! is_tax( 'service_category' ) ) {
		return;
	}

	wp_enqueue_script(
		'starter_theme-archive-filtering',
		get_template_directory_uri() . '/assets/js/archive-filtering.js',
		array( 'starter_theme-scroll-reveal' ),
		starter_theme_asset_version( 'assets/js/archive-filtering.js' ),
		true
	);

	wp_localize_script(
		'starter_theme-archive-filtering',
		'starterThemeArchiveFiltering',
		array(
			'toolbarSelector' => '.services-toolbar',
			'resultsSelector' => '[data-archive-results]',
			'filterSelector'  => '[data-archive-filter]',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'starter_theme_enqueue_archive_filtering', 40 );
