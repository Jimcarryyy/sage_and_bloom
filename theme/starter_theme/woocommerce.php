<?php
/**
 * WooCommerce fallback template.
 *
 * @package starter_theme
 */

get_header();

$is_catalog = is_shop() || is_product_taxonomy();
$hero_args  = null;

if ( $is_catalog ) {
	$hero_title = __( 'Shop Our Boutique', 'starter_theme' );
	$hero_desc  = __( 'Curated self-care products, gift sets, and salon favorites — shipped or ready for pickup.', 'starter_theme' );

	if ( is_product_taxonomy() ) {
		$term = get_queried_object();
		if ( $term ) {
			$hero_title = $term->name;
			if ( ! empty( $term->description ) ) {
				$hero_desc = wp_strip_all_tags( $term->description );
			}
		}
	}

	$hero_args = array(
		'eyebrow'  => __( 'Boutique', 'starter_theme' ),
		'title'    => $hero_title,
		'desc'     => $hero_desc,
		'image'    => starter_theme_page_hero_image_url(),
		'benefits' => array(
			__( 'Curated salon-quality products', 'starter_theme' ),
			__( 'Gift sets & cards available', 'starter_theme' ),
			__( 'Pickup in studio', 'starter_theme' ),
			__( 'New arrivals each season', 'starter_theme' ),
		),
	);
} elseif ( is_cart() ) {
	$hero_args = array(
		'eyebrow'  => __( 'Your Bag', 'starter_theme' ),
		'title'    => __( 'Cart', 'starter_theme' ),
		'desc'     => __( 'Review your picks, adjust quantities, and checkout when you are ready.', 'starter_theme' ),
		'image'    => starter_theme_page_hero_image_url(),
		'benefits' => array(
			__( 'Secure checkout', 'starter_theme' ),
			__( 'Studio pickup available', 'starter_theme' ),
			__( 'Gift-ready packaging', 'starter_theme' ),
			__( 'Easy quantity updates', 'starter_theme' ),
		),
	);
} elseif ( is_checkout() ) {
	if ( function_exists( 'starter_theme_is_order_received_page' ) && starter_theme_is_order_received_page() ) {
		$hero_args = array(
			'eyebrow'  => __( 'Thank You', 'starter_theme' ),
			'title'    => __( 'Order Confirmed', 'starter_theme' ),
			'desc'     => __( 'Your order is in. We will send a confirmation email with the details shortly.', 'starter_theme' ),
			'image'    => starter_theme_page_hero_image_url(),
			'benefits' => array(
				__( 'Order confirmation by email', 'starter_theme' ),
				__( 'Studio pickup available', 'starter_theme' ),
				__( 'Questions? Call our front desk', 'starter_theme' ),
			),
		);
	} else {
		$hero_args = array(
			'eyebrow'  => __( 'Almost There', 'starter_theme' ),
			'title'    => __( 'Checkout', 'starter_theme' ),
			'desc'     => __( 'Enter your details to complete your order.', 'starter_theme' ),
			'image'    => starter_theme_page_hero_image_url(),
			'benefits' => array(
				__( 'Encrypted payment', 'starter_theme' ),
				__( 'Order confirmation by email', 'starter_theme' ),
				__( 'Questions? Call our front desk', 'starter_theme' ),
			),
		);
	}
} elseif ( is_account_page() ) {
	if ( is_user_logged_in() ) {
		$user         = wp_get_current_user();
		$display_name = $user->first_name ? $user->first_name : $user->display_name;
		$hero_args    = array(
			'eyebrow'  => __( 'Your Account', 'starter_theme' ),
			'title'    => sprintf(
				/* translators: %s: customer first name */
				__( 'Welcome back, %s', 'starter_theme' ),
				$display_name
			),
			'desc'     => __( 'Manage appointments, boutique orders, and profile details in one place.', 'starter_theme' ),
			'image'    => starter_theme_page_hero_image_url(),
			'benefits' => array(
				__( 'Track orders & appointments', 'starter_theme' ),
				__( 'Saved addresses for checkout', 'starter_theme' ),
				__( 'Member offers & promo codes', 'starter_theme' ),
			),
		);
	} else {
		$hero_args = array(
			'eyebrow'  => __( 'Member Access', 'starter_theme' ),
			'title'    => __( 'Sign in or create an account', 'starter_theme' ),
			'desc'     => __( 'Access your orders, appointments, and saved details.', 'starter_theme' ),
			'image'    => starter_theme_page_hero_image_url(),
			'benefits' => array(
				__( 'Order history & receipts', 'starter_theme' ),
				__( 'Appointment tracking', 'starter_theme' ),
				__( 'Faster checkout', 'starter_theme' ),
			),
		);
	}
} elseif ( is_product() ) {
	$product_id = get_queried_object_id();
	$product    = $product_id ? wc_get_product( $product_id ) : null;
	$short_desc = $product ? $product->get_short_description() : '';
	$hero_image = '';

	if ( $product && function_exists( 'starter_theme_product_demo_image_url' ) ) {
		$hero_image = starter_theme_product_demo_image_url( $product->get_name() );
	}

	if ( ! $hero_image ) {
		$hero_image = starter_theme_page_hero_image_url( $product_id );
	}

	$hero_args = array(
		'eyebrow'  => __( 'Boutique', 'starter_theme' ),
		'title'    => $product ? $product->get_name() : get_the_title(),
		'desc'     => $short_desc ? wp_strip_all_tags( $short_desc ) : __( 'Salon-quality care for home — curated by our stylists.', 'starter_theme' ),
		'image'    => $hero_image,
		'benefits' => array(
			__( 'Salon-quality formulas', 'starter_theme' ),
			__( 'Studio pickup available', 'starter_theme' ),
			__( 'Gift-ready packaging', 'starter_theme' ),
			__( 'Curated by our team', 'starter_theme' ),
		),
	);
}

if ( $hero_args ) {
	starter_theme_render_page_hero( $hero_args );
}

$main_class = 'site-main woocommerce-main';
if ( $is_catalog ) {
	$main_class .= ' shop-archive';
} elseif ( is_cart() ) {
	$main_class .= ' woocommerce-cart-page';
} elseif ( is_checkout() ) {
	$main_class .= function_exists( 'starter_theme_is_order_received_page' ) && starter_theme_is_order_received_page()
		? ' woocommerce-order-received-page'
		: ' woocommerce-checkout-page';
} elseif ( is_product() ) {
	$main_class .= ' single-product-page';
} elseif ( is_account_page() ) {
	$main_class .= ' woocommerce-account-page';
}
?>

<main id="primary" class="<?php echo esc_attr( $main_class ); ?>">

	<?php if ( $is_catalog ) : ?>
		<section class="shop-catalog page-section page-section--cream">
			<div class="site-container site-container--shop">
	<?php else : ?>
		<section class="page-section page-section--cream woocommerce-page-section">
			<div class="site-container site-container--shop">
	<?php endif; ?>

	<?php woocommerce_content(); ?>

			</div>
		</section>

</main>

<?php
if ( $is_catalog ) {
	starter_theme_render_page_cta(
		array(
			'eyebrow'     => __( 'Need a recommendation?', 'starter_theme' ),
			'title'       => __( 'Ask our stylists in studio', 'starter_theme' ),
			'desc'        => __( 'Book a service and browse the boutique before or after your appointment.', 'starter_theme' ),
			'button_text' => __( 'Book Appointment', 'starter_theme' ),
		)
	);
}

get_footer();
