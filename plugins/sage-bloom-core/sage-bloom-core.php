<?php
/**
 * Plugin Name: Sage & Bloom Core
 * Description: Backend for Sage & Bloom — REST API, booking engine, Stripe test payments, and admin dashboard.
 * Version: 1.0.0
 * Author: Sage & Bloom
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Text Domain: sage-bloom-core
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

define( 'SBC_VERSION', '1.0.0' );
define( 'SBC_PLUGIN_FILE', __FILE__ );
define( 'SBC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SBC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once SBC_PLUGIN_DIR . 'includes/helpers.php';
require_once SBC_PLUGIN_DIR . 'includes/class-installer.php';
require_once SBC_PLUGIN_DIR . 'includes/class-activity-log.php';
require_once SBC_PLUGIN_DIR . 'includes/class-availability.php';
require_once SBC_PLUGIN_DIR . 'includes/class-booking-service.php';
require_once SBC_PLUGIN_DIR . 'includes/class-emails.php';
require_once SBC_PLUGIN_DIR . 'includes/class-stats.php';
require_once SBC_PLUGIN_DIR . 'includes/class-rest-api.php';
require_once SBC_PLUGIN_DIR . 'includes/class-admin-dashboard.php';
require_once SBC_PLUGIN_DIR . 'includes/class-admin-bookings.php';
require_once SBC_PLUGIN_DIR . 'includes/class-admin-settings.php';
require_once SBC_PLUGIN_DIR . 'includes/class-stripe-client.php';
require_once SBC_PLUGIN_DIR . 'includes/class-webhooks.php';
require_once SBC_PLUGIN_DIR . 'includes/class-my-account.php';
require_once SBC_PLUGIN_DIR . 'includes/class-seeder.php';

/**
 * Bootstrap plugin.
 */
function sbc_init() {
	SBC_Installer::maybe_upgrade();
	SBC_REST_API::init();
	SBC_Admin_Dashboard::init();
	SBC_Admin_Bookings::init();
	SBC_Admin_Settings::init();
	SBC_Webhooks::init();
	sbc_disable_legacy_cod_sample_payment();

	if ( class_exists( 'WooCommerce' ) ) {
		require_once SBC_PLUGIN_DIR . 'includes/class-stripe-gateway.php';
		add_filter( 'woocommerce_payment_gateways', 'sbc_register_stripe_gateway' );
		add_action( 'woocommerce_blocks_loaded', 'sbc_register_stripe_blocks_support' );
		SBC_My_Account::init();
		SBC_Seeder::seed_demo_coupons();
	}

	add_action( 'wp_enqueue_scripts', 'sbc_enqueue_frontend_assets' );
}
add_action( 'plugins_loaded', 'sbc_init' );

/**
 * Frontend assets for My Account bookings tab.
 *
 * @return void
 */
function sbc_enqueue_frontend_assets() {
	if ( ! function_exists( 'is_account_page' ) || ! is_account_page() ) {
		return;
	}

	wp_enqueue_style(
		'sbc-frontend',
		SBC_PLUGIN_URL . 'admin/css/admin.css',
		array(),
		SBC_VERSION
	);
}

/**
 * Register Stripe test gateway with WooCommerce.
 *
 * @param array $gateways Payment gateways.
 * @return array
 */
function sbc_register_stripe_gateway( $gateways ) {
	$gateways[] = 'SBC_Stripe_Gateway';
	return $gateways;
}

/**
 * Register Stripe gateway with WooCommerce Blocks checkout.
 *
 * @return void
 */
function sbc_register_stripe_blocks_support() {
	if ( ! class_exists( '\Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
		return;
	}

	require_once SBC_PLUGIN_DIR . 'includes/class-stripe-blocks-support.php';

	add_action(
		'woocommerce_blocks_payment_method_type_registration',
		static function ( $payment_method_registry ) {
			$payment_method_registry->register( new SBC_Stripe_Blocks_Support() );
		}
	);
}

/**
 * Disable the theme's legacy COD sample payment when this plugin is active.
 *
 * @return void
 */
function sbc_disable_legacy_cod_sample_payment() {
	if ( ! get_option( 'starter_theme_local_test_payment_enabled' ) ) {
		return;
	}

	$settings = get_option( 'woocommerce_cod_settings', array() );
	if ( is_array( $settings ) && ! empty( $settings['title'] ) && false !== strpos( $settings['title'], 'Sample Payment' ) ) {
		$settings['enabled'] = 'no';
		update_option( 'woocommerce_cod_settings', $settings );
	}

	delete_option( 'starter_theme_local_test_payment_enabled' );
}

/**
 * Plugin activation.
 */
function sbc_activate() {
	SBC_Installer::install();
	SBC_Installer::seed_default_availability();
	SBC_My_Account::add_endpoint();
	SBC_Seeder::seed_demo_coupons();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'sbc_activate' );

/**
 * Plugin deactivation.
 */
function sbc_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'sbc_deactivate' );
