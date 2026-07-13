<?php
/**
 * Product demo content and helpers.
 *
 * @package starter_theme
 */

/**
 * Demo catalog keyed by product title.
 *
 * @return array<string, array<string, mixed>>
 */
function starter_theme_product_demo_catalog() {
	return array(
		'Luxury Gift Set'         => array(
			'short'       => 'A curated trio of salon favorites — shampoo, mask, and hand cream — presented in gift-ready packaging.',
			'description' => '<p>Our most-loved formulas in one elegant set, perfect for treating yourself or someone special. Each product is chosen by our stylists for results you can feel after the first use.</p><h3>What\'s inside</h3><ul><li>Shine Restoring Shampoo (8 oz)</li><li>Hydrating Hair Mask (4 oz)</li><li>Spa Hand Cream (2 oz)</li><li>Reusable sage-green gift box with tissue</li></ul><h3>How to use</h3><p>Alternate the mask once weekly with your regular routine. Follow with hand cream after washing for soft, nourished skin.</p>',
			'highlights'  => "Salon-quality formulas\nGift-ready packaging\nIdeal for birthdays & holidays\nStudio pickup or shipping available",
			'image'       => 'product-gift-set.jpg',
		),
		'Hydrating Hair Mask'     => array(
			'short'       => 'Deep moisture treatment that restores softness, shine, and manageability for all hair types.',
			'description' => '<p>A rich, creamy mask infused with argan oil and shea butter to repair dry or color-treated hair without weighing it down. Used weekly in our studio — now available for your at-home routine.</p><h3>Key benefits</h3><ul><li>Intense hydration for dry, frizzy, or damaged hair</li><li>Color-safe and sulfate-free</li><li>Leaves hair silky, smooth, and easier to style</li></ul><h3>How to use</h3><p>Apply to clean, damp hair from mid-lengths to ends. Leave on 5–10 minutes, then rinse thoroughly. Use once weekly or as needed.</p>',
			'highlights'  => "For all hair types\nColor-safe formula\nUsed in our studio treatments\nResults after one use",
			'image'       => 'product-hair-mask.jpg',
		),
		'Shine Serum'             => array(
			'short'       => 'Lightweight finishing serum for smooth, glossy hair with heat protection up to 450°F.',
			'description' => '<p>This silky serum tames flyaways, adds mirror-like shine, and protects against heat styling. A few drops go a long way — our stylists use it as the final step on every blowout.</p><h3>Key benefits</h3><ul><li>Weightless shine without greasiness</li><li>Heat protection for blow-drying and flat ironing</li><li>Helps prevent split ends and frizz</li></ul><h3>How to use</h3><p>Work 2–3 drops through damp or dry hair, focusing on mid-lengths and ends. Style as usual.</p>',
			'highlights'  => "Lightweight & non-greasy\nHeat protection\nSalon blowout finish\nWorks on damp or dry hair",
			'image'       => 'product-shine-serum.jpg',
		),
		'Spa Hand Cream'          => array(
			'short'       => 'Rich, fast-absorbing cream with natural oils and vitamin E for soft, nourished hands.',
			'description' => '<p>Formulated for hands that work hard — whether you\'re styling hair all day or washing frequently. Absorbs quickly with no sticky residue, leaving skin velvety smooth.</p><h3>Key benefits</h3><ul><li>Deep moisture with jojoba and sweet almond oil</li><li>Vitamin E helps repair dry, cracked skin</li><li>Subtle botanical scent — never overpowering</li></ul><h3>How to use</h3><p>Massage a pea-sized amount into hands and cuticles as needed throughout the day.</p>',
			'highlights'  => "Fast-absorbing formula\nNatural oils & vitamin E\nNo sticky residue\nPerfect for daily use",
			'image'       => 'product-hand-cream.jpg',
		),
		'Revitalizing Body Scrub' => array(
			'short'       => 'Gentle sugar scrub with essential oils for soft, radiant skin from shoulders to toes.',
			'description' => '<p>Exfoliate and nourish in one step. Fine sugar crystals buff away dull skin while coconut oil and lavender leave you feeling refreshed and silky smooth.</p><h3>Key benefits</h3><ul><li>Gentle enough for sensitive skin</li><li>Natural sugar exfoliant — no microplastics</li><li>Leaves skin soft, never stripped</li></ul><h3>How to use</h3><p>Apply to damp skin in the shower, massage in circular motions, and rinse. Use 2–3 times per week.</p>',
			'highlights'  => "Natural sugar exfoliant\nEssential oil blend\nSoft, radiant skin\nIdeal pre-event prep",
			'image'       => 'product-body-scrub.jpg',
		),
		'Gift Card $50'           => array(
			'short'       => 'Digital or printed gift card redeemable for salon services and boutique products in studio.',
			'description' => '<p>Give the gift of self-care. Redeemable for any service or product at Sage & Bloom — from haircuts and color to curated boutique favorites.</p><h3>Details</h3><ul><li>$50 value — never expires</li><li>Redeem in studio for services or shop purchases</li><li>Delivered by email or ready for pickup in a gift sleeve</li><li>Cannot be exchanged for cash</li></ul><h3>Perfect for</h3><p>Birthdays, holidays, thank-you gifts, or treating someone to a spa day.</p>',
			'highlights'  => "Never expires\nServices & products\nEmail or pickup delivery\nBeautiful gift sleeve available",
			'image'       => 'product-gift-card.jpg',
		),
		'Daily Conditioner'       => array(
			'short'       => 'Everyday conditioner that detangles, softens, and protects hair without buildup.',
			'description' => '<p>Our go-to conditioner for clients who want manageable, healthy-looking hair every day. Lightweight enough for fine hair, nourishing enough for thick or curly textures.</p><h3>Key benefits</h3><ul><li>Instant detangling and softness</li><li>Strengthens hair against breakage</li><li>Safe for color-treated and keratin-treated hair</li></ul><h3>How to use</h3><p>After shampooing, apply from mid-lengths to ends. Leave on 1–2 minutes, then rinse. Use daily or as needed.</p>',
			'highlights'  => "Daily use formula\nDetangles & softens\nColor-safe\nNo heavy buildup",
			'image'       => 'product-conditioner.jpg',
		),
		'Self-Care Starter Kit'   => array(
			'short'       => 'Everything for an at-home spa evening — scrub, mask, hand cream, and aromatherapy candle.',
			'description' => '<p>Transform your bathroom into a calm retreat. This kit bundles our best-selling body and hand care with a soy candle for the full Sage & Bloom at-home experience.</p><h3>What\'s inside</h3><ul><li>Revitalizing Body Scrub (4 oz)</li><li>Hydrating Hair Mask (2 oz travel size)</li><li>Spa Hand Cream (2 oz)</li><li>Sage & Bloom soy candle (2 oz)</li></ul><h3>Perfect for</h3><p>Weekend reset, travel, or introducing someone to the boutique.</p>',
			'highlights'  => "Complete at-home spa kit\nTravel-friendly sizes\nIncludes aromatherapy candle\nSave vs. buying separately",
			'image'       => 'product-starter-kit.jpg',
		),
	);
}

/**
 * Bundled demo image URL for a product title.
 *
 * @param string $title Product title.
 * @return string
 */
function starter_theme_product_demo_image_url( $title ) {
	$map = function_exists( 'starter_theme_demo_image_map' ) ? starter_theme_demo_image_map() : array();

	if ( empty( $map['products'][ $title ] ) ) {
		return '';
	}

	$filename = $map['products'][ $title ];
	$path     = starter_theme_demo_images_dir() . '/' . $filename;

	if ( ! file_exists( $path ) ) {
		return '';
	}

	return get_template_directory_uri() . '/assets/images/demo/' . $filename;
}

/**
 * Get demo data for a product by title.
 *
 * @param string $title Product title.
 * @return array<string, mixed>|null
 */
function starter_theme_product_demo_data( $title ) {
	$catalog = starter_theme_product_demo_catalog();

	return isset( $catalog[ $title ] ) ? $catalog[ $title ] : null;
}

/**
 * Parse highlight lines for a product.
 *
 * @param int $product_id Product ID.
 * @return array<int, string>
 */
function starter_theme_product_highlights( $product_id ) {
	$product = wc_get_product( $product_id );

	if ( ! $product ) {
		return array();
	}

	$data = starter_theme_product_demo_data( $product->get_name() );

	if ( empty( $data['highlights'] ) ) {
		return array();
	}

	$lines = preg_split( '/\r\n|\r|\n/', (string) $data['highlights'] );

	return array_values( array_filter( array_map( 'trim', $lines ) ) );
}

/**
 * Apply rich demo copy and image to a product.
 *
 * @param int    $product_id Product ID.
 * @param string $title      Product title key.
 * @param bool   $force_image Replace image even when one exists.
 * @return bool
 */
function starter_theme_apply_product_demo_details( $product_id, $title, $force_image = false ) {
	$data = starter_theme_product_demo_data( $title );

	if ( ! $data || ! function_exists( 'wc_get_product' ) ) {
		return false;
	}

	$product = wc_get_product( $product_id );

	if ( ! $product ) {
		return false;
	}

	$product->set_short_description( $data['short'] );
	$product->set_description( $data['description'] );
	$product->save();

	$image_map = function_exists( 'starter_theme_demo_image_map' ) ? starter_theme_demo_image_map() : array();

	if ( isset( $image_map['products'][ $title ] ) && function_exists( 'starter_theme_set_product_demo_image' ) ) {
		starter_theme_set_product_demo_image( $product_id, $image_map['products'][ $title ], $force_image );
	} elseif ( ! empty( $data['image'] ) ) {
		if ( filter_var( $data['image'], FILTER_VALIDATE_URL ) && function_exists( 'starter_theme_sideload_product_image' ) ) {
			starter_theme_sideload_product_image( $product_id, $data['image'], $force_image );
		} elseif ( function_exists( 'starter_theme_set_product_demo_image' ) ) {
			starter_theme_set_product_demo_image( $product_id, $data['image'], $force_image );
		}
	}

	return true;
}

/**
 * Related products for the single product page.
 *
 * @param int $product_id Current product ID.
 * @param int $limit        Max items.
 * @return array<int, WC_Product>
 */
function starter_theme_related_products( $product_id, $limit = 4 ) {
	$product_id = (int) $product_id;
	$limit      = max( 1, (int) $limit );

	if ( ! function_exists( 'wc_get_product' ) ) {
		return array();
	}

	$terms = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );

	$args = array(
		'status'  => 'publish',
		'limit'   => $limit,
		'exclude' => array( $product_id ),
		'orderby' => 'rand',
	);

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		$args['category'] = $terms;
	}

	$products = wc_get_products( $args );

	return is_array( $products ) ? $products : array();
}

/**
 * Use bundled theme images for demo products (avoids stale media library attachments).
 *
 * @param string     $image   Product image HTML.
 * @param WC_Product $product Product object.
 * @param string     $size    Image size.
 * @param array      $attr    Image attributes.
 * @return string
 */
function starter_theme_product_demo_image_html( $image, $product, $size, $attr ) {
	if ( ! $product instanceof WC_Product ) {
		return $image;
	}

	$map = starter_theme_demo_image_map();

	if ( ! isset( $map['products'][ $product->get_name() ] ) ) {
		return $image;
	}

	$filename = $map['products'][ $product->get_name() ];
	$path     = starter_theme_demo_images_dir() . '/' . $filename;

	if ( ! file_exists( $path ) ) {
		return $image;
	}

	$url = get_template_directory_uri() . '/assets/images/demo/' . $filename;
	$alt = $product->get_name();

	return sprintf(
		'<img src="%1$s" alt="%2$s" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" loading="lazy" decoding="async" />',
		esc_url( $url ),
		esc_attr( $alt )
	);
}
add_filter( 'woocommerce_product_get_image', 'starter_theme_product_demo_image_html', 20, 4 );

add_filter( 'woocommerce_single_product_image_thumbnail_html', 'starter_theme_single_product_demo_thumbnail', 10, 2 );

/**
 * Render bundled demo image on single product pages.
 *
 * @param string $html Attachment HTML.
 * @param int    $attachment_id Attachment ID.
 * @return string
 */
function starter_theme_single_product_demo_thumbnail( $html, $attachment_id ) {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return $html;
	}

	$map = starter_theme_demo_image_map();

	if ( ! isset( $map['products'][ $product->get_name() ] ) ) {
		return $html;
	}

	$filename = $map['products'][ $product->get_name() ];
	$path     = starter_theme_demo_images_dir() . '/' . $filename;

	if ( ! file_exists( $path ) ) {
		return $html;
	}

	$url = get_template_directory_uri() . '/assets/images/demo/' . $filename;

	return sprintf(
		'<div data-thumb="%1$s" data-thumb-alt="%2$s" class="woocommerce-product-gallery__image"><a href="%1$s"><img width="600" height="600" src="%1$s" alt="%2$s" decoding="async" loading="lazy" /></a></div>',
		esc_url( $url ),
		esc_attr( $product->get_name() )
	);
}
