<?php
/**
 * Branded My Account dashboard.
 *
 * @package starter_theme
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/woocommerce/my-account-dashboard' );

do_action( 'woocommerce_account_dashboard' );
do_action( 'woocommerce_before_my_account' );
do_action( 'woocommerce_after_my_account' );
