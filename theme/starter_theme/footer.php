<?php
/**
 * The template for displaying the footer
 *
 * @package starter_theme
 */

$footer_tagline = starter_theme_business_option( 'footer_tagline', get_bloginfo( 'description' ) );
$business_email = starter_theme_business_option( 'business_email', '' );
$business_phone = starter_theme_business_option( 'business_phone', '' );
$business_addr  = starter_theme_business_option( 'business_address', '' );
?>

	<footer id="colophon" class="site-footer">
		<div class="site-container">
			<div class="footer-grid">
				<div class="footer-brand">
					<?php starter_theme_render_logo( 'footer' ); ?>
					<?php if ( $footer_tagline ) : ?>
						<p class="footer-brand__tagline"><?php echo esc_html( $footer_tagline ); ?></p>
					<?php endif; ?>
					<?php starter_theme_render_social_links(); ?>
				</div>

				<div class="footer-col">
					<p class="footer-col__title"><?php esc_html_e( 'Hours', 'starter_theme' ); ?></p>
					<?php starter_theme_render_hours(); ?>
				</div>

				<div class="footer-col">
					<p class="footer-col__title"><?php esc_html_e( 'Contact', 'starter_theme' ); ?></p>
					<div class="footer-col__content">
						<?php if ( $business_addr ) : ?>
							<p><?php echo nl2br( esc_html( $business_addr ) ); ?></p>
						<?php endif; ?>
						<?php if ( $business_phone ) : ?>
							<p><a href="tel:<?php echo esc_attr( starter_theme_business_phone_link() ); ?>"><?php echo esc_html( $business_phone ); ?></a></p>
						<?php endif; ?>
						<?php if ( $business_email ) : ?>
							<p><a href="mailto:<?php echo esc_attr( $business_email ); ?>"><?php echo esc_html( $business_email ); ?></a></p>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<div class="footer-col footer-col--nav">
						<p class="footer-col__title"><?php esc_html_e( 'Quick Links', 'starter_theme' ); ?></p>
						<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer', 'starter_theme' ); ?>">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footer',
									'menu_class'     => 'footer-menu',
									'depth'          => 1,
									'fallback_cb'    => false,
								)
							);
							?>
						</nav>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="site-footer__bottom">
			<div class="site-container">
				<p class="site-info">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( starter_theme_brand_name() ); ?></a>.
					<?php esc_html_e( 'All rights reserved.', 'starter_theme' ); ?>
				</p>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
