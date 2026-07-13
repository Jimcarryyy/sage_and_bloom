<?php
/**
 * Business settings page (ACF Free compatible).
 *
 * ACF Options Pages and Repeater fields require ACF Pro. This module stores
 * business settings on a private WordPress page instead.
 *
 * @package starter_theme
 */

/**
 * Get or create the private Business Settings page ID.
 *
 * @return int
 */
function starter_theme_business_settings_page_id() {
	static $page_id = null;

	if ( null !== $page_id ) {
		return $page_id;
	}

	$page = get_page_by_path( 'business-settings', OBJECT, 'page' );

	if ( $page instanceof WP_Post ) {
		$page_id = (int) $page->ID;
		return $page_id;
	}

	$new_id = wp_insert_post(
		array(
			'post_title'   => 'Business Settings',
			'post_name'    => 'business-settings',
			'post_status'  => 'private',
			'post_type'    => 'page',
			'post_content' => '',
		),
		true
	);

	$page_id = is_wp_error( $new_id ) ? 0 : (int) $new_id;

	return $page_id;
}

/**
 * Ensure the Business Settings page exists in admin.
 */
function starter_theme_ensure_business_settings_page() {
	if ( is_admin() || did_action( 'after_setup_theme' ) ) {
		starter_theme_business_settings_page_id();
	}
}
add_action( 'after_setup_theme', 'starter_theme_ensure_business_settings_page', 5 );
add_action( 'admin_init', 'starter_theme_ensure_business_settings_page' );

/**
 * Add Business Settings link to the admin menu (ACF Free).
 */
function starter_theme_register_business_settings_menu() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	$page_id = starter_theme_business_settings_page_id();

	if ( ! $page_id ) {
		return;
	}

	add_menu_page(
		__( 'Business Settings', 'starter_theme' ),
		__( 'Business Settings', 'starter_theme' ),
		'edit_posts',
		'starter-theme-business-settings',
		'starter_theme_render_business_settings_redirect',
		'dashicons-store',
		59
	);
}
add_action( 'admin_menu', 'starter_theme_register_business_settings_menu' );

/**
 * Redirect the Business Settings menu item to the settings page editor.
 */
function starter_theme_render_business_settings_redirect() {
	$page_id = starter_theme_business_settings_page_id();

	if ( $page_id ) {
		wp_safe_redirect( admin_url( 'post.php?post=' . $page_id . '&action=edit' ) );
		exit;
	}

	echo '<div class="wrap"><p>' . esc_html__( 'Unable to load Business Settings.', 'starter_theme' ) . '</p></div>';
}

/**
 * Where business ACF values are stored.
 *
 * @return int|string Post ID, or "option" when ACF Pro options page is used.
 */
function starter_theme_business_settings_target() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		return 'option';
	}

	return starter_theme_business_settings_page_id();
}

/**
 * Admin notice for ACF Free users.
 */
function starter_theme_acf_free_admin_notice() {
	if ( ! current_user_can( 'edit_posts' ) || function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	if ( ! function_exists( 'get_field' ) ) {
		return;
	}

	$page_id = starter_theme_business_settings_page_id();

	if ( ! $page_id ) {
		return;
	}

	printf(
		'<div class="notice notice-info is-dismissible"><p>%s</p></div>',
		wp_kses_post(
			sprintf(
				/* translators: %s: admin menu label */
				__( 'ACF Free detected: use <strong>%s</strong> in the sidebar to edit your phone, hours, and social links. Field groups are provided by the theme — you do not need to create them manually in ACF.', 'starter_theme' ),
				esc_html__( 'Business Settings', 'starter_theme' )
			)
		)
	);
}
add_action( 'admin_notices', 'starter_theme_acf_free_admin_notice' );

/**
 * Seed default business values onto the settings page for existing installs.
 */
function starter_theme_maybe_seed_business_page_fields() {
	if ( ! is_admin() || ! function_exists( 'update_field' ) || function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	if ( get_option( 'starter_theme_business_page_seeded' ) ) {
		return;
	}

	$page_id = starter_theme_business_settings_page_id();

	if ( ! $page_id ) {
		return;
	}

	if ( get_field( 'business_phone', $page_id ) ) {
		update_option( 'starter_theme_business_page_seeded', 1 );
		return;
	}

	if ( function_exists( 'starter_theme_seed_business_options' ) ) {
		starter_theme_seed_business_options();
	}

	update_option( 'starter_theme_business_page_seeded', 1 );
}
add_action( 'admin_init', 'starter_theme_maybe_seed_business_page_fields', 25 );
