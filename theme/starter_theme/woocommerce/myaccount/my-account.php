<?php
/**
 * My Account page
 *
 * @package starter_theme
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_account_navigation' );
?>

<div class="woocommerce-MyAccount-content ajax-loading-surface" data-my-account-content data-ajax-loading-surface>
	<?php do_action( 'woocommerce_account_content' ); ?>
</div>
