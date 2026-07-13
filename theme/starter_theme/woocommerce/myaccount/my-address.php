<?php
/**
 * My Addresses
 *
 * @package starter_theme
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'Billing address', 'woocommerce' ),
			'shipping' => __( 'Shipping address', 'woocommerce' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Billing address', 'woocommerce' ),
		),
		$customer_id
	);
}
?>

<div class="account-addresses">
	<p class="account-addresses__lead">
		<?php echo apply_filters( 'woocommerce_my_account_my_address_description', esc_html__( 'The following addresses will be used on the checkout page by default.', 'woocommerce' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</p>

	<div class="account-addresses__grid">
		<?php foreach ( $get_addresses as $name => $address_title ) : ?>
			<?php $address = wc_get_account_formatted_address( $name ); ?>
			<article class="account-address-card woocommerce-Address">
				<header class="account-address-card__header woocommerce-Address-title title">
					<h3 class="account-address-card__title"><?php echo esc_html( $address_title ); ?></h3>
					<a class="account-address-card__edit" href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>">
						<?php
						printf(
							/* translators: %s: Address title */
							$address ? esc_html__( 'Edit %s', 'woocommerce' ) : esc_html__( 'Add %s', 'woocommerce' ),
							esc_html( $address_title )
						);
						?>
					</a>
				</header>
				<div class="account-address-card__body">
					<address class="account-address-card__address">
						<?php
						echo $address ? wp_kses_post( $address ) : esc_html__( 'You have not set up this type of address yet.', 'woocommerce' );
						do_action( 'woocommerce_my_account_after_my_address', $name );
						?>
					</address>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</div>
