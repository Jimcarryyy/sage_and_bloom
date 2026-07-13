<?php
/**
 * Shared error panel — code, message, search, quick links.
 *
 * @package starter_theme
 *
 * @var array $args {
 *     @type string $code          Optional error code (e.g. 404).
 *     @type string $lede          Optional body copy below the code.
 *     @type bool   $show_search   Show search form.
 *     @type string $search_prompt Label above search form.
 *     @type bool   $show_links    Show quick-link cards.
 *     @type array  $links         Optional quick-link overrides.
 * }
 */

$code          = isset( $args['code'] ) ? $args['code'] : '';
$lede          = isset( $args['lede'] ) ? $args['lede'] : '';
$show_search   = ! empty( $args['show_search'] );
$search_prompt = isset( $args['search_prompt'] ) ? $args['search_prompt'] : __( 'Search our site', 'starter_theme' );
$show_links    = ! empty( $args['show_links'] );
$links         = ! empty( $args['links'] ) ? (array) $args['links'] : starter_theme_error_quick_links();

if ( ! $code && ! $lede && ! $show_search && ! $show_links ) {
	return;
}
?>

<div class="error-panel">
	<?php if ( $code ) : ?>
		<p class="error-panel__code" aria-hidden="true"><?php echo esc_html( $code ); ?></p>
	<?php endif; ?>

	<?php if ( $lede ) : ?>
		<p class="error-panel__lede"><?php echo esc_html( $lede ); ?></p>
	<?php endif; ?>

	<?php if ( $show_search ) : ?>
		<div class="error-panel__search">
			<?php if ( $search_prompt ) : ?>
				<p class="error-panel__search-label"><?php echo esc_html( $search_prompt ); ?></p>
			<?php endif; ?>
			<?php get_search_form(); ?>
		</div>
	<?php endif; ?>

	<?php if ( $show_links && ! empty( $links ) ) : ?>
		<div class="error-links" aria-label="<?php esc_attr_e( 'Helpful links', 'starter_theme' ); ?>">
			<?php foreach ( $links as $link ) : ?>
				<?php
				if ( empty( $link['url'] ) || empty( $link['label'] ) ) {
					continue;
				}
				?>
				<a class="error-link-card" href="<?php echo esc_url( $link['url'] ); ?>">
					<span class="error-link-card__label"><?php echo esc_html( $link['label'] ); ?></span>
					<?php if ( ! empty( $link['desc'] ) ) : ?>
						<span class="error-link-card__desc"><?php echo esc_html( $link['desc'] ); ?></span>
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
