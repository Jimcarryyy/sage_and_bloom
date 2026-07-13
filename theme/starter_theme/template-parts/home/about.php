<?php
/**
 * Homepage about section.
 *
 * @package starter_theme
 */

$about_heading = starter_theme_home_field( 'about_heading', 'Your Neighborhood Salon' );
$about_content = starter_theme_home_field(
	'about_content',
	'<p>We combine expert salon services with a curated boutique shop — all in one welcoming space downtown.</p>'
);
$about_points  = starter_theme_parse_lines(
	(string) starter_theme_home_field(
		'about_points',
		"Expert cuts, color, and nail care\nCurated self-care products in our boutique\nWarm hospitality for walk-ins and appointments\nDowntown location with free parking"
	)
);
?>

<section id="about" class="home-about home-section reveal">
	<div class="site-container">
		<div class="home-about__layout">
			<div class="home-about__main">
				<?php
				get_template_part(
					'template-parts/shared/section-header',
					null,
					array(
						'eyebrow'  => __( 'About Us', 'starter_theme' ),
						'title'    => $about_heading,
						'desc'     => '',
						'align'    => 'left',
					)
				);
				?>
				<div class="home-about__content entry-content">
					<?php echo wp_kses_post( $about_content ); ?>
				</div>
				<p class="home-about__link">
					<a class="text-link" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">
						<?php esc_html_e( 'Learn more about us', 'starter_theme' ); ?>
					</a>
				</p>
			</div>

			<?php if ( ! empty( $about_points ) ) : ?>
				<aside class="home-about__aside">
					<h3 class="home-about__aside-title"><?php esc_html_e( 'Why locals choose us', 'starter_theme' ); ?></h3>
					<?php
					get_template_part(
						'template-parts/shared/benefit-list',
						null,
						array(
							'items'    => $about_points,
							'modifier' => 'about',
						)
					);
					?>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>
