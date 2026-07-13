<?php
/**
 * Dashboard statistics.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Stats service.
 */
class SBC_Stats {

	/**
	 * Admin dashboard KPIs.
	 *
	 * @return array<string, mixed>
	 */
	public static function get_dashboard_stats() {
		global $wpdb;

		$bookings_table = sbc_table( 'bookings' );
		$today          = gmdate( 'Y-m-d' );

		$today_bookings = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$bookings_table} WHERE booking_date = %s AND status IN ('pending','confirmed')", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$today
			)
		);

		$pending_bookings = (int) $wpdb->get_var(
			"SELECT COUNT(*) FROM {$bookings_table} WHERE status = 'pending'" // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		);

		$stats = array(
			'today_bookings'   => $today_bookings,
			'pending_bookings' => $pending_bookings,
			'recent_orders'    => 0,
			'revenue_30d'      => 0.0,
			'low_stock_count'  => 0,
			'bookings_by_status' => self::bookings_by_status(),
			'orders_last_30_days' => array(),
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$stats['recent_orders'] = self::recent_order_count( 7 );
			$stats['revenue_30d']   = self::revenue_last_days( 30 );
			$stats['low_stock_count'] = self::low_stock_count();
			$stats['orders_last_30_days'] = self::orders_by_day( 30 );
		}

		return $stats;
	}

	/**
	 * Booking counts grouped by status.
	 *
	 * @return array<string, int>
	 */
	public static function bookings_by_status() {
		global $wpdb;

		$table = sbc_table( 'bookings' );
		$rows  = $wpdb->get_results( "SELECT status, COUNT(*) AS total FROM {$table} GROUP BY status", ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$counts = array_fill_keys( sbc_booking_statuses(), 0 );
		foreach ( $rows as $row ) {
			$counts[ $row['status'] ] = (int) $row['total'];
		}

		return $counts;
	}

	/**
	 * Recent order count.
	 *
	 * @param int $days Days back.
	 * @return int
	 */
	public static function recent_order_count( $days ) {
		$orders = wc_get_orders(
			array(
				'limit'        => -1,
				'status'       => array_keys( wc_get_order_statuses() ),
				'date_created' => '>' . ( time() - ( DAY_IN_SECONDS * absint( $days ) ) ),
				'return'       => 'ids',
			)
		);

		return is_array( $orders ) ? count( $orders ) : 0;
	}

	/**
	 * Revenue for completed/processing orders in last N days.
	 *
	 * @param int $days Days back.
	 * @return float
	 */
	public static function revenue_last_days( $days ) {
		$orders = wc_get_orders(
			array(
				'limit'        => -1,
				'status'       => array( 'processing', 'completed' ),
				'date_created' => '>' . ( time() - ( DAY_IN_SECONDS * absint( $days ) ) ),
				'return'       => 'objects',
			)
		);

		$total = 0.0;
		foreach ( $orders as $order ) {
			$total += (float) $order->get_total();
		}

		return round( $total, 2 );
	}

	/**
	 * Count products at or below low stock threshold.
	 *
	 * @return int
	 */
	public static function low_stock_count() {
		$products = wc_get_products(
			array(
				'limit'      => -1,
				'status'     => 'publish',
				'stock_status' => 'instock',
				'manage_stock' => true,
				'return'     => 'objects',
			)
		);

		$count = 0;
		foreach ( $products as $product ) {
			if ( $product->managing_stock() && $product->get_stock_quantity() <= wc_get_low_stock_amount() ) {
				++$count;
			}
		}

		return $count;
	}

	/**
	 * Daily order totals for chart data.
	 *
	 * @param int $days Days back.
	 * @return array<int, array{date: string, total: float, count: int}>
	 */
	public static function orders_by_day( $days ) {
		$data = array();

		for ( $i = $days - 1; $i >= 0; $i-- ) {
			$date = gmdate( 'Y-m-d', time() - ( DAY_IN_SECONDS * $i ) );
			$data[] = array(
				'date'  => $date,
				'total' => 0.0,
				'count' => 0,
			);
		}

		$orders = wc_get_orders(
			array(
				'limit'        => -1,
				'status'       => array( 'processing', 'completed' ),
				'date_created' => '>' . ( time() - ( DAY_IN_SECONDS * absint( $days ) ) ),
				'return'       => 'objects',
			)
		);

		$indexed = array();
		foreach ( $data as $row ) {
			$indexed[ $row['date'] ] = &$row;
		}

		foreach ( $orders as $order ) {
			$date = $order->get_date_created() ? $order->get_date_created()->date( 'Y-m-d' ) : '';
			if ( isset( $indexed[ $date ] ) ) {
				$indexed[ $date ]['total'] += (float) $order->get_total();
				$indexed[ $date ]['count']++;
			}
		}

		return array_values(
			array_map(
				static function ( $row ) {
					$row['total'] = round( $row['total'], 2 );
					return $row;
				},
				$data
			)
		);
	}
}
