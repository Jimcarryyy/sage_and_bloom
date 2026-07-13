<?php
/**
 * Availability and slot generation.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Availability engine.
 */
class SBC_Availability {

	/**
	 * Get weekly rules keyed by day of week.
	 *
	 * @return array<int, object>
	 */
	public static function get_rules() {
		global $wpdb;

		$table = sbc_table( 'availability_rules' );
		$rows  = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY day_of_week ASC" ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$rules = array();
		foreach ( $rows as $row ) {
			$rules[ (int) $row->day_of_week ] = $row;
		}

		return $rules;
	}

	/**
	 * Update weekly rules.
	 *
	 * @param array<int, array<string, mixed>> $rules Rules payload.
	 * @return void
	 */
	public static function update_rules( $rules ) {
		global $wpdb;

		$table = sbc_table( 'availability_rules' );

		foreach ( $rules as $day => $rule ) {
			$wpdb->replace(
				$table,
				array(
					'day_of_week'    => absint( $day ),
					'start_time'     => sanitize_text_field( $rule['start_time'] ?? '09:00:00' ),
					'end_time'       => sanitize_text_field( $rule['end_time'] ?? '17:00:00' ),
					'is_closed'      => ! empty( $rule['is_closed'] ) ? 1 : 0,
					'slot_duration'  => absint( $rule['slot_duration'] ?? sbc_get_setting( 'default_slot_duration', 60 ) ),
					'buffer_minutes' => absint( $rule['buffer_minutes'] ?? sbc_get_setting( 'default_buffer_minutes', 15 ) ),
				),
				array( '%d', '%s', '%s', '%d', '%d', '%d' )
			);
		}
	}

	/**
	 * Whether a date is blocked.
	 *
	 * @param string $date Y-m-d.
	 * @return bool
	 */
	public static function is_date_blocked( $date ) {
		global $wpdb;

		$table = sbc_table( 'blocked_dates' );

		return (bool) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT id FROM {$table} WHERE blocked_date = %s LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$date
			)
		);
	}

	/**
	 * Available slots for a service on a date.
	 *
	 * @param int    $service_id Service post ID.
	 * @param string $date       Y-m-d.
	 * @return array<int, array{time: string, label: string, duration: int}>
	 */
	public static function get_slots( $service_id, $date ) {
		if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
			return array();
		}

		if ( self::is_date_blocked( $date ) ) {
			return array();
		}

		$day_of_week = (int) gmdate( 'w', strtotime( $date . ' UTC' ) );
		$rules       = self::get_rules();
		$rule        = isset( $rules[ $day_of_week ] ) ? $rules[ $day_of_week ] : null;

		if ( ! $rule || ! empty( $rule->is_closed ) ) {
			return array();
		}

		$duration = self::service_duration( $service_id );
		$slot_gap = max( 15, (int) $rule->slot_duration );
		$buffer   = max( 0, (int) $rule->buffer_minutes );

		$start = strtotime( $date . ' ' . $rule->start_time );
		$end   = strtotime( $date . ' ' . $rule->end_time );

		if ( ! $start || ! $end || $start >= $end ) {
			return array();
		}

		$booked = SBC_Booking_Service::get_booked_ranges( $date );
		$slots  = array();
		$cursor = $start;

		while ( ( $cursor + ( $duration * 60 ) ) <= $end ) {
			$slot_end = $cursor + ( $duration * 60 );

			if ( ! self::overlaps_booked( $cursor, $slot_end + ( $buffer * 60 ), $booked ) ) {
				$time = gmdate( 'H:i:s', $cursor );
				$slots[] = array(
					'time'     => $time,
					'label'    => sbc_format_time( $time ),
					'duration' => $duration,
				);
			}

			$cursor += $slot_gap * 60;
		}

		return $slots;
	}

	/**
	 * Service duration in minutes.
	 *
	 * @param int $service_id Service post ID.
	 * @return int
	 */
	public static function service_duration( $service_id ) {
		if ( function_exists( 'get_field' ) ) {
			$duration = get_field( 'service_duration', $service_id );
			if ( $duration ) {
				return sbc_parse_duration_minutes( $duration );
			}
		}

		return (int) sbc_get_setting( 'default_slot_duration', 60 );
	}

	/**
	 * Check overlap with booked ranges.
	 *
	 * @param int                 $start  Start timestamp.
	 * @param int                 $end    End timestamp.
	 * @param array<int, array>   $booked Booked ranges.
	 * @return bool
	 */
	private static function overlaps_booked( $start, $end, $booked ) {
		foreach ( $booked as $range ) {
			if ( $start < $range['end'] && $end > $range['start'] ) {
				return true;
			}
		}
		return false;
	}
}
