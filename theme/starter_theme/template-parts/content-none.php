<?php
/**
 * Template part for displaying a message that posts cannot be found.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package starter_theme
 */

?>

<section class="no-results not-found">
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

		<div class="error-panel error-panel--compact">
			<p class="error-panel__lede">
				<?php
				printf(
					wp_kses(
						/* translators: 1: link to WP admin new post page. */
						__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'starter_theme' ),
						array(
							'a' => array(
								'href' => array(),
							),
						)
					),
					esc_url( admin_url( 'post-new.php' ) )
				);
				?>
			</p>
		</div>

	<?php elseif ( is_search() ) : ?>

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

	<?php else : ?>

		<?php
		starter_theme_render_error_content(
			array(
				'lede'          => __( 'It seems we cannot find what you are looking for. Perhaps searching can help.', 'starter_theme' ),
				'show_search'   => true,
				'search_prompt' => __( 'Search our site', 'starter_theme' ),
				'show_links'    => false,
			)
		);
		?>

	<?php endif; ?>
</section>
