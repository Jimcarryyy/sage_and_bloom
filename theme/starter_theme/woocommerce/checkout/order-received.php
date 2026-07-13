<?php
/**
 * Order received message.
 *
 * @package starter_theme
 * @version 8.8.0
 *
 * @var WC_Order|false $order
 */

defined( 'ABSPATH' ) || exit;

$message = apply_filters(
	'woocommerce_thankyou_order_received_text',
	esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ),
	$order
);
?>

<div class="order-confirmation__notice">
	<p class="order-confirmation__notice-text woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
		<?php echo wp_kses_post( $message ); ?>
	</p>
</div>
