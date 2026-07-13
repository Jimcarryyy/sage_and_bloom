<?php
/**
 * Service card for grids and archives.
 *
 * @package starter_theme
 */

$price    = get_field( 'service_price' );
$duration = get_field( 'service_duration' );
$short    = get_field( 'service_short_desc' );

if ( empty( $short ) ) {
	$short = get_the_excerpt();
}
?>

<li <?php post_class( 'service-card reveal' ); ?>>
	<div class="service-card__inner">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="service-card__image-wrap">
				<a class="service-card__image" href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'medium_large' ); ?>
				</a>
				<?php if ( $price ) : ?>
					<span class="service-card__price-badge"><?php echo esc_html( $price ); ?></span>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="service-card__body">
			<h3 class="service-card__title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>

			<?php if ( $price || $duration ) : ?>
				<div class="service-card__meta">
					<?php if ( $duration ) : ?>
						<span class="service-card__pill"><?php echo esc_html( $duration ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $short ) : ?>
				<p class="service-card__desc"><?php echo esc_html( wp_strip_all_tags( $short ) ); ?></p>
			<?php endif; ?>

			<a class="btn btn--outline btn--sm service-card__cta" href="<?php the_permalink(); ?>">
				<?php esc_html_e( 'View Service', 'starter_theme' ); ?>
			</a>
		</div>
	</div>
</li>
