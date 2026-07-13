<?php
/**
 * Template Name: Contact
 * Template for the Contact page.
 *
 * @package starter_theme
 */

get_header();

while ( have_posts() ) :
	the_post();

	$intro = get_field( 'contact_intro' );
	$intro = $intro ? wp_strip_all_tags( (string) $intro ) : __( 'Visit our studio, give us a call, or send a message anytime.', 'starter_theme' );

	starter_theme_render_page_hero(
		array(
			'eyebrow'  => __( 'Get in Touch', 'starter_theme' ),
			'title'    => get_the_title(),
			'desc'     => $intro,
			'image'    => starter_theme_page_hero_image_url( get_the_ID() ),
			'benefits' => array(
				__( 'Same-day replies on messages', 'starter_theme' ),
				__( 'Walk-ins welcome when available', 'starter_theme' ),
				__( 'Free parking downtown', 'starter_theme' ),
				__( 'Gift cards & boutique pickup', 'starter_theme' ),
			),
		)
	);

	$phone   = starter_theme_business_option( 'business_phone', '' );
	$email   = starter_theme_business_option( 'business_email', '' );
	$address = starter_theme_business_option( 'business_address', '' );
	?>

	<main id="primary" class="site-main contact-page">

		<section class="page-section page-section--cream reveal">
			<div class="site-container site-container--narrow">
				<div class="contact-layout">
					<aside class="contact-sidebar" aria-label="<?php esc_attr_e( 'Contact information', 'starter_theme' ); ?>">
						<div class="contact-cards">
							<?php if ( $address ) : ?>
								<article class="info-card">
									<span class="info-card__label"><?php esc_html_e( 'Visit Us', 'starter_theme' ); ?></span>
									<p class="info-card__value"><?php echo nl2br( esc_html( $address ) ); ?></p>
								</article>
							<?php endif; ?>

							<?php if ( $phone ) : ?>
								<article class="info-card">
									<span class="info-card__label"><?php esc_html_e( 'Call', 'starter_theme' ); ?></span>
									<p class="info-card__value">
										<a href="tel:<?php echo esc_attr( starter_theme_business_phone_link() ); ?>"><?php echo esc_html( $phone ); ?></a>
									</p>
								</article>
							<?php endif; ?>

							<?php if ( $email ) : ?>
								<article class="info-card">
									<span class="info-card__label"><?php esc_html_e( 'Email', 'starter_theme' ); ?></span>
									<p class="info-card__value">
										<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
									</p>
								</article>
							<?php endif; ?>

							<article class="info-card">
								<span class="info-card__label"><?php esc_html_e( 'Hours', 'starter_theme' ); ?></span>
								<div class="info-card__value info-card__value--hours"><?php starter_theme_render_hours(); ?></div>
							</article>
						</div>

						<div class="contact-cards__social">
							<?php starter_theme_render_social_links(); ?>
						</div>
					</aside>

					<div class="contact-form-panel">
						<?php
						get_template_part(
							'template-parts/shared/section-header',
							null,
							array(
								'eyebrow' => __( 'Send a Message', 'starter_theme' ),
								'title'   => __( 'We\'d love to hear from you', 'starter_theme' ),
								'desc'    => __( 'Fill out the form and we\'ll get back to you within one business day.', 'starter_theme' ),
								'align'   => 'left',
							)
						);

						if ( get_the_content() ) {
							echo '<div class="entry-content contact-form-panel__intro">';
							the_content();
							echo '</div>';
						}

						$form_shortcode = get_field( 'contact_form_shortcode' );
						if ( $form_shortcode ) {
							echo do_shortcode( $form_shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							get_template_part( 'template-parts/forms/contact-fallback' );
						}
						?>
					</div>
				</div>
			</div>
		</section>

		<?php get_template_part( 'template-parts/contact/map' ); ?>

		<?php
		starter_theme_render_page_cta(
			array(
				'eyebrow' => __( 'Prefer to call?', 'starter_theme' ),
				'title'   => __( 'We\'re here when you need us', 'starter_theme' ),
				'desc'    => __( 'Book online for the fastest confirmation, or call during business hours.', 'starter_theme' ),
			)
		);
		?>

	</main>

	<?php
endwhile;

get_footer();
