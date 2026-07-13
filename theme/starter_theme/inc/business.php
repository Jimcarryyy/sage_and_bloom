<?php
/**
 * Business settings helpers (ACF Options).
 *
 * @package starter_theme
 */

/**
 * Get a business option field value.
 *
 * @param string $key     Field name.
 * @param mixed  $default Default when empty.
 * @return mixed
 */
function starter_theme_business_option( $key, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}

	$targets = array();

	if ( function_exists( 'starter_theme_business_settings_target' ) ) {
		$targets[] = starter_theme_business_settings_target();
	}

	$targets[] = 'option';

	if ( function_exists( 'starter_theme_business_settings_page_id' ) ) {
		$page_id = starter_theme_business_settings_page_id();
		if ( $page_id ) {
			$targets[] = $page_id;
		}
	}

	$targets = array_unique( array_filter( $targets ) );

	foreach ( $targets as $target ) {
		$value = get_field( $key, $target );

		if ( null !== $value && '' !== $value && ! ( is_array( $value ) && empty( $value ) ) ) {
			return $value;
		}
	}

	return $default;
}

/**
 * Get formatted business phone for tel: links.
 *
 * @return string
 */
function starter_theme_business_phone_link() {
	$phone = starter_theme_business_option( 'business_phone', '' );
	return preg_replace( '/[^0-9+]/', '', $phone );
}

/**
 * Get URL for the booking page.
 *
 * @return string
 */
function starter_theme_booking_url() {
	$page = get_page_by_path( 'book-now', OBJECT, 'page' );
	if ( $page ) {
		return get_permalink( $page );
	}
	return home_url( '/book-now/' );
}

/**
 * Get URL for the shop page.
 *
 * @return string
 */
function starter_theme_shop_url() {
	if ( function_exists( 'wc_get_page_id' ) ) {
		$shop_id = wc_get_page_id( 'shop' );
		if ( $shop_id > 0 ) {
			return get_permalink( $shop_id );
		}
	}
	return home_url( '/shop/' );
}

/**
 * Get URL for services archive.
 *
 * @return string
 */
function starter_theme_services_url() {
	return get_post_type_archive_link( 'service' ) ?: home_url( '/services/' );
}

/**
 * Default About page values when ACF repeater is empty.
 *
 * @return array<int, array{title: string, text: string}>
 */
function starter_theme_about_values_fallback() {
	return array(
		array(
			'title' => __( 'Quality', 'starter_theme' ),
			'text'  => __( 'Expert services with premium products and ongoing training.', 'starter_theme' ),
		),
		array(
			'title' => __( 'Community', 'starter_theme' ),
			'text'  => __( 'A warm downtown space where neighbors connect and feel at home.', 'starter_theme' ),
		),
		array(
			'title' => __( 'Care', 'starter_theme' ),
			'text'  => __( 'Personalized attention and thoughtful recommendations for every client.', 'starter_theme' ),
		),
	);
}

/**
 * Render opening hours from ACF repeater.
 *
 * @return void
 */
function starter_theme_render_hours() {
	$hours = starter_theme_business_option( 'business_hours', array() );

	if ( empty( $hours ) ) {
		$weekdays = starter_theme_business_option( 'hours_weekdays', '9:00 AM – 7:00 PM' );
		$saturday = starter_theme_business_option( 'hours_saturday', '10:00 AM – 5:00 PM' );
		$sunday   = starter_theme_business_option( 'hours_sunday', '' );
		$closed   = starter_theme_business_option( 'hours_sunday_closed', true );

		$hours = array(
			array(
				'day'    => 'Mon – Fri',
				'hours'  => $weekdays,
				'closed' => empty( $weekdays ),
			),
			array(
				'day'    => 'Saturday',
				'hours'  => $saturday,
				'closed' => empty( $saturday ),
			),
			array(
				'day'    => 'Sunday',
				'hours'  => $sunday,
				'closed' => (bool) $closed,
			),
		);
	}

	echo '<ul class="hours-list">';
	foreach ( $hours as $row ) {
		$day    = isset( $row['day'] ) ? $row['day'] : '';
		$time   = isset( $row['hours'] ) ? $row['hours'] : '';
		$closed = ! empty( $row['closed'] );

		if ( empty( $day ) ) {
			continue;
		}

		printf(
			'<li class="hours-list__item%s"><span class="hours-list__day">%s</span><span class="hours-list__time">%s</span></li>',
			$closed ? ' hours-list__item--closed' : '',
			esc_html( $day ),
			esc_html( $closed ? __( 'Closed', 'starter_theme' ) : $time )
		);
	}
	echo '</ul>';
}

/**
 * Render social links from ACF repeater.
 *
 * @return void
 */
function starter_theme_render_social_links() {
	$social = starter_theme_business_option( 'social_links', array() );

	if ( empty( $social ) ) {
		$instagram = starter_theme_business_option( 'social_instagram', '' );
		$facebook  = starter_theme_business_option( 'social_facebook', '' );

		$social = array();

		if ( $instagram ) {
			$social[] = array(
				'label' => 'Instagram',
				'url'   => $instagram,
			);
		}

		if ( $facebook ) {
			$social[] = array(
				'label' => 'Facebook',
				'url'   => $facebook,
			);
		}
	}

	if ( empty( $social ) ) {
		return;
	}

	echo '<ul class="social-links">';
	foreach ( $social as $link ) {
		$url   = isset( $link['url'] ) ? $link['url'] : '';
		$label = isset( $link['label'] ) ? $link['label'] : '';

		if ( empty( $url ) ) {
			continue;
		}

		printf(
			'<li><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></li>',
			esc_url( $url ),
			esc_html( $label ?: $url )
		);
	}
	echo '</ul>';
}
