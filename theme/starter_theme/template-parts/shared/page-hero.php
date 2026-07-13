<?php
/**
 * Dark immersive hero for inner pages.
 *
 * @package starter_theme
 *
 * @var array $args {
 *     @type string       $eyebrow       Optional eyebrow label.
 *     @type string       $title         Page heading.
 *     @type string       $desc          Lead paragraph.
 *     @type string       $image         Background image URL.
 *     @type array        $benefits      Optional checklist items.
 *     @type array|null   $primary_cta   Optional ['text','url'].
 *     @type array|null   $secondary_cta Optional ['text','url'].
 * }
 */

$eyebrow       = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$title         = isset( $args['title'] ) ? $args['title'] : '';
$desc          = isset( $args['desc'] ) ? $args['desc'] : '';
$image         = isset( $args['image'] ) ? $args['image'] : '';
$benefits      = isset( $args['benefits'] ) ? (array) $args['benefits'] : array();
$primary_cta   = isset( $args['primary_cta'] ) ? $args['primary_cta'] : null;
$secondary_cta = isset( $args['secondary_cta'] ) ? $args['secondary_cta'] : null;

if ( empty( $title ) ) {
	return;
}

$media_style = '';
if ( $image ) {
	$media_style = sprintf(
		' style="background-image: url(%s);"',
		esc_url( $image )
	);
}
?>

<header class="page-hero reveal">
	<?php if ( $image ) : ?>
		<div class="page-hero__media"<?php echo $media_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-hidden="true"></div>
	<?php endif; ?>
	<div class="page-hero__overlay" aria-hidden="true"></div>

	<div class="site-container">
		<div class="page-hero__content">
			<?php if ( $eyebrow ) : ?>
				<span class="page-hero__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<?php endif; ?>

			<h1 class="page-hero__title"><?php echo esc_html( $title ); ?></h1>

			<?php if ( $desc ) : ?>
				<p class="page-hero__intro"><?php echo esc_html( $desc ); ?></p>
			<?php endif; ?>

			<?php
			if ( ! empty( $benefits ) ) {
				get_template_part(
					'template-parts/shared/benefit-list',
					null,
					array(
						'items'    => $benefits,
						'modifier' => 'hero',
					)
				);
			}
			?>

			<?php if ( $primary_cta || $secondary_cta ) : ?>
				<div class="page-hero__actions">
					<?php if ( ! empty( $primary_cta['text'] ) && ! empty( $primary_cta['url'] ) ) : ?>
						<a class="btn btn--primary btn--lg" href="<?php echo esc_url( $primary_cta['url'] ); ?>">
							<?php echo esc_html( $primary_cta['text'] ); ?>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $secondary_cta['text'] ) && ! empty( $secondary_cta['url'] ) ) : ?>
						<a class="btn btn--ghost" href="<?php echo esc_url( $secondary_cta['url'] ); ?>">
							<?php echo esc_html( $secondary_cta['text'] ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</header>
