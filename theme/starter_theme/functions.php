<?php
/**
 * starter_theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package starter_theme
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function starter_theme_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on starter_theme, use a find and replace
		* to change 'starter_theme' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'starter_theme', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'starter_theme' ),
			'footer' => esc_html__( 'Footer', 'starter_theme' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'starter_theme_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'starter_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function starter_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'starter_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'starter_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function starter_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'starter_theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'starter_theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'starter_theme_widgets_init' );

/**
 * Cache-bust theme asset enqueues when the file changes.
 *
 * @param string $relative_path Path relative to theme root.
 * @return string
 */
function starter_theme_asset_version( $relative_path ) {
	$path = get_template_directory() . '/' . ltrim( $relative_path, '/' );

	return file_exists( $path ) ? (string) filemtime( $path ) : _S_VERSION;
}

/**
 * Enqueue scripts and styles.
 */
function starter_theme_scripts() {
	$theme_uri = get_template_directory_uri();
	$version   = _S_VERSION;

	wp_enqueue_style(
		'starter_theme-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lexend:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'starter_theme-style', get_stylesheet_uri(), array( 'starter_theme-fonts' ), $version );
	wp_style_add_data( 'starter_theme-style', 'rtl', 'replace' );

	wp_enqueue_style( 'starter_theme-variables', $theme_uri . '/assets/css/variables.css', array( 'starter_theme-style' ), starter_theme_asset_version( 'assets/css/variables.css' ) );
	wp_enqueue_style( 'starter_theme-layout', $theme_uri . '/assets/css/layout.css', array( 'starter_theme-variables' ), starter_theme_asset_version( 'assets/css/layout.css' ) );
	wp_enqueue_style( 'starter_theme-components', $theme_uri . '/assets/css/components.css', array( 'starter_theme-layout' ), $version );
	wp_enqueue_style( 'starter_theme-animations', $theme_uri . '/assets/css/animations.css', array( 'starter_theme-components' ), $version );
	wp_enqueue_style( 'starter_theme-pages', $theme_uri . '/assets/css/pages.css', array( 'starter_theme-components' ), starter_theme_asset_version( 'assets/css/pages.css' ) );
	wp_enqueue_style( 'starter_theme-forms', $theme_uri . '/assets/css/forms.css', array( 'starter_theme-components' ), $version );

	if ( is_front_page() ) {
		wp_enqueue_style( 'starter_theme-homepage', $theme_uri . '/assets/css/homepage.css', array( 'starter_theme-components' ), starter_theme_asset_version( 'assets/css/homepage.css' ) );
		wp_enqueue_style( 'starter_theme-services', $theme_uri . '/assets/css/services.css', array( 'starter_theme-components' ), starter_theme_asset_version( 'assets/css/services.css' ) );
		if ( class_exists( 'WooCommerce' ) ) {
			$shop_deps = array( 'starter_theme-components' );
			if ( wp_style_is( 'woocommerce-general', 'registered' ) ) {
				$shop_deps[] = 'woocommerce-general';
				$shop_deps[] = 'woocommerce-layout';
			}
			wp_enqueue_style( 'starter_theme-shop', $theme_uri . '/assets/css/shop.css', $shop_deps, starter_theme_asset_version( 'assets/css/shop.css' ) );
		}
	}

	if ( is_post_type_archive( 'service' ) || is_singular( 'service' ) || is_tax( 'service_category' ) ) {
		wp_enqueue_style( 'starter_theme-services', $theme_uri . '/assets/css/services.css', array( 'starter_theme-components' ), starter_theme_asset_version( 'assets/css/services.css' ) );
	}

	if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		$shop_deps = array( 'starter_theme-components' );
		if ( wp_style_is( 'woocommerce-general', 'registered' ) ) {
			$shop_deps[] = 'woocommerce-general';
			$shop_deps[] = 'woocommerce-layout';
		}
		wp_enqueue_style( 'starter_theme-shop', $theme_uri . '/assets/css/shop.css', $shop_deps, starter_theme_asset_version( 'assets/css/shop.css' ) );
	}

	wp_enqueue_script( 'starter_theme-navigation', $theme_uri . '/js/navigation.js', array(), $version, true );
	wp_enqueue_script( 'starter_theme-scroll-reveal', $theme_uri . '/js/scroll-reveal.js', array(), starter_theme_asset_version( 'js/scroll-reveal.js' ), true );
	wp_enqueue_script(
		'starter_theme-page-loader',
		$theme_uri . '/assets/js/page-loader.js',
		array(),
		starter_theme_asset_version( 'assets/js/page-loader.js' ),
		true
	);

	if ( is_page_template( 'templates/page-contact.php' ) ) {
		wp_enqueue_style(
			'leaflet',
			'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
			array(),
			'1.9.4'
		);
		wp_enqueue_script(
			'leaflet',
			'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
			array(),
			'1.9.4',
			true
		);
		wp_enqueue_script(
			'starter_theme-contact-map',
			$theme_uri . '/assets/js/contact-map.js',
			array( 'leaflet' ),
			starter_theme_asset_version( 'assets/js/contact-map.js' ),
			true
		);
	}

	if ( is_page_template( 'templates/page-booking.php' ) && class_exists( 'SBC_Booking_Service' ) ) {
		wp_enqueue_script(
			'starter_theme-booking-api',
			$theme_uri . '/assets/js/booking-api.js',
			array(),
			starter_theme_asset_version( 'assets/js/booking-api.js' ),
			true
		);

		wp_localize_script(
			'starter_theme-booking-api',
			'sbcBooking',
			array(
				'restUrl'        => esc_url_raw( rest_url( 'sage-bloom/v1/' ) ),
				'nonce'          => wp_create_nonce( 'wp_rest' ),
				'i18nSuccess'    => __( 'Thank you! Your appointment request has been sent.', 'starter_theme' ),
				'i18nError'      => __( 'Something went wrong. Please try again.', 'starter_theme' ),
				'i18nNoSlots'    => __( 'No available times for this date. Please choose another day.', 'starter_theme' ),
				'i18nSelectSlot' => __( 'Please select an available time slot.', 'starter_theme' ),
				'i18nSubmitting' => __( 'Submitting…', 'starter_theme' ),
				'i18nSubmit'     => __( 'Request Appointment', 'starter_theme' ),
			)
		);
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'starter_theme_scripts' );

/**
 * Enqueue overrides last so they win over Underscores and WooCommerce defaults.
 */
function starter_theme_enqueue_overrides() {
	$overrides_path = get_template_directory() . '/assets/css/overrides.css';

	wp_enqueue_style(
		'starter_theme-overrides',
		get_template_directory_uri() . '/assets/css/overrides.css',
		array( 'starter_theme-forms', 'starter_theme-pages', 'starter_theme-animations' ),
		file_exists( $overrides_path ) ? filemtime( $overrides_path ) : _S_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'starter_theme_enqueue_overrides', 99 );

/**
 * Fallback primary menu when no menu is assigned.
 */
function starter_theme_fallback_menu() {
	$items = array(
		array( __( 'Home', 'starter_theme' ), home_url( '/' ) ),
		array( __( 'About', 'starter_theme' ), home_url( '/about/' ) ),
		array( __( 'Services', 'starter_theme' ), starter_theme_services_url() ),
		array( __( 'Shop', 'starter_theme' ), starter_theme_shop_url() ),
		array( __( 'Book Now', 'starter_theme' ), starter_theme_booking_url() ),
		array( __( 'Contact', 'starter_theme' ), home_url( '/contact/' ) ),
	);

	echo '<ul id="primary-menu" class="menu">';
	foreach ( $items as $item ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( $item[1] ),
			esc_html( $item[0] )
		);
	}
	echo '</ul>';
}

/**
 * Business helpers and ACF option getters.
 */
require get_template_directory() . '/inc/business.php';
require get_template_directory() . '/inc/business-settings.php';

/**
 * Homepage setup and ACF field groups.
 */
require get_template_directory() . '/inc/homepage.php';
require get_template_directory() . '/inc/page-helpers.php';
require get_template_directory() . '/inc/error-helpers.php';
require get_template_directory() . '/inc/service-helpers.php';
require get_template_directory() . '/inc/product-helpers.php';
require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/acf-options.php';
require get_template_directory() . '/inc/acf-fields.php';
require get_template_directory() . '/inc/forms.php';
require get_template_directory() . '/inc/demo-images.php';
require get_template_directory() . '/inc/branding.php';
require get_template_directory() . '/inc/demo-content.php';

if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
	require get_template_directory() . '/inc/my-account.php';
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

