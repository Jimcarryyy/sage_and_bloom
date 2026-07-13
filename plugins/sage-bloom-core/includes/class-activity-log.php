<?php
/**
 * Activity log for bookings and orders.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Activity log service.
 */
class SBC_Activity_Log {

	/**
	 * Write a log entry.
	 *
	 * @param string               $entity_type Entity type.
	 * @param int                  $entity_id   Entity ID.
	 * @param string               $action      Action name.
	 * @param array<string, mixed> $meta        Optional metadata.
	 * @return void
	 */
	public static function log( $entity_type, $entity_id, $action, $meta = array() ) {
		global $wpdb;

		$wpdb->insert(
			sbc_table( 'activity_log' ),
			array(
				'entity_type' => sanitize_key( $entity_type ),
				'entity_id'   => absint( $entity_id ),
				'action'      => sanitize_key( $action ),
				'user_id'     => get_current_user_id(),
				'meta'        => wp_json_encode( $meta ),
			),
			array( '%s', '%d', '%s', '%d', '%s' )
		);
	}

	/**
	 * Recent log entries.
	 *
	 * @param int $limit Max rows.
	 * @return array<int, object>
	 */
	public static function recent( $limit = 20 ) {
		global $wpdb;

		$table = sbc_table( 'activity_log' );

		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$table} ORDER BY created_at DESC LIMIT %d", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				absint( $limit )
			)
		);
	}
}
