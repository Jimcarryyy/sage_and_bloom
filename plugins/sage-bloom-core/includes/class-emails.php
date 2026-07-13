<?php
/**
 * Booking notification emails.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Email notifications.
 */
class SBC_Emails {

	/**
	 * Send booking received email to customer and admin.
	 *
	 * @param int $booking_id Booking ID.
	 * @return void
	 */
	public static function send_booking_received( $booking_id ) {
		$booking = SBC_Booking_Service::get( $booking_id );
		if ( ! $booking ) {
			return;
		}

		$formatted = SBC_Booking_Service::format( $booking );
		$admin     = sbc_business_option( 'business_email', get_option( 'admin_email' ) );

		$subject = sprintf(
			/* translators: %s: customer name */
			__( 'Booking request received — %s', 'sage-bloom-core' ),
			$formatted['customer_name']
		);

		$body = self::booking_body( $formatted, __( 'We received your appointment request and will confirm shortly.', 'sage-bloom-core' ) );

		wp_mail( $booking->customer_email, $subject, $body );
		wp_mail( $admin, $subject, $body );
	}

	/**
	 * Send booking confirmed email.
	 *
	 * @param int $booking_id Booking ID.
	 * @return void
	 */
	public static function send_booking_confirmed( $booking_id ) {
		$booking = SBC_Booking_Service::get( $booking_id );
		if ( ! $booking ) {
			return;
		}

		$formatted = SBC_Booking_Service::format( $booking );
		$subject   = __( 'Your appointment is confirmed', 'sage-bloom-core' );
		$body      = self::booking_body( $formatted, __( 'Your appointment has been confirmed. We look forward to seeing you!', 'sage-bloom-core' ) );

		wp_mail( $booking->customer_email, $subject, $body );
	}

	/**
	 * Build plain-text booking email body.
	 *
	 * @param array<string, mixed> $booking Formatted booking.
	 * @param string               $intro   Intro line.
	 * @return string
	 */
	private static function booking_body( $booking, $intro ) {
		$lines = array(
			$intro,
			'',
			sprintf( __( 'Service: %s', 'sage-bloom-core' ), $booking['service_name'] ),
			sprintf( __( 'Date: %s', 'sage-bloom-core' ), $booking['booking_date'] ),
			sprintf( __( 'Time: %s', 'sage-bloom-core' ), $booking['time_label'] ),
			sprintf( __( 'Status: %s', 'sage-bloom-core' ), ucfirst( $booking['status'] ) ),
		);

		if ( ! empty( $booking['message'] ) ) {
			$lines[] = '';
			$lines[] = __( 'Notes:', 'sage-bloom-core' );
			$lines[] = $booking['message'];
		}

		$lines[] = '';
		$lines[] = sbc_business_option( 'business_name', get_bloginfo( 'name' ) );

		return implode( "\n", $lines );
	}
}
