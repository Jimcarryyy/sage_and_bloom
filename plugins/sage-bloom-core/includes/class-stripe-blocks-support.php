<?php
/**
 * WooCommerce Blocks checkout integration for Stripe test gateway.
 *
 * @package Sage_Bloom_Core
 */

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

defined( 'ABSPATH' ) || exit;

/**
 * Blocks payment method type.
 */
final class SBC_Stripe_Blocks_Support extends AbstractPaymentMethodType {

	/**
	 * Gateway ID.
	 *
	 * @var string
	 */
	protected $name = 'sbc_stripe_test';

	/**
	 * Initialize settings.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->settings = get_option( 'woocommerce_sbc_stripe_test_settings', array() );
	}

	/**
	 * Whether method is active on block checkout.
	 *
	 * @return bool
	 */
	public function is_active() {
		if ( ! filter_var( $this->get_setting( 'enabled', 'yes' ), FILTER_VALIDATE_BOOLEAN ) ) {
			return false;
		}

		if ( ! sbc_stripe_publishable_key() || ! sbc_stripe_secret_key() ) {
			return false;
		}

		return sbc_is_local_storefront();
	}

	/**
	 * Script handles for block checkout.
	 *
	 * @return array<int, string>
	 */
	public function get_payment_method_script_handles() {
		wp_register_script(
			'stripe-js',
			'https://js.stripe.com/v3/',
			array(),
			'3',
			true
		);

		wp_register_script(
			'sbc-stripe-blocks',
			SBC_PLUGIN_URL . 'assets/js/stripe-blocks.js',
			array(
				'stripe-js',
				'wc-blocks-registry',
				'wc-settings',
				'wp-element',
				'wp-html-entities',
				'wc-sanitize',
			),
			SBC_VERSION,
			true
		);

		wp_register_style(
			'sbc-stripe-blocks',
			SBC_PLUGIN_URL . 'assets/css/stripe-checkout.css',
			array(),
			SBC_VERSION
		);

		wp_enqueue_style( 'sbc-stripe-blocks' );

		return array( 'sbc-stripe-blocks' );
	}

	/**
	 * Data passed to the blocks payment script.
	 *
	 * @return array<string, mixed>
	 */
	public function get_payment_method_data() {
		return array(
			'title'          => $this->get_setting( 'title', __( 'Credit / Debit Card (Test)', 'sage-bloom-core' ) ),
			'description'    => $this->get_setting( 'description', __( 'Pay securely with Stripe test cards. No real charges are made.', 'sage-bloom-core' ) ),
			'supports'       => $this->get_supported_features(),
			'publishableKey' => sbc_stripe_publishable_key(),
			'testNote'       => __( 'Test card: 4242 4242 4242 4242 · Any future expiry · Any CVC', 'sage-bloom-core' ),
		);
	}
}
