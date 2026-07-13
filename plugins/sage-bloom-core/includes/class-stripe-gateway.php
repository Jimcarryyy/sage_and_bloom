<?php
/**
 * WooCommerce Stripe test payment gateway.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Stripe test gateway for local portfolio demos.
 */
class SBC_Stripe_Gateway extends WC_Payment_Gateway {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id                 = 'sbc_stripe_test';
		$this->icon               = '';
		$this->has_fields         = true;
		$this->method_title       = __( 'Stripe (Test Mode)', 'sage-bloom-core' );
		$this->method_description = __( 'Accept test card payments via Stripe Payment Intents. Local/test keys only.', 'sage-bloom-core' );
		$this->supports           = array( 'products' );

		$this->init_form_fields();
		$this->init_settings();

		$this->enabled     = $this->get_option( 'enabled', 'yes' );
		$this->title       = $this->get_option( 'title', __( 'Credit / Debit Card (Test)', 'sage-bloom-core' ) );
		$this->description = $this->get_option( 'description', __( 'Pay securely with Stripe test cards. No real charges are made.', 'sage-bloom-core' ) );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_checkout_scripts' ) );
		add_action( 'woocommerce_api_sbc_stripe_webhook', array( 'SBC_Webhooks', 'handle_wc_api_webhook' ) );
		add_action( 'woocommerce_rest_checkout_process_payment_with_context', array( __CLASS__, 'process_blocks_payment' ), 10, 2 );
	}

	/**
	 * Gateway settings fields.
	 *
	 * @return void
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'sage-bloom-core' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Stripe test payments', 'sage-bloom-core' ),
				'default' => 'yes',
			),
			'title'   => array(
				'title'       => __( 'Title', 'sage-bloom-core' ),
				'type'        => 'text',
				'description' => __( 'Payment method title shown at checkout.', 'sage-bloom-core' ),
				'default'     => __( 'Credit / Debit Card (Test)', 'sage-bloom-core' ),
			),
			'description' => array(
				'title'       => __( 'Description', 'sage-bloom-core' ),
				'type'        => 'textarea',
				'description' => __( 'Payment method description shown at checkout.', 'sage-bloom-core' ),
				'default'     => __( 'Pay securely with Stripe test cards. No real charges are made.', 'sage-bloom-core' ),
			),
		);
	}

	/**
	 * Whether gateway is available.
	 *
	 * @return bool
	 */
	public function is_available() {
		if ( 'yes' !== $this->enabled ) {
			return false;
		}

		if ( ! sbc_stripe_publishable_key() || ! sbc_stripe_secret_key() ) {
			return false;
		}

		if ( ! sbc_is_local_storefront() ) {
			return false;
		}

		return parent::is_available();
	}

	/**
	 * Payment fields on checkout.
	 *
	 * @return void
	 */
	public function payment_fields() {
		if ( $this->description ) {
			echo wp_kses_post( wpautop( wptexturize( $this->description ) ) );
		}

		echo '<div id="sbc-stripe-card-element" class="sbc-stripe-card-element"></div>';
		echo '<div id="sbc-stripe-card-errors" class="sbc-stripe-card-errors" role="alert"></div>';
		echo '<input type="hidden" name="sbc_stripe_payment_method_id" id="sbc-stripe-payment-method-id" value="" />';
		echo '<p class="sbc-stripe-test-note"><small>' . esc_html__( 'Test card: 4242 4242 4242 4242 · Any future expiry · Any CVC', 'sage-bloom-core' ) . '</small></p>';
	}

	/**
	 * Enqueue Stripe.js on checkout.
	 *
	 * @return void
	 */
	public function enqueue_checkout_scripts() {
		if ( ! is_checkout() || is_order_received_page() ) {
			return;
		}

		if ( ! $this->is_available() ) {
			return;
		}

		wp_enqueue_script( 'stripe-js', 'https://js.stripe.com/v3/', array(), '3', true );
		wp_enqueue_script(
			'sbc-stripe-checkout',
			SBC_PLUGIN_URL . 'assets/js/stripe-checkout.js',
			array( 'stripe-js', 'jquery' ),
			SBC_VERSION,
			true
		);

		wp_localize_script(
			'sbc-stripe-checkout',
			'sbcStripeCheckout',
			array(
				'publishableKey' => sbc_stripe_publishable_key(),
				'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
				'nonce'          => wp_create_nonce( 'sbc_stripe_checkout' ),
				'gatewayId'      => $this->id,
				'i18nProcessing' => __( 'Processing payment…', 'sage-bloom-core' ),
				'i18nError'      => __( 'Payment failed. Please check your card details.', 'sage-bloom-core' ),
			)
		);

		wp_enqueue_style(
			'sbc-stripe-checkout',
			SBC_PLUGIN_URL . 'assets/css/stripe-checkout.css',
			array(),
			SBC_VERSION
		);
	}

	/**
	 * Process payment.
	 *
	 * @param int $order_id Order ID.
	 * @return array<string, string>
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			wc_add_notice( __( 'Order not found.', 'sage-bloom-core' ), 'error' );
			return array( 'result' => 'fail' );
		}

		$payment_method_id = isset( $_POST['sbc_stripe_payment_method_id'] ) // phpcs:ignore WordPress.Security.NonceVerification.Missing
			? sanitize_text_field( wp_unslash( $_POST['sbc_stripe_payment_method_id'] ) ) // phpcs:ignore WordPress.Security.NonceVerification.Missing
			: '';

		$charge = self::charge_stripe_order( $order, $payment_method_id, $this->get_return_url( $order ) );

		if ( is_wp_error( $charge ) ) {
			wc_add_notice( $charge->get_error_message(), 'error' );
			return array( 'result' => 'fail' );
		}

		WC()->cart->empty_cart();

		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order ),
		);
	}

	/**
	 * Process Stripe payment for WooCommerce Blocks checkout.
	 *
	 * @param \Automattic\WooCommerce\StoreApi\Payments\PaymentContext $context Payment context.
	 * @param \Automattic\WooCommerce\StoreApi\Payments\PaymentResult  $result  Payment result.
	 * @return void
	 */
	public static function process_blocks_payment( $context, &$result ) {
		if ( 'sbc_stripe_test' !== $context->payment_method || ! empty( $result->status ) ) {
			return;
		}

		$order             = $context->order;
		$payment_method_id = isset( $context->payment_data['sbc_stripe_payment_method_id'] )
			? sanitize_text_field( $context->payment_data['sbc_stripe_payment_method_id'] )
			: '';

		$gateways = WC()->payment_gateways()->payment_gateways();
		$gateway  = isset( $gateways['sbc_stripe_test'] ) ? $gateways['sbc_stripe_test'] : null;
		$return   = $gateway ? $gateway->get_return_url( $order ) : $order->get_checkout_order_received_url();

		$charge = self::charge_stripe_order( $order, $payment_method_id, $return );

		if ( is_wp_error( $charge ) ) {
			if ( class_exists( '\Automattic\WooCommerce\StoreApi\Exceptions\RouteException' ) ) {
				throw new \Automattic\WooCommerce\StoreApi\Exceptions\RouteException(
					'sbc_stripe_payment_failed',
					wp_strip_all_tags( $charge->get_error_message() ),
					400
				);
			}

			wc_add_notice( $charge->get_error_message(), 'error' );
			$result->set_status( 'failure' );
			return;
		}

		$result->set_status( 'success' );
		$result->set_redirect_url( $return );
	}

	/**
	 * Charge an order via Stripe Payment Intent.
	 *
	 * @param \WC_Order $order             Order object.
	 * @param string    $payment_method_id Stripe payment method ID.
	 * @param string    $return_url        Return URL for redirect-based auth.
	 * @return true|\WP_Error
	 */
	public static function charge_stripe_order( $order, $payment_method_id, $return_url ) {
		if ( ! $payment_method_id ) {
			return new WP_Error( 'missing_payment_method', __( 'Please enter your card details.', 'sage-bloom-core' ) );
		}

		$amount   = (int) round( (float) $order->get_total() * 100 );
		$currency = strtolower( $order->get_currency() );

		if ( $amount <= 0 ) {
			return new WP_Error( 'invalid_amount', __( 'Invalid order total for payment.', 'sage-bloom-core' ) );
		}

		$intent = SBC_Stripe_Client::create_payment_intent(
			array(
				'amount'         => $amount,
				'currency'       => $currency,
				'payment_method' => $payment_method_id,
				'confirm'        => true,
				'return_url'     => $return_url,
				'description'    => sprintf( 'Order #%s — %s', $order->get_order_number(), get_bloginfo( 'name' ) ),
				'metadata'       => array(
					'order_id' => (string) $order->get_id(),
					'site'     => home_url(),
				),
			)
		);

		if ( is_wp_error( $intent ) ) {
			return $intent;
		}

		if ( ! empty( $intent['status'] ) && 'succeeded' === $intent['status'] ) {
			$order->payment_complete( $intent['id'] );
			$order->add_order_note( sprintf( __( 'Stripe test payment completed (%s).', 'sage-bloom-core' ), $intent['id'] ) );
			SBC_Activity_Log::log( 'order', $order->get_id(), 'payment_completed', array( 'intent_id' => $intent['id'] ) );
			return true;
		}

		if ( ! empty( $intent['status'] ) && 'requires_action' === $intent['status'] ) {
			return new WP_Error(
				'requires_action',
				__( 'Additional authentication is required. Use test card 4000 0025 0000 3155 for 3D Secure demos.', 'sage-bloom-core' )
			);
		}

		$message = ! empty( $intent['status'] )
			? sprintf(
				/* translators: %s: Stripe payment status */
				__( 'Payment could not be completed (status: %s).', 'sage-bloom-core' ),
				$intent['status']
			)
			: __( 'Payment could not be completed.', 'sage-bloom-core' );

		return new WP_Error( 'payment_failed', $message );
	}
}
