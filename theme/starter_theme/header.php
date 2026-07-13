<?php
/**
 * The header for our theme
 *
 * @package starter_theme
 */

$business_phone = starter_theme_business_option( 'business_phone', '' );
$booking_url    = starter_theme_booking_url();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page-loader" class="page-loader" aria-hidden="true">
	<div class="page-loader__panel">
		<div class="page-loader__mark" aria-hidden="true">
			<img src="<?php echo esc_url( starter_theme_logo_url( 'mark' ) ); ?>" alt="" class="page-loader__icon" width="64" height="64" decoding="async" />
			<span class="page-loader__ring"></span>
		</div>
		<div class="page-loader__track" aria-hidden="true">
			<span class="page-loader__bar"></span>
		</div>
	</div>
</div>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'starter_theme' ); ?></a>

	<?php if ( $business_phone ) : ?>
	<div class="site-header-group" id="site-header-group">
	<div class="header-topbar">
		<div class="site-container">
			<span class="header-topbar__status">
				<?php echo esc_html( starter_theme_business_option( 'business_status', __( 'Open today — Walk-ins welcome', 'starter_theme' ) ) ); ?>
			</span>
			<a class="header-topbar__phone" href="tel:<?php echo esc_attr( starter_theme_business_phone_link() ); ?>">
				<?php echo esc_html( $business_phone ); ?>
			</a>
		</div>
	</div>
	<?php else : ?>
	<div class="site-header-group" id="site-header-group">
	<?php endif; ?>

	<header id="masthead" class="site-header">
		<div class="site-container">
			<div class="site-branding">
				<?php starter_theme_render_logo(); ?>
				<?php if ( is_front_page() && ! is_paged() ) : ?>
					<h1 class="screen-reader-text"><?php echo esc_html( starter_theme_brand_name() ); ?></h1>
				<?php endif; ?>
			</div>

			<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary', 'starter_theme' ); ?>">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'starter_theme' ); ?></button>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'fallback_cb'    => 'starter_theme_fallback_menu',
					)
				);
				?>
			</nav>

			<div class="site-header__actions">
				<?php get_template_part( 'template-parts/header/account-link' ); ?>
				<?php get_template_part( 'template-parts/header/cart-link' ); ?>
				<a class="btn btn--primary btn--header" href="<?php echo esc_url( $booking_url ); ?>">
					<?php esc_html_e( 'Book Now', 'starter_theme' ); ?>
				</a>
			</div>
		</div>
	</header>
	</div><!-- .site-header-group -->
