<?php
/**
 * Custom search form.
 *
 * @package starter_theme
 */

$unique_id = wp_unique_id( 'search-form-' );
?>

<form role="search" method="get" class="error-search site-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $unique_id ); ?>">
		<?php esc_html_e( 'Search for:', 'starter_theme' ); ?>
	</label>
	<div class="error-search__field">
		<input
			type="search"
			id="<?php echo esc_attr( $unique_id ); ?>"
			class="error-search__input"
			placeholder="<?php esc_attr_e( 'Search services, products, journal…', 'starter_theme' ); ?>"
			value="<?php echo esc_attr( get_search_query() ); ?>"
			name="s"
		/>
		<button type="submit" class="btn btn--primary error-search__submit">
			<?php esc_html_e( 'Search', 'starter_theme' ); ?>
		</button>
	</div>
</form>
