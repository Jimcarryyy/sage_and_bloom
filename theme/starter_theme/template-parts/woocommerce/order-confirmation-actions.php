<?php
/**
 * Action links on the order confirmation page.
 *
 * @package starter_theme
 */

$shop_id  = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : 0;
$shop_url = $shop_id > 0 ? get_permalink( $shop_id ) : home_url( '/shop/' );
?>

<div class="order-confirmation__actions">
	<a class="btn btn--primary btn--lg" href="<?php echo esc_url( $shop_url ); ?>">
		<?php esc_html_e( 'Continue shopping', 'starter_theme' ); ?>
	</a>
	<a class="btn btn--outline btn--lg" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
		<?php esc_html_e( 'Contact us', 'starter_theme' ); ?>
	</a>
</div>
