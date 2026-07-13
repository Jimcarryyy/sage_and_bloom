<?php
/**
 * Admin menu and dashboard.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin dashboard.
 */
class SBC_Admin_Dashboard {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
	}

	/**
	 * Register admin menu.
	 *
	 * @return void
	 */
	public static function register_menu() {
		add_menu_page(
			__( 'Sage & Bloom', 'sage-bloom-core' ),
			__( 'Sage & Bloom', 'sage-bloom-core' ),
			'manage_options',
			'sbc-dashboard',
			array( __CLASS__, 'render_dashboard' ),
			'dashicons-store',
			3
		);

		add_submenu_page(
			'sbc-dashboard',
			__( 'Overview', 'sage-bloom-core' ),
			__( 'Overview', 'sage-bloom-core' ),
			'manage_options',
			'sbc-dashboard',
			array( __CLASS__, 'render_dashboard' )
		);
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @param string $hook Hook suffix.
	 * @return void
	 */
	public static function enqueue_assets( $hook ) {
		if ( false === strpos( $hook, 'sbc-' ) && 'toplevel_page_sbc-dashboard' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'sbc-admin',
			SBC_PLUGIN_URL . 'admin/css/admin.css',
			array(),
			SBC_VERSION
		);
	}

	/**
	 * Render overview page.
	 *
	 * @return void
	 */
	public static function render_dashboard() {
		$stats = SBC_Stats::get_dashboard_stats();
		$recent_activity = SBC_Activity_Log::recent( 10 );

		include SBC_PLUGIN_DIR . 'admin/views/dashboard.php';
	}
}
