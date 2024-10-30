<?php
/**
 * Calendar block
 *
 * @package smoobu-calendar
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No skiddies please!' );
}

/**
 * Calendar block class
 */
final class Smoobu_Blocks {
	/**
	 * Class instance
	 *
	 * @var Smoobu_Blocks
	 */
	protected static $instance = null;

	/**
	 * Main instance
	 *
	 * @return Smoobu_Blocks
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor, loads main actions, methods etc.
	 */
	public function __construct() {
		// register blocks.
		add_action( 'init', array( $this, 'calendar' ) );
	}

	/**
	 * Main function where block and related JS is being registered
	 *
	 * @return void
	 */
	public function calendar() {
		if ( ! function_exists( 'register_block_type' ) ) {
			// Gutenberg is not active.
			return;
		}

		wp_register_script(
			'smoobu-calendar-block',
			SMOOBU_URI . 'assets/js/blocks/calendar.js',
			array( 'wp-blocks', 'wp-element', 'wp-editor' ),
			'0.1.0',
			true
		);

		register_block_type(
			'smoobu-calendar/calendar',
			array(
				'editor_script'   => 'smoobu-calendar-block',
				'render_callback' => array( 'Smoobu_Blocks', 'calendar_render_callback' ),
			)
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'smoobu-calendar-block', 'smoobu-calendar' );
		}
	}

	/**
	 * Calendar block, visible in the frontend, rendering
	 *
	 * @param array $attributes block attributes.
	 * @return string
	 */
	public static function calendar_render_callback( $attributes ) {
		if ( ! empty( $attributes['property_id'] ) ) {
			$calendar = new Smoobu_Calendar( $attributes['property_id'], $attributes['layout'] );
			$calendar->run();

			// stream to output buffer in order to return it and not to print to screen immediately.
			ob_start();

			// load calendar template.
			Smoobu_Main::load_template(
				'calendar',
				array(
					'property_id' => $attributes['property_id'],
					'layout'      => $calendar->get_layout_json(),
				)
			);

			return ob_get_clean();
		}

		return false;
	}
}
