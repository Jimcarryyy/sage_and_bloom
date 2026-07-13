<?php
/**
 * My Account helpers and WooCommerce customizations.
 *
 * @package starter_theme
 */

/**
 * Dashboard summary data for the current customer.
 *
 * @param WP_User|null $user User object.
 * @return array<string, mixed>
 */
function starter_theme_my_account_dashboard_data( $user = null ) {
	if ( ! $user instanceof WP_User ) {
		$user = wp_get_current_user();
	}

	$data = array(
		'orders_count'    => 0,
		'bookings_count'  => 0,
		'recent_orders'   => array(),
		'upcoming_bookings' => array(),
	);

	if ( ! $user->ID || ! function_exists( 'wc_get_orders' ) ) {
		return $data;
	}

	$orders = wc_get_orders(
		array(
			'customer_id' => $user->ID,
			'limit'       => 3,
			'orderby'     => 'date',
			'order'       => 'DESC',
			'status'      => array_keys( wc_get_order_statuses() ),
		)
	);

	$data['recent_orders'] = $orders;
	$data['orders_count']  = count(
		wc_get_orders(
			array(
				'customer_id' => $user->ID,
				'limit'       => -1,
				'return'      => 'ids',
				'status'      => array_keys( wc_get_order_statuses() ),
			)
		)
	);

	if ( class_exists( 'SBC_Booking_Service' ) ) {
		$bookings = SBC_Booking_Service::query(
			array(
				'email' => $user->user_email,
				'limit' => 20,
			)
		);

		if ( empty( $bookings ) ) {
			$bookings = SBC_Booking_Service::query(
				array(
					'user_id' => $user->ID,
					'limit'   => 20,
				)
			);
		}

		$data['bookings_count'] = count( $bookings );
		$today                  = gmdate( 'Y-m-d' );

		foreach ( $bookings as $booking ) {
			if ( in_array( $booking->status, array( 'cancelled', 'completed' ), true ) ) {
				continue;
			}

			if ( $booking->booking_date >= $today ) {
				$data['upcoming_bookings'][] = SBC_Booking_Service::format( $booking );
			}
		}

		$data['upcoming_bookings'] = array_slice( $data['upcoming_bookings'], 0, 3 );
	}

	return $data;
}

/**
 * Icon markup for account navigation items.
 *
 * @param string $endpoint WooCommerce endpoint key.
 * @return string
 */
function starter_theme_my_account_nav_icon( $endpoint ) {
	$icons = array(
		'dashboard'    => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',
		'orders'       => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 6h15l-1.5 9h-12z"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/><path d="M6 6L5 3H2"/></svg>',
		'bookings'     => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>',
		'downloads'    => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><path d="M5 21h14"/></svg>',
		'edit-address' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 21s7-4.5 7-11a7 7 0 10-14 0c0 6.5 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>',
		'edit-account' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0112 0v1"/></svg>',
		'customer-logout' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>',
	);

	return isset( $icons[ $endpoint ] ) ? $icons[ $endpoint ] : $icons['dashboard'];
}

/**
 * Branded intro on account sub-pages (orders, addresses, etc.).
 *
 * @return void
 */
function starter_theme_my_account_endpoint_intro() {
	if ( ! is_user_logged_in() || ! is_account_page() ) {
		return;
	}

	$intros = array(
		'orders'       => array(
			'title' => __( 'Your orders', 'starter_theme' ),
			'desc'  => __( 'Track boutique purchases, order status, and receipts.', 'starter_theme' ),
		),
		'edit-address' => array(
			'title' => __( 'Addresses', 'starter_theme' ),
			'desc'  => __( 'Update billing and shipping details for faster checkout.', 'starter_theme' ),
		),
		'edit-account' => array(
			'title' => __( 'Account details', 'starter_theme' ),
			'desc'  => __( 'Change your name, email, and password.', 'starter_theme' ),
		),
		'downloads'    => array(
			'title' => __( 'Downloads', 'starter_theme' ),
			'desc'  => __( 'Access digital products attached to your orders.', 'starter_theme' ),
		),
	);

	foreach ( $intros as $endpoint => $copy ) {
		if ( is_wc_endpoint_url( $endpoint ) ) {
			printf(
				'<div class="account-endpoint-intro"><h2 class="account-endpoint-intro__title">%s</h2><p class="account-endpoint-intro__desc">%s</p></div>',
				esc_html( $copy['title'] ),
				esc_html( $copy['desc'] )
			);
			return;
		}
	}
}
add_action( 'woocommerce_account_content', 'starter_theme_my_account_endpoint_intro', 5 );

/**
 * Open consistent spacing wrapper for logged-in account endpoints.
 *
 * @return void
 */
function starter_theme_my_account_content_open() {
	if ( ! is_user_logged_in() || ! is_account_page() ) {
		return;
	}

	echo '<div class="account-endpoint">';
}
add_action( 'woocommerce_account_content', 'starter_theme_my_account_content_open', 1 );

/**
 * Close account endpoint wrapper.
 *
 * @return void
 */
function starter_theme_my_account_content_close() {
	if ( ! is_user_logged_in() || ! is_account_page() ) {
		return;
	}

	echo '</div>';
}
add_action( 'woocommerce_account_content', 'starter_theme_my_account_content_close', 999 );

/**
 * Enqueue AJAX account navigation on logged-in My Account pages.
 *
 * @return void
 */
function starter_theme_enqueue_my_account_nav() {
	if ( ! function_exists( 'is_account_page' ) || ! is_account_page() || ! is_user_logged_in() ) {
		return;
	}

	$script_path = 'assets/js/my-account-nav.js';
	$deps        = array( 'starter_theme-scroll-reveal' );

	if ( wp_script_is( 'jquery', 'registered' ) ) {
		$deps[] = 'jquery';
	}

	if ( wp_script_is( 'wc-country-select', 'registered' ) ) {
		$deps[] = 'wc-country-select';
	}

	if ( wp_script_is( 'wc-address-i18n', 'registered' ) ) {
		$deps[] = 'wc-address-i18n';
	}

	wp_enqueue_script(
		'starter_theme-my-account-nav',
		get_template_directory_uri() . '/' . $script_path,
		$deps,
		starter_theme_asset_version( $script_path ),
		true
	);

	wp_localize_script(
		'starter_theme-my-account-nav',
		'starterThemeMyAccount',
		array(
			'accountBaseUrl'  => esc_url_raw( trailingslashit( wc_get_page_permalink( 'myaccount' ) ) ),
			'contentSelector' => '[data-my-account-content]',
			'navSelector'     => '.woocommerce-MyAccount-navigation',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'starter_theme_enqueue_my_account_nav', 40 );
