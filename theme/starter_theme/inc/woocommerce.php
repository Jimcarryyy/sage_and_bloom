<?php
/**
 * WooCommerce theme integration.
 *
 * @package starter_theme
 */

/**
 * Declare WooCommerce theme support.
 */
function starter_theme_woocommerce_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 400,
			'single_image_width'    => 600,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'max_rows'        => 6,
				'default_columns' => 4,
				'min_columns'     => 2,
				'max_columns'     => 4,
			),
		)
	);

	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'starter_theme_woocommerce_setup' );

/**
 * Remove default WooCommerce wrappers — theme wraps in woocommerce.php.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Hide duplicate archive title on catalog pages (custom hero is used).
 *
 * @param bool $show Whether to show the page title.
 * @return bool
 */
function starter_theme_woocommerce_show_page_title( $show ) {
	if ( is_shop() || is_product_taxonomy() ) {
		return false;
	}

	return $show;
}
add_filter( 'woocommerce_show_page_title', 'starter_theme_woocommerce_show_page_title' );

/**
 * Open shop toolbar row (result count + sorting).
 */
function starter_theme_shop_toolbar_open() {
	if ( ! is_shop() && ! is_product_taxonomy() ) {
		return;
	}

	echo '<div class="shop-toolbar">';
}
add_action( 'woocommerce_before_shop_loop', 'starter_theme_shop_toolbar_open', 19 );

/**
 * Close shop toolbar row.
 */
function starter_theme_shop_toolbar_close() {
	if ( ! is_shop() && ! is_product_taxonomy() ) {
		return;
	}

	echo '</div>';
}
add_action( 'woocommerce_before_shop_loop', 'starter_theme_shop_toolbar_close', 31 );

/**
 * Wrap shop loop output for AJAX ordering refreshes.
 */
function starter_theme_shop_archive_results_open() {
	if ( ! is_shop() && ! is_product_taxonomy() ) {
		return;
	}

	echo '<div class="archive-results" data-archive-results data-archive-scroll-target>';
}
add_action( 'woocommerce_before_shop_loop', 'starter_theme_shop_archive_results_open', 35 );

/**
 * Close shop archive results wrapper.
 */
function starter_theme_shop_archive_results_close() {
	if ( ! is_shop() && ! is_product_taxonomy() ) {
		return;
	}

	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop', 'starter_theme_shop_archive_results_close', 15 );

/**
 * Enqueue AJAX catalog ordering script on shop archives.
 */
function starter_theme_enqueue_archive_ordering() {
	if ( ! function_exists( 'is_shop' ) || ( ! is_shop() && ! is_product_taxonomy() ) ) {
		return;
	}

	wp_enqueue_script(
		'starter_theme-archive-ordering',
		get_template_directory_uri() . '/assets/js/archive-ordering.js',
		array( 'jquery', 'woocommerce' ),
		starter_theme_asset_version( 'assets/js/archive-ordering.js' ),
		true
	);

	wp_localize_script(
		'starter_theme-archive-ordering',
		'starterThemeArchiveOrdering',
		array(
			'toolbarSelector' => '.shop-toolbar',
			'resultsSelector' => '[data-archive-results]',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'starter_theme_enqueue_archive_ordering', 40 );

/**
 * Remove default sidebar on shop pages.
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Products per page.
 *
 * @return int
 */
function starter_theme_products_per_page() {
	return 12;
}
add_filter( 'loop_shop_per_page', 'starter_theme_products_per_page', 20 );

/**
 * Use the theme single-product layout (tabs/related handled in template).
 */
function starter_theme_woocommerce_single_product_layout() {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
}
add_action( 'init', 'starter_theme_woocommerce_single_product_layout' );

/**
 * Ensure WooCommerce uses USD for demo/local installs.
 *
 * @return void
 */
function starter_theme_woocommerce_configure_currency() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	update_option( 'woocommerce_currency', 'USD' );
	update_option( 'woocommerce_currency_pos', 'left' );
	update_option( 'woocommerce_price_num_decimals', '2' );
	update_option( 'woocommerce_price_decimal_sep', '.' );
	update_option( 'woocommerce_price_thousand_sep', ',' );
}
add_action( 'after_switch_theme', 'starter_theme_woocommerce_configure_currency' );

/**
 * Enable customer registration and checkout account options for portfolio demos.
 *
 * @return void
 */
function starter_theme_woocommerce_configure_accounts() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
	update_option( 'woocommerce_enable_signup_and_login_from_checkout', 'yes' );
	update_option( 'woocommerce_enable_guest_checkout', 'yes' );
	update_option( 'woocommerce_registration_generate_username', 'yes' );
	update_option( 'woocommerce_registration_generate_password', 'yes' );
}
add_action( 'after_switch_theme', 'starter_theme_woocommerce_configure_accounts' );

/**
 * One-time currency migration for existing installs.
 *
 * @return void
 */
function starter_theme_upgrade_currency_usd() {
	if ( get_option( 'starter_theme_currency_usd' ) || ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	starter_theme_woocommerce_configure_currency();
	update_option( 'starter_theme_currency_usd', 1 );
}
add_action( 'init', 'starter_theme_upgrade_currency_usd' );

/**
 * One-time customer account settings migration for existing installs.
 *
 * @return void
 */
function starter_theme_upgrade_customer_accounts() {
	if ( get_option( 'starter_theme_customer_accounts' ) || ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	starter_theme_woocommerce_configure_accounts();
	update_option( 'starter_theme_customer_accounts', 1 );
}
add_action( 'init', 'starter_theme_upgrade_customer_accounts' );

/**
 * Check whether this install is local enough to auto-enable a fake payment option.
 *
 * @return bool
 */
function starter_theme_is_local_storefront() {
	$environment = function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production';

	if ( in_array( $environment, array( 'local', 'development' ), true ) ) {
		return true;
	}

	$host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';

	return (bool) preg_match( '/(^localhost(:\d+)?$)|(^127\.0\.0\.1(:\d+)?$)|(\.test(:\d+)?$)|(\.local(:\d+)?$)/i', $host );
}

/**
 * Whether the current request is the checkout thank-you screen.
 *
 * @return bool
 */
function starter_theme_is_order_received_page() {
	return function_exists( 'is_order_received_page' ) && is_order_received_page();
}

/**
 * Refresh header cart count after AJAX add/remove events.
 *
 * @param array $fragments Cart fragments.
 * @return array
 */
function starter_theme_cart_header_fragment( $fragments ) {
	ob_start();
	get_template_part( 'template-parts/header/cart-link' );
	$fragments['.header-cart'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'starter_theme_cart_header_fragment' );

/**
 * Enqueue AJAX add-to-cart script site-wide when WooCommerce is active.
 */
function starter_theme_enqueue_add_to_cart() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	wp_enqueue_script(
		'starter_theme-add-to-cart',
		get_template_directory_uri() . '/assets/js/add-to-cart.js',
		array( 'jquery' ),
		starter_theme_asset_version( 'assets/js/add-to-cart.js' ),
		true
	);

	wp_localize_script(
		'starter_theme-add-to-cart',
		'starterThemeAddToCart',
		array(
			'wcAjaxUrl'            => WC_AJAX::get_endpoint( '%%endpoint%%' ),
			'cartUrl'              => wc_get_cart_url(),
			'isCart'               => is_cart(),
			'cartRedirectAfterAdd' => get_option( 'woocommerce_cart_redirect_after_add' ),
			'i18nViewCart'         => esc_attr__( 'View cart', 'starter_theme' ),
			'i18nAddedToCart'      => esc_attr__( 'Added to your bag', 'starter_theme' ),
			'i18nViewBag'          => esc_attr__( 'View bag', 'starter_theme' ),
			'toastDuration'        => 3500,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'starter_theme_enqueue_add_to_cart', 45 );

/**
 * Theme handles AJAX add to cart — avoid duplicate handlers from WooCommerce core.
 */
function starter_theme_dequeue_wc_add_to_cart() {
	wp_dequeue_script( 'wc-add-to-cart' );
}
add_action( 'wp_enqueue_scripts', 'starter_theme_dequeue_wc_add_to_cart', 99 );
