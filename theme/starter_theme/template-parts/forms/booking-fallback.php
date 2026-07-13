<?php
/**
 * Fallback booking form when CF7 is not configured.
 *
 * @package starter_theme
 */

$services = get_posts(
	array(
		'post_type'      => 'service',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	)
);
?>

<form class="booking-form form-grid" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
	<input type="hidden" name="action" value="starter_theme_booking">
	<?php wp_nonce_field( 'starter_theme_booking', 'booking_nonce' ); ?>

	<div class="form-field">
		<label for="booking-name"><?php esc_html_e( 'Name', 'starter_theme' ); ?></label>
		<input type="text" id="booking-name" name="booking_name" required>
	</div>

	<div class="form-field">
		<label for="booking-email"><?php esc_html_e( 'Email', 'starter_theme' ); ?></label>
		<input type="email" id="booking-email" name="booking_email" required>
	</div>

	<div class="form-field">
		<label for="booking-phone"><?php esc_html_e( 'Phone', 'starter_theme' ); ?></label>
		<input type="tel" id="booking-phone" name="booking_phone">
	</div>

	<div class="form-field">
		<label for="booking-date"><?php esc_html_e( 'Preferred Date', 'starter_theme' ); ?></label>
		<input type="date" id="booking-date" name="booking_date">
	</div>

	<?php if ( ! empty( $services ) ) : ?>
		<div class="form-field form-field--full">
			<label for="booking-service"><?php esc_html_e( 'Service', 'starter_theme' ); ?></label>
			<select id="booking-service" name="booking_service">
				<option value=""><?php esc_html_e( 'Select a service', 'starter_theme' ); ?></option>
				<?php foreach ( $services as $service ) : ?>
					<option value="<?php echo esc_attr( $service->post_title ); ?>"><?php echo esc_html( $service->post_title ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	<?php endif; ?>

	<div class="form-field form-field--full">
		<label for="booking-message"><?php esc_html_e( 'Message', 'starter_theme' ); ?></label>
		<textarea id="booking-message" name="booking_message" rows="4"></textarea>
	</div>

	<div class="form-field form-field--full">
		<button type="submit" class="form-submit"><?php esc_html_e( 'Request Appointment', 'starter_theme' ); ?></button>
	</div>
</form>

<p class="form-note"><?php esc_html_e( 'Install Contact Form 7 and add a shortcode in the Booking page settings for production use.', 'starter_theme' ); ?></p>
