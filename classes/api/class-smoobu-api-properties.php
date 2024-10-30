<?php
/**
 * Update properties from the API
 *
 * @package smoobu-calendar
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No skiddies please!' );
}

/**
 * Properties API class
 */
class Smoobu_Api_Properties extends Smoobu_Api {
	/**
	 * Properties API endpoint url
	 *
	 * @var string
	 */
	protected $endpoint = SMOOBU_API_PROPERTIES_ENDPOINT;

	/**
	 * Fetch properties from the API
	 *
	 * @return void
	 */
	public function fetch_properties() {
		// get data from Smoobu API.
		$this->handle_data();

		if ( ! empty( $this->data ) ) {
			$properties = wp_json_encode( $this->data->apartments );

			update_option( 'smoobu_properties_list', $properties );
		}
	}
}
