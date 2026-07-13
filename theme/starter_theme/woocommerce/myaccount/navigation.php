<?php
/**
 * My Account navigation with icons.
 *
 * @package starter_theme
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_navigation' );

$user       = wp_get_current_user();
$first_name = $user->first_name ? $user->first_name : $user->display_name;
?>
<nav class="woocommerce-MyAccount-navigation account-nav" aria-label="<?php esc_attr_e( 'Account pages', 'starter_theme' ); ?>">
	<div class="account-nav__profile">
		<div class="account-nav__avatar" aria-hidden="true">
			<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
		</div>
		<div class="account-nav__meta">
			<p class="account-nav__label"><?php esc_html_e( 'Signed in as', 'starter_theme' ); ?></p>
			<p class="account-nav__name"><?php echo esc_html( $first_name ); ?></p>
		</div>
	</div>
	<ul>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<?php $is_logout = 'customer-logout' === $endpoint; ?>
			<li class="<?php echo esc_attr( wc_get_account_menu_item_classes( $endpoint ) ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'aria-current="page"' : ''; ?><?php echo $is_logout ? '' : ' data-my-account-nav-link data-no-loader'; ?>>
					<span class="account-nav__icon"><?php echo starter_theme_my_account_nav_icon( $endpoint ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="account-nav__text"><?php echo esc_html( $label ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<div class="account-nav__help">
		<p class="account-nav__help-title"><?php esc_html_e( 'Need help?', 'starter_theme' ); ?></p>
		<p class="account-nav__help-text"><?php esc_html_e( 'Call our front desk or book your next visit online.', 'starter_theme' ); ?></p>
		<?php if ( function_exists( 'starter_theme_booking_url' ) ) : ?>
			<a class="btn btn--outline btn--sm account-nav__help-btn" href="<?php echo esc_url( starter_theme_booking_url() ); ?>">
				<?php esc_html_e( 'Book appointment', 'starter_theme' ); ?>
			</a>
		<?php endif; ?>
	</div>
</nav>
<?php
do_action( 'woocommerce_after_account_navigation' );
