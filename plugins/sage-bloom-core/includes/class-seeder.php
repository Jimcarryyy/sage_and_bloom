<?php
/**
 * Demo data seeder for portfolio demos.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Seed helper.
 */
class SBC_Seeder {

	/**
	 * Seed demo coupons once.
	 *
	 * @return void
	 */
	public static function seed_demo_coupons() {
		if ( get_option( 'sbc_demo_coupons_seeded' ) || ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$coupons = array(
			array(
				'code'     => 'WELCOME15',
				'amount'   => '15',
				'type'     => 'percent',
				'desc'     => 'Welcome discount for new boutique shoppers.',
			),
			array(
				'code'     => 'BRIDAL10',
				'amount'   => '10',
				'type'     => 'percent',
				'desc'     => 'Bridal package promo for portfolio demos.',
			),
		);

		foreach ( $coupons as $coupon_data ) {
			if ( wc_get_coupon_id_by_code( $coupon_data['code'] ) ) {
				continue;
			}

			$coupon = new WC_Coupon();
			$coupon->set_code( $coupon_data['code'] );
			$coupon->set_discount_type( $coupon_data['type'] );
			$coupon->set_amount( $coupon_data['amount'] );
			$coupon->set_description( $coupon_data['desc'] );
			$coupon->save();
		}

		update_option( 'sbc_demo_coupons_seeded', 1 );
	}
}
