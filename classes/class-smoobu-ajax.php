<?php
/**
 * Ajax actions
 *
 * @package complete-coherence
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No skiddies please!' );
}

/**
 * Ajax class
 */
final class Smoobu_Ajax {
	/**
	 * Class instance
	 *
	 * @var Smoobu_Ajax
	 */
	protected static $instance = null;

	/**
	 * Main instance
	 *
	 * @return Smoobu_Ajax
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
		// resources & learning news.
		add_action( 'wp_ajax_check_api_connection', array( $this, 'check_api_connection' ) );
		add_action( 'wp_ajax_get_calendar_styling', array( $this, 'get_calendar_styling' ) );

	}

	/**
	 * Check API connection
	 *
	 * @return void
	 */
	public static function check_api_connection() {
		check_ajax_referer( 'connection_check_nonce', 'security' );

		if ( isset( $_POST['api_key'] ) ) {
			$api_key = sanitize_text_field( wp_unslash( $_POST['api_key'] ) );
		}

		// get response.
		$api          = new Smoobu_Api();
		$check_result = $api->get_api_check( $api_key, SMOOBU_API_USER_ENDPOINT );

		if ( false === $check_result ) {
			$response = array(
				'status' => 'OK',
			);
		} else {
			$response = array(
				'status'  => 'ERROR',
				'message' => $check_result,
			);
		}

		echo wp_json_encode( $response );

		exit;
	}

	/**
	 * Get calendar custom styling settings
	 *
	 * @return void
	 */
	public static function get_calendar_styling() {
		check_ajax_referer( 'styling_nonce', 'security' );

		$styling = Smoobu_Utility::get_custom_theme_styling();

		// border related values.
		if ( isset( $_POST['smoobu_custom_styling_border_shadow'] ) ) {
			$styling['border_shadow'] = sanitize_text_field( wp_unslash( $_POST['smoobu_custom_styling_border_shadow'] ) );
		}

		if ( isset( $_POST['smoobu_custom_styling_border_radius'] ) ) {
			$styling['border_radius'] = sanitize_text_field( wp_unslash( $_POST['smoobu_custom_styling_border_radius'] ) );
		}

		// color related values.
		foreach ( $styling['colors'] as $key => $empty ) {
			if ( isset( $_POST[ 'smoobu_custom_styling_color_' . $key ] ) ) {
				$styling['colors'][ $key ] = sanitize_text_field( wp_unslash( $_POST[ 'smoobu_custom_styling_color_' . $key ] ) );
			}
		}

		$css = Smoobu_Utility::get_custom_css( $styling );

		$response = array(
			'status' => 'OK',
			'css'    => $css,
		);

		echo wp_json_encode( $response );

		exit;
	}
}
