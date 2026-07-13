<?php
/**
 * Admin settings view.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

$settings = get_option( 'sbc_settings', array() );
?>
<div class="wrap sbc-admin">
	<h1><?php esc_html_e( 'Sage & Bloom Settings', 'sage-bloom-core' ); ?></h1>

	<form method="post" action="options.php">
		<?php settings_fields( 'sbc_settings_group' ); ?>

		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="sbc-stripe-pk"><?php esc_html_e( 'Stripe publishable key', 'sage-bloom-core' ); ?></label></th>
				<td>
					<input type="text" class="regular-text" id="sbc-stripe-pk" name="sbc_settings[stripe_publishable_key]" value="<?php echo esc_attr( $settings['stripe_publishable_key'] ?? '' ); ?>" placeholder="pk_test_...">
					<p class="description"><?php esc_html_e( 'Test publishable key from your Stripe dashboard.', 'sage-bloom-core' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="sbc-stripe-sk"><?php esc_html_e( 'Stripe secret key', 'sage-bloom-core' ); ?></label></th>
				<td>
					<input type="password" class="regular-text" id="sbc-stripe-sk" name="sbc_settings[stripe_secret_key]" value="<?php echo esc_attr( $settings['stripe_secret_key'] ?? '' ); ?>" placeholder="sk_test_...">
					<p class="description"><?php esc_html_e( 'Only sk_test_ keys are accepted. You can also define SBC_STRIPE_SECRET_KEY in wp-config.php.', 'sage-bloom-core' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="sbc-stripe-wh"><?php esc_html_e( 'Stripe webhook secret', 'sage-bloom-core' ); ?></label></th>
				<td>
					<input type="password" class="regular-text" id="sbc-stripe-wh" name="sbc_settings[stripe_webhook_secret]" value="<?php echo esc_attr( $settings['stripe_webhook_secret'] ?? '' ); ?>" placeholder="whsec_...">
					<p class="description">
						<?php
						printf(
							/* translators: %s: webhook URL */
							esc_html__( 'Webhook URL: %s', 'sage-bloom-core' ),
							'<code>' . esc_url( rest_url( 'sage-bloom/v1/webhooks/stripe' ) ) . '</code>'
						);
						?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="sbc-slot-duration"><?php esc_html_e( 'Default slot duration (minutes)', 'sage-bloom-core' ); ?></label></th>
				<td>
					<input type="number" min="15" step="15" id="sbc-slot-duration" name="sbc_settings[default_slot_duration]" value="<?php echo esc_attr( $settings['default_slot_duration'] ?? '60' ); ?>">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="sbc-buffer"><?php esc_html_e( 'Buffer between appointments (minutes)', 'sage-bloom-core' ); ?></label></th>
				<td>
					<input type="number" min="0" step="5" id="sbc-buffer" name="sbc_settings[default_buffer_minutes]" value="<?php echo esc_attr( $settings['default_buffer_minutes'] ?? '15' ); ?>">
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>
	</form>

	<section class="sbc-panel">
		<h2><?php esc_html_e( 'Local setup notes', 'sage-bloom-core' ); ?></h2>
		<ul>
			<li><?php esc_html_e( 'Stripe test gateway only appears on localhost / .local domains.', 'sage-bloom-core' ); ?></li>
			<li><?php esc_html_e( 'Use Mailpit or similar to capture booking and order emails locally.', 'sage-bloom-core' ); ?></li>
			<li><?php esc_html_e( 'Forward webhooks with: stripe listen --forward-to http://localhost/wordpress/wp-json/sage-bloom/v1/webhooks/stripe', 'sage-bloom-core' ); ?></li>
		</ul>
	</section>
</div>
