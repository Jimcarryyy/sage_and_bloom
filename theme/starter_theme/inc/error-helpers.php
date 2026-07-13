<?php
/**
 * Error page helpers (404, search empty, server errors).
 *
 * @package starter_theme
 */

/**
 * Error page configuration presets.
 *
 * @return array<string, array<string, mixed>>
 */
function starter_theme_error_configs() {
	return array(
		'404' => array(
			'code'          => '404',
			'eyebrow'       => __( 'Page not found', 'starter_theme' ),
			'title'         => __( 'This page wandered off', 'starter_theme' ),
			'desc'          => __( 'The link may be broken, or the page may have moved. Let us help you find your way back to our salon and boutique.', 'starter_theme' ),
			'context'       => '404',
			'show_search'   => true,
			'search_prompt' => __( 'Try searching our site', 'starter_theme' ),
			'show_links'    => true,
			'benefits'      => array(
				__( 'Browse our boutique and self-care picks', 'starter_theme' ),
				__( 'Book cuts, color, nails, and more', 'starter_theme' ),
				__( 'Call or visit us downtown', 'starter_theme' ),
			),
			'primary_cta'   => array(
				'text' => __( 'Back to Home', 'starter_theme' ),
				'url'  => home_url( '/' ),
			),
		),
		'403' => array(
			'code'          => '403',
			'eyebrow'       => __( 'Access denied', 'starter_theme' ),
			'title'         => __( 'You do not have permission here', 'starter_theme' ),
			'desc'          => __( 'This area is restricted. If you believe this is a mistake, please contact our team.', 'starter_theme' ),
			'context'       => '404',
			'show_search'   => false,
			'show_links'    => true,
			'benefits'      => array(
				__( 'Return to public pages', 'starter_theme' ),
				__( 'Book an appointment online', 'starter_theme' ),
				__( 'Reach us by phone or email', 'starter_theme' ),
			),
			'primary_cta'   => array(
				'text' => __( 'Back to Home', 'starter_theme' ),
				'url'  => home_url( '/' ),
			),
		),
		'500' => array(
			'code'          => '500',
			'eyebrow'       => __( 'Server error', 'starter_theme' ),
			'title'         => __( 'Something went wrong on our end', 'starter_theme' ),
			'desc'          => __( 'We are working to fix the issue. Please try again in a moment or explore the rest of our site.', 'starter_theme' ),
			'context'       => '404',
			'show_search'   => true,
			'search_prompt' => __( 'Search our site', 'starter_theme' ),
			'show_links'    => true,
			'benefits'      => array(
				__( 'Refresh the page in a moment', 'starter_theme' ),
				__( 'Browse shop and services', 'starter_theme' ),
				__( 'Contact us if the problem continues', 'starter_theme' ),
			),
			'primary_cta'   => array(
				'text' => __( 'Back to Home', 'starter_theme' ),
				'url'  => home_url( '/' ),
			),
		),
		'503' => array(
			'code'          => '503',
			'eyebrow'       => __( 'Temporarily unavailable', 'starter_theme' ),
			'title'         => __( 'We will be back shortly', 'starter_theme' ),
			'desc'          => __( 'Our site is undergoing brief maintenance. Thank you for your patience.', 'starter_theme' ),
			'context'       => '404',
			'show_search'   => false,
			'show_links'    => true,
			'benefits'      => array(
				__( 'Check back in a few minutes', 'starter_theme' ),
				__( 'Call us to book by phone', 'starter_theme' ),
				__( 'Visit our downtown studio', 'starter_theme' ),
			),
			'primary_cta'   => array(
				'text' => __( 'Back to Home', 'starter_theme' ),
				'url'  => home_url( '/' ),
			),
		),
		'search' => array(
			'code'          => '',
			'eyebrow'       => __( 'No results', 'starter_theme' ),
			'title'         => __( 'Nothing matched your search', 'starter_theme' ),
			'desc'          => __( 'Try different keywords, or browse our services and boutique instead.', 'starter_theme' ),
			'context'       => 'search',
			'show_search'   => true,
			'search_prompt' => __( 'Try another search', 'starter_theme' ),
			'show_links'    => true,
			'benefits'      => array(),
		),
		'empty' => array(
			'code'          => '',
			'show_search'   => true,
			'search_prompt' => __( 'Search our site', 'starter_theme' ),
			'show_links'    => false,
		),
	);
}

/**
 * Get a single error configuration with optional overrides.
 *
 * @param string               $key       Config key.
 * @param array<string, mixed> $overrides Optional overrides.
 * @return array<string, mixed>
 */
function starter_theme_get_error_config( $key, $overrides = array() ) {
	$configs = starter_theme_error_configs();
	$config  = isset( $configs[ $key ] ) ? $configs[ $key ] : $configs['404'];

	return array_merge( $config, $overrides );
}

/**
 * Quick-link cards for error pages.
 *
 * @return array<int, array{label: string, desc: string, url: string}>
 */
function starter_theme_error_quick_links() {
	$links = array(
		array(
			'label' => __( 'Home', 'starter_theme' ),
			'desc'  => __( 'Return to the homepage', 'starter_theme' ),
			'url'   => home_url( '/' ),
		),
		array(
			'label' => __( 'Services', 'starter_theme' ),
			'desc'  => __( 'Cuts, color, nails, and more', 'starter_theme' ),
			'url'   => starter_theme_services_url(),
		),
	);

	if ( class_exists( 'WooCommerce' ) ) {
		$links[] = array(
			'label' => __( 'Shop', 'starter_theme' ),
			'desc'  => __( 'Browse our boutique picks', 'starter_theme' ),
			'url'   => starter_theme_shop_url(),
		);
	}

	$links[] = array(
		'label' => __( 'Book Now', 'starter_theme' ),
		'desc'  => __( 'Schedule your appointment', 'starter_theme' ),
		'url'   => starter_theme_booking_url(),
	);

	$links[] = array(
		'label' => __( 'Contact', 'starter_theme' ),
		'desc'  => __( 'Call, email, or visit us', 'starter_theme' ),
		'url'   => home_url( '/contact/' ),
	);

	return $links;
}

/**
 * Render the shared error content panel.
 *
 * @param array<string, mixed> $args Panel overrides.
 * @return void
 */
function starter_theme_render_error_content( $args = array() ) {
	get_template_part( 'template-parts/shared/error-content', null, $args );
}

/**
 * Render hero for an error page.
 *
 * @param array<string, mixed> $config Error config.
 * @return void
 */
function starter_theme_render_error_hero( $config ) {
	$image = '';
	if ( ! empty( $config['context'] ) && function_exists( 'starter_theme_page_hero_default_url' ) ) {
		$image = starter_theme_page_hero_default_url( $config['context'] );
	}

	starter_theme_render_page_hero(
		array(
			'eyebrow'       => isset( $config['eyebrow'] ) ? $config['eyebrow'] : '',
			'title'         => isset( $config['title'] ) ? $config['title'] : '',
			'desc'          => isset( $config['desc'] ) ? $config['desc'] : '',
			'image'         => $image,
			'benefits'      => isset( $config['benefits'] ) ? (array) $config['benefits'] : array(),
			'primary_cta'   => isset( $config['primary_cta'] ) ? $config['primary_cta'] : null,
			'secondary_cta' => isset( $config['secondary_cta'] ) ? $config['secondary_cta'] : null,
		)
	);
}

/**
 * Render a full error page body (hero, panel, closing CTA).
 *
 * @param string               $key       Config key.
 * @param array<string, mixed> $overrides Config overrides.
 * @param array<string, mixed> $cta_args  CTA overrides.
 * @return void
 */
function starter_theme_render_error_page( $key, $overrides = array(), $cta_args = array() ) {
	$config = starter_theme_get_error_config( $key, $overrides );

	starter_theme_render_error_hero( $config );
	?>
	<main id="primary" class="site-main error-page">
		<section class="page-section page-section--cream reveal">
			<div class="site-container">
				<?php
				starter_theme_render_error_content(
					array(
						'code'          => isset( $config['code'] ) ? $config['code'] : '',
						'lede'          => isset( $config['lede'] ) ? $config['lede'] : '',
						'show_search'   => ! empty( $config['show_search'] ),
						'search_prompt' => isset( $config['search_prompt'] ) ? $config['search_prompt'] : '',
						'show_links'    => ! empty( $config['show_links'] ),
					)
				);
				?>
			</div>
		</section>

		<?php
		starter_theme_render_page_cta(
			array_merge(
				array(
					'eyebrow'     => __( 'Need a hand?', 'starter_theme' ),
					'title'       => __( 'We are here to help', 'starter_theme' ),
					'desc'        => __( 'Book online, browse the boutique, or reach out — we would love to hear from you.', 'starter_theme' ),
					'button_text' => __( 'Book Now', 'starter_theme' ),
					'button_url'  => starter_theme_booking_url(),
				),
				$cta_args
			)
		);
		?>
	</main>
	<?php
}

/**
 * Human-readable post type label for search results.
 *
 * @param string $post_type Post type slug.
 * @return string
 */
function starter_theme_search_result_type_label( $post_type ) {
	$obj = get_post_type_object( $post_type );

	if ( $obj && ! empty( $obj->labels->singular_name ) ) {
		return $obj->labels->singular_name;
	}

	return ucfirst( str_replace( array( '-', '_' ), ' ', $post_type ) );
}
