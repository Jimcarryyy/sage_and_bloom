<?php
/**
 * Import bundled demo images into the WordPress media library.
 *
 * Images sourced from Pexels (free license) and bundled under assets/images/demo/.
 *
 * @package starter_theme
 */

/**
 * Get the demo images directory path.
 *
 * @return string
 */
function starter_theme_demo_images_dir() {
	return get_template_directory() . '/assets/images/demo';
}

/**
 * Import a theme-bundled image into the media library.
 *
 * @param string $filename File name inside assets/images/demo/.
 * @param int    $post_id  Optional parent post ID.
 * @return int Attachment ID or 0 on failure.
 */
function starter_theme_import_demo_image( $filename, $post_id = 0 ) {
	$path = starter_theme_demo_images_dir() . '/' . $filename;

	if ( ! file_exists( $path ) ) {
		return 0;
	}

	$existing = starter_theme_get_demo_attachment_id( $filename );
	if ( $existing ) {
		return $existing;
	}

	$upload_dir = wp_upload_dir();

	if ( ! empty( $upload_dir['error'] ) ) {
		return 0;
	}

	$contents = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	if ( false === $contents ) {
		return 0;
	}

	$dest_name = wp_unique_filename( $upload_dir['path'], $filename );
	$upload    = wp_upload_bits( $dest_name, null, $contents );

	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$filetype = wp_check_filetype( $dest_name, null );

	$attachment = array(
		'post_mime_type' => $filetype['type'],
		'post_title'     => sanitize_text_field( pathinfo( $filename, PATHINFO_FILENAME ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attach_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );

	if ( ! $attach_id || is_wp_error( $attach_id ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';

	$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
	wp_update_attachment_metadata( $attach_id, $attach_data );
	update_post_meta( $attach_id, '_starter_theme_demo_source', $filename );

	return (int) $attach_id;
}

/**
 * Find an existing imported demo attachment by source filename.
 *
 * @param string $filename Demo filename.
 * @return int
 */
function starter_theme_get_demo_attachment_id( $filename ) {
	$query = new WP_Query(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_starter_theme_demo_source',
			'meta_value'     => $filename,
		)
	);

	if ( empty( $query->posts ) ) {
		return 0;
	}

	return (int) $query->posts[0];
}

/**
 * Import a bundled demo image without reusing an existing attachment.
 *
 * @param string $filename File name inside assets/images/demo/.
 * @param int    $post_id  Optional parent post ID.
 * @return int Attachment ID or 0 on failure.
 */
function starter_theme_import_demo_image_fresh( $filename, $post_id = 0 ) {
	$path = starter_theme_demo_images_dir() . '/' . $filename;

	if ( ! file_exists( $path ) ) {
		return 0;
	}

	if ( ! function_exists( 'wp_upload_bits' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	$contents = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	if ( false === $contents ) {
		return 0;
	}

	$upload_dir = wp_upload_dir();

	if ( ! empty( $upload_dir['error'] ) ) {
		return 0;
	}

	$dest_name = wp_unique_filename( $upload_dir['path'], $filename );
	$upload    = wp_upload_bits( $dest_name, null, $contents );

	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}

	$filetype = wp_check_filetype( $dest_name, null );

	$attachment = array(
		'post_mime_type' => $filetype['type'],
		'post_title'     => sanitize_text_field( pathinfo( $filename, PATHINFO_FILENAME ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attach_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );

	if ( ! $attach_id || is_wp_error( $attach_id ) ) {
		return 0;
	}

	$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
	wp_update_attachment_metadata( $attach_id, $attach_data );
	update_post_meta( $attach_id, '_starter_theme_demo_source', $filename );

	return (int) $attach_id;
}

/**
 * Set featured image from a bundled demo file.
 *
 * @param int    $post_id  Post ID.
 * @param string $filename Demo image filename.
 * @return bool
 */
function starter_theme_set_post_demo_image( $post_id, $filename ) {
	$post_id = (int) $post_id;

	if ( ! $post_id || has_post_thumbnail( $post_id ) ) {
		return false;
	}

	$attach_id = starter_theme_import_demo_image( $filename, $post_id );

	if ( ! $attach_id ) {
		return false;
	}

	return (bool) set_post_thumbnail( $post_id, $attach_id );
}

/**
 * Whether an attachment is the WooCommerce placeholder image.
 *
 * @param int $attachment_id Attachment ID.
 * @return bool
 */
function starter_theme_is_wc_placeholder_image( $attachment_id ) {
	$placeholder_id = (int) get_option( 'woocommerce_placeholder_image', 0 );

	return $placeholder_id > 0 && $placeholder_id === (int) $attachment_id;
}

/**
 * Sideload a remote image and assign it to a WooCommerce product.
 *
 * @param int    $product_id Product ID.
 * @param string $url        Image URL.
 * @param bool   $force      Replace existing images when true.
 * @return bool
 */
function starter_theme_sideload_product_image( $product_id, $url, $force = false ) {
	$product_id = (int) $product_id;

	if ( ! $product_id || ! $url || ! function_exists( 'wc_get_product' ) ) {
		return false;
	}

	$product = wc_get_product( $product_id );

	if ( ! $product ) {
		return false;
	}

	$current_image = (int) $product->get_image_id();

	if ( $current_image && ! $force && ! starter_theme_is_wc_placeholder_image( $current_image ) ) {
		return false;
	}

	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$attach_id = media_sideload_image( $url, $product_id, $product->get_name(), 'id' );

	if ( is_wp_error( $attach_id ) || ! $attach_id ) {
		return false;
	}

	if ( $force && $current_image && (int) $attach_id !== $current_image ) {
		wp_delete_attachment( $current_image, true );
	}

	$product->set_image_id( (int) $attach_id );
	$product->save();

	return true;
}

/**
 * Set WooCommerce product image from bundled demo file.
 *
 * @param int    $product_id Product ID.
 * @param string $filename   Demo image filename.
 * @param bool   $force      Replace existing images when true.
 * @return bool
 */
function starter_theme_set_product_demo_image( $product_id, $filename, $force = false ) {
	$product_id = (int) $product_id;

	if ( ! $product_id || ! function_exists( 'wc_get_product' ) ) {
		return false;
	}

	$product = wc_get_product( $product_id );

	if ( ! $product ) {
		return false;
	}

	$current_image = (int) $product->get_image_id();

	if ( $current_image && ! $force && ! starter_theme_is_wc_placeholder_image( $current_image ) ) {
		return false;
	}

	if ( $force ) {
		$existing_demo = starter_theme_get_demo_attachment_id( $filename );
		if ( $existing_demo ) {
			wp_delete_attachment( $existing_demo, true );
		}
		$attach_id = starter_theme_import_demo_image_fresh( $filename, $product_id );
	} else {
		$attach_id = starter_theme_import_demo_image( $filename, $product_id );
	}

	if ( ! $attach_id && function_exists( 'starter_theme_product_demo_data' ) ) {
		$post = get_post( $product_id );
		if ( $post ) {
			$data = starter_theme_product_demo_data( $post->post_title );
			if ( ! empty( $data['image'] ) ) {
				if ( filter_var( $data['image'], FILTER_VALIDATE_URL ) ) {
					return starter_theme_sideload_product_image( $product_id, $data['image'], $force );
				}

				return starter_theme_set_product_demo_image( $product_id, $data['image'], $force );
			}
		}
	}

	if ( ! $attach_id ) {
		return false;
	}

	if ( $force && $current_image && (int) $attach_id !== $current_image ) {
		wp_delete_attachment( $current_image, true );
	}

	$product->set_image_id( $attach_id );
	$product->save();

	return true;
}

/**
 * Demo image map keyed by post title.
 *
 * @return array<string, string>
 */
function starter_theme_demo_image_map() {
	return array(
		'services' => array(
			'Haircut & Style'     => 'service-haircut.jpg',
			'Color & Highlights'  => 'service-color.jpg',
			'Deep Conditioning'   => 'service-conditioning.jpg',
			'Classic Manicure'    => 'service-manicure.jpg',
			'Gel Manicure'        => 'service-gel-manicure.jpg',
			'Spa Pedicure'        => 'service-pedicure.jpg',
			'Bridal Package'      => 'service-bridal.jpg',
			'Self-Care Bundle'    => 'service-bundle.jpg',
		),
		'products' => array(
			'Luxury Gift Set'          => 'product-gift-set.jpg',
			'Hydrating Hair Mask'      => 'product-hair-mask.jpg',
			'Shine Serum'              => 'product-shine-serum.jpg',
			'Spa Hand Cream'           => 'product-hand-cream.jpg',
			'Revitalizing Body Scrub'  => 'product-body-scrub.jpg',
			'Gift Card $50'            => 'product-gift-card.jpg',
			'Daily Conditioner'        => 'product-conditioner.jpg',
			'Self-Care Starter Kit'    => 'product-starter-kit.jpg',
		),
		'team' => array(
			'Alex Rivera'  => 'team-alex.jpg',
			'Jordan Lee'   => 'team-jordan.jpg',
			'Sam Taylor'   => 'team-sam.jpg',
			'Casey Morgan' => 'team-casey.jpg',
		),
		'posts' => array(
			'5 Tips for Healthy Hair Between Visits'   => 'blog-hair-tips.jpg',
			'New Arrivals: Spring Self-Care Collection' => 'blog-spring.jpg',
			'Why We Love Local Business'              => 'blog-local.jpg',
		),
	);
}

/**
 * Attach demo images to content and homepage hero.
 */
function starter_theme_seed_demo_images() {
	$map = starter_theme_demo_image_map();

	foreach ( $map['services'] as $title => $filename ) {
		$post = get_page_by_title( $title, OBJECT, 'service' );
		if ( $post ) {
			starter_theme_set_post_demo_image( $post->ID, $filename );
		}
	}

	foreach ( $map['products'] as $title => $filename ) {
		$post = get_page_by_title( $title, OBJECT, 'product' );
		if ( $post ) {
			starter_theme_set_product_demo_image( $post->ID, $filename );
		}
	}

	foreach ( $map['team'] as $title => $filename ) {
		$post = get_page_by_title( $title, OBJECT, 'team' );
		if ( $post ) {
			starter_theme_set_post_demo_image( $post->ID, $filename );
		}
	}

	foreach ( $map['posts'] as $title => $filename ) {
		$post = get_page_by_title( $title, OBJECT, 'post' );
		if ( $post ) {
			starter_theme_set_post_demo_image( $post->ID, $filename );
		}
	}

	$front_page_id = (int) get_option( 'page_on_front' );

	if ( $front_page_id && function_exists( 'get_field' ) && ! get_field( 'hero_image', $front_page_id ) ) {
		$hero_id = starter_theme_import_demo_image( 'hero-salon.jpg', $front_page_id );

		if ( $hero_id ) {
			update_field( 'hero_image', $hero_id, $front_page_id );
		}
	}

	update_option( 'starter_theme_demo_images_seeded', 1 );
}

/**
 * Seed demo images once in admin (covers existing installs).
 */
function starter_theme_maybe_seed_demo_images() {
	if ( ! is_admin() || get_option( 'starter_theme_demo_images_seeded' ) ) {
		return;
	}

	if ( ! function_exists( 'wp_upload_bits' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	starter_theme_seed_demo_images();
}
add_action( 'admin_init', 'starter_theme_maybe_seed_demo_images', 30 );
