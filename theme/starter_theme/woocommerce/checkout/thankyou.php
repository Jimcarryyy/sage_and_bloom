<?php
/**
 * Thank you page.
 *
 * @package starter_theme
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order order-confirmation">

	<?php if ( $order ) : ?>

		<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<div class="order-confirmation__panel order-confirmation__panel--error">
				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
					<?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?>
				</p>

				<div class="order-confirmation__actions">
					<a class="btn btn--primary" href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>">
						<?php esc_html_e( 'Try payment again', 'starter_theme' ); ?>
					</a>
					<?php if ( is_user_logged_in() ) : ?>
						<a class="btn btn--outline" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
							<?php esc_html_e( 'My account', 'woocommerce' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

		<?php else : ?>

			<?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>

			<div class="order-confirmation__overview" aria-label="<?php esc_attr_e( 'Order summary', 'starter_theme' ); ?>">
				<div class="order-meta-cards">
					<div class="order-meta-card">
						<span class="order-meta-card__label"><?php esc_html_e( 'Order number', 'starter_theme' ); ?></span>
						<strong class="order-meta-card__value">#<?php echo esc_html( $order->get_order_number() ); ?></strong>
					</div>

					<div class="order-meta-card">
						<span class="order-meta-card__label"><?php esc_html_e( 'Date', 'starter_theme' ); ?></span>
						<strong class="order-meta-card__value"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
					</div>

					<?php if ( $order->get_billing_email() ) : ?>
						<div class="order-meta-card">
							<span class="order-meta-card__label"><?php esc_html_e( 'Email', 'starter_theme' ); ?></span>
							<strong class="order-meta-card__value"><?php echo esc_html( $order->get_billing_email() ); ?></strong>
						</div>
					<?php endif; ?>

					<div class="order-meta-card order-meta-card--accent">
						<span class="order-meta-card__label"><?php esc_html_e( 'Total', 'starter_theme' ); ?></span>
						<strong class="order-meta-card__value"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
					</div>

					<?php if ( $order->get_payment_method_title() ) : ?>
						<div class="order-meta-card">
							<span class="order-meta-card__label"><?php esc_html_e( 'Payment method', 'starter_theme' ); ?></span>
							<strong class="order-meta-card__value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="order-confirmation__content">
				<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
				<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
			</div>

			<?php get_template_part( 'template-parts/woocommerce/order-confirmation-actions' ); ?>

		<?php endif; ?>

	<?php else : ?>

		<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>
		<?php get_template_part( 'template-parts/woocommerce/order-confirmation-actions' ); ?>

	<?php endif; ?>

</div>
