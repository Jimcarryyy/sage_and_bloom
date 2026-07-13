<?php
/**
 * REST API endpoints.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * REST API controller.
 */
class SBC_REST_API {

	const NAMESPACE = 'sage-bloom/v1';

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
	}

	/**
	 * Register routes.
	 *
	 * @return void
	 */
	public static function register_routes() {
		register_rest_route(
			self::NAMESPACE,
			'/services',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( __CLASS__, 'get_services' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/testimonials',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( __CLASS__, 'get_testimonials' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/business',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( __CLASS__, 'get_business' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/availability',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( __CLASS__, 'get_availability' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'service_id' => array(
						'required'          => true,
						'validate_callback' => static function ( $value ) {
							return absint( $value ) > 0;
						},
					),
					'date'       => array(
						'required'          => true,
						'validate_callback' => static function ( $value ) {
							return (bool) preg_match( '/^\d{4}-\d{2}-\d{2}$/', (string) $value );
						},
					),
				),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/bookings',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( __CLASS__, 'create_booking' ),
					'permission_callback' => array( __CLASS__, 'verify_public_nonce' ),
				),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/bookings/(?P<id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( __CLASS__, 'get_booking' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'token' => array(
						'required' => true,
					),
				),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/admin/bookings',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( __CLASS__, 'admin_get_bookings' ),
					'permission_callback' => array( __CLASS__, 'admin_permission' ),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( __CLASS__, 'admin_update_booking' ),
					'permission_callback' => array( __CLASS__, 'admin_permission' ),
				),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/admin/stats',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( __CLASS__, 'admin_stats' ),
				'permission_callback' => array( __CLASS__, 'admin_permission' ),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/admin/availability',
			array(
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => array( __CLASS__, 'admin_update_availability' ),
				'permission_callback' => array( __CLASS__, 'admin_permission' ),
			)
		);
	}

	/**
	 * Verify REST nonce for public write endpoints.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return bool
	 */
	public static function verify_public_nonce( $request ) {
		$nonce = $request->get_header( 'X-WP-Nonce' );
		return (bool) wp_verify_nonce( $nonce, 'wp_rest' );
	}

	/**
	 * Admin permission check.
	 *
	 * @return bool
	 */
	public static function admin_permission() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * GET /services
	 *
	 * @return WP_REST_Response
	 */
	public static function get_services() {
		$posts = get_posts(
			array(
				'post_type'      => 'service',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order title',
				'order'          => 'ASC',
			)
		);

		$services = array();
		foreach ( $posts as $post ) {
			$terms = wp_get_post_terms( $post->ID, 'service_category', array( 'fields' => 'all' ) );
			$services[] = array(
				'id'          => $post->ID,
				'title'       => $post->post_title,
				'excerpt'     => get_the_excerpt( $post ),
				'permalink'   => get_permalink( $post ),
				'price'       => function_exists( 'get_field' ) ? get_field( 'service_price', $post->ID ) : '',
				'duration'    => function_exists( 'get_field' ) ? get_field( 'service_duration', $post->ID ) : '',
				'duration_minutes' => SBC_Availability::service_duration( $post->ID ),
				'categories'  => array_map(
					static function ( $term ) {
						return array(
							'id'   => $term->term_id,
							'name' => $term->name,
							'slug' => $term->slug,
						);
					},
					is_array( $terms ) ? $terms : array()
				),
			);
		}

		return rest_ensure_response( array( 'services' => $services ) );
	}

	/**
	 * GET /testimonials
	 *
	 * @return WP_REST_Response
	 */
	public static function get_testimonials() {
		$posts = get_posts(
			array(
				'post_type'      => 'testimonial',
				'posts_per_page' => 10,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);

		$items = array();
		foreach ( $posts as $post ) {
			$items[] = array(
				'id'     => $post->ID,
				'client' => function_exists( 'get_field' ) ? get_field( 'testimonial_client', $post->ID ) : $post->post_title,
				'role'   => function_exists( 'get_field' ) ? get_field( 'testimonial_role', $post->ID ) : '',
				'quote'  => function_exists( 'get_field' ) ? get_field( 'testimonial_quote', $post->ID ) : '',
				'rating' => (int) ( function_exists( 'get_field' ) ? get_field( 'testimonial_rating', $post->ID ) : 5 ),
			);
		}

		return rest_ensure_response( array( 'testimonials' => $items ) );
	}

	/**
	 * GET /business
	 *
	 * @return WP_REST_Response
	 */
	public static function get_business() {
		return rest_ensure_response(
			array(
				'name'    => sbc_business_option( 'business_name', get_bloginfo( 'name' ) ),
				'phone'   => sbc_business_option( 'business_phone', '' ),
				'email'   => sbc_business_option( 'business_email', get_option( 'admin_email' ) ),
				'address' => sbc_business_option( 'business_address', '' ),
				'hours'   => sbc_business_option( 'business_hours', array() ),
			)
		);
	}

	/**
	 * GET /availability
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public static function get_availability( $request ) {
		$service_id = absint( $request['service_id'] );
		$date       = sanitize_text_field( $request['date'] );

		return rest_ensure_response(
			array(
				'service_id' => $service_id,
				'date'       => $date,
				'slots'      => SBC_Availability::get_slots( $service_id, $date ),
			)
		);
	}

	/**
	 * POST /bookings
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response|WP_Error
	 */
	public static function create_booking( $request ) {
		$result = SBC_Booking_Service::create( $request->get_json_params() ?: $request->get_params() );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		$booking = SBC_Booking_Service::get( $result );

		return rest_ensure_response(
			array(
				'booking' => SBC_Booking_Service::format( $booking, true ),
				'message' => __( 'Your appointment request has been submitted.', 'sage-bloom-core' ),
			)
		);
	}

	/**
	 * GET /bookings/{id}
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response|WP_Error
	 */
	public static function get_booking( $request ) {
		$booking = SBC_Booking_Service::get_by_token( absint( $request['id'] ), sanitize_text_field( $request['token'] ) );

		if ( ! $booking ) {
			return new WP_Error( 'not_found', __( 'Booking not found.', 'sage-bloom-core' ), array( 'status' => 404 ) );
		}

		return rest_ensure_response( array( 'booking' => SBC_Booking_Service::format( $booking ) ) );
	}

	/**
	 * GET /admin/bookings
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public static function admin_get_bookings( $request ) {
		$bookings = SBC_Booking_Service::query(
			array(
				'status'    => $request->get_param( 'status' ),
				'date_from' => $request->get_param( 'date_from' ),
				'date_to'   => $request->get_param( 'date_to' ),
				'limit'     => absint( $request->get_param( 'limit' ) ?: 100 ),
			)
		);

		return rest_ensure_response(
			array(
				'bookings' => array_map(
					static function ( $booking ) {
						return SBC_Booking_Service::format( $booking );
					},
					$bookings
				),
			)
		);
	}

	/**
	 * PATCH /admin/bookings
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response|WP_Error
	 */
	public static function admin_update_booking( $request ) {
		$id     = absint( $request->get_param( 'id' ) );
		$status = sanitize_key( $request->get_param( 'status' ) );
		$staff  = absint( $request->get_param( 'staff_id' ) );

		if ( ! $id ) {
			return new WP_Error( 'missing_id', __( 'Booking ID is required.', 'sage-bloom-core' ), array( 'status' => 400 ) );
		}

		$result = SBC_Booking_Service::update_status( $id, $status, $staff );
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		$booking = SBC_Booking_Service::get( $id );

		return rest_ensure_response( array( 'booking' => SBC_Booking_Service::format( $booking ) ) );
	}

	/**
	 * GET /admin/stats
	 *
	 * @return WP_REST_Response
	 */
	public static function admin_stats() {
		return rest_ensure_response( SBC_Stats::get_dashboard_stats() );
	}

	/**
	 * PUT /admin/availability
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public static function admin_update_availability( $request ) {
		$rules = $request->get_param( 'rules' );
		if ( is_array( $rules ) ) {
			SBC_Availability::update_rules( $rules );
		}

		return rest_ensure_response(
			array(
				'rules' => SBC_Availability::get_rules(),
			)
		);
	}
}
