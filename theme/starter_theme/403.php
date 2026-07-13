<?php
/**
 * Forbidden access page.
 *
 * @package starter_theme
 */

get_header();

starter_theme_render_error_page( '403' );

get_footer();
