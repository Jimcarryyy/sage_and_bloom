<?php
/**
 * ACF Options page — Business Settings.
 *
 * @package starter_theme
 */

/**
 * Register the Business Settings options page.
 */
function starter_theme_register_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Business Settings', 'starter_theme' ),
			'menu_title' => __( 'Business Settings', 'starter_theme' ),
			'menu_slug'  => 'business-settings',
			'capability' => 'edit_posts',
			'icon_url'   => 'dashicons-store',
			'position'   => 59,
		)
	);
}
add_action( 'acf/init', 'starter_theme_register_options_page' );

/**
 * Register business settings field group.
 */
function starter_theme_register_business_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$locations = array();

	if ( function_exists( 'acf_add_options_page' ) ) {
		$locations[] = array(
			array(
				'param'    => 'options_page',
				'operator' => '==',
				'value'    => 'business-settings',
			),
		);
	} else {
		$page_id = starter_theme_business_settings_page_id();

		if ( ! $page_id ) {
			return;
		}

		$locations[] = array(
			array(
				'param'    => 'page',
				'operator' => '==',
				'value'    => $page_id,
			),
		);
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_starter_business',
			'title'    => 'Business Settings',
			'fields'   => starter_theme_get_business_field_definitions(),
			'location' => $locations,
		)
	);
}
add_action( 'acf/init', 'starter_theme_register_business_fields', 20 );

/**
 * Business settings field definitions (ACF Free compatible).
 *
 * @return array
 */
function starter_theme_get_business_field_definitions() {
	return array(
		array(
			'key'           => 'field_business_phone',
			'label'         => 'Phone Number',
			'name'          => 'business_phone',
			'type'          => 'text',
			'default_value' => '(555) 123-4567',
		),
		array(
			'key'           => 'field_business_email',
			'label'         => 'Email',
			'name'          => 'business_email',
			'type'          => 'email',
			'default_value' => 'hello@sageandbloom.studio',
		),
		array(
			'key'           => 'field_business_address',
			'label'         => 'Address',
			'name'          => 'business_address',
			'type'          => 'textarea',
			'rows'          => 3,
			'default_value' => "123 Main Street\nYour City, ST 12345",
		),
		array(
			'key'           => 'field_business_status',
			'label'         => 'Status Message',
			'name'          => 'business_status',
			'type'          => 'text',
			'default_value' => 'Open today — Walk-ins welcome',
		),
		array(
			'key'          => 'field_business_map',
			'label'        => 'Google Maps Embed URL',
			'name'         => 'business_map_embed',
			'type'         => 'url',
			'instructions' => 'Optional legacy iframe URL. The contact page uses the interactive map below by default.',
		),
		array(
			'key'           => 'field_business_map_lat',
			'label'         => 'Map Latitude',
			'name'          => 'business_map_lat',
			'type'          => 'text',
			'instructions'  => 'Used for the interactive contact map.',
			'default_value' => '14.5995',
		),
		array(
			'key'           => 'field_business_map_lng',
			'label'         => 'Map Longitude',
			'name'          => 'business_map_lng',
			'type'          => 'text',
			'instructions'  => 'Used for the interactive contact map.',
			'default_value' => '120.9842',
		),
		array(
			'key'           => 'field_business_map_zoom',
			'label'         => 'Map Zoom Level',
			'name'          => 'business_map_zoom',
			'type'          => 'number',
			'default_value' => 15,
			'min'           => 8,
			'max'           => 18,
		),
		array(
			'key'           => 'field_hours_weekdays',
			'label'         => 'Weekday Hours (Mon – Fri)',
			'name'          => 'hours_weekdays',
			'type'          => 'text',
			'default_value' => '9:00 AM – 7:00 PM',
		),
		array(
			'key'           => 'field_hours_saturday',
			'label'         => 'Saturday Hours',
			'name'          => 'hours_saturday',
			'type'          => 'text',
			'default_value' => '10:00 AM – 5:00 PM',
		),
		array(
			'key'           => 'field_hours_sunday',
			'label'         => 'Sunday Hours',
			'name'          => 'hours_sunday',
			'type'          => 'text',
			'instructions'  => 'Leave blank when closed on Sunday.',
		),
		array(
			'key'           => 'field_hours_sunday_closed',
			'label'         => 'Closed on Sunday',
			'name'          => 'hours_sunday_closed',
			'type'          => 'true_false',
			'ui'            => 1,
			'default_value' => 1,
		),
		array(
			'key'          => 'field_social_instagram',
			'label'        => 'Instagram URL',
			'name'         => 'social_instagram',
			'type'         => 'url',
			'instructions' => 'Full profile URL, e.g. https://instagram.com/yourbusiness',
		),
		array(
			'key'          => 'field_social_facebook',
			'label'        => 'Facebook URL',
			'name'         => 'social_facebook',
			'type'         => 'url',
			'instructions' => 'Full page URL, e.g. https://facebook.com/yourbusiness',
		),
		array(
			'key'           => 'field_footer_tagline',
			'label'         => 'Footer Tagline',
			'name'          => 'footer_tagline',
			'type'          => 'textarea',
			'rows'          => 2,
			'default_value' => 'Your neighborhood salon & boutique — quality care, warm welcome.',
		),
	);
}

/**
 * Register CPT field groups.
 */
function starter_theme_register_cpt_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'    => 'group_starter_service',
			'title'  => 'Service Details',
			'fields' => array(
				array(
					'key'   => 'field_service_price',
					'label' => 'Price',
					'name'  => 'service_price',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_service_duration',
					'label' => 'Duration',
					'name'  => 'service_duration',
					'type'  => 'text',
				),
				array(
					'key'           => 'field_service_short_desc',
					'label'         => 'Short Description',
					'name'          => 'service_short_desc',
					'type'          => 'textarea',
					'rows'          => 3,
				),
				array(
					'key'           => 'field_service_featured',
					'label'         => 'Featured on Homepage',
					'name'          => 'service_featured',
					'type'          => 'true_false',
					'ui'            => 1,
					'default_value' => 0,
				),
				array(
					'key'           => 'field_service_includes',
					'label'         => 'What\'s Included',
					'name'          => 'service_includes',
					'type'          => 'textarea',
					'rows'          => 5,
					'instructions'  => 'One item per line. Shown on the service detail page.',
				),
				array(
					'key'           => 'field_service_highlights',
					'label'         => 'Before Your Visit',
					'name'          => 'service_highlights',
					'type'          => 'textarea',
					'rows'          => 4,
					'instructions'  => 'One tip per line.',
				),
				array(
					'key'           => 'field_service_booking_note',
					'label'         => 'Booking Note',
					'name'          => 'service_booking_note',
					'type'          => 'textarea',
					'rows'          => 2,
					'instructions'  => 'Short note shown in the booking sidebar.',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'service',
					),
				),
			),
		)
	);

	acf_add_local_field_group(
		array(
			'key'    => 'group_starter_team',
			'title'  => 'Team Member Details',
			'fields' => array(
				array(
					'key'   => 'field_team_role',
					'label' => 'Role / Title',
					'name'  => 'team_role',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_team_bio',
					'label' => 'Bio',
					'name'  => 'team_bio',
					'type'  => 'textarea',
					'rows'  => 4,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'team',
					),
				),
			),
		)
	);

	acf_add_local_field_group(
		array(
			'key'    => 'group_starter_testimonial',
			'title'  => 'Testimonial Details',
			'fields' => array(
				array(
					'key'   => 'field_testimonial_quote',
					'label' => 'Quote',
					'name'  => 'testimonial_quote',
					'type'  => 'textarea',
					'rows'  => 4,
				),
				array(
					'key'   => 'field_testimonial_client',
					'label' => 'Client Name',
					'name'  => 'testimonial_client',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_testimonial_role',
					'label' => 'Client Context',
					'name'  => 'testimonial_role',
					'type'  => 'text',
					'instructions' => 'Short label such as Bridal client or Boutique regular.',
				),
				array(
					'key'           => 'field_testimonial_rating',
					'label'         => 'Rating (1–5)',
					'name'          => 'testimonial_rating',
					'type'          => 'number',
					'min'           => 1,
					'max'           => 5,
					'default_value' => 5,
				),
				array(
					'key'           => 'field_testimonial_photo',
					'label'         => 'Client Photo',
					'name'          => 'testimonial_photo',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'testimonial',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'starter_theme_register_cpt_fields' );

/**
 * Register page template field groups.
 */
function starter_theme_register_page_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'    => 'group_starter_about_page',
			'title'  => 'About Page Content',
			'fields' => array(
				array(
					'key'   => 'field_about_intro',
					'label' => 'Intro Text',
					'name'  => 'about_intro',
					'type'  => 'wysiwyg',
					'toolbar' => 'basic',
				),
				array(
					'key'           => 'field_about_mission',
					'label'         => 'Mission Statement',
					'name'          => 'about_mission',
					'type'          => 'textarea',
					'rows'          => 3,
				),
				array(
					'key'           => 'field_about_highlights',
					'label'         => 'Story Highlights',
					'name'          => 'about_highlights',
					'type'          => 'textarea',
					'rows'          => 4,
					'instructions'  => 'One highlight per line. Shown in the hero checklist and story sidebar.',
					'default_value' => "Expert cuts, color, and nail care\nCurated self-care products in our boutique\nWarm hospitality for walk-ins and appointments\nDowntown location with free parking",
				),
				array(
					'key'          => 'field_about_values',
					'label'        => 'Values',
					'name'         => 'about_values',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add Value',
					'sub_fields'   => array(
						array(
							'key'   => 'field_value_title',
							'label' => 'Title',
							'name'  => 'title',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_value_text',
							'label' => 'Description',
							'name'  => 'text',
							'type'  => 'textarea',
							'rows'  => 2,
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'templates/page-about.php',
					),
				),
			),
		)
	);

	acf_add_local_field_group(
		array(
			'key'    => 'group_starter_booking_page',
			'title'  => 'Booking Page Content',
			'fields' => array(
				array(
					'key'   => 'field_booking_intro',
					'label' => 'Intro Text',
					'name'  => 'booking_intro',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				array(
					'key'          => 'field_booking_form_shortcode',
					'label'        => 'Form Shortcode',
					'name'         => 'booking_form_shortcode',
					'type'         => 'text',
					'instructions' => 'Paste your Contact Form 7 shortcode, e.g. [contact-form-7 id="123"]',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'templates/page-booking.php',
					),
				),
			),
		)
	);

	acf_add_local_field_group(
		array(
			'key'    => 'group_starter_contact_page',
			'title'  => 'Contact Page Content',
			'fields' => array(
				array(
					'key'   => 'field_contact_intro',
					'label' => 'Intro Text',
					'name'  => 'contact_intro',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				array(
					'key'           => 'field_contact_show_map',
					'label'         => 'Show Map',
					'name'          => 'contact_show_map',
					'type'          => 'true_false',
					'ui'            => 1,
					'default_value' => 1,
				),
				array(
					'key'          => 'field_contact_form_shortcode',
					'label'        => 'Form Shortcode',
					'name'         => 'contact_form_shortcode',
					'type'         => 'text',
					'instructions' => 'Optional Contact Form 7 shortcode.',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'templates/page-contact.php',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'starter_theme_register_page_fields' );
