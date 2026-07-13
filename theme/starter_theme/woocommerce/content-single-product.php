<?php
/**
 * Single product content — boutique detail layout.
 *
 * @package starter_theme
 */

defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo wp_kses_post( get_the_password_form() );
	return;
}

$highlights = function_exists( 'starter_theme_product_highlights' )
	? starter_theme_product_highlights( $product->get_id() )
	: array();

$categories = wc_get_product_category_list( $product->get_id(), ', ' );
$shop_url   = function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) > 0
	? get_permalink( wc_get_page_id( 'shop' ) )
	: home_url( '/shop/' );
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'product-detail-wrap', $product ); ?>>

	<nav class="product-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'starter_theme' ); ?>">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'starter_theme' ); ?></a>
		<span aria-hidden="true">/</span>
		<a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop', 'starter_theme' ); ?></a>
		<span aria-hidden="true">/</span>
		<span class="product-breadcrumb__current"><?php the_title(); ?></span>
	</nav>

	<div class="product-detail">
		<div class="product-detail__gallery">
			<?php woocommerce_show_product_sale_flash(); ?>
			<div class="product-detail__media">
				<?php woocommerce_show_product_images(); ?>
			</div>
		</div>

		<div class="product-detail__summary">
			<div class="product-summary-card">
				<?php if ( $categories ) : ?>
					<p class="product-summary-card__eyebrow"><?php echo wp_kses_post( $categories ); ?></p>
				<?php endif; ?>

				<?php woocommerce_template_single_title(); ?>
				<?php woocommerce_template_single_rating(); ?>
				<?php woocommerce_template_single_price(); ?>

				<div class="product-summary-card__excerpt">
					<?php woocommerce_template_single_excerpt(); ?>
				</div>

				<?php if ( ! empty( $highlights ) ) : ?>
					<div class="product-summary-card__highlights">
						<h2 class="product-summary-card__highlights-title"><?php esc_html_e( 'Why you\'ll love it', 'starter_theme' ); ?></h2>
						<?php
						get_template_part(
							'template-parts/shared/benefit-list',
							null,
							array(
								'items'    => $highlights,
								'modifier' => 'about',
							)
						);
						?>
					</div>
				<?php endif; ?>

				<div class="product-summary-card__purchase">
					<?php woocommerce_template_single_add_to_cart(); ?>
				</div>

				<ul class="product-summary-card__trust">
					<li><?php esc_html_e( 'Salon-quality formulas', 'starter_theme' ); ?></li>
					<li><?php esc_html_e( 'Studio pickup available', 'starter_theme' ); ?></li>
					<li><?php esc_html_e( 'Gift-ready packaging', 'starter_theme' ); ?></li>
				</ul>

				<div class="product-summary-card__meta">
					<?php woocommerce_template_single_meta(); ?>
				</div>
			</div>
		</div>
	</div>

	<?php if ( $product->get_description() ) : ?>
		<div class="product-detail__description">
			<?php
			get_template_part(
				'template-parts/shared/section-header',
				null,
				array(
					'eyebrow' => __( 'Product details', 'starter_theme' ),
					'title'   => __( 'More about this product', 'starter_theme' ),
					'align'   => 'left',
				)
			);
			?>
			<div class="product-detail__description-content entry-content">
				<?php the_content(); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php
	$related = function_exists( 'starter_theme_related_products' )
		? starter_theme_related_products( $product->get_id(), 4 )
		: array();

	if ( ! empty( $related ) ) :
		?>
		<div class="product-detail__related">
			<?php
			get_template_part(
				'template-parts/shared/section-header',
				null,
				array(
					'eyebrow' => __( 'You may also like', 'starter_theme' ),
					'title'   => __( 'Related products', 'starter_theme' ),
					'align'   => 'left',
				)
			);
			?>
			<ul class="products product-detail__related-grid">
				<?php
				foreach ( $related as $related_product ) {
					$post_object = get_post( $related_product->get_id() );
					if ( ! $post_object ) {
						continue;
					}
					$GLOBALS['post'] = $post_object; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					setup_postdata( $post_object );
					wc_get_template_part( 'content', 'product' );
				}
				wp_reset_postdata();
				?>
			</ul>
		</div>
	<?php endif; ?>

</div>

<?php
do_action( 'woocommerce_after_single_product' );
