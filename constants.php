<?php
/**
 * All plugin related constants
 *
 * @package smoobu-calendar
 */

// plugin version.
define( 'SMOOBU_VERSION', '1.2.2' );

// used in templates loading.
define( 'SMOOBU_NAME', 'smoobu-calendar' );

// URI and path.
define( 'SMOOBU_URI', plugin_dir_url( __FILE__ ) );
define( 'SMOOBU_PATH', plugin_dir_path( __FILE__ ) );

// API endpoints.
define( 'SMOOBU_API_PROPERTIES_ENDPOINT', 'https://login.smoobu.com/api/apartment' );
define( 'SMOOBU_API_AVAILABILITY_ENDPOINT', 'https://login.smoobu.com/api/rates' );
define( 'SMOOBU_API_USER_ENDPOINT', 'https://login.smoobu.com/api/me' );

// Smoobu links.
define( 'SMOOBU_DEVELOPERS_LINK', 'https://login.smoobu.com/en/settings/channels/edit/70' );
