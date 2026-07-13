<?php
/**
 * Header account link — Log in / Sign up for guests, My Account when logged in.
 *
 * @package starter_theme
 */

if ( ! function_exists( 'wc_get_page_permalink' ) ) {
	return;
}

$account_url = wc_get_page_permalink( 'myaccount' );
if ( ! $account_url ) {
	return;
}

$registration_enabled = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
$is_logged_in         = is_user_logged_in();
$user                 = $is_logged_in ? wp_get_current_user() : null;
$register_url         = $registration_enabled
	? add_query_arg( 'action', 'register', $account_url )
	: $account_url;

if ( $is_logged_in && $user instanceof WP_User ) {
	$display_name = $user->first_name ? $user->first_name : $user->display_name;
	$label        = $display_name ? $display_name : __( 'My Account', 'starter_theme' );
	$href         = $account_url;
	$aria         = sprintf(
		/* translators: %s: customer display name */
		__( 'My account for %s', 'starter_theme' ),
		$display_name ? $display_name : __( 'you', 'starter_theme' )
	);
} else {
	$label = __( 'Log in', 'starter_theme' );
	$href  = $account_url;
	$aria  = __( 'Log in to your account', 'starter_theme' );
}
?>
<div class="header-account<?php echo $is_logged_in ? ' header-account--logged-in' : ' header-account--guest'; ?>">
	<a class="header-account__link" href="<?php echo esc_url( $href ); ?>" aria-label="<?php echo esc_attr( $aria ); ?>">
		<svg class="header-account__icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
			<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
			<circle cx="12" cy="7" r="4"/>
		</svg>
		<span class="header-account__label"><?php echo esc_html( $label ); ?></span>
	</a>
	<?php if ( ! $is_logged_in && $registration_enabled ) : ?>
		<a class="header-account__signup" href="<?php echo esc_url( $register_url ); ?>">
			<?php esc_html_e( 'Sign up', 'starter_theme' ); ?>
		</a>
	<?php endif; ?>
</div>
