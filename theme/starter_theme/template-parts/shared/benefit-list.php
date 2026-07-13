<?php
/**
 * Checklist-style benefit list.
 *
 * @package starter_theme
 *
 * @var array  $args {
 *     @type array  $items    List of benefit strings.
 *     @type string $modifier Optional BEM modifier class.
 * }
 */

$items    = isset( $args['items'] ) ? (array) $args['items'] : array();
$modifier = isset( $args['modifier'] ) ? $args['modifier'] : '';

if ( empty( $items ) ) {
	return;
}
?>

<ul class="benefit-list<?php echo $modifier ? ' benefit-list--' . esc_attr( $modifier ) : ''; ?>">
	<?php foreach ( $items as $item ) : ?>
		<li class="benefit-list__item"><?php echo esc_html( $item ); ?></li>
	<?php endforeach; ?>
</ul>
