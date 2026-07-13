<?php
/**
 * The main template file
 *
 * @package starter_theme
 */

get_header();

if ( is_home() && ! is_front_page() ) {
	$posts_page = get_queried_object();
	$hero_title = $posts_page instanceof WP_Post ? $posts_page->post_title : __( 'Journal', 'starter_theme' );
	$hero_desc  = __( 'Tips, new arrivals, and stories from our salon & boutique.', 'starter_theme' );

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
				<div class="blog-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', 'blog-card' );
					endwhile;
					?>
				</div>
				<?php the_posts_pagination(); ?>
			</div>
		</section>

		<?php starter_theme_render_page_cta(); ?>

	</main>

	<?php
	get_footer();
	return;
}
?>

<main id="primary" class="site-main">

	<?php
	if ( have_posts() ) :

		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', get_post_type() );
		endwhile;

		the_posts_navigation();

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;
	?>

</main>

<?php
get_sidebar();
get_footer();
