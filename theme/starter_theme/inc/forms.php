<?php
/**
 * Form submission handlers for fallback forms.
 *
 * @package starter_theme
 */

/**
 * Legacy booking form handler — kept for fallback form when plugin is inactive.
 */
function starter_theme_handle_booking_form() {
	if ( class_exists( 'SBC_Booking_Service' ) ) {
		wp_safe_redirect( starter_theme_booking_url() );
		exit;
	}

	if ( ! isset( $_POST['booking_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['booking_nonce'] ) ), 'starter_theme_booking' ) ) {
		wp_die( esc_html__( 'Security check failed.', 'starter_theme' ) );
	}

	$name    = isset( $_POST['booking_name'] ) ? sanitize_text_field( wp_unslash( $_POST['booking_name'] ) ) : '';
	$email   = isset( $_POST['booking_email'] ) ? sanitize_email( wp_unslash( $_POST['booking_email'] ) ) : '';
	$phone   = isset( $_POST['booking_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['booking_phone'] ) ) : '';
	$date    = isset( $_POST['booking_date'] ) ? sanitize_text_field( wp_unslash( $_POST['booking_date'] ) ) : '';
	$service = isset( $_POST['booking_service'] ) ? sanitize_text_field( wp_unslash( $_POST['booking_service'] ) ) : '';
	$message = isset( $_POST['booking_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['booking_message'] ) ) : '';

	$admin_email = starter_theme_business_option( 'business_email', get_option( 'admin_email' ) );
	$subject     = sprintf( __( 'New booking request from %s', 'starter_theme' ), $name );
	$body        = sprintf(
		"Name: %s\nEmail: %s\nPhone: %s\nDate: %s\nService: %s\nMessage: %s",
		$name,
		$email,
		$phone,
		$date,
		$service,
		$message
	);

	wp_mail( $admin_email, $subject, $body );

	wp_safe_redirect( add_query_arg( 'booking', 'sent', wp_get_referer() ?: starter_theme_booking_url() ) );
	exit;
}
add_action( 'admin_post_starter_theme_booking', 'starter_theme_handle_booking_form' );
add_action( 'admin_post_nopriv_starter_theme_booking', 'starter_theme_handle_booking_form' );

/**
 * Handle contact form submission.
 */
function starter_theme_handle_contact_form() {
	if ( ! isset( $_POST['contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['contact_nonce'] ) ), 'starter_theme_contact' ) ) {
		wp_die( esc_html__( 'Security check failed.', 'starter_theme' ) );
	}

	$name    = isset( $_POST['contact_name'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_name'] ) ) : '';
	$email   = isset( $_POST['contact_email'] ) ? sanitize_email( wp_unslash( $_POST['contact_email'] ) ) : '';
	$subject = isset( $_POST['contact_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_subject'] ) ) : __( 'Website contact', 'starter_theme' );
	$message = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) : '';

	$admin_email = starter_theme_business_option( 'business_email', get_option( 'admin_email' ) );
	$body        = sprintf( "Name: %s\nEmail: %s\n\n%s", $name, $email, $message );

	wp_mail( $admin_email, $subject, $body );

	wp_safe_redirect( add_query_arg( 'contact', 'sent', wp_get_referer() ?: home_url( '/contact/' ) ) );
	exit;
}
add_action( 'admin_post_starter_theme_contact', 'starter_theme_handle_contact_form' );
add_action( 'admin_post_nopriv_starter_theme_contact', 'starter_theme_handle_contact_form' );

/**
 * Show form success notices.
 */
function starter_theme_form_notices() {
	if ( isset( $_GET['booking'] ) && 'sent' === $_GET['booking'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		echo '<div class="form-notice form-notice--success"><p>' . esc_html__( 'Thank you! Your booking request has been sent.', 'starter_theme' ) . '</p></div>';
	}
	if ( isset( $_GET['contact'] ) && 'sent' === $_GET['contact'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		echo '<div class="form-notice form-notice--success"><p>' . esc_html__( 'Thank you! Your message has been sent.', 'starter_theme' ) . '</p></div>';
	}
}
add_action( 'wp_body_open', 'starter_theme_form_notices', 20 );
