<?php
/**
 * Shared section header with eyebrow label.
 *
 * @package starter_theme
 *
 * @var string $eyebrow  Optional eyebrow text.
 * @var string $title    Section heading.
 * @var string $desc     Optional description.
 */

$eyebrow = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$title   = isset( $args['title'] ) ? $args['title'] : '';
$desc    = isset( $args['desc'] ) ? $args['desc'] : '';
$align   = isset( $args['align'] ) ? $args['align'] : 'center';

if ( empty( $title ) ) {
	return;
}

$header_class = 'section-header';
if ( 'left' === $align ) {
	$header_class .= ' section-header--left';
}
?>

<div class="<?php echo esc_attr( $header_class ); ?>">
	<?php if ( $eyebrow ) : ?>
		<span class="section-header__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
	<?php endif; ?>
	<h2 class="section-header__title"><?php echo esc_html( $title ); ?></h2>
	<?php if ( $desc ) : ?>
		<p class="section-header__desc"><?php echo esc_html( $desc ); ?></p>
	<?php endif; ?>
</div>
