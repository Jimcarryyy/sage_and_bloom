<?php
/**
 * Header cart icon + item count.
 *
 * @package starter_theme
 */

if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
	return;
}

$count = WC()->cart->get_cart_contents_count();
?>
<a class="header-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'View cart', 'starter_theme' ); ?>">
	<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
	<?php if ( $count > 0 ) : ?>
		<span class="header-cart__count"><?php echo esc_html( $count ); ?></span>
	<?php endif; ?>
</a>
