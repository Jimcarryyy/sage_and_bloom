<?php
/**
 * Login Form
 *
 * @package starter_theme
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_customer_login_form' );

get_template_part( 'template-parts/woocommerce/my-account-guest-intro' );

$registration_enabled = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
?>

<div class="account-auth<?php echo $registration_enabled ? '' : ' account-auth--login-only'; ?>">
	<?php if ( $registration_enabled ) : ?>
		<div class="account-auth__grid u-columns col2-set" id="customer_login">
			<div class="account-auth-card u-column1 col-1">
	<?php else : ?>
		<div class="account-auth-card account-auth-card--solo" id="customer_login">
	<?php endif; ?>

				<header class="account-auth-card__header">
					<h2><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>
					<p class="account-auth-card__lead">
						<?php esc_html_e( 'Welcome back — sign in to view orders, appointments, and saved details.', 'starter_theme' ); ?>
					</p>
				</header>

				<form class="woocommerce-form woocommerce-form-login login account-form account-form--login" method="post" novalidate>
					<?php do_action( 'woocommerce_login_form_start' ); ?>

					<div class="account-form__stack">
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
						</p>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
							<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
						</p>

						<?php do_action( 'woocommerce_login_form' ); ?>
					</div>

					<div class="account-form__meta">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
							<span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
						</label>
					</div>

					<div class="account-form__actions">
						<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
						<button type="submit" class="woocommerce-button button woocommerce-form-login__submit account-form__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>">
							<?php esc_html_e( 'Log in', 'woocommerce' ); ?>
						</button>
					</div>

					<div class="account-form__footer">
						<p class="woocommerce-LostPassword lost_password">
							<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
						</p>
					</div>

					<?php do_action( 'woocommerce_login_form_end' ); ?>
				</form>

	<?php if ( $registration_enabled ) : ?>
			</div>

			<div class="account-auth-card account-auth-card--register u-column2 col-2">
				<header class="account-auth-card__header">
					<h2><?php esc_html_e( 'Register', 'woocommerce' ); ?></h2>
					<p class="account-auth-card__lead">
						<?php esc_html_e( 'Create your member account for faster checkout and appointment tracking.', 'starter_theme' ); ?>
					</p>
				</header>

				<form method="post" class="woocommerce-form woocommerce-form-register register account-form account-form--register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>
					<?php do_action( 'woocommerce_register_form_start' ); ?>

					<div class="account-form__stack">
						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
								<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
							</p>
						<?php endif; ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
							<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
						</p>

						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
								<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
							</p>
						<?php else : ?>
							<p class="account-form__hint">
								<?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?>
							</p>
						<?php endif; ?>

						<?php do_action( 'woocommerce_register_form' ); ?>
					</div>

					<div class="account-form__actions">
						<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
						<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit account-form__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>">
							<?php esc_html_e( 'Register', 'woocommerce' ); ?>
						</button>
					</div>

					<?php do_action( 'woocommerce_register_form_end' ); ?>
				</form>
			</div>
		</div>
	<?php else : ?>
		</div>
	<?php endif; ?>
</div>

<?php
do_action( 'woocommerce_after_customer_login_form' );
