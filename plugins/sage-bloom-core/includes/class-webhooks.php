<?php
/**
 * Stripe webhook handlers.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Webhook handler.
 */
class SBC_Webhooks {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_webhook_route' ) );
	}

	/**
	 * Register REST webhook endpoint.
	 *
	 * @return void
	 */
	public static function register_webhook_route() {
		register_rest_route(
			SBC_REST_API::NAMESPACE,
			'/webhooks/stripe',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( __CLASS__, 'handle_rest_webhook' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Handle Stripe webhook via REST.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response|WP_Error
	 */
	public static function handle_rest_webhook( $request ) {
		$payload   = $request->get_body();
		$signature = $request->get_header( 'stripe-signature' );

		if ( ! $signature ) {
			return new WP_Error( 'missing_signature', __( 'Missing Stripe signature.', 'sage-bloom-core' ), array( 'status' => 400 ) );
		}

		$event = SBC_Stripe_Client::construct_webhook_event( $payload, $signature );
		if ( is_wp_error( $event ) ) {
			return $event;
		}

		self::process_event( $event );

		return rest_ensure_response( array( 'received' => true ) );
	}

	/**
	 * Legacy WC API webhook endpoint.
	 *
	 * @return void
	 */
	public static function handle_wc_api_webhook() {
		$payload   = file_get_contents( 'php://input' );
		$signature = isset( $_SERVER['HTTP_STRIPE_SIGNATURE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_STRIPE_SIGNATURE'] ) ) : '';

		if ( ! $payload || ! $signature ) {
			status_header( 400 );
			exit;
		}

		$event = SBC_Stripe_Client::construct_webhook_event( $payload, $signature );
		if ( is_wp_error( $event ) ) {
			status_header( 400 );
			exit;
		}

		self::process_event( $event );
		status_header( 200 );
		exit;
	}

	/**
	 * Process Stripe event.
	 *
	 * @param array<string, mixed> $event Event payload.
	 * @return void
	 */
	private static function process_event( $event ) {
		$type = isset( $event['type'] ) ? $event['type'] : '';

		if ( 'payment_intent.succeeded' === $type ) {
			self::handle_payment_intent_succeeded( $event['data']['object'] ?? array() );
		}
	}

	/**
	 * Mark order complete when payment intent succeeds.
	 *
	 * @param array<string, mixed> $intent PaymentIntent object.
	 * @return void
	 */
	private static function handle_payment_intent_succeeded( $intent ) {
		$order_id = isset( $intent['metadata']['order_id'] ) ? absint( $intent['metadata']['order_id'] ) : 0;
		if ( ! $order_id ) {
			return;
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		if ( ! $order->is_paid() ) {
			$order->payment_complete( $intent['id'] ?? '' );
			$order->add_order_note( __( 'Stripe webhook: payment_intent.succeeded', 'sage-bloom-core' ) );
		}

		if ( $order->has_status( 'processing' ) ) {
			$order->update_status( 'completed', __( 'Auto-completed via Stripe test webhook.', 'sage-bloom-core' ) );
		}

		SBC_Activity_Log::log( 'order', $order_id, 'webhook_payment_succeeded', array( 'intent_id' => $intent['id'] ?? '' ) );
	}
}
