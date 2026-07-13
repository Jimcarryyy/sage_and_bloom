<?php
/**
 * Admin bookings management.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Bookings admin screen.
 */
class SBC_Admin_Bookings {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_post_sbc_update_booking', array( __CLASS__, 'handle_update' ) );
	}

	/**
	 * Register submenu.
	 *
	 * @return void
	 */
	public static function register_menu() {
		add_submenu_page(
			'sbc-dashboard',
			__( 'Bookings', 'sage-bloom-core' ),
			__( 'Bookings', 'sage-bloom-core' ),
			'manage_options',
			'sbc-bookings',
			array( __CLASS__, 'render_page' )
		);
	}

	/**
	 * Handle booking status update.
	 *
	 * @return void
	 */
	public static function handle_update() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized.', 'sage-bloom-core' ) );
		}

		check_admin_referer( 'sbc_update_booking' );

		$id     = absint( $_POST['booking_id'] ?? 0 );
		$status = sanitize_key( $_POST['status'] ?? '' );
		$staff  = absint( $_POST['staff_id'] ?? 0 );

		if ( $id ) {
			SBC_Booking_Service::update_status( $id, $status, $staff );
		}

		wp_safe_redirect( add_query_arg( array( 'page' => 'sbc-bookings', 'updated' => 1 ), admin_url( 'admin.php' ) ) );
		exit;
	}

	/**
	 * Render bookings page.
	 *
	 * @return void
	 */
	public static function render_page() {
		$status_filter = isset( $_GET['status'] ) ? sanitize_key( wp_unslash( $_GET['status'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$view          = isset( $_GET['view'] ) ? sanitize_key( wp_unslash( $_GET['view'] ) ) : 'list'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$bookings = SBC_Booking_Service::query(
			array(
				'status' => $status_filter,
				'limit'  => 100,
			)
		);

		$team = get_posts(
			array(
				'post_type'      => 'team',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		include SBC_PLUGIN_DIR . 'admin/views/bookings.php';
	}
}
