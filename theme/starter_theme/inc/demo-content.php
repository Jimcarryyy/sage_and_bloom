<?php
/**
 * Demo content seeder for portfolio showcase.
 *
 * @package starter_theme
 */

/**
 * Run demo content import once.
 */
function starter_theme_seed_demo_content() {
	if ( get_option( 'starter_theme_demo_seeded' ) ) {
		return;
	}

	starter_theme_create_demo_pages();
	starter_theme_create_demo_services();
	starter_theme_create_demo_team();
	starter_theme_create_demo_testimonials();
	starter_theme_create_demo_posts();
	starter_theme_create_demo_menu();
	starter_theme_seed_business_options();

	if ( class_exists( 'WooCommerce' ) ) {
		starter_theme_create_demo_products();
	}

	starter_theme_seed_demo_images();

	update_option( 'starter_theme_demo_seeded', 1 );

	if ( '' === get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'starter_theme_seed_demo_content' );
add_action( 'admin_init', 'starter_theme_seed_demo_content' );

/**
 * Admin notice with re-seed option.
 */
function starter_theme_demo_admin_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( get_option( 'starter_theme_demo_seeded' ) ) {
		return;
	}

	echo '<div class="notice notice-info"><p>' . esc_html__( 'Local Business theme: importing demo content…', 'starter_theme' ) . '</p></div>';
}
add_action( 'admin_notices', 'starter_theme_demo_admin_notice' );

/**
 * Create core pages.
 */
function starter_theme_create_demo_pages() {
	$pages = array(
		array(
			'title'    => 'Home page',
			'slug'     => 'home-page',
			'template' => '',
			'front'    => true,
		),
		array(
			'title'    => 'About',
			'slug'     => 'about',
			'template' => 'templates/page-about.php',
		),
		array(
			'title'    => 'Book Now',
			'slug'     => 'book-now',
			'template' => 'templates/page-booking.php',
		),
		array(
			'title'    => 'Contact',
			'slug'     => 'contact',
			'template' => 'templates/page-contact.php',
		),
		array(
			'title'    => 'Blog',
			'slug'     => 'blog',
			'template' => '',
			'posts'    => true,
		),
	);

	foreach ( $pages as $page_data ) {
		$existing = get_page_by_path( $page_data['slug'], OBJECT, 'page' );

		if ( $existing ) {
			$page_id = $existing->ID;
		} else {
			$page_id = wp_insert_post(
				array(
					'post_title'   => $page_data['title'],
					'post_name'    => $page_data['slug'],
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_content' => '',
				)
			);
		}

		if ( ! empty( $page_data['template'] ) && $page_id ) {
			update_post_meta( $page_id, '_wp_page_template', $page_data['template'] );
		}

		if ( ! empty( $page_data['front'] ) && $page_id ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $page_id );
		}

		if ( ! empty( $page_data['front'] ) && $page_id && function_exists( 'update_field' ) ) {
			update_field( 'hero_eyebrow', 'Open today — Walk-ins welcome', $page_id );
			update_field(
				'hero_benefits',
				"Walk-ins welcome\nFree parking\nGift cards available\nExpert stylists & curated products",
				$page_id
			);
			update_field(
				'trust_badges',
				"Licensed professionals\nPremium products\nBridal & events\nNeighborhood favorite",
				$page_id
			);
			update_field( 'stat_1_value', '10+', $page_id );
			update_field( 'stat_1_label', 'Years serving our community', $page_id );
			update_field( 'stat_2_value', '5', $page_id );
			update_field( 'stat_2_label', 'Expert stylists on staff', $page_id );
			update_field( 'stat_3_value', '500+', $page_id );
			update_field( 'stat_3_label', 'Happy clients each year', $page_id );
			update_field( 'stat_4_value', '4.9★', $page_id );
			update_field( 'stat_4_label', 'Average client rating', $page_id );
			update_field(
				'about_points',
				"Expert cuts, color, and nail care\nCurated self-care products in our boutique\nWarm hospitality for walk-ins and appointments\nDowntown location with free parking",
				$page_id
			);
		}

		if ( ! empty( $page_data['posts'] ) && $page_id ) {
			update_option( 'page_for_posts', $page_id );
		}
	}

	if ( function_exists( 'update_field' ) ) {
		$about = get_page_by_path( 'about', OBJECT, 'page' );
		if ( $about ) {
			wp_update_post(
				array(
					'ID'           => $about->ID,
					'post_content' => '<p>We opened our doors to bring together expert salon services and a thoughtfully curated boutique under one roof. Whether you are here for a fresh cut, a relaxing pedicure, or a gift for someone special, our team is here to make it effortless.</p><p>From walk-ins to bridal parties, we treat every appointment with the same warm welcome and attention to detail.</p>',
				)
			);
			update_field(
				'about_intro',
				'A neighborhood destination for salon services and curated self-care products — rooted in quality, community, and care.',
				$about->ID
			);
			update_field( 'about_mission', 'We believe everyone deserves to feel confident, refreshed, and welcomed — every time they walk through our door.', $about->ID );
			update_field(
				'about_highlights',
				"Expert cuts, color, and nail care\nCurated self-care products in our boutique\nWarm hospitality for walk-ins and appointments\nDowntown location with free parking",
				$about->ID
			);
			update_field(
				'about_values',
				array(
					array( 'title' => 'Quality', 'text' => 'Expert services with premium products.' ),
					array( 'title' => 'Community', 'text' => 'A warm space for locals to connect.' ),
					array( 'title' => 'Care', 'text' => 'Personalized attention for every client.' ),
				),
				$about->ID
			);
		}

		$booking = get_page_by_path( 'book-now', OBJECT, 'page' );
		if ( $booking ) {
			update_field( 'booking_intro', 'Fill out the form below and we will confirm your appointment by email or phone.', $booking->ID );
		}

		$contact = get_page_by_path( 'contact', OBJECT, 'page' );
		if ( $contact ) {
			update_field( 'contact_intro', 'Visit our studio, give us a call, or send a message anytime.', $contact->ID );
			update_field( 'contact_show_map', 1, $contact->ID );
		}
	}
}

/**
 * Seed business ACF options.
 */
function starter_theme_seed_business_options() {
	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	$target = function_exists( 'starter_theme_business_settings_target' )
		? starter_theme_business_settings_target()
		: 'option';

	update_field( 'business_phone', '(555) 123-4567', $target );
	update_field( 'business_email', 'hello@sageandbloom.studio', $target );
	update_field( 'business_address', "123 Main Street\nYour City, ST 12345", $target );
	update_field( 'business_status', 'Open today — Walk-ins welcome', $target );
	update_field( 'footer_tagline', 'Your neighborhood salon & boutique — quality care, warm welcome.', $target );
	update_field( 'hours_weekdays', '9:00 AM – 7:00 PM', $target );
	update_field( 'hours_saturday', '10:00 AM – 5:00 PM', $target );
	update_field( 'hours_sunday', '', $target );
	update_field( 'hours_sunday_closed', 1, $target );
	update_field( 'social_instagram', 'https://instagram.com/', $target );
	update_field( 'social_facebook', 'https://facebook.com/', $target );
	update_field( 'business_map_lat', '14.5995', $target );
	update_field( 'business_map_lng', '120.9842', $target );
	update_field( 'business_map_zoom', '15', $target );
}

/**
 * Rich demo copy for individual services.
 *
 * @return array<string, array{content: string, includes: string, highlights: string, booking_note: string}>
 */
function starter_theme_service_demo_details() {
	return array(
		'Haircut & Style'    => array(
			'content'      => '<p>Our signature haircut and style service begins with a thoughtful consultation — we listen to your lifestyle, texture, and goals before any scissors touch your hair.</p><p>Your visit includes a relaxing shampoo, precision cut, and a finished blow-dry style so you leave looking polished and feeling confident.</p>',
			'includes'     => "Personal consultation\nShampoo and scalp massage\nPrecision cut tailored to your face shape\nBlow-dry style and finishing products\nStyling tips for at-home maintenance",
			'highlights'   => "Arrive with dry or lightly damp hair\nBring inspiration photos if you have them\nPlan 45–60 minutes for your visit\nAsk about add-on treatments",
			'booking_note' => 'First-time clients: please arrive 10 minutes early to complete a brief intake form.',
		),
		'Color & Highlights' => array(
			'content'      => '<p>Whether you want a full transformation or subtle dimension, our color specialists create custom formulas for your skin tone, hair history, and maintenance preferences.</p><p>From balayage and foils to root touch-ups and toning glosses, every session includes bond-protecting products and a thorough consultation so your color stays vibrant between visits.</p>',
			'includes'     => "Color consultation and strand test when needed\nCustom color or highlight application\nToning and gloss finish\nShampoo and blow-dry style\nAftercare product recommendations",
			'highlights'   => "Avoid washing hair 24 hours before your appointment\nDisclose recent color or chemical treatments\nColor services may take 2–3 hours\nPatch test available upon request",
			'booking_note' => 'A card on file is required for color appointments. Cancellations within 24 hours may incur a fee.',
		),
		'Deep Conditioning'  => array(
			'content'      => '<p>Restore moisture, shine, and manageability with a professional deep-conditioning treatment designed for dry, color-treated, or heat-damaged hair.</p><p>We use salon-grade masks and steam or heat activation when appropriate, leaving your hair softer, smoother, and easier to style.</p>',
			'includes'     => "Hair and scalp assessment\nDeep conditioning mask application\nSteam or heat activation\nRinse and lightweight finish\nTake-home care advice",
			'highlights'   => "Ideal as an add-on after color services\nGreat before special events\nAllow 30–45 minutes\nCan be booked with a cut or style",
			'booking_note' => 'Walk-ins welcome when availability allows.',
		),
		'Classic Manicure'   => array(
			'content'      => '<p>A timeless manicure that keeps your hands looking neat and polished. We shape, buff, and care for cuticles before applying your choice of classic polish.</p><p>Perfect for everyday elegance or as a quick refresh between gel appointments.</p>',
			'includes'     => "Nail shape and length consultation\nCuticle care and hand soak\nNail buff and polish application\nHand lotion finish\nPolish touch-up within 3 days",
			'highlights'   => "Remove old polish before arriving if possible\nChoose from our seasonal color wall\nAllow 30 minutes\nSanitized tools for every guest",
			'booking_note' => 'Group bookings of 3+ available — call ahead to reserve side-by-side seating.',
		),
		'Gel Manicure'       => array(
			'content'      => '<p>Enjoy chip-resistant shine for up to two weeks with our gel manicure. We prep nails carefully for long-lasting adhesion and a smooth, glossy finish.</p><p>Removal is available in-studio — please do not peel gel polish at home to protect your natural nails.</p>',
			'includes'     => "Nail prep and cuticle care\nGel base, color, and top coat\nLED cure for durable finish\nCuticle oil and hand lotion\nComplimentary repair within 5 days",
			'highlights'   => "Allow 45 minutes for application\nSchedule removal with your next visit\nAvoid water for 2 hours after service\nBring open-toed shoes if pairing with pedicure",
			'booking_note' => 'Gel removal add-on available for $10 when booked with a new gel set.',
		),
		'Spa Pedicure'       => array(
			'content'      => '<p>Unwind with our spa pedicure — a full foot renewal featuring a warm soak, exfoliation, callus care, massage, and flawless polish.</p><p>We prioritize hygiene and comfort so you leave relaxed, refreshed, and ready to sandal season.</p>',
			'includes'     => "Aromatherapy foot soak\nExfoliation and callus care\nLower-leg massage\nNail shape and polish\nHot towel finish",
			'highlights'   => "Wear comfortable clothing\nAllow 60 minutes\nDiabetic-friendly care available on request\nAdd paraffin wax for extra softness",
			'booking_note' => 'Please reschedule if you have open cuts or fungal infections on feet.',
		),
		'Bridal Package'     => array(
			'content'      => '<p>Look and feel your best on the big day with our bridal package — a curated experience covering hair styling, makeup consultation, and a trial run with your bridal party in mind.</p><p>We coordinate timelines with your planner or venue and offer on-location styling for wedding day itself (travel fees may apply).</p>',
			'includes'     => "Bridal consultation and mood board review\nTrial hair styling session\nMakeup look consultation\nDay-of timeline planning\nTouch-up kit recommendations",
			'highlights'   => "Book trial 4–8 weeks before your wedding\nBring veil or hair accessories to trial\nSchedule day-of styling 3+ hours before ceremony\nBridal party add-ons available",
			'booking_note' => '50% deposit required to reserve wedding dates. Contract provided at consultation.',
		),
		'Self-Care Bundle'   => array(
			'content'      => '<p>Carve out time for yourself with our self-care bundle — a restorative hair treatment paired with a classic manicure in one seamless visit.</p><p>Ideal for busy schedules when you want maximum relaxation without booking two separate appointments.</p>',
			'includes'     => "Deep conditioning hair treatment\nShampoo and blow-dry finish\nClassic manicure with polish\nComplimentary beverage\nCombined booking discount",
			'highlights'   => "Plan for approximately 2 hours\nPerfect gift for birthdays or holidays\nUpgrade to gel manicure for $15\nAvailable Tuesday–Thursday mornings",
			'booking_note' => 'Bundle pricing applies when both services are booked together in one visit.',
		),
	);
}

/**
 * Apply rich demo fields to a service post.
 *
 * @param int    $post_id Service post ID.
 * @param string $title   Service title key.
 */
function starter_theme_apply_service_demo_details( $post_id, $title ) {
	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	$details = starter_theme_service_demo_details();
	if ( ! isset( $details[ $title ] ) ) {
		return;
	}

	$data = $details[ $title ];

	update_field( 'service_includes', $data['includes'], $post_id );
	update_field( 'service_highlights', $data['highlights'], $post_id );
	update_field( 'service_booking_note', $data['booking_note'], $post_id );

	$post = get_post( $post_id );
	if ( $post && strlen( wp_strip_all_tags( $post->post_content ) ) < 120 ) {
		wp_update_post(
			array(
				'ID'           => $post_id,
				'post_content' => $data['content'],
			)
		);
	}
}

/**
 * One-time upgrade for sites seeded before contact map + rich services.
 */
function starter_theme_upgrade_content_v2() {
	if ( get_option( 'starter_theme_content_v2' ) ) {
		return;
	}

	if ( function_exists( 'update_field' ) ) {
		$contact = get_page_by_path( 'contact', OBJECT, 'page' );
		if ( $contact ) {
			update_field( 'contact_show_map', 1, $contact->ID );
		}

		$target = function_exists( 'starter_theme_business_settings_target' )
			? starter_theme_business_settings_target()
			: 'option';

		if ( ! get_field( 'business_map_lat', $target ) ) {
			update_field( 'business_map_lat', '14.5995', $target );
		}
		if ( ! get_field( 'business_map_lng', $target ) ) {
			update_field( 'business_map_lng', '120.9842', $target );
		}
		if ( ! get_field( 'business_map_zoom', $target ) ) {
			update_field( 'business_map_zoom', '15', $target );
		}
	}

	$services = get_posts(
		array(
			'post_type'      => 'service',
			'posts_per_page' => -1,
			'post_status'    => 'any',
		)
	);

	foreach ( $services as $service ) {
		starter_theme_apply_service_demo_details( $service->ID, $service->post_title );
	}

	update_option( 'starter_theme_content_v2', 1 );
}
add_action( 'init', 'starter_theme_upgrade_content_v2' );

/**
 * One-time upgrade: unique page heroes, custom logo, branding.
 */
function starter_theme_upgrade_content_v3() {
	if ( get_option( 'starter_theme_content_v3' ) ) {
		return;
	}

	if ( function_exists( 'starter_theme_sideload_page_hero_image' ) ) {
		$page_map = array(
			'about'    => 'about',
			'contact'  => 'contact',
			'book-now' => 'booking',
		);

		foreach ( $page_map as $slug => $context ) {
			$page = get_page_by_path( $slug, OBJECT, 'page' );
			if ( $page ) {
				starter_theme_sideload_page_hero_image( $page->ID, starter_theme_page_hero_default_url( $context ) );
			}
		}
	}

	update_option( 'starter_theme_content_v3', 1 );
}
add_action( 'init', 'starter_theme_upgrade_content_v3' );

/**
 * One-time rebrand to Sage & Bloom + USD currency.
 */
function starter_theme_upgrade_content_v4() {
	if ( get_option( 'starter_theme_content_v4' ) ) {
		return;
	}

	if ( function_exists( 'starter_theme_apply_brand_defaults' ) ) {
		starter_theme_apply_brand_defaults();
	}

	if ( function_exists( 'starter_theme_woocommerce_configure_currency' ) ) {
		starter_theme_woocommerce_configure_currency();
	}

	update_option( 'starter_theme_content_v4', 1 );
}
add_action( 'init', 'starter_theme_upgrade_content_v4' );

/**
 * One-time upgrade: matched product images and rich product copy.
 */
function starter_theme_upgrade_content_v5() {
	if ( get_option( 'starter_theme_content_v5' ) || ! function_exists( 'starter_theme_apply_product_demo_details' ) ) {
		return;
	}

	$products = get_posts(
		array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
		)
	);

	foreach ( $products as $product_id ) {
		$post = get_post( $product_id );
		if ( $post ) {
			starter_theme_apply_product_demo_details( $product_id, $post->post_title, true );
		}
	}

	update_option( 'starter_theme_content_v5', 1 );
}
add_action( 'admin_init', 'starter_theme_upgrade_content_v5' );

/**
 * Re-assign product images with corrected Unsplash URLs.
 */
function starter_theme_upgrade_content_v6() {
	if ( get_option( 'starter_theme_content_v6' ) || ! function_exists( 'starter_theme_apply_product_demo_details' ) ) {
		return;
	}

	$products = get_posts(
		array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
		)
	);

	foreach ( $products as $product_id ) {
		$post = get_post( $product_id );
		if ( $post ) {
			starter_theme_apply_product_demo_details( $product_id, $post->post_title, true );
		}
	}

	update_option( 'starter_theme_content_v6', 1 );
}
add_action( 'admin_init', 'starter_theme_upgrade_content_v6' );

/**
 * Import bundled local product images (reliable filenames in assets/images/demo/).
 */
function starter_theme_upgrade_content_v7() {
	if ( get_option( 'starter_theme_content_v7' ) || ! function_exists( 'starter_theme_apply_product_demo_details' ) ) {
		return;
	}

	starter_theme_upgrade_product_images_force();

	update_option( 'starter_theme_content_v7', 1 );
}
add_action( 'admin_init', 'starter_theme_upgrade_content_v7' );

/**
 * Re-import product images when bundled files are updated.
 */
function starter_theme_upgrade_content_v8() {
	if ( get_option( 'starter_theme_content_v8' ) || ! function_exists( 'starter_theme_upgrade_product_images_force' ) ) {
		return;
	}

	starter_theme_upgrade_product_images_force();

	update_option( 'starter_theme_content_v8', 1 );
}
add_action( 'admin_init', 'starter_theme_upgrade_content_v8' );

/**
 * Final product image refresh after corrected bundled files.
 */
function starter_theme_upgrade_content_v9() {
	if ( get_option( 'starter_theme_content_v9' ) || ! function_exists( 'starter_theme_upgrade_product_images_force' ) ) {
		return;
	}

	starter_theme_upgrade_product_images_force();

	update_option( 'starter_theme_content_v9', 1 );
}
add_action( 'admin_init', 'starter_theme_upgrade_content_v9', 20 );

/**
 * Re-import after local demo files were corrected in the theme bundle.
 */
function starter_theme_upgrade_content_v11() {
	if ( get_option( 'starter_theme_content_v11' ) || ! function_exists( 'starter_theme_upgrade_product_images_force' ) ) {
		return;
	}

	starter_theme_upgrade_product_images_force();

	update_option( 'starter_theme_content_v11', 1 );
}
add_action( 'init', 'starter_theme_upgrade_content_v11', 99 );

/**
 * One-time upgrade: testimonial client context labels.
 */
function starter_theme_upgrade_content_v12() {
	if ( get_option( 'starter_theme_content_v12' ) ) {
		return;
	}

	starter_theme_create_demo_testimonials();

	update_option( 'starter_theme_content_v12', 1 );
}
add_action( 'init', 'starter_theme_upgrade_content_v12', 99 );

/**
 * Force-refresh all demo product images from bundled files.
 *
 * @return void
 */
function starter_theme_upgrade_product_images_force() {
	$products = get_posts(
		array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
		)
	);

	foreach ( $products as $product_id ) {
		$post = get_post( $product_id );
		if ( $post ) {
			starter_theme_apply_product_demo_details( $product_id, $post->post_title, true );
		}
	}
}

/**
 * Create demo services.
 */
function starter_theme_create_demo_services() {
	$categories = array(
		'Hair'     => 'hair',
		'Nails'    => 'nails',
		'Packages' => 'packages',
	);

	$cat_ids = array();
	foreach ( $categories as $name => $slug ) {
		$term = term_exists( $slug, 'service_category' );
		if ( ! $term ) {
			$term = wp_insert_term( $name, 'service_category', array( 'slug' => $slug ) );
		}
		$cat_ids[ $slug ] = is_array( $term ) ? $term['term_id'] : $term;
	}

	$services = array(
		array( 'title' => 'Haircut & Style', 'cat' => 'hair', 'price' => '$45', 'duration' => '45 min', 'featured' => 1, 'desc' => 'Consultation, wash, cut, and blow-dry style.' ),
		array( 'title' => 'Color & Highlights', 'cat' => 'hair', 'price' => 'From $95', 'duration' => '2 hrs', 'featured' => 1, 'desc' => 'Full color or partial highlights with toning.' ),
		array( 'title' => 'Deep Conditioning', 'cat' => 'hair', 'price' => '$35', 'duration' => '30 min', 'featured' => 0, 'desc' => 'Restorative treatment for dry or damaged hair.' ),
		array( 'title' => 'Classic Manicure', 'cat' => 'nails', 'price' => '$30', 'duration' => '30 min', 'featured' => 1, 'desc' => 'Shape, cuticle care, and polish.' ),
		array( 'title' => 'Gel Manicure', 'cat' => 'nails', 'price' => '$45', 'duration' => '45 min', 'featured' => 0, 'desc' => 'Long-lasting gel polish application.' ),
		array( 'title' => 'Spa Pedicure', 'cat' => 'nails', 'price' => '$55', 'duration' => '60 min', 'featured' => 1, 'desc' => 'Relaxing foot soak, exfoliation, and polish.' ),
		array( 'title' => 'Bridal Package', 'cat' => 'packages', 'price' => '$199', 'duration' => '3 hrs', 'featured' => 1, 'desc' => 'Hair, makeup consult, and trial styling.' ),
		array( 'title' => 'Self-Care Bundle', 'cat' => 'packages', 'price' => '$120', 'duration' => '2 hrs', 'featured' => 0, 'desc' => 'Hair treatment plus manicure combo.' ),
	);

	foreach ( $services as $service ) {
		$existing = get_page_by_title( $service['title'], OBJECT, 'service' );
		if ( $existing ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_title'   => $service['title'],
				'post_content' => '<p>' . $service['desc'] . '</p>',
				'post_status'  => 'publish',
				'post_type'    => 'service',
			)
		);

		if ( $post_id && function_exists( 'update_field' ) ) {
			update_field( 'service_price', $service['price'], $post_id );
			update_field( 'service_duration', $service['duration'], $post_id );
			update_field( 'service_short_desc', $service['desc'], $post_id );
			update_field( 'service_featured', $service['featured'], $post_id );
			starter_theme_apply_service_demo_details( $post_id, $service['title'] );
		}

		if ( $post_id && isset( $cat_ids[ $service['cat'] ] ) ) {
			wp_set_object_terms( $post_id, (int) $cat_ids[ $service['cat'] ], 'service_category' );
		}

		if ( $post_id && function_exists( 'starter_theme_set_post_demo_image' ) ) {
			$image_map = starter_theme_demo_image_map();
			if ( isset( $image_map['services'][ $service['title'] ] ) ) {
				starter_theme_set_post_demo_image( $post_id, $image_map['services'][ $service['title'] ] );
			}
		}
	}
}

/**
 * Create demo team members.
 */
function starter_theme_create_demo_team() {
	$team = array(
		array( 'name' => 'Alex Rivera', 'role' => 'Lead Stylist', 'bio' => '10+ years crafting cuts and color for all hair types.' ),
		array( 'name' => 'Jordan Lee', 'role' => 'Nail Technician', 'bio' => 'Specializing in gel art and spa pedicures.' ),
		array( 'name' => 'Sam Taylor', 'role' => 'Color Specialist', 'bio' => 'Balayage and corrective color expert.' ),
		array( 'name' => 'Casey Morgan', 'role' => 'Studio Manager', 'bio' => 'Ensuring every visit feels welcoming and seamless.' ),
	);

	foreach ( $team as $member ) {
		if ( get_page_by_title( $member['name'], OBJECT, 'team' ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_title'  => $member['name'],
				'post_status' => 'publish',
				'post_type'   => 'team',
			)
		);

		if ( $post_id && function_exists( 'update_field' ) ) {
			update_field( 'team_role', $member['role'], $post_id );
			update_field( 'team_bio', $member['bio'], $post_id );
		}

		if ( $post_id && function_exists( 'starter_theme_set_post_demo_image' ) ) {
			$image_map = starter_theme_demo_image_map();
			if ( isset( $image_map['team'][ $member['name'] ] ) ) {
				starter_theme_set_post_demo_image( $post_id, $image_map['team'][ $member['name'] ] );
			}
		}
	}
}

/**
 * Create demo testimonials.
 */
function starter_theme_create_demo_testimonials() {
	$items = array(
		array(
			'title'  => 'Review from Elena R.',
			'quote'  => 'The bridal package was perfect — stress-free and beautiful results.',
			'client' => 'Elena R.',
			'role'   => 'Bridal client',
			'rating' => 5,
		),
		array(
			'title'  => 'Review from Sarah M.',
			'quote'  => 'Absolutely love this place! Professional service and a beautiful shop.',
			'client' => 'Sarah M.',
			'role'   => 'Color & cut regular',
			'rating' => 5,
		),
		array(
			'title'  => 'Review from James L.',
			'quote'  => 'Best salon experience in town. I always leave feeling refreshed.',
			'client' => 'James L.',
			'role'   => 'Walk-in guest',
			'rating' => 5,
		),
		array(
			'title'  => 'Review from Priya K.',
			'quote'  => 'The team is so talented and friendly. Highly recommend the spa pedicure!',
			'client' => 'Priya K.',
			'role'   => 'Spa pedicure guest',
			'rating' => 5,
		),
		array(
			'title'  => 'Review from Maria G.',
			'quote'  => 'Great products in the shop and amazing hair color results every time.',
			'client' => 'Maria G.',
			'role'   => 'Boutique shopper',
			'rating' => 5,
		),
		array(
			'title'  => 'Review from Tom H.',
			'quote'  => 'Easy booking and always on time. My go-to neighborhood spot.',
			'client' => 'Tom H.',
			'role'   => 'Monthly member',
			'rating' => 4,
		),
	);

	foreach ( $items as $item ) {
		$post = get_page_by_title( $item['title'], OBJECT, 'testimonial' );

		if ( $post ) {
			if ( function_exists( 'update_field' ) ) {
				update_field( 'testimonial_role', $item['role'], $post->ID );
			}
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_title'  => $item['title'],
				'post_status' => 'publish',
				'post_type'   => 'testimonial',
			)
		);

		if ( $post_id && function_exists( 'update_field' ) ) {
			update_field( 'testimonial_quote', $item['quote'], $post_id );
			update_field( 'testimonial_client', $item['client'], $post_id );
			update_field( 'testimonial_role', $item['role'], $post_id );
			update_field( 'testimonial_rating', $item['rating'], $post_id );
		}
	}
}

/**
 * Create demo blog posts.
 */
function starter_theme_create_demo_posts() {
	$posts = array(
		array(
			'title'   => '5 Tips for Healthy Hair Between Visits',
			'content' => '<p>Keep your hair looking salon-fresh with these simple at-home care tips from our stylists.</p>',
		),
		array(
			'title'   => 'New Arrivals: Spring Self-Care Collection',
			'content' => '<p>Discover our latest boutique picks — curated oils, masks, and gift sets now in the shop.</p>',
		),
		array(
			'title'   => 'Why We Love Local Business',
			'content' => '<p>Supporting our neighborhood means building relationships that last. Thank you for being part of our story.</p>',
		),
	);

	foreach ( $posts as $post_data ) {
		if ( get_page_by_title( $post_data['title'], OBJECT, 'post' ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_title'   => $post_data['title'],
				'post_content' => $post_data['content'],
				'post_status'  => 'publish',
				'post_type'    => 'post',
			)
		);

		if ( $post_id && function_exists( 'starter_theme_set_post_demo_image' ) ) {
			$image_map = starter_theme_demo_image_map();
			if ( isset( $image_map['posts'][ $post_data['title'] ] ) ) {
				starter_theme_set_post_demo_image( $post_id, $image_map['posts'][ $post_data['title'] ] );
			}
		}
	}
}

/**
 * Create demo WooCommerce products.
 */
function starter_theme_create_demo_products() {
	if ( ! class_exists( 'WC_Product_Simple' ) ) {
		return;
	}

	$categories = array(
		'Gift Sets'  => 'gift-sets',
		'Hair Care'  => 'hair-care',
		'Skin Care'  => 'skin-care',
	);

	foreach ( $categories as $name => $slug ) {
		if ( ! term_exists( $slug, 'product_cat' ) ) {
			wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
		}
	}

	$products = array(
		array( 'name' => 'Luxury Gift Set', 'price' => '49.99', 'cat' => 'gift-sets', 'featured' => true ),
		array( 'name' => 'Hydrating Hair Mask', 'price' => '24.99', 'cat' => 'hair-care', 'featured' => true ),
		array( 'name' => 'Shine Serum', 'price' => '18.99', 'cat' => 'hair-care', 'featured' => false ),
		array( 'name' => 'Spa Hand Cream', 'price' => '14.99', 'cat' => 'skin-care', 'featured' => true ),
		array( 'name' => 'Revitalizing Body Scrub', 'price' => '22.99', 'cat' => 'skin-care', 'featured' => false ),
		array( 'name' => 'Gift Card $50', 'price' => '50.00', 'cat' => 'gift-sets', 'featured' => true ),
		array( 'name' => 'Daily Conditioner', 'price' => '19.99', 'cat' => 'hair-care', 'featured' => false ),
		array( 'name' => 'Self-Care Starter Kit', 'price' => '39.99', 'cat' => 'gift-sets', 'featured' => true ),
	);

	foreach ( $products as $product_data ) {
		if ( wc_get_product_id_by_sku( sanitize_title( $product_data['name'] ) ) ) {
			continue;
		}

		$existing = get_page_by_title( $product_data['name'], OBJECT, 'product' );
		if ( $existing ) {
			continue;
		}

		$demo = function_exists( 'starter_theme_product_demo_data' )
			? starter_theme_product_demo_data( $product_data['name'] )
			: null;

		$product = new WC_Product_Simple();
		$product->set_name( $product_data['name'] );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_description( $demo ? $demo['description'] : '' );
		$product->set_short_description( $demo ? $demo['short'] : '' );
		$product->set_regular_price( $product_data['price'] );
		$product->set_sku( sanitize_title( $product_data['name'] ) );
		$product->set_featured( $product_data['featured'] );
		$product->set_manage_stock( false );
		$product->set_stock_status( 'instock' );

		$product_id = $product->save();

		$term = get_term_by( 'slug', $product_data['cat'], 'product_cat' );
		if ( $term && $product_id ) {
			wp_set_object_terms( $product_id, (int) $term->term_id, 'product_cat' );
		}

		if ( $product_id && function_exists( 'starter_theme_apply_product_demo_details' ) ) {
			starter_theme_apply_product_demo_details( $product_id, $product_data['name'], true );
		} elseif ( $product_id && function_exists( 'starter_theme_set_product_demo_image' ) ) {
			$image_map = starter_theme_demo_image_map();
			if ( isset( $image_map['products'][ $product_data['name'] ] ) ) {
				starter_theme_set_product_demo_image( $product_id, $image_map['products'][ $product_data['name'] ] );
			}
		}
	}
}

/**
 * Create primary navigation menu.
 */
function starter_theme_create_demo_menu() {
	$menu_name = 'Primary Menu';
	$menu      = wp_get_nav_menu_object( $menu_name );

	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );
	} else {
		$menu_id = $menu->term_id;
	}

	$existing_items = wp_get_nav_menu_items( $menu_id );
	if ( ! empty( $existing_items ) ) {
		$locations           = get_theme_mod( 'nav_menu_locations', array() );
		$locations['menu-1'] = $menu_id;
		$locations['footer'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
		return;
	}

	$items = array(
		array( 'Home', home_url( '/' ) ),
		array( 'About', home_url( '/about/' ) ),
		array( 'Services', starter_theme_services_url() ),
		array( 'Shop', starter_theme_shop_url() ),
		array( 'Book Now', starter_theme_booking_url() ),
		array( 'Contact', home_url( '/contact/' ) ),
	);

	foreach ( $items as $item ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'  => $item[0],
				'menu-item-url'    => $item[1],
				'menu-item-status' => 'publish',
			)
		);
	}

	$locations            = get_theme_mod( 'nav_menu_locations', array() );
	$locations['menu-1']  = $menu_id;
	$locations['footer']  = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Delayed product seed when WooCommerce activates after theme.
 */
function starter_theme_maybe_seed_products() {
	if ( ! class_exists( 'WooCommerce' ) || get_option( 'starter_theme_products_seeded' ) ) {
		return;
	}

	if ( get_option( 'starter_theme_demo_seeded' ) ) {
		starter_theme_create_demo_products();
		update_option( 'starter_theme_products_seeded', 1 );
	}
}
add_action( 'admin_init', 'starter_theme_maybe_seed_products' );
