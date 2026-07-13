<?php
/**
 * Fallback contact form when CF7 is not configured.
 *
 * @package starter_theme
 */

?>

<form class="contact-form form-grid" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
	<input type="hidden" name="action" value="starter_theme_contact">
	<?php wp_nonce_field( 'starter_theme_contact', 'contact_nonce' ); ?>

	<div class="form-field">
		<label for="contact-name"><?php esc_html_e( 'Name', 'starter_theme' ); ?></label>
		<input type="text" id="contact-name" name="contact_name" required>
	</div>

	<div class="form-field">
		<label for="contact-email"><?php esc_html_e( 'Email', 'starter_theme' ); ?></label>
		<input type="email" id="contact-email" name="contact_email" required>
	</div>

	<div class="form-field form-field--full">
		<label for="contact-subject"><?php esc_html_e( 'Subject', 'starter_theme' ); ?></label>
		<input type="text" id="contact-subject" name="contact_subject">
	</div>

	<div class="form-field form-field--full">
		<label for="contact-message"><?php esc_html_e( 'Message', 'starter_theme' ); ?></label>
		<textarea id="contact-message" name="contact_message" rows="5" required></textarea>
	</div>

	<div class="form-field form-field--full">
		<button type="submit" class="form-submit"><?php esc_html_e( 'Send Message', 'starter_theme' ); ?></button>
	</div>
</form>

<p class="form-note"><?php esc_html_e( 'Install Contact Form 7 and add a shortcode in the Contact page settings for production use.', 'starter_theme' ); ?></p>
