<?php
/**
 * Admin bookings view.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap sbc-admin">
	<h1><?php esc_html_e( 'Bookings', 'sage-bloom-core' ); ?></h1>

	<?php if ( ! empty( $_GET['updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Booking updated.', 'sage-bloom-core' ); ?></p></div>
	<?php endif; ?>

	<nav class="nav-tab-wrapper">
		<a class="nav-tab <?php echo 'list' === $view ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=sbc-bookings&view=list' ) ); ?>"><?php esc_html_e( 'Inbox', 'sage-bloom-core' ); ?></a>
		<a class="nav-tab <?php echo 'calendar' === $view ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=sbc-bookings&view=calendar' ) ); ?>"><?php esc_html_e( 'Calendar', 'sage-bloom-core' ); ?></a>
	</nav>

	<div class="sbc-filters">
		<?php foreach ( array_merge( array( '' => __( 'All', 'sage-bloom-core' ) ), array_combine( sbc_booking_statuses(), array_map( 'ucfirst', sbc_booking_statuses() ) ) ) as $key => $label ) : ?>
			<a class="button <?php echo $status_filter === $key ? 'button-primary' : ''; ?>" href="<?php echo esc_url( add_query_arg( array( 'page' => 'sbc-bookings', 'status' => $key, 'view' => $view ), admin_url( 'admin.php' ) ) ); ?>"><?php echo esc_html( $label ); ?></a>
		<?php endforeach; ?>
	</div>

	<?php if ( 'calendar' === $view ) : ?>
		<section class="sbc-panel sbc-panel--wide">
			<h2><?php esc_html_e( 'Upcoming appointments', 'sage-bloom-core' ); ?></h2>
			<?php if ( empty( $bookings ) ) : ?>
				<p><?php esc_html_e( 'No bookings found.', 'sage-bloom-core' ); ?></p>
			<?php else : ?>
				<div class="sbc-calendar">
					<?php
					$grouped = array();
					foreach ( $bookings as $booking ) {
						$grouped[ $booking->booking_date ][] = $booking;
					}
					ksort( $grouped );
					foreach ( $grouped as $date => $day_bookings ) :
						?>
						<div class="sbc-calendar-day">
							<h3><?php echo esc_html( mysql2date( get_option( 'date_format' ), $date ) ); ?></h3>
							<ul>
								<?php foreach ( $day_bookings as $booking ) : ?>
									<?php $row = SBC_Booking_Service::format( $booking ); ?>
									<li>
										<span class="sbc-status sbc-status--<?php echo esc_attr( $row['status'] ); ?>"><?php echo esc_html( $row['time_label'] ); ?></span>
										<strong><?php echo esc_html( $row['service_name'] ); ?></strong>
										<?php echo esc_html( $row['customer_name'] ); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</section>
	<?php else : ?>
		<table class="widefat striped sbc-bookings-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Customer', 'sage-bloom-core' ); ?></th>
					<th><?php esc_html_e( 'Service', 'sage-bloom-core' ); ?></th>
					<th><?php esc_html_e( 'Date / Time', 'sage-bloom-core' ); ?></th>
					<th><?php esc_html_e( 'Status', 'sage-bloom-core' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'sage-bloom-core' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $bookings ) ) : ?>
					<tr><td colspan="5"><?php esc_html_e( 'No bookings found.', 'sage-bloom-core' ); ?></td></tr>
				<?php else : ?>
					<?php foreach ( $bookings as $booking ) : ?>
						<?php $row = SBC_Booking_Service::format( $booking ); ?>
						<tr>
							<td>
								<strong><?php echo esc_html( $row['customer_name'] ); ?></strong><br>
								<a href="mailto:<?php echo esc_attr( $row['customer_email'] ); ?>"><?php echo esc_html( $row['customer_email'] ); ?></a>
								<?php if ( $row['customer_phone'] ) : ?>
									<br><?php echo esc_html( $row['customer_phone'] ); ?>
								<?php endif; ?>
							</td>
							<td><?php echo esc_html( $row['service_name'] ); ?></td>
							<td><?php echo esc_html( $row['booking_date'] . ' · ' . $row['time_label'] ); ?></td>
							<td><span class="sbc-status sbc-status--<?php echo esc_attr( $row['status'] ); ?>"><?php echo esc_html( ucfirst( $row['status'] ) ); ?></span></td>
							<td>
								<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="sbc-inline-form">
									<?php wp_nonce_field( 'sbc_update_booking' ); ?>
									<input type="hidden" name="action" value="sbc_update_booking">
									<input type="hidden" name="booking_id" value="<?php echo esc_attr( (string) $row['id'] ); ?>">
									<select name="status">
										<?php foreach ( sbc_booking_statuses() as $status ) : ?>
											<option value="<?php echo esc_attr( $status ); ?>" <?php selected( $row['status'], $status ); ?>><?php echo esc_html( ucfirst( $status ) ); ?></option>
										<?php endforeach; ?>
									</select>
									<?php if ( ! empty( $team ) ) : ?>
										<select name="staff_id">
											<option value="0"><?php esc_html_e( 'Assign staff', 'sage-bloom-core' ); ?></option>
											<?php foreach ( $team as $member ) : ?>
												<option value="<?php echo esc_attr( (string) $member->ID ); ?>" <?php selected( $row['staff_id'], $member->ID ); ?>><?php echo esc_html( $member->post_title ); ?></option>
											<?php endforeach; ?>
										</select>
									<?php endif; ?>
									<button type="submit" class="button button-small"><?php esc_html_e( 'Update', 'sage-bloom-core' ); ?></button>
								</form>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
