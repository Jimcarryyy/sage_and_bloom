<?php
/**
 * Rich single service detail layout.
 *
 * @package starter_theme
 *
 * @var WP_Post $post Current service post.
 */

$post_id   = get_the_ID();
$price     = get_field( 'service_price' );
$duration  = get_field( 'service_duration' );
$short     = get_field( 'service_short_desc' );
$includes  = starter_theme_service_includes( $post_id );
$highlights = starter_theme_service_highlights( $post_id );
$note      = starter_theme_service_booking_note( $post_id );
$related   = starter_theme_related_services( $post_id, 3 );
$terms     = get_the_terms( $post_id, 'service_category' );
$category  = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
?>

<section class="page-section page-section--cream service-detail-section reveal">
	<div class="site-container">
		<div class="service-detail">
			<div class="service-detail__main">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="service-detail__media">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>

				<div class="service-detail__content-block">
					<?php
					get_template_part(
						'template-parts/shared/section-header',
						null,
						array(
							'eyebrow' => $category ? $category : __( 'Service Overview', 'starter_theme' ),
							'title'   => __( 'About this service', 'starter_theme' ),
							'desc'    => $short ? wp_strip_all_tags( (string) $short ) : '',
							'align'   => 'left',
						)
					);
					?>

					<div class="service-detail__content entry-content">
						<?php
						if ( get_the_content() ) {
							the_content();
						} else {
							echo '<p>' . esc_html( $short ? wp_strip_all_tags( (string) $short ) : __( 'Our team delivers expert care tailored to your goals — with premium products and a warm, welcoming experience every visit.', 'starter_theme' ) ) . '</p>';
						}
						?>
					</div>
				</div>

				<?php if ( ! empty( $includes ) ) : ?>
					<div class="service-detail__block">
						<h3 class="service-detail__block-title"><?php esc_html_e( 'What\'s included', 'starter_theme' ); ?></h3>
						<?php
						get_template_part(
							'template-parts/shared/benefit-list',
							null,
							array(
								'items'    => $includes,
								'modifier' => 'about',
							)
						);
						?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $highlights ) ) : ?>
					<div class="service-detail__block service-detail__block--muted">
						<h3 class="service-detail__block-title"><?php esc_html_e( 'Before your visit', 'starter_theme' ); ?></h3>
						<?php
						get_template_part(
							'template-parts/shared/benefit-list',
							null,
							array(
								'items'    => $highlights,
								'modifier' => 'about',
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>

			<aside class="service-detail__aside">
				<div class="service-booking-card">
					<span class="service-booking-card__label"><?php esc_html_e( 'Book this service', 'starter_theme' ); ?></span>

					<?php if ( $price || $duration ) : ?>
						<div class="service-booking-card__meta">
							<?php if ( $price ) : ?>
								<p class="service-booking-card__price"><?php echo esc_html( $price ); ?></p>
							<?php endif; ?>
							<?php if ( $duration ) : ?>
								<p class="service-booking-card__duration"><?php echo esc_html( $duration ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<p class="service-booking-card__text">
						<?php esc_html_e( 'Reserve online or call our front desk — walk-ins welcome when available.', 'starter_theme' ); ?>
					</p>

					<div class="service-booking-card__actions">
						<a class="btn btn--primary btn--lg" href="<?php echo esc_url( starter_theme_booking_url() ); ?>">
							<?php esc_html_e( 'Book Appointment', 'starter_theme' ); ?>
						</a>
						<?php
						$phone = starter_theme_business_option( 'business_phone', '' );
						if ( $phone ) :
							?>
							<a class="btn btn--outline" href="tel:<?php echo esc_attr( starter_theme_business_phone_link() ); ?>">
								<?php echo esc_html( $phone ); ?>
							</a>
						<?php endif; ?>
					</div>

					<?php if ( $note ) : ?>
						<p class="service-booking-card__note"><?php echo esc_html( $note ); ?></p>
					<?php endif; ?>
				</div>
			</aside>
		</div>
	</div>
</section>

<?php if ( ! empty( $related ) ) : ?>
	<section class="page-section service-related reveal">
		<div class="site-container">
			<?php
			get_template_part(
				'template-parts/shared/section-header',
				null,
				array(
					'eyebrow' => __( 'You may also like', 'starter_theme' ),
					'title'   => __( 'Related Services', 'starter_theme' ),
					'desc'    => __( 'Explore more treatments from our menu.', 'starter_theme' ),
				)
			);
			?>
			<ul class="services-grid services-grid--related">
				<?php
				foreach ( $related as $related_post ) {
					global $post;
					$post = $related_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					setup_postdata( $post );
					get_template_part( 'template-parts/services/card' );
				}
				wp_reset_postdata();
				?>
			</ul>
			<p class="section-cta">
				<a class="btn btn--outline" href="<?php echo esc_url( starter_theme_services_url() ); ?>">
					<?php esc_html_e( 'View All Services', 'starter_theme' ); ?>
				</a>
			</p>
		</div>
	</section>
<?php endif; ?>
