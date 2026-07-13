<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package starter_theme
 */

$post_type_label = starter_theme_search_result_type_label( get_post_type() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card search-result-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="blog-card__image" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'medium_large' ); ?>
		</a>
	<?php endif; ?>

	<div class="blog-card__body">
		<p class="blog-card__meta">
			<span class="search-result-card__type"><?php echo esc_html( $post_type_label ); ?></span>
			<?php if ( 'post' === get_post_type() ) : ?>
				<span class="search-result-card__sep" aria-hidden="true">·</span>
				<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			<?php endif; ?>
		</p>
		<h2 class="blog-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
		<p class="blog-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
	</div>
</article>
