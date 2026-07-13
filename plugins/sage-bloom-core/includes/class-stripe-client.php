<?php
/**
 * Stripe HTTP client (no Composer dependency).
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Minimal Stripe API client.
 */
class SBC_Stripe_Client {

	const API_BASE = 'https://api.stripe.com/v1/';

	/**
	 * Create a PaymentIntent.
	 *
	 * @param array<string, mixed> $params Intent params.
	 * @return array<string, mixed>|WP_Error
	 */
	public static function create_payment_intent( $params ) {
		return self::request( 'POST', 'payment_intents', $params );
	}

	/**
	 * Retrieve a PaymentIntent.
	 *
	 * @param string $intent_id Intent ID.
	 * @return array<string, mixed>|WP_Error
	 */
	public static function retrieve_payment_intent( $intent_id ) {
		return self::request( 'GET', 'payment_intents/' . rawurlencode( $intent_id ) );
	}

	/**
	 * Construct webhook event from payload.
	 *
	 * @param string $payload   Raw body.
	 * @param string $signature Stripe-Signature header.
	 * @return array<string, mixed>|WP_Error
	 */
	public static function construct_webhook_event( $payload, $signature ) {
		$secret = sbc_stripe_webhook_secret();
		if ( ! $secret ) {
			return new WP_Error( 'missing_webhook_secret', __( 'Stripe webhook secret is not configured.', 'sage-bloom-core' ) );
		}

		$parts = array();
		foreach ( explode( ',', $signature ) as $part ) {
			$pair = explode( '=', trim( $part ), 2 );
			if ( 2 === count( $pair ) ) {
				$parts[ $pair[0] ][] = $pair[1];
			}
		}

		if ( empty( $parts['t'][0] ) || empty( $parts['v1'] ) ) {
			return new WP_Error( 'invalid_signature', __( 'Invalid Stripe signature.', 'sage-bloom-core' ) );
		}

		$timestamp = $parts['t'][0];
		$signed    = $timestamp . '.' . $payload;
		$valid     = false;

		foreach ( $parts['v1'] as $sig ) {
			$expected = hash_hmac( 'sha256', $signed, $secret );
			if ( hash_equals( $expected, $sig ) ) {
				$valid = true;
				break;
			}
		}

		if ( ! $valid ) {
			return new WP_Error( 'invalid_signature', __( 'Stripe webhook signature verification failed.', 'sage-bloom-core' ) );
		}

		$event = json_decode( $payload, true );
		if ( ! is_array( $event ) ) {
			return new WP_Error( 'invalid_payload', __( 'Invalid webhook payload.', 'sage-bloom-core' ) );
		}

		return $event;
	}

	/**
	 * Stripe API request.
	 *
	 * @param string               $method HTTP method.
	 * @param string               $path   API path.
	 * @param array<string, mixed> $params Body params.
	 * @return array<string, mixed>|WP_Error
	 */
	private static function request( $method, $path, $params = array() ) {
		$secret = sbc_stripe_secret_key();
		if ( ! $secret ) {
			return new WP_Error( 'missing_secret_key', __( 'Stripe secret key is not configured.', 'sage-bloom-core' ) );
		}

		if ( ! sbc_is_local_storefront() && ! self::is_test_key( $secret ) ) {
			return new WP_Error( 'live_key_blocked', __( 'Live Stripe keys are blocked outside local development.', 'sage-bloom-core' ) );
		}

		$args = array(
			'method'  => $method,
			'headers' => array(
				'Authorization'  => 'Bearer ' . $secret,
				'Content-Type' => 'application/x-www-form-urlencoded',
			),
			'timeout' => 30,
		);

		if ( 'GET' === $method ) {
			$url = self::API_BASE . $path;
			if ( ! empty( $params ) ) {
				$url = add_query_arg( $params, $url );
			}
		} else {
			$url = self::API_BASE . $path;
			$args['body'] = self::encode_params( $params );
		}

		$response = wp_remote_request( $url, $args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		$code = wp_remote_retrieve_response_code( $response );

		if ( $code >= 400 ) {
			$message = isset( $body['error']['message'] ) ? $body['error']['message'] : __( 'Stripe request failed.', 'sage-bloom-core' );
			return new WP_Error( 'stripe_error', $message, array( 'status' => $code ) );
		}

		return is_array( $body ) ? $body : array();
	}

	/**
	 * Flatten params for Stripe form encoding.
	 *
	 * @param array<string, mixed> $params Params.
	 * @param string               $prefix Prefix.
	 * @return array<string, string>
	 */
	private static function encode_params( $params, $prefix = '' ) {
		$result = array();

		foreach ( $params as $key => $value ) {
			$full_key = $prefix ? $prefix . '[' . $key . ']' : $key;

			if ( is_array( $value ) ) {
				$result = array_merge( $result, self::encode_params( $value, $full_key ) );
			} elseif ( is_bool( $value ) ) {
				$result[ $full_key ] = $value ? 'true' : 'false';
			} else {
				$result[ $full_key ] = (string) $value;
			}
		}

		return $result;
	}

	/**
	 * Whether a key is a Stripe test key.
	 *
	 * @param string $key Secret key.
	 * @return bool
	 */
	private static function is_test_key( $key ) {
		return 0 === strpos( $key, 'sk_test_' );
	}
}
