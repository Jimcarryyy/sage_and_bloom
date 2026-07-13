<?php
/**
 * Stats counter band — shared across homepage and inner pages.
 *
 * @package starter_theme
 *
 * @var array $args {
 *     @type array  $stats    Array of value/label pairs.
 *     @type string $modifier Optional BEM modifier.
 *     @type string $label    Optional aria-label.
 * }
 */

$stats     = isset( $args['stats'] ) ? (array) $args['stats'] : array();
$modifier  = isset( $args['modifier'] ) ? $args['modifier'] : '';
$aria      = isset( $args['label'] ) ? $args['label'] : __( 'Highlights', 'starter_theme' );

if ( empty( $stats ) ) {
	return;
}

$class = 'stats-band';
if ( $modifier ) {
	$class .= ' stats-band--' . esc_attr( $modifier );
}
?>

<section class="<?php echo esc_attr( $class ); ?> reveal" aria-label="<?php echo esc_attr( $aria ); ?>">
	<div class="site-container">
		<ul class="stats-band__grid">
			<?php foreach ( $stats as $stat ) : ?>
				<li class="stats-band__item">
					<p class="stats-band__value"><?php echo esc_html( $stat['value'] ?? '' ); ?></p>
					<p class="stats-band__label"><?php echo esc_html( $stat['label'] ?? '' ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
