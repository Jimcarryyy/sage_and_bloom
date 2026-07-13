<?php
/**
 * Register ACF field groups for editable homepage sections.
 *
 * @package starter_theme
 */

/**
 * Register homepage field group when ACF is available.
 */
function starter_theme_register_homepage_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'    => 'group_starter_homepage',
			'title'  => 'Homepage Content',
			'fields' => array(
				array(
					'key'   => 'field_starter_home_hero_tab',
					'label' => 'Hero',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_starter_home_hero_heading',
					'label'         => 'Hero Heading',
					'name'          => 'hero_heading',
					'type'          => 'text',
					'default_value' => 'Welcome to Our Salon & Boutique',
				),
				array(
					'key'           => 'field_starter_home_hero_subheading',
					'label'         => 'Hero Subheading',
					'name'          => 'hero_subheading',
					'type'          => 'textarea',
					'rows'          => 3,
					'default_value' => 'Quality services, curated products, and a warm local experience.',
				),
				array(
					'key'           => 'field_starter_home_hero_button_text',
					'label'         => 'Hero Button Text',
					'name'          => 'hero_button_text',
					'type'          => 'text',
					'default_value' => 'Book Appointment',
				),
				array(
					'key'           => 'field_starter_home_hero_button_url',
					'label'         => 'Hero Button URL',
					'name'          => 'hero_button_url',
					'type'          => 'url',
					'default_value' => '/book-now/',
				),
				array(
					'key'           => 'field_starter_home_hero_image',
					'label'         => 'Hero Background Image',
					'name'          => 'hero_image',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'medium',
				),
				array(
					'key'           => 'field_starter_home_hero_eyebrow',
					'label'         => 'Hero Eyebrow',
					'name'          => 'hero_eyebrow',
					'type'          => 'text',
					'instructions'  => 'Small label above the headline. Leave empty to use business status.',
					'default_value' => '',
				),
				array(
					'key'           => 'field_starter_home_hero_benefits',
					'label'         => 'Hero Benefits',
					'name'          => 'hero_benefits',
					'type'          => 'textarea',
					'rows'          => 4,
					'instructions'  => 'One benefit per line. Shown as a checklist below the subheading.',
					'default_value' => "Walk-ins welcome\nFree parking\nGift cards available\nExpert stylists & curated products",
				),
				array(
					'key'   => 'field_starter_home_trust_tab',
					'label' => 'Trust & Stats',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_starter_home_trust_badges',
					'label'         => 'Trust Badges',
					'name'          => 'trust_badges',
					'type'          => 'textarea',
					'rows'          => 4,
					'instructions'  => 'One badge per line. Shown in the strip below the hero.',
					'default_value' => "Licensed professionals\nPremium products\nBridal & events\nNeighborhood favorite",
				),
				array(
					'key'           => 'field_starter_home_stat_1_value',
					'label'         => 'Stat 1 — Value',
					'name'          => 'stat_1_value',
					'type'          => 'text',
					'default_value' => '10+',
				),
				array(
					'key'           => 'field_starter_home_stat_1_label',
					'label'         => 'Stat 1 — Label',
					'name'          => 'stat_1_label',
					'type'          => 'text',
					'default_value' => 'Years serving our community',
				),
				array(
					'key'           => 'field_starter_home_stat_2_value',
					'label'         => 'Stat 2 — Value',
					'name'          => 'stat_2_value',
					'type'          => 'text',
					'default_value' => '5',
				),
				array(
					'key'           => 'field_starter_home_stat_2_label',
					'label'         => 'Stat 2 — Label',
					'name'          => 'stat_2_label',
					'type'          => 'text',
					'default_value' => 'Expert stylists on staff',
				),
				array(
					'key'           => 'field_starter_home_stat_3_value',
					'label'         => 'Stat 3 — Value',
					'name'          => 'stat_3_value',
					'type'          => 'text',
					'default_value' => '500+',
				),
				array(
					'key'           => 'field_starter_home_stat_3_label',
					'label'         => 'Stat 3 — Label',
					'name'          => 'stat_3_label',
					'type'          => 'text',
					'default_value' => 'Happy clients each year',
				),
				array(
					'key'           => 'field_starter_home_stat_4_value',
					'label'         => 'Stat 4 — Value',
					'name'          => 'stat_4_value',
					'type'          => 'text',
					'default_value' => '4.9★',
				),
				array(
					'key'           => 'field_starter_home_stat_4_label',
					'label'         => 'Stat 4 — Label',
					'name'          => 'stat_4_label',
					'type'          => 'text',
					'default_value' => 'Average client rating',
				),
				array(
					'key'   => 'field_starter_home_info_tab',
					'label' => 'Info Bar',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_starter_home_info_text',
					'label'         => 'Info Bar Text',
					'name'          => 'info_bar_text',
					'type'          => 'text',
					'default_value' => 'Walk-ins welcome · Free parking · Gift cards available',
				),
				array(
					'key'   => 'field_starter_home_about_tab',
					'label' => 'About',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_starter_home_about_heading',
					'label'         => 'About Heading',
					'name'          => 'about_heading',
					'type'          => 'text',
					'default_value' => 'Your Neighborhood Salon',
				),
				array(
					'key'           => 'field_starter_home_about_content',
					'label'         => 'About Content',
					'name'          => 'about_content',
					'type'          => 'wysiwyg',
					'toolbar'       => 'basic',
					'media_upload'  => 0,
					'default_value' => '<p>We combine expert salon services with a curated boutique shop — all in one welcoming space downtown.</p>',
				),
				array(
					'key'           => 'field_starter_home_about_points',
					'label'         => 'About Highlights',
					'name'          => 'about_points',
					'type'          => 'textarea',
					'rows'          => 4,
					'instructions'  => 'One highlight per line. Shown in the sidebar checklist.',
					'default_value' => "Expert cuts, color, and nail care\nCurated self-care products in our boutique\nWarm hospitality for walk-ins and appointments\nDowntown location with free parking",
				),
				array(
					'key'   => 'field_starter_home_services_tab',
					'label' => 'Featured Services',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_starter_home_services_heading',
					'label'         => 'Section Heading',
					'name'          => 'services_heading',
					'type'          => 'text',
					'default_value' => 'Our Services',
				),
				array(
					'key'           => 'field_starter_home_featured_services',
					'label'         => 'Featured Services',
					'name'          => 'featured_services',
					'type'          => 'relationship',
					'post_type'     => array( 'service' ),
					'filters'       => array( 'search' ),
					'return_format' => 'object',
					'max'           => 6,
				),
				array(
					'key'   => 'field_starter_home_products_tab',
					'label' => 'Featured Products',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_starter_home_products_heading',
					'label'         => 'Section Heading',
					'name'          => 'products_heading',
					'type'          => 'text',
					'default_value' => 'Shop Favorites',
				),
				array(
					'key'           => 'field_starter_home_products_count',
					'label'         => 'Number of Products',
					'name'          => 'featured_products_count',
					'type'          => 'number',
					'default_value' => 4,
					'min'           => 1,
					'max'           => 8,
				),
				array(
					'key'   => 'field_starter_home_testimonials_tab',
					'label' => 'Testimonials',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_starter_home_testimonials_heading',
					'label'         => 'Section Heading',
					'name'          => 'testimonials_heading',
					'type'          => 'text',
					'default_value' => 'What Clients Say',
				),
				array(
					'key'           => 'field_starter_home_featured_testimonials',
					'label'         => 'Featured Testimonials',
					'name'          => 'featured_testimonials',
					'type'          => 'relationship',
					'post_type'     => array( 'testimonial' ),
					'filters'       => array( 'search' ),
					'return_format' => 'object',
					'max'           => 6,
				),
				array(
					'key'   => 'field_starter_home_cta_tab',
					'label' => 'Call to Action',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_starter_home_cta_heading',
					'label'         => 'CTA Heading',
					'name'          => 'cta_heading',
					'type'          => 'text',
					'default_value' => 'Ready for your appointment?',
				),
				array(
					'key'           => 'field_starter_home_cta_text',
					'label'         => 'CTA Text',
					'name'          => 'cta_text',
					'type'          => 'textarea',
					'rows'          => 2,
					'default_value' => 'Book online or call us today. Walk-ins welcome when available.',
				),
				array(
					'key'           => 'field_starter_home_cta_button_text',
					'label'         => 'CTA Button Text',
					'name'          => 'cta_button_text',
					'type'          => 'text',
					'default_value' => 'Book Now',
				),
				array(
					'key'           => 'field_starter_home_cta_button_url',
					'label'         => 'CTA Button URL',
					'name'          => 'cta_button_url',
					'type'          => 'url',
					'default_value' => '/book-now/',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page_type',
						'operator' => '==',
						'value'    => 'front_page',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'starter_theme_register_homepage_fields' );
