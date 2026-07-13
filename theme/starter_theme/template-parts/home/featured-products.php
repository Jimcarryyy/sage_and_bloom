<?php
/**
 * Homepage featured products section (WooCommerce).
 *
 * @package starter_theme
 */

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$heading = starter_theme_home_field( 'products_heading', 'Shop Favorites' );
$count   = (int) starter_theme_home_field( 'featured_products_count', 4 );

$products = wc_get_products(
	array(
		'limit'    => max( 1, $count ),
		'status'   => 'publish',
		'featured' => true,
		'orderby'  => 'date',
		'order'    => 'DESC',
	)
);

if ( empty( $products ) ) {
	$products = wc_get_products(
		array(
			'limit'   => max( 1, $count ),
			'status'  => 'publish',
			'orderby' => 'date',
			'order'   => 'DESC',
		)
	);
}

if ( empty( $products ) ) {
	return;
}
?>

<section class="home-products home-section reveal ajax-loading-surface" data-ajax-loading-surface>
	<div class="site-container">
		<?php
		get_template_part(
			'template-parts/shared/section-header',
			null,
			array(
				'eyebrow' => __( 'Boutique', 'starter_theme' ),
				'title'   => $heading,
				'desc'    => __( 'Curated self-care products for home.', 'starter_theme' ),
			)
		);
		?>

		<ul class="products columns-4 home-products__grid">
			<?php foreach ( $products as $product ) : ?>
				<?php
				$post_object = get_post( $product->get_id() );
				setup_postdata( $GLOBALS['post'] = $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				wc_get_template_part( 'content', 'product' );
				?>
			<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>
		</ul>

		<p class="section-cta">
			<a class="btn btn--secondary" href="<?php echo esc_url( starter_theme_shop_url() ); ?>">
				<?php esc_html_e( 'Visit the Shop', 'starter_theme' ); ?>
			</a>
		</p>
	</div>
</section>
