<?php
/**
 * Shared helpers.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Table name with prefix.
 *
 * @param string $name Table suffix.
 * @return string
 */
function sbc_table( $name ) {
	global $wpdb;
	return $wpdb->prefix . 'sb_' . $name;
}

/**
 * Whether the site is running on a local storefront host.
 *
 * @return bool
 */
function sbc_is_local_storefront() {
	if ( function_exists( 'starter_theme_is_local_storefront' ) ) {
		return starter_theme_is_local_storefront();
	}

	$host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
	return (bool) preg_match( '/(^localhost(:\d+)?$)|(^127\.0\.0\.1(:\d+)?$)|(\.test(:\d+)?$)|(\.local(:\d+)?$)/i', $host );
}

/**
 * Plugin settings with defaults.
 *
 * @param string $key     Setting key.
 * @param mixed  $default Default value.
 * @return mixed
 */
function sbc_get_setting( $key, $default = '' ) {
	$settings = get_option( 'sbc_settings', array() );
	return isset( $settings[ $key ] ) && '' !== $settings[ $key ] ? $settings[ $key ] : $default;
}

/**
 * Update a single plugin setting.
 *
 * @param string $key   Setting key.
 * @param mixed  $value Setting value.
 * @return void
 */
function sbc_update_setting( $key, $value ) {
	$settings         = get_option( 'sbc_settings', array() );
	$settings[ $key ] = $value;
	update_option( 'sbc_settings', $settings );
}

/**
 * Stripe secret key from settings or wp-config constant.
 *
 * @return string
 */
function sbc_stripe_secret_key() {
	if ( defined( 'SBC_STRIPE_SECRET_KEY' ) && SBC_STRIPE_SECRET_KEY ) {
		return SBC_STRIPE_SECRET_KEY;
	}
	return (string) sbc_get_setting( 'stripe_secret_key', '' );
}

/**
 * Stripe publishable key from settings or wp-config constant.
 *
 * @return string
 */
function sbc_stripe_publishable_key() {
	if ( defined( 'SBC_STRIPE_PUBLISHABLE_KEY' ) && SBC_STRIPE_PUBLISHABLE_KEY ) {
		return SBC_STRIPE_PUBLISHABLE_KEY;
	}
	return (string) sbc_get_setting( 'stripe_publishable_key', '' );
}

/**
 * Stripe webhook secret from settings or wp-config constant.
 *
 * @return string
 */
function sbc_stripe_webhook_secret() {
	if ( defined( 'SBC_STRIPE_WEBHOOK_SECRET' ) && SBC_STRIPE_WEBHOOK_SECRET ) {
		return SBC_STRIPE_WEBHOOK_SECRET;
	}
	return (string) sbc_get_setting( 'stripe_webhook_secret', '' );
}

/**
 * Valid booking statuses.
 *
 * @return array<string>
 */
function sbc_booking_statuses() {
	return array( 'pending', 'confirmed', 'completed', 'cancelled' );
}

/**
 * Parse service duration string to minutes.
 *
 * @param string $duration Duration label e.g. "2 hrs", "45 min".
 * @return int
 */
function sbc_parse_duration_minutes( $duration ) {
	$duration = strtolower( trim( (string) $duration ) );

	if ( preg_match( '/(\d+)\s*h/', $duration, $matches ) ) {
		return (int) $matches[1] * 60;
	}

	if ( preg_match( '/(\d+)\s*m/', $duration, $matches ) ) {
		return (int) $matches[1];
	}

	return (int) sbc_get_setting( 'default_slot_duration', 60 );
}

/**
 * Get business option via theme helper when available.
 *
 * @param string $key     Field key.
 * @param mixed  $default Default.
 * @return mixed
 */
function sbc_business_option( $key, $default = '' ) {
	if ( function_exists( 'starter_theme_business_option' ) ) {
		return starter_theme_business_option( $key, $default );
	}

	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $key, 'option' );
		if ( null !== $value && '' !== $value ) {
			return $value;
		}
	}

	return $default;
}

/**
 * Format time for display.
 *
 * @param string $time Time in H:i:s.
 * @return string
 */
function sbc_format_time( $time ) {
	$timestamp = strtotime( '1970-01-01 ' . $time );
	return $timestamp ? gmdate( 'g:i A', $timestamp ) : $time;
}
