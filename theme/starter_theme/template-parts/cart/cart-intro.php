<?php
/**
 * Cart page context bar — item count and continue shopping.
 *
 * @package starter_theme
 */

if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
	return;
}

$count    = WC()->cart->get_cart_contents_count();
$is_empty = 0 === $count;
$shop_id  = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : 0;
$shop_url = $shop_id > 0 ? get_permalink( $shop_id ) : home_url( '/shop/' );
?>
<div class="cart-intro<?php echo $is_empty ? ' cart-intro--empty' : ''; ?>">
	<div class="cart-intro__copy">
		<p class="cart-intro__eyebrow"><?php esc_html_e( 'Your bag', 'starter_theme' ); ?></p>
		<?php if ( $is_empty ) : ?>
			<p class="cart-intro__meta"><?php esc_html_e( 'Your bag is empty', 'starter_theme' ); ?></p>
			<p class="cart-intro__hint"><?php esc_html_e( 'Browse our boutique picks below or head back to the shop.', 'starter_theme' ); ?></p>
		<?php else : ?>
			<p class="cart-intro__meta">
				<?php
				printf(
					/* translators: %d: number of items in cart */
					esc_html( _n( '%d item ready for checkout', '%d items ready for checkout', $count, 'starter_theme' ) ),
					(int) $count
				);
				?>
			</p>
		<?php endif; ?>
	</div>
	<a class="btn btn--outline btn--sm cart-intro__back" href="<?php echo esc_url( $shop_url ); ?>">
		<?php esc_html_e( 'Continue shopping', 'starter_theme' ); ?>
	</a>
</div>
