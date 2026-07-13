<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package starter_theme
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function starter_theme_body_classes( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	if ( is_front_page() ) {
		$classes[] = 'front-page';
	}

	if ( is_singular( 'service' ) ) {
		$classes[] = 'single-service';
	}

	if ( is_post_type_archive( 'service' ) || is_tax( 'service_category' ) ) {
		$classes[] = 'archive-service';
	}

	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		$classes[] = 'woocommerce-page';
		$classes[] = 'no-sidebar';
	}

	if ( function_exists( 'is_cart' ) && is_cart() ) {
		$classes[] = 'woocommerce-cart';
		$classes[] = 'no-sidebar';
	}

	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		$classes[] = 'woocommerce-checkout';
		$classes[] = 'no-sidebar';
	}

	if ( function_exists( 'starter_theme_is_order_received_page' ) && starter_theme_is_order_received_page() ) {
		$classes[] = 'woocommerce-order-received-page';
	}

	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		$classes[] = 'woocommerce-account';
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'starter_theme_body_classes' );

/**
 * Add lazy loading to theme images.
 *
 * @param array $attr Image attributes.
 * @return array
 */
function starter_theme_lazy_load_images( $attr ) {
	if ( empty( $attr['loading'] ) ) {
		$attr['loading'] = 'lazy';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'starter_theme_lazy_load_images' );

/**
 * Remove duplicate URLs from navigation menus.
 *
 * @param array $items Menu items.
 * @return array
 */
function starter_theme_dedupe_nav_menu_items( $items ) {
	if ( empty( $items ) ) {
		return $items;
	}

	$seen     = array();
	$filtered = array();

	foreach ( $items as $item ) {
		$key = starter_theme_menu_item_dedupe_key( $item );

		if ( isset( $seen[ $key ] ) ) {
			continue;
		}

		$seen[ $key ] = true;
		$filtered[]   = $item;
	}

	return $filtered;
}
add_filter( 'wp_get_nav_menu_items', 'starter_theme_dedupe_nav_menu_items' );

/**
 * Build a stable key for menu deduplication.
 *
 * @param WP_Post $item Menu item.
 * @return string
 */
function starter_theme_menu_item_dedupe_key( $item ) {
	$post_id = url_to_postid( $item->url );
	$url     = untrailingslashit( strtolower( $item->url ) );

	if ( ! $post_id && function_exists( 'wc_get_page_id' ) ) {
		$shop_id = wc_get_page_id( 'shop' );

		if ( $shop_id > 0 ) {
			$shop_permalink = untrailingslashit( strtolower( get_permalink( $shop_id ) ) );

			if ( $url === $shop_permalink ) {
				$post_id = $shop_id;
			}
		}
	}

	if ( $post_id > 0 ) {
		return 'post:' . $post_id;
	}

	if ( ! empty( $item->object_id ) && 'custom' !== $item->type ) {
		return $item->type . ':' . (int) $item->object_id;
	}

	$query = wp_parse_url( $item->url, PHP_URL_QUERY );

	if ( $query ) {
		parse_str( $query, $params );

		if ( ! empty( $params['post_type'] ) ) {
			return 'archive:' . sanitize_key( $params['post_type'] );
		}
	}

	return 'url:' . $url;
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function starter_theme_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'starter_theme_pingback_header' );
