<?php
/**
 * Blog card for archive grid.
 *
 * @package starter_theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="blog-card__image" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'medium_large' ); ?>
		</a>
	<?php endif; ?>

	<div class="blog-card__body">
		<p class="blog-card__meta">
			<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		</p>
		<h2 class="blog-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
		<p class="blog-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
	</div>
</article>
