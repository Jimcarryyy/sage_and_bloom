<?php
/**
 * Branding — logo and contextual hero imagery.
 *
 * @package starter_theme
 */

/**
 * Store display name.
 *
 * @return string
 */
function starter_theme_brand_name() {
	return 'Sage & Bloom';
}

/**
 * Theme logo URL.
 *
 * @param string $variant header|footer|mark
 * @return string
 */
function starter_theme_logo_url( $variant = 'header' ) {
	$file = 'logo.svg';

	if ( 'footer' === $variant ) {
		$file = 'logo-footer.svg';
	} elseif ( 'mark' === $variant ) {
		$file = 'logo-mark.svg';
	}

	return get_template_directory_uri() . '/assets/images/' . $file;
}

/**
 * Render site logo markup.
 *
 * Uses the icon mark plus HTML text so the logo stays visible even when
 * parent containers collapse (img max-width: 100% on a zero-width flex item).
 *
 * @param string $variant header|footer|mark
 * @return void
 */
function starter_theme_render_logo( $variant = 'header' ) {
	$home     = esc_url( home_url( '/' ) );
	$name     = esc_attr( starter_theme_brand_name() );
	$mark     = esc_url( starter_theme_logo_url( 'mark' ) );
	$tagline  = esc_html__( 'Salon & Boutique', 'starter_theme' );
	$is_footer = 'footer' === $variant;

	if ( 'mark' === $variant ) {
		printf(
			'<img src="%1$s" class="custom-logo custom-logo--mark" alt="" width="64" height="64" decoding="async" />',
			$mark
		);
		return;
	}

	$link_class = 'site-logo custom-logo-link';
	if ( $is_footer ) {
		$link_class .= ' site-logo--footer custom-logo-link--footer';
	}

	printf(
		'<a href="%1$s" class="%2$s" rel="home" aria-label="%3$s">',
		$home,
		esc_attr( $link_class ),
		$name
	);

	printf(
		'<img src="%1$s" class="site-logo__mark" alt="" width="36" height="36" decoding="async" />',
		$mark
	);

	echo '<span class="site-logo__text">';
	printf(
		'<span class="site-logo__name">%s</span>',
		esc_html( starter_theme_brand_name() )
	);
	printf(
		'<span class="site-logo__tagline">%s</span>',
		$tagline
	);
	echo '</span></a>';
}

/**
 * Default hero image URLs keyed by page context.
 *
 * @return array<string, string>
 */
function starter_theme_page_hero_defaults() {
	return array(
		'home'     => 'https://images.unsplash.com/photo-1633681926022-84c23e8cb04d?auto=format&fit=crop&w=1600&q=80',
		'about'    => 'https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=1600&q=80',
		'contact'  => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=1600&q=80',
		'booking'  => 'https://images.unsplash.com/photo-1506784365847-bbad939e9335?auto=format&fit=crop&w=1600&q=80',
		'shop'     => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=1600&q=80',
		'cart'     => 'https://images.unsplash.com/photo-1607083206869-4c7672e72a8a?auto=format&fit=crop&w=1600&q=80',
		'checkout' => 'https://images.unsplash.com/photo-1556742502-ec7c0e9f34b1?auto=format&fit=crop&w=1600&q=80',
		'account'  => 'https://images.unsplash.com/photo-1551836022-d5d88e1328db?auto=format&fit=crop&w=1600&q=80',
		'services' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=1600&q=80',
		'blog'     => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80',
		'product'  => 'https://images.unsplash.com/photo-1571875257727-256c39da42af?auto=format&fit=crop&w=1600&q=80',
		'search'   => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1600&q=80',
		'404'      => 'https://images.unsplash.com/photo-1493612276216-ee3925520721?auto=format&fit=crop&w=1600&q=80',
		'default'  => 'https://images.unsplash.com/photo-1634449574104-4c367c0d8652?auto=format&fit=crop&w=1600&q=80',
	);
}

/**
 * Resolve hero context key for the current request.
 *
 * @param int $post_id Optional post ID.
 * @return string
 */
function starter_theme_page_hero_context( $post_id = 0 ) {
	if ( is_front_page() ) {
		return 'home';
	}

	if ( function_exists( 'is_cart' ) && is_cart() ) {
		return 'cart';
	}

	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		return 'checkout';
	}

	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		return 'account';
	}

	if ( function_exists( 'is_product' ) && is_product() ) {
		return 'product';
	}

	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
		return 'shop';
	}

	if ( is_post_type_archive( 'service' ) || is_tax( 'service_category' ) ) {
		return 'services';
	}

	if ( is_singular( 'service' ) ) {
		return 'services';
	}

	if ( is_home() || is_category() || is_tag() || is_singular( 'post' ) ) {
		return 'blog';
	}

	if ( is_search() ) {
		return 'search';
	}

	if ( is_404() ) {
		return '404';
	}

	if ( $post_id ) {
		$template = get_page_template_slug( $post_id );

		if ( 'templates/page-about.php' === $template ) {
			return 'about';
		}
		if ( 'templates/page-contact.php' === $template ) {
			return 'contact';
		}
		if ( 'templates/page-booking.php' === $template ) {
			return 'booking';
		}

		$page = get_post( $post_id );
		if ( $page && 'page' === $page->post_type ) {
			$slug = $page->post_name;
			if ( isset( starter_theme_page_hero_defaults()[ $slug ] ) ) {
				return $slug;
			}
		}
	}

	return 'default';
}

/**
 * Default hero URL for a context key.
 *
 * @param string $context Context key.
 * @return string
 */
function starter_theme_page_hero_default_url( $context = '' ) {
	$defaults = starter_theme_page_hero_defaults();
	$context  = $context ? $context : starter_theme_page_hero_context();

	if ( isset( $defaults[ $context ] ) ) {
		return $defaults[ $context ];
	}

	return $defaults['default'];
}

/**
 * Sideload a remote hero image as a page featured image.
 *
 * @param int    $post_id Page ID.
 * @param string $url     Image URL.
 * @return bool
 */
function starter_theme_sideload_page_hero_image( $post_id, $url ) {
	$post_id = (int) $post_id;

	if ( ! $post_id || has_post_thumbnail( $post_id ) || ! $url ) {
		return false;
	}

	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$attach_id = media_sideload_image( $url, $post_id, null, 'id' );

	if ( is_wp_error( $attach_id ) || ! $attach_id ) {
		return false;
	}

	return (bool) set_post_thumbnail( $post_id, (int) $attach_id );
}

/**
 * Apply Sage & Bloom branding defaults to the site.
 *
 * @return void
 */
function starter_theme_apply_brand_defaults() {
	update_option( 'blogname', starter_theme_brand_name() );
	update_option( 'blogdescription', __( 'Salon services & curated boutique — quality care, warm welcome.', 'starter_theme' ) );

	if ( function_exists( 'update_field' ) ) {
		$target = function_exists( 'starter_theme_business_settings_target' )
			? starter_theme_business_settings_target()
			: 'option';

		update_field( 'business_email', 'hello@sageandbloom.studio', $target );
		update_field( 'footer_tagline', __( 'Salon services & curated boutique — quality care, warm welcome.', 'starter_theme' ), $target );
	}

	remove_theme_mod( 'custom_logo' );
}
