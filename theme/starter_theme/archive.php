<?php
/**
 * The template for displaying archive pages (blog).
 *
 * @package starter_theme
 */

get_header();

$hero_title = __( 'Journal', 'starter_theme' );
$hero_desc  = __( 'Tips, new arrivals, and stories from our salon & boutique.', 'starter_theme' );

if ( is_category() || is_tag() || is_tax() ) {
	$hero_title = get_the_archive_title();
	$hero_title = wp_strip_all_tags( $hero_title );
	$hero_desc  = get_the_archive_description();
	$hero_desc  = $hero_desc ? wp_strip_all_tags( $hero_desc ) : $hero_desc;
} elseif ( is_home() && ! is_front_page() ) {
	$posts_page = get_queried_object();
	if ( $posts_page instanceof WP_Post ) {
		$hero_title = $posts_page->post_title;
	}
}

starter_theme_render_page_hero(
	array(
		'eyebrow' => __( 'From the Studio', 'starter_theme' ),
		'title'   => $hero_title,
		'desc'    => $hero_desc,
		'image'   => starter_theme_page_hero_image_url(),
	)
);
?>

<main id="primary" class="site-main blog-archive">

	<section class="page-section page-section--cream reveal">
		<div class="site-container">

			<?php if ( have_posts() ) : ?>

				<div class="blog-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', 'blog-card' );
					endwhile;
					?>
				</div>

				<?php the_posts_pagination(); ?>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>

		</div>
	</section>

	<?php starter_theme_render_page_cta(); ?>

</main>

<?php
get_footer();
