<?php
/**
 * Webhook API Endpoints & retrieving the data
 *
 * @package smoobu-calendar
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No skiddies please!' );
}

/**
 * Plugin Endpoints Class
 */
class Smoobu_Webhook {
	/**
	 * Class instance
	 *
	 * @var Smoobu_Webhook
	 */
	protected static $instance = null;

	/**
	 * Main instance
	 *
	 * @return Smoobu_Webhook
	 */
	public static function instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor, loads main actions, methods etc.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'routes' ) );
	}

	/**
	 * Register API endpoints routes
	 *
	 * @return void
	 */
	public function routes() {
		// all brokers data.
		register_rest_route(
			'smoobu-calendar/v1',
			'update',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'fetch_availability' ),
			)
		);
	}

	/**
	 * Webhook used to update proeprty availability after booking in Smoobu platform
	 *
	 * @param  WP_REST_Request $request request data.
	 * @return string
	 */
	public function fetch_availability( WP_REST_Request $request ) {
		global $wpdb;

		$action = $request->get_param( 'action' );
		$data   = $request->get_param( 'data' );

		if ( ! empty( $action ) && ! empty( $data ) ) {
			// update availability.
			if ( 'updateRates' === $action ) {
				foreach ( $data as $property_id => $dates ) {
					foreach ( $dates as $date => $attributes ) {
						if ( 0 === $attributes['available'] ) {
							// insert booked dates (new reservation).
							// phpcs:ignore
							$wpdb->insert(
								$wpdb->prefix . 'smoobu_calendar_availability',
								array(
									'property_id' => $property_id,
									'busy_date'   => $date,
								)
							);
						} else {
							// delete booked dates (canceled reservation).
							// phpcs:ignore
							$wpdb->query(
								$wpdb->prepare(
									"DELETE FROM {$wpdb->prefix}smoobu_calendar_availability WHERE property_id = %d AND busy_date = %s",
									$property_id,
									$date
								)
							);
						}
					}
				}
			}
		}

		return 'OK';
	}
}
