<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package starter_theme
 */

get_header();

$search_query = get_search_query();
$found_count  = $GLOBALS['wp_query']->found_posts;

if ( $search_query && $found_count > 0 ) {
	/* translators: 1: number of results, 2: search query. */
	$hero_title = sprintf(
		_n(
			'%1$d result for "%2$s"',
			'%1$d results for "%2$s"',
			$found_count,
			'starter_theme'
		),
		$found_count,
		$search_query
	);
	$hero_desc = __( 'Browse matches from our journal, services, and boutique.', 'starter_theme' );
} elseif ( $search_query ) {
	$hero_title = sprintf(
		/* translators: %s: search query. */
		__( 'No results for "%s"', 'starter_theme' ),
		$search_query
	);
	$hero_desc = __( 'Try different keywords, or explore the links below.', 'starter_theme' );
} else {
	$hero_title = __( 'Search our site', 'starter_theme' );
	$hero_desc  = __( 'Find services, products, journal posts, and more.', 'starter_theme' );
}

starter_theme_render_page_hero(
	array(
		'eyebrow' => __( 'Search', 'starter_theme' ),
		'title'   => $hero_title,
		'desc'    => $hero_desc,
		'image'   => function_exists( 'starter_theme_page_hero_default_url' )
			? starter_theme_page_hero_default_url( 'search' )
			: '',
	)
);
?>

<main id="primary" class="site-main search-results">

	<section class="page-section page-section--cream reveal">
		<div class="site-container">

			<?php if ( have_posts() ) : ?>

				<div class="search-toolbar">
					<?php get_search_form(); ?>
					<?php if ( $found_count > 0 ) : ?>
						<p class="search-toolbar__count">
							<?php
							printf(
								/* translators: %d: number of search results. */
								esc_html( _n( '%d match found', '%d matches found', $found_count, 'starter_theme' ) ),
								(int) $found_count
							);
							?>
						</p>
					<?php endif; ?>
				</div>

				<div class="blog-grid search-results__grid">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', 'search' );
					endwhile;
					?>
				</div>

				<?php the_posts_pagination(); ?>

			<?php else : ?>

				<?php
				starter_theme_render_error_content(
					array(
						'lede'          => __( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'starter_theme' ),
						'show_search'   => true,
						'search_prompt' => __( 'Try another search', 'starter_theme' ),
						'show_links'    => true,
					)
				);
				?>

			<?php endif; ?>

		</div>
	</section>

	<?php starter_theme_render_page_cta(); ?>

</main>

<?php
get_footer();
