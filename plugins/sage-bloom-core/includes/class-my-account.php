<?php
/**
 * WooCommerce My Account booking history.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * My Account integration.
 */
class SBC_My_Account {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'add_menu_item' ) );
		add_action( 'init', array( __CLASS__, 'add_endpoint' ) );
		add_action( 'woocommerce_account_bookings_endpoint', array( __CLASS__, 'render_bookings' ) );
		add_filter( 'query_vars', array( __CLASS__, 'add_query_var' ), 0 );
	}

	/**
	 * Register bookings endpoint.
	 *
	 * @return void
	 */
	public static function add_endpoint() {
		add_rewrite_endpoint( 'bookings', EP_ROOT | EP_PAGES );
	}

	/**
	 * Add query var.
	 *
	 * @param array $vars Query vars.
	 * @return array
	 */
	public static function add_query_var( $vars ) {
		$vars[] = 'bookings';
		return $vars;
	}

	/**
	 * Add menu item after orders.
	 *
	 * @param array $items Menu items.
	 * @return array
	 */
	public static function add_menu_item( $items ) {
		$new = array();

		foreach ( $items as $key => $label ) {
			$new[ $key ] = $label;
			if ( 'orders' === $key ) {
				$new['bookings'] = __( 'Bookings', 'sage-bloom-core' );
			}
		}

		if ( ! isset( $new['bookings'] ) ) {
			$new['bookings'] = __( 'Bookings', 'sage-bloom-core' );
		}

		return $new;
	}

	/**
	 * Render bookings tab content.
	 *
	 * @return void
	 */
	public static function render_bookings() {
		$user = wp_get_current_user();
		if ( ! $user->ID ) {
			return;
		}

		$bookings = SBC_Booking_Service::query(
			array(
				'email' => $user->user_email,
				'limit' => 50,
			)
		);

		if ( empty( $bookings ) ) {
			$bookings = SBC_Booking_Service::query(
				array(
					'user_id' => $user->ID,
					'limit'   => 50,
				)
			);
		}

		echo '<div class="sbc-my-bookings">';
		echo '<h2>' . esc_html__( 'My Appointments', 'sage-bloom-core' ) . '</h2>';

		if ( empty( $bookings ) ) {
			echo '<p>' . esc_html__( 'You have no appointments yet.', 'sage-bloom-core' ) . '</p>';
			if ( function_exists( 'starter_theme_booking_url' ) ) {
				echo '<p><a class="button" href="' . esc_url( starter_theme_booking_url() ) . '">' . esc_html__( 'Book an appointment', 'sage-bloom-core' ) . '</a></p>';
			}
			echo '</div>';
			return;
		}

		echo '<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table sbc-bookings-table">';
		echo '<thead><tr>';
		echo '<th>' . esc_html__( 'Service', 'sage-bloom-core' ) . '</th>';
		echo '<th>' . esc_html__( 'Date', 'sage-bloom-core' ) . '</th>';
		echo '<th>' . esc_html__( 'Time', 'sage-bloom-core' ) . '</th>';
		echo '<th>' . esc_html__( 'Status', 'sage-bloom-core' ) . '</th>';
		echo '</tr></thead><tbody>';

		foreach ( $bookings as $booking ) {
			$row = SBC_Booking_Service::format( $booking );
			echo '<tr>';
			echo '<td data-title="' . esc_attr__( 'Service', 'sage-bloom-core' ) . '">' . esc_html( $row['service_name'] ) . '</td>';
			echo '<td data-title="' . esc_attr__( 'Date', 'sage-bloom-core' ) . '">' . esc_html( $row['booking_date'] ) . '</td>';
			echo '<td data-title="' . esc_attr__( 'Time', 'sage-bloom-core' ) . '">' . esc_html( $row['time_label'] ) . '</td>';
			echo '<td data-title="' . esc_attr__( 'Status', 'sage-bloom-core' ) . '"><span class="sbc-status sbc-status--' . esc_attr( $row['status'] ) . '">' . esc_html( ucfirst( $row['status'] ) ) . '</span></td>';
			echo '</tr>';
		}

		echo '</tbody></table></div>';
	}
}
