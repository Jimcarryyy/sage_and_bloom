<?php
/**
 * Homepage trust badge strip.
 *
 * @package starter_theme
 */

$badges = starter_theme_home_trust_badges();

if ( empty( $badges ) ) {
	return;
}
?>

<section class="home-trust-strip" aria-label="<?php esc_attr_e( 'Why clients choose us', 'starter_theme' ); ?>">
	<div class="site-container">
		<ul class="home-trust-strip__list">
			<?php foreach ( $badges as $badge ) : ?>
				<li class="home-trust-strip__item"><?php echo esc_html( $badge ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
