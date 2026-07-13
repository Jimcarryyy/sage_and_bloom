<?php
/**
 * My Account dashboard content.
 *
 * @package starter_theme
 */

$user = wp_get_current_user();
$data = starter_theme_my_account_dashboard_data( $user );

$display_name = $user->first_name ? $user->first_name : $user->display_name;
$orders_url   = wc_get_endpoint_url( 'orders' );
$bookings_url = wc_get_endpoint_url( 'bookings' );
$address_url  = wc_get_endpoint_url( 'edit-address' );
$account_url  = wc_get_endpoint_url( 'edit-account' );
$shop_url     = function_exists( 'starter_theme_shop_url' ) ? starter_theme_shop_url() : wc_get_page_permalink( 'shop' );
$booking_url  = function_exists( 'starter_theme_booking_url' ) ? starter_theme_booking_url() : home_url( '/book/' );
?>

<div class="account-dashboard">
	<div class="account-dashboard__welcome">
		<div class="account-dashboard__welcome-copy">
			<p class="account-dashboard__eyebrow"><?php esc_html_e( 'Your member hub', 'starter_theme' ); ?></p>
			<h2 class="account-dashboard__title">
				<?php
				printf(
					/* translators: %s: customer first name */
					esc_html__( 'Welcome back, %s', 'starter_theme' ),
					esc_html( $display_name )
				);
				?>
			</h2>
			<p class="account-dashboard__lead">
				<?php esc_html_e( 'Manage salon appointments, boutique orders, and account details — all in one place.', 'starter_theme' ); ?>
			</p>
		</div>
		<div class="account-dashboard__welcome-actions">
			<a class="btn btn--primary" href="<?php echo esc_url( $booking_url ); ?>">
				<?php esc_html_e( 'Book appointment', 'starter_theme' ); ?>
			</a>
			<a class="btn btn--outline" href="<?php echo esc_url( $shop_url ); ?>">
				<?php esc_html_e( 'Shop boutique', 'starter_theme' ); ?>
			</a>
		</div>
	</div>

	<div class="account-dashboard__stats">
		<div class="account-stat-card">
			<span class="account-stat-card__value"><?php echo esc_html( (string) $data['orders_count'] ); ?></span>
			<span class="account-stat-card__label"><?php esc_html_e( 'Orders placed', 'starter_theme' ); ?></span>
		</div>
		<div class="account-stat-card">
			<span class="account-stat-card__value"><?php echo esc_html( (string) $data['bookings_count'] ); ?></span>
			<span class="account-stat-card__label"><?php esc_html_e( 'Appointments', 'starter_theme' ); ?></span>
		</div>
		<div class="account-stat-card account-stat-card--accent">
			<span class="account-stat-card__value">15%</span>
			<span class="account-stat-card__label"><?php esc_html_e( 'Member offer: WELCOME15', 'starter_theme' ); ?></span>
		</div>
	</div>

	<div class="account-dashboard__grid">
		<section class="account-panel">
			<div class="account-panel__header">
				<h3 class="account-panel__title"><?php esc_html_e( 'Recent orders', 'starter_theme' ); ?></h3>
				<a class="account-panel__link" href="<?php echo esc_url( $orders_url ); ?>">
					<?php esc_html_e( 'View all', 'starter_theme' ); ?>
				</a>
			</div>

			<?php if ( ! empty( $data['recent_orders'] ) ) : ?>
				<ul class="account-list">
					<?php foreach ( $data['recent_orders'] as $order ) : ?>
						<?php
						if ( ! $order instanceof WC_Order ) {
							continue;
						}
						?>
						<li class="account-list__item">
							<div class="account-list__main">
								<strong><?php printf( esc_html__( 'Order #%s', 'starter_theme' ), esc_html( $order->get_order_number() ) ); ?></strong>
								<span><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span>
							</div>
							<div class="account-list__meta">
								<span class="account-list__amount"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
								<span class="account-status account-status--<?php echo esc_attr( $order->get_status() ); ?>">
									<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
								</span>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<div class="account-empty">
					<p><?php esc_html_e( 'No orders yet — explore our curated boutique picks.', 'starter_theme' ); ?></p>
					<a class="btn btn--secondary btn--sm" href="<?php echo esc_url( $shop_url ); ?>">
						<?php esc_html_e( 'Start shopping', 'starter_theme' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</section>

		<section class="account-panel">
			<div class="account-panel__header">
				<h3 class="account-panel__title"><?php esc_html_e( 'Upcoming appointments', 'starter_theme' ); ?></h3>
				<a class="account-panel__link" href="<?php echo esc_url( $bookings_url ); ?>">
					<?php esc_html_e( 'View all', 'starter_theme' ); ?>
				</a>
			</div>

			<?php if ( ! empty( $data['upcoming_bookings'] ) ) : ?>
				<ul class="account-list">
					<?php foreach ( $data['upcoming_bookings'] as $booking ) : ?>
						<li class="account-list__item">
							<div class="account-list__main">
								<strong><?php echo esc_html( $booking['service_name'] ); ?></strong>
								<span>
									<?php
									echo esc_html(
										sprintf(
											/* translators: 1: date 2: time */
											__( '%1$s at %2$s', 'starter_theme' ),
											$booking['booking_date'],
											$booking['time_label']
										)
									);
									?>
								</span>
							</div>
							<div class="account-list__meta">
								<span class="account-status account-status--<?php echo esc_attr( $booking['status'] ); ?>">
									<?php echo esc_html( ucfirst( $booking['status'] ) ); ?>
								</span>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<div class="account-empty">
					<p><?php esc_html_e( 'No upcoming appointments. Treat yourself to a salon visit.', 'starter_theme' ); ?></p>
					<a class="btn btn--secondary btn--sm" href="<?php echo esc_url( $booking_url ); ?>">
						<?php esc_html_e( 'Book now', 'starter_theme' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</section>
	</div>

	<div class="account-dashboard__quick-links">
		<a class="account-quick-link" href="<?php echo esc_url( $orders_url ); ?>">
			<span class="account-quick-link__title"><?php esc_html_e( 'Order history', 'starter_theme' ); ?></span>
			<span class="account-quick-link__desc"><?php esc_html_e( 'Receipts, status, and reorders', 'starter_theme' ); ?></span>
		</a>
		<a class="account-quick-link" href="<?php echo esc_url( $bookings_url ); ?>">
			<span class="account-quick-link__title"><?php esc_html_e( 'Appointments', 'starter_theme' ); ?></span>
			<span class="account-quick-link__desc"><?php esc_html_e( 'Salon booking history', 'starter_theme' ); ?></span>
		</a>
		<a class="account-quick-link" href="<?php echo esc_url( $address_url ); ?>">
			<span class="account-quick-link__title"><?php esc_html_e( 'Addresses', 'starter_theme' ); ?></span>
			<span class="account-quick-link__desc"><?php esc_html_e( 'Billing and shipping details', 'starter_theme' ); ?></span>
		</a>
		<a class="account-quick-link" href="<?php echo esc_url( $account_url ); ?>">
			<span class="account-quick-link__title"><?php esc_html_e( 'Profile & password', 'starter_theme' ); ?></span>
			<span class="account-quick-link__desc"><?php esc_html_e( 'Update your account info', 'starter_theme' ); ?></span>
		</a>
	</div>

	<div class="account-dashboard__perks">
		<h3 class="account-dashboard__perks-title"><?php esc_html_e( 'Member perks', 'starter_theme' ); ?></h3>
		<ul class="account-perks">
			<li><?php esc_html_e( 'Studio pickup for boutique orders', 'starter_theme' ); ?></li>
			<li><?php esc_html_e( 'Priority booking reminders by email', 'starter_theme' ); ?></li>
			<li><?php esc_html_e( 'Exclusive promo codes at checkout', 'starter_theme' ); ?></li>
			<li><?php esc_html_e( 'Salon-quality product recommendations', 'starter_theme' ); ?></li>
		</ul>
	</div>
</div>
