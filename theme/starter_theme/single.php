<?php
/**
 * The template for displaying all single posts.
 *
 * @package starter_theme
 */

get_header();

while ( have_posts() ) :
	the_post();

	starter_theme_render_page_hero(
		array(
			'eyebrow' => get_the_date(),
			'title'   => get_the_title(),
			'desc'    => has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : '',
			'image'   => has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : starter_theme_page_hero_image_url(),
		)
	);
	?>

	<main id="primary" class="site-main single-post">

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-section reveal' ); ?>>
			<div class="site-container">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="single-post__featured">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>

				<div class="page-body entry-content">
					<?php the_content(); ?>
				</div>
			</div>
		</article>

		<div class="site-container">
			<?php
			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'starter_theme' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'starter_theme' ) . '</span> <span class="nav-title">%title</span>',
				)
			);
			?>
		</div>

		<?php starter_theme_render_page_cta(); ?>

	</main>

	<?php
endwhile;

get_footer();
