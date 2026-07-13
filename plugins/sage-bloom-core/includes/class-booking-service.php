<?php
/**
 * Booking CRUD and status machine.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Booking service.
 */
class SBC_Booking_Service {

	/**
	 * Create a booking.
	 *
	 * @param array<string, mixed> $data Booking payload.
	 * @return int|WP_Error
	 */
	public static function create( $data ) {
		global $wpdb;

		$service_id = absint( $data['service_id'] ?? 0 );
		$date       = sanitize_text_field( $data['booking_date'] ?? '' );
		$time       = sanitize_text_field( $data['booking_time'] ?? '' );
		$name       = sanitize_text_field( $data['customer_name'] ?? '' );
		$email      = sanitize_email( $data['customer_email'] ?? '' );
		$phone      = sanitize_text_field( $data['customer_phone'] ?? '' );
		$message    = sanitize_textarea_field( $data['message'] ?? '' );

		if ( ! $service_id || ! get_post( $service_id ) || 'service' !== get_post_type( $service_id ) ) {
			return new WP_Error( 'invalid_service', __( 'Please select a valid service.', 'sage-bloom-core' ), array( 'status' => 400 ) );
		}

		if ( ! $name || ! $email || ! is_email( $email ) ) {
			return new WP_Error( 'invalid_contact', __( 'Name and a valid email are required.', 'sage-bloom-core' ), array( 'status' => 400 ) );
		}

		if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) || ! preg_match( '/^\d{2}:\d{2}(:\d{2})?$/', $time ) ) {
			return new WP_Error( 'invalid_datetime', __( 'Please choose a valid date and time.', 'sage-bloom-core' ), array( 'status' => 400 ) );
		}

		if ( strlen( $time ) === 5 ) {
			$time .= ':00';
		}

		$slots = SBC_Availability::get_slots( $service_id, $date );
		$valid = wp_list_pluck( $slots, 'time' );

		if ( ! in_array( $time, $valid, true ) ) {
			return new WP_Error( 'slot_unavailable', __( 'That time slot is no longer available.', 'sage-bloom-core' ), array( 'status' => 409 ) );
		}

		$duration = SBC_Availability::service_duration( $service_id );
		$token    = wp_generate_password( 32, false, false );
		$user_id  = get_current_user_id();

		$inserted = $wpdb->insert(
			sbc_table( 'bookings' ),
			array(
				'service_id'       => $service_id,
				'staff_id'         => absint( $data['staff_id'] ?? 0 ),
				'user_id'          => $user_id,
				'customer_name'    => $name,
				'customer_email'   => $email,
				'customer_phone'   => $phone,
				'booking_date'     => $date,
				'booking_time'     => $time,
				'duration_minutes' => $duration,
				'status'           => 'pending',
				'message'          => $message,
				'access_token'     => $token,
			),
			array( '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s' )
		);

		if ( ! $inserted ) {
			return new WP_Error( 'db_error', __( 'Could not save your booking. Please try again.', 'sage-bloom-core' ), array( 'status' => 500 ) );
		}

		$booking_id = (int) $wpdb->insert_id;
		SBC_Activity_Log::log( 'booking', $booking_id, 'created', array( 'status' => 'pending' ) );
		SBC_Emails::send_booking_received( $booking_id );

		return $booking_id;
	}

	/**
	 * Get booking by ID.
	 *
	 * @param int $id Booking ID.
	 * @return object|null
	 */
	public static function get( $id ) {
		global $wpdb;

		return $wpdb->get_row(
			$wpdb->prepare(
				'SELECT * FROM ' . sbc_table( 'bookings' ) . ' WHERE id = %d',
				absint( $id )
			)
		);
	}

	/**
	 * Get booking by access token.
	 *
	 * @param int    $id    Booking ID.
	 * @param string $token Access token.
	 * @return object|null
	 */
	public static function get_by_token( $id, $token ) {
		global $wpdb;

		return $wpdb->get_row(
			$wpdb->prepare(
				'SELECT * FROM ' . sbc_table( 'bookings' ) . ' WHERE id = %d AND access_token = %s',
				absint( $id ),
				sanitize_text_field( $token )
			)
		);
	}

	/**
	 * Update booking status.
	 *
	 * @param int    $id     Booking ID.
	 * @param string $status New status.
	 * @param int    $staff_id Optional staff assignment.
	 * @return true|WP_Error
	 */
	public static function update_status( $id, $status, $staff_id = 0 ) {
		global $wpdb;

		$status = sanitize_key( $status );
		if ( ! in_array( $status, sbc_booking_statuses(), true ) ) {
			return new WP_Error( 'invalid_status', __( 'Invalid booking status.', 'sage-bloom-core' ) );
		}

		$booking = self::get( $id );
		if ( ! $booking ) {
			return new WP_Error( 'not_found', __( 'Booking not found.', 'sage-bloom-core' ) );
		}

		$data   = array( 'status' => $status );
		$format = array( '%s' );

		if ( $staff_id > 0 ) {
			$data['staff_id'] = absint( $staff_id );
			$format[]         = '%d';
		}

		$wpdb->update(
			sbc_table( 'bookings' ),
			$data,
			array( 'id' => absint( $id ) ),
			$format,
			array( '%d' )
		);

		SBC_Activity_Log::log(
			'booking',
			$id,
			'status_changed',
			array(
				'from' => $booking->status,
				'to'   => $status,
			)
		);

		if ( 'confirmed' === $status ) {
			SBC_Emails::send_booking_confirmed( $id );
		}

		return true;
	}

	/**
	 * List bookings with filters.
	 *
	 * @param array<string, mixed> $args Query args.
	 * @return array<int, object>
	 */
	public static function query( $args = array() ) {
		global $wpdb;

		$table  = sbc_table( 'bookings' );
		$where  = array( '1=1' );
		$params = array();

		if ( ! empty( $args['status'] ) ) {
			$where[]  = 'status = %s';
			$params[] = sanitize_key( $args['status'] );
		}

		if ( ! empty( $args['date_from'] ) ) {
			$where[]  = 'booking_date >= %s';
			$params[] = sanitize_text_field( $args['date_from'] );
		}

		if ( ! empty( $args['date_to'] ) ) {
			$where[]  = 'booking_date <= %s';
			$params[] = sanitize_text_field( $args['date_to'] );
		}

		if ( ! empty( $args['user_id'] ) ) {
			$where[]  = 'user_id = %d';
			$params[] = absint( $args['user_id'] );
		}

		if ( ! empty( $args['email'] ) ) {
			$where[]  = 'customer_email = %s';
			$params[] = sanitize_email( $args['email'] );
		}

		$limit  = isset( $args['limit'] ) ? absint( $args['limit'] ) : 50;
		$sql    = "SELECT * FROM {$table} WHERE " . implode( ' AND ', $where ) . ' ORDER BY booking_date ASC, booking_time ASC LIMIT %d';
		$params[] = $limit;

		if ( empty( $params ) ) {
			return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table} ORDER BY booking_date DESC, booking_time DESC LIMIT %d", $limit ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}

		return $wpdb->get_results( $wpdb->prepare( $sql, $params ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Booked time ranges for a date.
	 *
	 * @param string $date Y-m-d.
	 * @return array<int, array{start: int, end: int}>
	 */
	public static function get_booked_ranges( $date ) {
		global $wpdb;

		$table = sbc_table( 'bookings' );
		$rows  = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT booking_time, duration_minutes FROM {$table} WHERE booking_date = %s AND status IN ('pending','confirmed')", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$date
			)
		);

		$ranges = array();
		foreach ( $rows as $row ) {
			$start    = strtotime( $date . ' ' . $row->booking_time );
			$duration = max( 15, (int) $row->duration_minutes );
			$ranges[] = array(
				'start' => $start,
				'end'   => $start + ( $duration * 60 ),
			);
		}

		return $ranges;
	}

	/**
	 * Format booking for API response.
	 *
	 * @param object $booking Booking row.
	 * @param bool   $include_token Include access token.
	 * @return array<string, mixed>
	 */
	public static function format( $booking, $include_token = false ) {
		$service = get_post( (int) $booking->service_id );
		$staff   = (int) $booking->staff_id ? get_post( (int) $booking->staff_id ) : null;

		$data = array(
			'id'             => (int) $booking->id,
			'service_id'     => (int) $booking->service_id,
			'service_name'   => $service ? $service->post_title : '',
			'staff_id'       => (int) $booking->staff_id,
			'staff_name'     => $staff ? $staff->post_title : '',
			'customer_name'  => $booking->customer_name,
			'customer_email' => $booking->customer_email,
			'customer_phone' => $booking->customer_phone,
			'booking_date'   => $booking->booking_date,
			'booking_time'   => substr( $booking->booking_time, 0, 5 ),
			'time_label'     => sbc_format_time( $booking->booking_time ),
			'duration'       => (int) $booking->duration_minutes,
			'status'         => $booking->status,
			'message'        => $booking->message,
			'created_at'     => $booking->created_at,
		);

		if ( $include_token ) {
			$data['access_token'] = $booking->access_token;
		}

		return $data;
	}
}
