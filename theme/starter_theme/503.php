<?php
/**
 * Service unavailable page.
 *
 * @package starter_theme
 */

get_header();

starter_theme_render_error_page( '503' );

get_footer();
