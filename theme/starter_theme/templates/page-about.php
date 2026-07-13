<?php
/**
 * Template Name: About
 * Template for the About page.
 *
 * @package starter_theme
 */

get_header();

while ( have_posts() ) :
	the_post();

	$intro    = get_field( 'about_intro' );
	$intro    = $intro ? wp_strip_all_tags( (string) $intro ) : '';
	$mission  = get_field( 'about_mission' );
	$values   = get_field( 'about_values' );
	$image    = starter_theme_page_hero_image_url( get_the_ID() );
	$benefits = starter_theme_about_highlights();

	if ( ! $intro ) {
		$intro = __( 'A neighborhood destination for salon services and curated self-care products — rooted in quality, community, and care.', 'starter_theme' );
	}

	if ( ! $mission ) {
		$mission = __( 'We believe everyone deserves to feel confident, refreshed, and welcomed — every time they walk through our door.', 'starter_theme' );
	}

	if ( empty( $values ) || ! is_array( $values ) ) {
		$values = starter_theme_about_values_fallback();
	}

	starter_theme_render_page_hero(
		array(
			'eyebrow'  => __( 'About Us', 'starter_theme' ),
			'title'    => get_the_title(),
			'desc'     => $intro,
			'image'    => $image,
			'benefits' => array_slice( $benefits, 0, 4 ),
			'primary_cta'   => array(
				'text' => __( 'Book Appointment', 'starter_theme' ),
				'url'  => starter_theme_booking_url(),
			),
			'secondary_cta' => array(
				'text' => __( 'View Services', 'starter_theme' ),
				'url'  => starter_theme_services_url(),
			),
		)
	);
	?>

	<main id="primary" class="site-main about-page">

		<section class="page-section page-section--cream about-story reveal">
			<div class="site-container">
				<div class="split-layout">
					<div class="split-layout__media">
						<?php if ( $image ) : ?>
							<img class="split-layout__image" src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy" />
						<?php else : ?>
							<div class="split-layout__placeholder" aria-hidden="true"></div>
						<?php endif; ?>
					</div>

					<div class="split-layout__body">
						<?php
						get_template_part(
							'template-parts/shared/section-header',
							null,
							array(
								'eyebrow' => __( 'Our Story', 'starter_theme' ),
								'title'   => __( 'Salon services & a curated boutique under one roof', 'starter_theme' ),
								'align'   => 'left',
							)
						);
						?>

						<?php if ( get_the_content() ) : ?>
							<div class="split-layout__content entry-content">
								<?php the_content(); ?>
							</div>
						<?php endif; ?>

						<?php
						get_template_part(
							'template-parts/shared/benefit-list',
							null,
							array(
								'items'    => $benefits,
								'modifier' => 'about',
							)
						);
						?>
					</div>
				</div>
			</div>
		</section>

		<section class="page-section about-mission-band reveal">
			<div class="site-container">
				<blockquote class="about-mission-quote">
					<p><?php echo esc_html( $mission ); ?></p>
				</blockquote>
			</div>
		</section>

		<?php
		get_template_part(
			'template-parts/shared/stats-band',
			null,
			array(
				'stats'    => starter_theme_page_stats(),
				'modifier' => 'page',
				'label'    => __( 'Salon highlights', 'starter_theme' ),
			)
		);
		?>

		<section class="page-section about-values-section reveal">
			<div class="site-container">
				<?php
				get_template_part(
					'template-parts/shared/section-header',
					null,
					array(
						'eyebrow' => __( 'What We Stand For', 'starter_theme' ),
						'title'   => __( 'Our Values', 'starter_theme' ),
						'desc'    => __( 'The principles that guide every service, product, and conversation in our studio.', 'starter_theme' ),
					)
				);
				?>

				<div class="feature-cards">
					<?php foreach ( $values as $index => $value ) : ?>
						<article class="feature-card">
							<span class="feature-card__index"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
							<h3 class="feature-card__title"><?php echo esc_html( $value['title'] ?? '' ); ?></h3>
							<p class="feature-card__text"><?php echo esc_html( $value['text'] ?? '' ); ?></p>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section class="page-section page-section--cream about-team-section reveal">
			<div class="site-container">
				<?php
				get_template_part(
					'template-parts/shared/section-header',
					null,
					array(
						'eyebrow' => __( 'The People Behind the Chair', 'starter_theme' ),
						'title'   => __( 'Meet Our Team', 'starter_theme' ),
						'desc'    => __( 'Experienced stylists and hospitality pros dedicated to your best visit.', 'starter_theme' ),
					)
				);

				$team = get_posts(
					array(
						'post_type'      => 'team',
						'posts_per_page' => -1,
						'orderby'        => 'menu_order',
						'order'          => 'ASC',
					)
				);
				?>

				<?php if ( ! empty( $team ) ) : ?>
					<div class="team-grid">
						<?php
						foreach ( $team as $member ) {
							get_template_part( 'template-parts/team/card', null, array( 'member' => $member ) );
						}
						?>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<?php starter_theme_render_page_cta(); ?>

	</main>

	<?php
endwhile;

get_footer();
