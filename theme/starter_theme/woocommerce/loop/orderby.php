<?php
/**
 * Catalog ordering — styled select with AJAX-friendly markup.
 *
 * @package starter_theme
 * @version 9.7.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! woocommerce_products_will_display() ) {
	return;
}

$select_id = 'woocommerce-orderby-' . wp_unique_id();
?>
<form class="archive-ordering" method="get" data-archive-ordering>
	<label class="archive-ordering__label" for="<?php echo esc_attr( $select_id ); ?>">
		<?php esc_html_e( 'Sort by', 'starter_theme' ); ?>
	</label>
	<div class="select-control">
		<select
			name="orderby"
			class="orderby select-control__input"
			id="<?php echo esc_attr( $select_id ); ?>"
			aria-label="<?php esc_attr_e( 'Shop order', 'woocommerce' ); ?>"
		>
			<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
				<?php if ( ! $show_default_orderby && 'menu_order' === $id ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>>
					<?php echo esc_html( $name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<span class="select-control__icon" aria-hidden="true">
			<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</span>
	</div>
	<?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
</form>
