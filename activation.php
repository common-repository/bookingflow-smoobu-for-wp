<?php
/**
 * SQL queries on plugin activation
 *
 * @package smoobu-calendar
 */

/**
 * SQL functions to be runned after plugin activation
 *
 * @return void
 */
function smoobu_activation() {
	global $wpdb;

	$table_name      = $wpdb->prefix . 'smoobu_calendar_availability';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`property_id` int(11) NOT NULL,
		`busy_date` date NOT NULL,
        PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
}
