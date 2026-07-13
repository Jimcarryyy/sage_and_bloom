<?php
/**
 * Register custom post types and taxonomies.
 *
 * @package starter_theme
 */

/**
 * Register service, team, and testimonial post types.
 */
function starter_theme_register_post_types() {
	register_post_type(
		'service',
		array(
			'labels'       => array(
				'name'          => __( 'Services', 'starter_theme' ),
				'singular_name' => __( 'Service', 'starter_theme' ),
				'add_new_item'  => __( 'Add New Service', 'starter_theme' ),
				'edit_item'     => __( 'Edit Service', 'starter_theme' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'services' ),
			'menu_icon'    => 'dashicons-hammer',
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		)
	);

	register_taxonomy(
		'service_category',
		'service',
		array(
			'labels'       => array(
				'name'          => __( 'Service Categories', 'starter_theme' ),
				'singular_name' => __( 'Service Category', 'starter_theme' ),
			),
			'public'       => true,
			'hierarchical' => true,
			'rewrite'      => array( 'slug' => 'service-category' ),
			'show_in_rest' => true,
		)
	);

	register_post_type(
		'team',
		array(
			'labels'       => array(
				'name'          => __( 'Team', 'starter_theme' ),
				'singular_name' => __( 'Team Member', 'starter_theme' ),
				'add_new_item'  => __( 'Add Team Member', 'starter_theme' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'team' ),
			'menu_icon'    => 'dashicons-groups',
			'show_in_rest' => true,
			'supports'     => array( 'title', 'thumbnail' ),
		)
	);

	register_post_type(
		'testimonial',
		array(
			'labels'       => array(
				'name'          => __( 'Testimonials', 'starter_theme' ),
				'singular_name' => __( 'Testimonial', 'starter_theme' ),
				'add_new_item'  => __( 'Add Testimonial', 'starter_theme' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'has_archive'  => false,
			'menu_icon'    => 'dashicons-format-quote',
			'show_in_rest' => true,
			'supports'     => array( 'title' ),
		)
	);
}
add_action( 'init', 'starter_theme_register_post_types' );

/**
 * Flush rewrite rules on theme activation.
 */
function starter_theme_rewrite_flush() {
	starter_theme_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'starter_theme_rewrite_flush' );
