<?php
/**
 * The template for displaying all pages
 *
 * @package starter_theme
 */

get_header();

while ( have_posts() ) :
	the_post();

	$is_cart           = function_exists( 'is_cart' ) && is_cart();
	$is_order_received = function_exists( 'starter_theme_is_order_received_page' ) && starter_theme_is_order_received_page();
	$is_checkout       = function_exists( 'is_checkout' ) && is_checkout() && ! $is_order_received;
	$is_account        = function_exists( 'is_account_page' ) && is_account_page();
	$is_wc_flow        = $is_cart || $is_checkout || $is_order_received;

	if ( $is_account ) {
		if ( is_user_logged_in() ) {
			$account_user = wp_get_current_user();
			$account_name = $account_user->first_name ? $account_user->first_name : $account_user->display_name;
			starter_theme_render_page_hero(
				array(
					'eyebrow'  => __( 'Your Account', 'starter_theme' ),
					'title'    => sprintf(
						/* translators: %s: customer first name */
						__( 'Welcome back, %s', 'starter_theme' ),
						$account_name
					),
					'desc'     => __( 'Manage appointments, boutique orders, and profile details in one place.', 'starter_theme' ),
					'image'    => starter_theme_page_hero_image_url( get_the_ID() ),
					'benefits' => array(
						__( 'Track orders & appointments', 'starter_theme' ),
						__( 'Saved addresses for checkout', 'starter_theme' ),
						__( 'Member offers & promo codes', 'starter_theme' ),
					),
				)
			);
		} else {
			starter_theme_render_page_hero(
				array(
					'eyebrow'  => __( 'Member Access', 'starter_theme' ),
					'title'    => __( 'Sign in or create an account', 'starter_theme' ),
					'desc'     => __( 'Access your orders, appointments, and saved details — all in one warm, welcoming place.', 'starter_theme' ),
					'image'    => starter_theme_page_hero_image_url( get_the_ID() ),
					'benefits' => array(
						__( 'Order history & receipts', 'starter_theme' ),
						__( 'Appointment tracking', 'starter_theme' ),
						__( 'Faster checkout', 'starter_theme' ),
					),
				)
			);
		}
	} elseif ( $is_cart ) {
		starter_theme_render_page_hero(
			array(
				'eyebrow'  => __( 'Your Bag', 'starter_theme' ),
				'title'    => __( 'Shopping Cart', 'starter_theme' ),
				'desc'     => __( 'Review your picks, adjust quantities, and checkout when you are ready.', 'starter_theme' ),
				'image'    => starter_theme_page_hero_image_url( get_the_ID() ),
				'benefits' => array(
					__( 'Secure checkout', 'starter_theme' ),
					__( 'Studio pickup available', 'starter_theme' ),
					__( 'Gift-ready packaging', 'starter_theme' ),
					__( 'Easy quantity updates', 'starter_theme' ),
				),
			)
		);
	} elseif ( $is_order_received ) {
		starter_theme_render_page_hero(
			array(
				'eyebrow'  => __( 'Thank You', 'starter_theme' ),
				'title'    => __( 'Order Confirmed', 'starter_theme' ),
				'desc'     => __( 'Your order is in. We will send a confirmation email with the details shortly.', 'starter_theme' ),
				'image'    => starter_theme_page_hero_image_url( get_the_ID() ),
				'benefits' => array(
					__( 'Order confirmation by email', 'starter_theme' ),
					__( 'Studio pickup available', 'starter_theme' ),
					__( 'Questions? Call our front desk', 'starter_theme' ),
				),
			)
		);
	} elseif ( $is_checkout ) {
		starter_theme_render_page_hero(
			array(
				'eyebrow'  => __( 'Almost There', 'starter_theme' ),
				'title'    => __( 'Checkout', 'starter_theme' ),
				'desc'     => __( 'Enter your details to complete your order.', 'starter_theme' ),
				'image'    => starter_theme_page_hero_image_url( get_the_ID() ),
				'benefits' => array(
					__( 'Encrypted payment', 'starter_theme' ),
					__( 'Order confirmation by email', 'starter_theme' ),
					__( 'Questions? Call our front desk', 'starter_theme' ),
				),
			)
		);
	} else {
		starter_theme_render_page_hero(
			array(
				'eyebrow' => get_bloginfo( 'name' ),
				'title'   => get_the_title(),
				'desc'    => has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : '',
				'image'   => starter_theme_page_hero_image_url( get_the_ID() ),
			)
		);
	}

	$main_class      = 'site-main';
	$section_class   = 'page-section reveal';
	$container_class = 'site-container';
	$body_class      = 'page-body entry-content';

	if ( $is_wc_flow || $is_account ) {
		if ( $is_cart ) {
			$main_class .= ' woocommerce-main woocommerce-cart-page';
		} elseif ( $is_order_received ) {
			$main_class .= ' woocommerce-main woocommerce-order-received-page';
		} elseif ( $is_account ) {
			$main_class .= ' woocommerce-main woocommerce-account-page';
		} else {
			$main_class .= ' woocommerce-main woocommerce-checkout-page';
		}

		$section_class   .= ' page-section--cream woocommerce-page-section';
		$container_class .= ' site-container--shop';
		$body_class      .= ' page-body--wide woocommerce-page-content';
	}
	?>

	<main id="primary" class="<?php echo esc_attr( $main_class ); ?>">

		<section class="<?php echo esc_attr( $section_class ); ?>">
			<div class="<?php echo esc_attr( $container_class ); ?><?php echo $is_cart ? ' ajax-loading-surface' : ''; ?>"<?php echo $is_cart ? ' data-ajax-loading-surface' : ''; ?>>
				<?php if ( $is_cart ) : ?>
					<?php get_template_part( 'template-parts/cart/cart-intro' ); ?>
				<?php endif; ?>

				<div class="<?php echo esc_attr( $body_class ); ?>">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'starter_theme' ),
							'after'  => '</div>',
						)
					);
					?>
				</div>
			</div>
		</section>

		<?php
		if ( comments_open() || get_comments_number() ) {
			echo '<div class="site-container">';
			comments_template();
			echo '</div>';
		}

		if ( ! $is_wc_flow || $is_order_received ) {
			starter_theme_render_page_cta(
				array(
					'eyebrow'     => $is_order_received ? __( 'While You Wait', 'starter_theme' ) : __( 'Visit Us', 'starter_theme' ),
					'title'       => $is_order_received ? __( 'Book your next visit', 'starter_theme' ) : __( 'Ready for your appointment?', 'starter_theme' ),
					'desc'        => $is_order_received
						? __( 'Treat yourself to salon services or browse more boutique picks before your pickup.', 'starter_theme' )
						: __( 'Book online or call us today. Walk-ins welcome when available.', 'starter_theme' ),
					'button_text' => __( 'Book Now', 'starter_theme' ),
				)
			);
		}
		?>

	</main>

	<?php
endwhile;

get_footer();
