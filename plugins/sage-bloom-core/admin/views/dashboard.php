<?php
/**
 * Admin dashboard view.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

$currency = function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '$';
?>
<div class="wrap sbc-admin">
	<h1><?php esc_html_e( 'Sage & Bloom Overview', 'sage-bloom-core' ); ?></h1>
	<p class="description"><?php esc_html_e( 'Backend dashboard for bookings, orders, and test payments.', 'sage-bloom-core' ); ?></p>

	<div class="sbc-kpi-grid">
		<div class="sbc-kpi-card">
			<span class="sbc-kpi-card__label"><?php esc_html_e( "Today's bookings", 'sage-bloom-core' ); ?></span>
			<strong class="sbc-kpi-card__value"><?php echo esc_html( (string) $stats['today_bookings'] ); ?></strong>
		</div>
		<div class="sbc-kpi-card">
			<span class="sbc-kpi-card__label"><?php esc_html_e( 'Pending requests', 'sage-bloom-core' ); ?></span>
			<strong class="sbc-kpi-card__value"><?php echo esc_html( (string) $stats['pending_bookings'] ); ?></strong>
		</div>
		<div class="sbc-kpi-card">
			<span class="sbc-kpi-card__label"><?php esc_html_e( 'Revenue (30 days)', 'sage-bloom-core' ); ?></span>
			<strong class="sbc-kpi-card__value"><?php echo esc_html( $currency . number_format_i18n( $stats['revenue_30d'], 2 ) ); ?></strong>
		</div>
		<div class="sbc-kpi-card">
			<span class="sbc-kpi-card__label"><?php esc_html_e( 'Low stock products', 'sage-bloom-core' ); ?></span>
			<strong class="sbc-kpi-card__value"><?php echo esc_html( (string) $stats['low_stock_count'] ); ?></strong>
		</div>
	</div>

	<div class="sbc-admin-grid">
		<section class="sbc-panel">
			<h2><?php esc_html_e( 'Bookings by status', 'sage-bloom-core' ); ?></h2>
			<ul class="sbc-status-list">
				<?php foreach ( $stats['bookings_by_status'] as $status => $count ) : ?>
					<li>
						<span class="sbc-status sbc-status--<?php echo esc_attr( $status ); ?>"><?php echo esc_html( ucfirst( $status ) ); ?></span>
						<strong><?php echo esc_html( (string) $count ); ?></strong>
					</li>
				<?php endforeach; ?>
			</ul>
			<p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=sbc-bookings' ) ); ?>"><?php esc_html_e( 'Manage bookings', 'sage-bloom-core' ); ?></a></p>
		</section>

		<section class="sbc-panel">
			<h2><?php esc_html_e( 'Recent activity', 'sage-bloom-core' ); ?></h2>
			<?php if ( empty( $recent_activity ) ) : ?>
				<p><?php esc_html_e( 'No activity logged yet.', 'sage-bloom-core' ); ?></p>
			<?php else : ?>
				<ul class="sbc-activity-list">
					<?php foreach ( $recent_activity as $entry ) : ?>
						<li>
							<strong><?php echo esc_html( ucfirst( $entry->entity_type ) . ' #' . $entry->entity_id ); ?></strong>
							<span><?php echo esc_html( str_replace( '_', ' ', $entry->action ) ); ?></span>
							<time><?php echo esc_html( mysql2date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $entry->created_at ) ); ?></time>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</section>
	</div>

	<?php if ( ! empty( $stats['orders_last_30_days'] ) ) : ?>
		<section class="sbc-panel sbc-panel--wide">
			<h2><?php esc_html_e( 'Orders (last 30 days)', 'sage-bloom-core' ); ?></h2>
			<table class="widefat striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Date', 'sage-bloom-core' ); ?></th>
						<th><?php esc_html_e( 'Orders', 'sage-bloom-core' ); ?></th>
						<th><?php esc_html_e( 'Revenue', 'sage-bloom-core' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( array_slice( array_reverse( $stats['orders_last_30_days'] ), 0, 7 ) as $row ) : ?>
						<tr>
							<td><?php echo esc_html( $row['date'] ); ?></td>
							<td><?php echo esc_html( (string) $row['count'] ); ?></td>
							<td><?php echo esc_html( $currency . number_format_i18n( $row['total'], 2 ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</section>
	<?php endif; ?>
</div>
