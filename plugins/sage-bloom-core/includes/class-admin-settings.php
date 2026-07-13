<?php
/**
 * Plugin settings page.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Settings admin screen.
 */
class SBC_Admin_Settings {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	/**
	 * Register submenu.
	 *
	 * @return void
	 */
	public static function register_menu() {
		add_submenu_page(
			'sbc-dashboard',
			__( 'Settings', 'sage-bloom-core' ),
			__( 'Settings', 'sage-bloom-core' ),
			'manage_options',
			'sbc-settings',
			array( __CLASS__, 'render_page' )
		);
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public static function register_settings() {
		register_setting(
			'sbc_settings_group',
			'sbc_settings',
			array(
				'sanitize_callback' => array( __CLASS__, 'sanitize_settings' ),
			)
		);
	}

	/**
	 * Sanitize settings input.
	 *
	 * @param array<string, mixed> $input Raw input.
	 * @return array<string, mixed>
	 */
	public static function sanitize_settings( $input ) {
		$output = get_option( 'sbc_settings', array() );

		$keys = array(
			'stripe_publishable_key',
			'stripe_secret_key',
			'stripe_webhook_secret',
			'default_slot_duration',
			'default_buffer_minutes',
		);

		foreach ( $keys as $key ) {
			if ( isset( $input[ $key ] ) ) {
				$output[ $key ] = sanitize_text_field( $input[ $key ] );
			}
		}

		if ( ! empty( $output['stripe_secret_key'] ) && 0 !== strpos( $output['stripe_secret_key'], 'sk_test_' ) ) {
			add_settings_error( 'sbc_settings', 'invalid_stripe_key', __( 'Only Stripe test secret keys (sk_test_...) are allowed.', 'sage-bloom-core' ) );
			$output['stripe_secret_key'] = '';
		}

		return $output;
	}

	/**
	 * Render settings page.
	 *
	 * @return void
	 */
	public static function render_page() {
		include SBC_PLUGIN_DIR . 'admin/views/settings.php';
	}
}
