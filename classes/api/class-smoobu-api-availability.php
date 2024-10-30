<?php
/**
 * Update availability from the API
 *
 * @package smoobu-calendar
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No skiddies please!' );
}

/**
 * Availability API class
 */
class Smoobu_Api_Availability extends Smoobu_Api {
	/**
	 * Properties API endpoint url
	 *
	 * @var string
	 */
	protected $endpoint = SMOOBU_API_AVAILABILITY_ENDPOINT;


	/**
	 * Construct, we set request params here
	 */
	public function __construct() {
		$this->params = array(
			'start_date' => $this->get_start_date(),
			'end_date'   => $this->get_end_date(),
			'apartments' => $this->get_apartments_ids(),
		);

		parent::__construct();
	}

	/**
	 * Get availability checking start date
	 *
	 * @return string
	 */
	private function get_start_date() {
		$start_date = date( 'Y-m-01' );
		$start_date = apply_filters( 'smoobu_availability_start_date', $start_date );

		return $start_date;
	}

	/**
	 * Get availability checking end date
	 *
	 * @return string
	 */
	private function get_end_date() {
		$end_date = date( 'Y-m-t', strtotime( '+1 year' ) );
		$end_date = apply_filters( 'smoobu_availability_end_date', $end_date );

		return $end_date;
	}

	/**
	 * Set apartments IDs array for params
	 *
	 * @return array
	 */
	private function get_apartments_ids() {
		$apartments_ids = array();

		$apartments = Smoobu_Utility::get_available_properties();

		if ( ! empty( $apartments ) ) {
			foreach ( $apartments as $apartment ) {
				$apartments_ids[] = $apartment->id;
			}
		}

		return $apartments_ids;
	}


	/**
	 * Fetch properties from the API
	 *
	 * @return void
	 */
	public function fetch_availability() {
		global $wpdb;

		// get data from Smoobu API.
		$this->handle_data();

		if ( ! empty( $this->data ) ) {
			$availabilities = $this->data->data;

			if ( ! empty( $availabilities ) ) {
				foreach ( $availabilities as $property_id => $availability ) {
					// delete all current availability calendar entries for this property.
					// phpcs:ignore
					$wpdb->query(
						$wpdb->prepare(
							"DELETE FROM {$wpdb->prefix}smoobu_calendar_availability WHERE property_id = %d",
							$property_id
						)
					);

					// insert all busy dates.
					foreach ( $availability as $date => $attributes ) {
						// insert only if the date is already taken.
						if ( 0 === $attributes->available ) {
							// @TODO - depending on testing results, might need to remake to multiple insert with one query.
							// phpcs:ignore
							$wpdb->insert(
								$wpdb->prefix . 'smoobu_calendar_availability',
								array(
									'property_id' => $property_id,
									'busy_date'   => $date,
								)
							);
						}
					}
				}
			}
		}
	}
}
