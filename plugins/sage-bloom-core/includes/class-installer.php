<?php
/**
 * Database installer and migrations.
 *
 * @package Sage_Bloom_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Plugin installer.
 */
class SBC_Installer {

	const DB_VERSION = '1.0.0';

	/**
	 * Run install on activation.
	 *
	 * @return void
	 */
	public static function install() {
		self::create_tables();
		update_option( 'sbc_db_version', self::DB_VERSION );
	}

	/**
	 * Run migrations when version changes.
	 *
	 * @return void
	 */
	public static function maybe_upgrade() {
		$installed = get_option( 'sbc_db_version', '' );
		if ( self::DB_VERSION !== $installed ) {
			self::install();
		}
	}

	/**
	 * Create custom database tables.
	 *
	 * @return void
	 */
	public static function create_tables() {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset = $wpdb->get_charset_collate();

		$bookings = sbc_table( 'bookings' );
		$rules    = sbc_table( 'availability_rules' );
		$blocked  = sbc_table( 'blocked_dates' );
		$activity = sbc_table( 'activity_log' );

		$sql = "CREATE TABLE {$bookings} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			service_id bigint(20) unsigned NOT NULL DEFAULT 0,
			staff_id bigint(20) unsigned NOT NULL DEFAULT 0,
			user_id bigint(20) unsigned NOT NULL DEFAULT 0,
			customer_name varchar(191) NOT NULL DEFAULT '',
			customer_email varchar(191) NOT NULL DEFAULT '',
			customer_phone varchar(50) NOT NULL DEFAULT '',
			booking_date date NOT NULL,
			booking_time time NOT NULL,
			duration_minutes smallint unsigned NOT NULL DEFAULT 60,
			status varchar(20) NOT NULL DEFAULT 'pending',
			message text NULL,
			access_token varchar(64) NOT NULL DEFAULT '',
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY service_id (service_id),
			KEY booking_date (booking_date),
			KEY status (status),
			KEY access_token (access_token)
		) {$charset};

		CREATE TABLE {$rules} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			day_of_week tinyint unsigned NOT NULL DEFAULT 0,
			start_time time NOT NULL DEFAULT '09:00:00',
			end_time time NOT NULL DEFAULT '17:00:00',
			is_closed tinyint(1) NOT NULL DEFAULT 0,
			slot_duration smallint unsigned NOT NULL DEFAULT 60,
			buffer_minutes smallint unsigned NOT NULL DEFAULT 15,
			PRIMARY KEY  (id),
			UNIQUE KEY day_of_week (day_of_week)
		) {$charset};

		CREATE TABLE {$blocked} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			blocked_date date NOT NULL,
			reason varchar(191) NOT NULL DEFAULT '',
			PRIMARY KEY  (id),
			UNIQUE KEY blocked_date (blocked_date)
		) {$charset};

		CREATE TABLE {$activity} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			entity_type varchar(50) NOT NULL DEFAULT '',
			entity_id bigint(20) unsigned NOT NULL DEFAULT 0,
			action varchar(50) NOT NULL DEFAULT '',
			user_id bigint(20) unsigned NOT NULL DEFAULT 0,
			meta longtext NULL,
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY entity (entity_type, entity_id),
			KEY created_at (created_at)
		) {$charset};";

		dbDelta( $sql );
	}

	/**
	 * Seed default weekly availability rules.
	 *
	 * @return void
	 */
	public static function seed_default_availability() {
		global $wpdb;

		$table  = sbc_table( 'availability_rules' );
		$exists = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		if ( $exists > 0 ) {
			return;
		}

		$defaults = array(
			array( 1, '09:00:00', '19:00:00', 0, 60, 15 ),
			array( 2, '09:00:00', '19:00:00', 0, 60, 15 ),
			array( 3, '09:00:00', '19:00:00', 0, 60, 15 ),
			array( 4, '09:00:00', '19:00:00', 0, 60, 15 ),
			array( 5, '09:00:00', '19:00:00', 0, 60, 15 ),
			array( 6, '10:00:00', '17:00:00', 0, 60, 15 ),
			array( 0, '00:00:00', '00:00:00', 1, 60, 15 ),
		);

		foreach ( $defaults as $row ) {
			$wpdb->insert(
				$table,
				array(
					'day_of_week'    => $row[0],
					'start_time'     => $row[1],
					'end_time'       => $row[2],
					'is_closed'      => $row[3],
					'slot_duration'  => $row[4],
					'buffer_minutes' => $row[5],
				),
				array( '%d', '%s', '%s', '%d', '%d', '%d' )
			);
		}
	}
}
