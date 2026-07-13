<?php
/**
 * REST-driven booking form powered by Sage & Bloom Core.
 *
 * @package starter_theme
 */

if ( ! class_exists( 'SBC_Booking_Service' ) ) {
	get_template_part( 'template-parts/forms/booking-fallback' );
	return;
}

$services = get_posts(
	array(
		'post_type'      => 'service',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	)
);
?>

<div class="booking-app" data-booking-app>
	<div class="form-notice form-notice--success" data-booking-notice hidden></div>

	<form class="booking-form form-grid" data-booking-form novalidate>
		<?php if ( ! empty( $services ) ) : ?>
			<div class="form-field form-field--full">
				<label for="booking-service"><?php esc_html_e( 'Service', 'starter_theme' ); ?> <span aria-hidden="true">*</span></label>
				<select id="booking-service" data-booking-service required>
					<option value=""><?php esc_html_e( 'Select a service', 'starter_theme' ); ?></option>
					<?php foreach ( $services as $service ) : ?>
						<option value="<?php echo esc_attr( (string) $service->ID ); ?>"><?php echo esc_html( $service->post_title ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>

		<div class="form-field">
			<label for="booking-date"><?php esc_html_e( 'Preferred Date', 'starter_theme' ); ?> <span aria-hidden="true">*</span></label>
			<input type="date" id="booking-date" data-booking-date required>
		</div>

		<div class="form-field form-field--full booking-slots" data-booking-slots hidden>
			<label><?php esc_html_e( 'Available Times', 'starter_theme' ); ?> <span aria-hidden="true">*</span></label>
			<div class="booking-slots__list" data-booking-slots-list role="listbox" aria-label="<?php esc_attr_e( 'Available appointment times', 'starter_theme' ); ?>"></div>
			<input type="hidden" data-booking-time value="">
		</div>

		<div class="form-field">
			<label for="booking-name"><?php esc_html_e( 'Name', 'starter_theme' ); ?> <span aria-hidden="true">*</span></label>
			<input type="text" id="booking-name" name="customer_name" required autocomplete="name">
		</div>

		<div class="form-field">
			<label for="booking-email"><?php esc_html_e( 'Email', 'starter_theme' ); ?> <span aria-hidden="true">*</span></label>
			<input type="email" id="booking-email" name="customer_email" required autocomplete="email">
		</div>

		<div class="form-field">
			<label for="booking-phone"><?php esc_html_e( 'Phone', 'starter_theme' ); ?></label>
			<input type="tel" id="booking-phone" name="customer_phone" autocomplete="tel">
		</div>

		<div class="form-field form-field--full">
			<label for="booking-message"><?php esc_html_e( 'Notes', 'starter_theme' ); ?></label>
			<textarea id="booking-message" name="message" rows="4"></textarea>
		</div>

		<div class="form-field form-field--full">
			<button type="submit" class="form-submit" data-booking-submit><?php esc_html_e( 'Request Appointment', 'starter_theme' ); ?></button>
		</div>
	</form>
</div>

<p class="form-note"><?php esc_html_e( 'Choose a service and date to see live availability. We will confirm your appointment by email.', 'starter_theme' ); ?></p>
