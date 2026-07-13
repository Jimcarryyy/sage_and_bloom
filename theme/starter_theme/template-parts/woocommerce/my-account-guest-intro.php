<?php
/**
 * Guest login / register intro.
 *
 * @package starter_theme
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="account-guest-intro">
	<div class="account-guest-intro__layout">
		<div class="account-guest-intro__copy">
			<p class="account-guest-intro__eyebrow"><?php esc_html_e( 'Sage & Bloom members', 'starter_theme' ); ?></p>
			<h2 class="account-guest-intro__title"><?php esc_html_e( 'Your salon & boutique account', 'starter_theme' ); ?></h2>
			<p class="account-guest-intro__lead">
				<?php esc_html_e( 'Sign in to view orders, track appointments, save addresses, and access member offers.', 'starter_theme' ); ?>
			</p>
		</div>

		<aside class="account-guest-intro__aside" aria-label="<?php esc_attr_e( 'Member benefits', 'starter_theme' ); ?>">
			<p class="account-guest-intro__aside-label"><?php esc_html_e( 'Member benefits', 'starter_theme' ); ?></p>
			<ul class="account-guest-intro__benefits">
				<li><?php esc_html_e( 'Order history & receipts', 'starter_theme' ); ?></li>
				<li><?php esc_html_e( 'Appointment booking history', 'starter_theme' ); ?></li>
				<li><?php esc_html_e( 'Faster checkout with saved details', 'starter_theme' ); ?></li>
				<li><?php esc_html_e( 'Exclusive promo codes', 'starter_theme' ); ?></li>
			</ul>
		</aside>
	</div>
</div>
