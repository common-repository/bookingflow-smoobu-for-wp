<?php
/**
 * Calendar related functions
 *
 * @package smoobu-calendar
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No skiddies please!' );
}

/**
 * Calendar helper functions
 */
class Smoobu_Calendar {
	/**
	 * Property ID
	 *
	 * @var integer
	 */
	private $property_id = 0;

	/**
	 * Calendar layout
	 *
	 * @var string
	 */
	private $layout = '';

	/**
	 * Default calendar layout
	 *
	 * @var array
	 */
	private $default_layout = array( 1, 3 );

	/**
	 * Busy days array
	 *
	 * @var array
	 */
	private $busy_days = array();

	/**
	 * Class constructor
	 *
	 * @param int    $property_id property ID.
	 * @param string $layout calendar layout.
	 */
	public function __construct( $property_id = 0, $layout = '' ) {
		$this->property_id = $property_id;

		$this->set_layout( $layout );
	}

	/**
	 * Initialize calendar related functions
	 *
	 * @return void
	 */
	public function run() {
		$this->set_busy_days();
		$this->enqueue_scripts();
	}

	/**
	 * Return calendar layout JSON encoded value
	 *
	 * @return array
	 */
	public function get_layout_json() {
		return wp_json_encode( $this->layout );
	}

	/**
	 * Set calendar layout (no of rows and cols)
	 *
	 * @param string $layout layout string, default 1x3.
	 * @return void
	 */
	private function set_layout( $layout ) {
		// form an array of measures.
		$layout_arr = array();

		if ( ! empty( $layout ) ) {
			$layout_tmp = explode( 'x', $layout );

			if ( 2 === count( $layout_tmp ) ) {
				$rows = (int) $layout_tmp[0];
				$cols = (int) $layout_tmp[1];

				if ( $rows > 0 && $cols > 0 ) {
					$layout_arr = array( $rows, $cols );
				}
			}
		}

		$layout_arr = apply_filters( 'smoobu_set_calendar_layout', $layout_arr );

		if ( ! empty( $layout_arr ) ) {
			$this->layout = $layout_arr;
		} else {
			// if layout is not set, apply default 1x3 value.
			$default_layout = apply_filters( 'smoobu_default_calendar_layout', $this->default_layout );

			$this->layout = $default_layout;
		}
	}

	/**
	 * Set busy days array
	 *
	 * @return void
	 */
	private function set_busy_days() {
		global $wpdb;

		if ( ! empty( $this->property_id ) ) {
			$days = wp_cache_get( 'smoobu_busy_days_' . $this->property_id );

			if ( false === $days ) {
				// phpcs:ignore
				$days = $wpdb->get_col(
					$wpdb->prepare(
						"SELECT busy_date FROM {$wpdb->prefix}smoobu_calendar_availability WHERE property_id = %d",
						$this->property_id
					)
				);

				wp_cache_set( 'smoobu_busy_days_' . $this->property_id, $days );
			}

			$days = apply_filters( 'smoobu_set_calendar_busy_days', $days );

			$this->busy_days = $days;
		}
	}

	/**
	 * Add busy days JS array for the particular property
	 * Necessary when there are more than one calendar in the same page.
	 * Using wp_localize_script we add a different busy days variable for each property.
	 *
	 * @return void
	 */
	private function enqueue_scripts() {
		// required.
		wp_enqueue_style( 'smoobu-calendar-css-main' );
		wp_enqueue_script( 'smoobu-calendar-js' );

		// run localization for datepicker manually because it does not get fired automatically as we enqueue scripts not within the relevant hook.
		wp_localize_jquery_ui_datepicker();

		// user chosen calendar theme.
		$theme = Smoobu_Utility::get_current_theme();

		wp_enqueue_style( 'smoobu-calendar-css-theme-' . $theme );

		// add custom styling.
		$this->enqueue_custom_styles();

		// initialize busy days of the property.
		if ( is_numeric( $this->property_id ) && $this->property_id > 0 ) {
			wp_localize_script(
				'smoobu-calendar-js',
				'smoobu_calendar_attributes_' . $this->property_id,
				array(
					'busy_days' => $this->busy_days,
				)
			);
		}
	}

	/**
	 * Enqueue custom styling
	 *
	 * @param bool $force whether to force load custom styles.
	 * @return void
	 */
	public function enqueue_custom_styles( $force = false ) {
		global $smoobu_custom_styles_loaded;

		// load custom styles only if applicable or forced.
		if ( ( true === $force || ! empty( get_option( 'smoobu_custom_styling' ) ) ) && empty( $smoobu_custom_styles_loaded ) ) {
			$smoobu_custom_styles_loaded = true;

			$theme = Smoobu_Utility::get_current_theme();
			$css   = Smoobu_Utility::get_custom_css();

			wp_add_inline_style( 'smoobu-calendar-css-theme-' . $theme, $css );
		}
	}
}
