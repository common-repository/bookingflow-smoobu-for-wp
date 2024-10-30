<?php
/**
 * Utility class
 *
 * @package smoobu-calendar
 */

/**
 * Utility class
 */
class Smoobu_Utility {
	/**
	 * Return array of available calendar layouts
	 *
	 * @return array
	 */
	public static function get_available_layouts() {
		$layouts = array(
			'1x3' => '1 x 3',
			'1x2' => '1 x 2',
			'1x1' => '1 x 1',
			'2x1' => '2 x 1',
			'3x1' => '3 x 1',
		);

		$layouts = apply_filters( 'smoobu_available_layouts', $layouts );

		return $layouts;
	}

	/**
	 * Print available calendar layouts
	 *
	 * @param string $current current layout.
	 * @return void
	 */
	public static function available_layouts_options( $current = '' ) {
		$layouts = self::get_available_layouts();

		foreach ( $layouts as $key => $val ) {
			?>
			<option value="<?php echo esc_attr( $key ); ?>" <?php echo ( $current === $key ? 'selected' : '' ); ?>><?php echo esc_html( $val ); ?></option>
			<?php
		}
	}

	/**
	 * Return array of available properties
	 *
	 * @return array
	 */
	public static function get_available_properties() {
		$available_properties = json_decode( get_option( 'smoobu_properties_list' ) );

		$available_properties = apply_filters( 'smoobu_available_properties', $available_properties );

		return $available_properties;
	}

	/**
	 * Print available properties
	 *
	 * @param int $current_id current property ID.
	 * @return void
	 */
	public static function available_properties_options( $current_id = 0 ) {
		global $wpdb;

		$options    = '';
		$properties = self::get_available_properties();

		if ( ! empty( $properties ) ) {
			foreach ( $properties as $property ) {
				?>
				<option value="<?php echo esc_attr( $property->id ); ?>" <?php echo ( $property->id === $current_id ? 'selected' : '' ); ?>><?php echo esc_attr( $property->name ); ?></option>
				<?php
			}
		} else {
			?>
			<option value="" disabled><?php esc_html_e( 'Please update your properties list under Settings -> Smoobu Calendar', 'smoobu-calendar' ); ?></option>
			<?php
		}
	}

	/**
	 * Get current theme
	 *
	 * @return string
	 */
	public static function get_current_theme() {
		$theme = get_option( 'smoobu_calendar_theme' );
		$theme = apply_filters( 'smoobu_active_theme', $theme );

		if ( empty( $theme ) ) {
			return 'default';
		} else {
			return $theme;
		}
	}

	/**
	 * Return array of available calendar themes
	 *
	 * @return array
	 */
	public static function get_available_themes() {
		$themes = array(
			'default' => 'Default',
			'dark'    => 'Dark',
		);

		$themes = apply_filters( 'smoobu_available_theme', $themes );

		return $themes;
	}

	/**
	 * Print available calendar themes
	 *
	 * @param string $current current layout.
	 * @return void
	 */
	public static function available_themes_options( $current = '' ) {
		$layouts = self::get_available_themes();

		foreach ( $layouts as $key => $val ) {
			?>
			<option value="<?php echo esc_attr( $key ); ?>" <?php echo ( $current === $key ? 'selected' : '' ); ?>><?php echo esc_html( $val ); ?></option>
			<?php
		}
	}

	/**
	 * Get if full width layout is set
	 *
	 * @return bool
	 */
	public static function get_is_full_width() {
		$full_width = get_option( 'smoobu_full_width' );

		$full_width = apply_filters( 'smoobu_is_full_width', $full_width );

		if ( ! empty( $full_width ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Return formatted custom CSS
	 *
	 * @param array $styles custom styles array.
	 * @return string
	 */
	public static function get_custom_css( $styles = array() ) {
		$css        = '';
		$full_width = self::get_is_full_width();

		if ( empty( $styles ) ) {
			$styles = self::get_custom_theme_styling();
		}

		if ( true === $full_width ) {
			$css .= '.smoobu-calendar .ui-datepicker-inline { min-width: 180px; width: 100% !important; }';
		}

		// `empty` intentionally, not a mistype.
		if ( empty( $styles['border_shadow'] ) ) {
			$css .= '.smoobu-calendar .ui-widget.ui-widget-content { -webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none; }';
		}

		if ( ! empty( $styles['border_radius'] ) ) {
			$css .= '.smoobu-calendar .ui-widget.ui-widget-content { -webkit-border-radius: ' . $styles['border_radius'] . 'px; -moz-border-radius: ' . $styles['border_radius'] . 'px; border-radius: ' . $styles['border_radius'] . 'px; }';
		}

		if ( ! empty( $styles['colors']['general_bg'] ) ) {
			$css .= '.smoobu-calendar .ui-widget-content { background: ' . $styles['colors']['general_bg'] . '; }';
		}

		if ( ! empty( $styles['colors']['header_bg'] ) ) {
			$css .= '.smoobu-calendar .ui-widget-header { background: ' . $styles['colors']['header_bg'] . '; }';
		}

		if ( ! empty( $styles['colors']['header'] ) ) {
			$css .= '.smoobu-calendar .ui-widget-header { color: ' . $styles['colors']['header'] . '; }';
		}

		if ( ! empty( $styles['colors']['days'] ) ) {
			$css .= '.smoobu-calendar .ui-widget-header th span { color: ' . $styles['colors']['days'] . '; }';
			$css .= '.smoobu-calendar .ui-datepicker-calendar tr th { color: ' . $styles['colors']['days'] . '; }';
		}

		if ( ! empty( $styles['colors']['regular_bg'] ) ) {
			$css .= '.smoobu-calendar .ui-state-default,
			.smoobu-calendar .ui-widget-content .ui-state-default,
			.smoobu-calendar .ui-widget-header .ui-state-default,
			.smoobu-calendar .ui-button,
			html .smoobu-calendar .ui-button.ui-state-disabled:hover, 
			html .smoobu-calendar .ui-button.ui-state-disabled:active { background: ' . $styles['colors']['regular_bg'] . '; }';
		}

		if ( ! empty( $styles['colors']['regular'] ) ) {
			$css .= '.smoobu-calendar .ui-state-default,
			.smoobu-calendar .ui-widget-content .ui-state-default,
			.smoobu-calendar .ui-widget-header .ui-state-default,
			.smoobu-calendar .ui-button,
			html .smoobu-calendar .ui-button.ui-state-disabled:hover, 
			html .smoobu-calendar .ui-button.ui-state-disabled:active { color: ' . $styles['colors']['regular'] . '; }';
			$css .= '.smoobu-calendar .ui-state-default a,
			.smoobu-calendar .ui-state-default a:link,
			.smoobu-calendar .ui-state-default a:visited,
			a.smoobu-calendar .ui-button,
			a:link.smoobu-calendar .ui-button,
			a:visited.smoobu-calendar .ui-button,
			.smoobu-calendar .ui-button { color: ' . $styles['colors']['regular'] . '; }';
		}

		if ( ! empty( $styles['colors']['disabled_bg'] ) ) {
			$css .= '.smoobu-calendar .ui-state-disabled .ui-state-default,
			.smoobu-calendar .ui-widget-content .ui-state-disabled .ui-state-default,
			.smoobu-calendar .ui-widget-header .ui-state-disabled .ui-state-default { background-color: ' . $styles['colors']['disabled_bg'] . '; }';
		}

		if ( ! empty( $styles['colors']['disabled'] ) ) {
			$css .= '.smoobu-calendar .ui-state-disabled .ui-state-default,
			.smoobu-calendar .ui-widget-content .ui-state-disabled .ui-state-default,
			.smoobu-calendar .ui-widget-header .ui-state-disabled .ui-state-default { color: ' . $styles['colors']['disabled'] . '; }';
		}

		if ( ! empty( $styles['colors']['highlighted_bg'] ) ) {
			$css .= '.smoobu-calendar .ui-state-highlight,
			.smoobu-calendar .ui-widget-content .ui-state-highlight,
			.smoobu-calendar .ui-widget-header .ui-state-highlight { background: ' . $styles['colors']['highlighted_bg'] . '; }';

			$css .= '.smoobu-calendar .ui-state-checked { background: ' . $styles['colors']['highlighted_bg'] . '; }';
		}

		if ( ! empty( $styles['colors']['highlighted'] ) ) {
			$css .= '.smoobu-calendar .ui-state-highlight,
			.smoobu-calendar .ui-widget-content .ui-state-highlight,
			.smoobu-calendar .ui-widget-header .ui-state-highlight { color: ' . $styles['colors']['highlighted'] . '; }';
		}

		if ( ! empty( $styles['colors']['hover_bg'] ) ) {
			$css .= '.smoobu-calendar .ui-state-hover,
			.smoobu-calendar .ui-widget-content .ui-state-hover,
			.smoobu-calendar .ui-widget-header .ui-state-hover,
			.smoobu-calendar .ui-state-focus,
			.smoobu-calendar .ui-widget-content .ui-state-focus,
			.smoobu-calendar .ui-widget-header .ui-state-focus,
			.smoobu-calendar .ui-button:hover,
			.smoobu-calendar .ui-button:focus { background: ' . $styles['colors']['hover_bg'] . '; }';
		}

		if ( ! empty( $styles['colors']['hover'] ) ) {
			$css .= '.smoobu-calendar .ui-state-hover,
			.smoobu-calendar .ui-widget-content .ui-state-hover,
			.smoobu-calendar .ui-widget-header .ui-state-hover,
			.smoobu-calendar .ui-state-focus,
			.smoobu-calendar .ui-widget-content .ui-state-focus,
			.smoobu-calendar .ui-widget-header .ui-state-focus,
			.smoobu-calendar .ui-button:hover,
			.smoobu-calendar .ui-button:focus { color: ' . $styles['colors']['hover'] . '; }';

			$css .= '.smoobu-calendar .ui-state-hover a,
			.smoobu-calendar .ui-state-hover a:hover,
			.smoobu-calendar .ui-state-hover a:link,
			.smoobu-calendar .ui-state-hover a:visited,
			.smoobu-calendar .ui-state-focus a,
			.smoobu-calendar .ui-state-focus a:hover,
			.smoobu-calendar .ui-state-focus a:link,
			.smoobu-calendar .ui-state-focus a:visited,
			a.smoobu-calendar .ui-button:hover,
			a.smoobu-calendar .ui-button:focus { color: ' . $styles['colors']['hover'] . '; }';
		}

		if ( ! empty( $styles['colors']['selected_bg'] ) ) {
			$css .= '.smoobu-calendar .ui-state-active,
			.smoobu-calendar .ui-widget-content .ui-state-active,
			.smoobu-calendar .ui-widget-header .ui-state-active,
			a.smoobu-calendar .ui-button:active,
			.smoobu-calendar .ui-button:active,
			.smoobu-calendar .ui-button.ui-state-active:hover { background: ' . $styles['colors']['selected_bg'] . '; }';
		}

		if ( ! empty( $styles['colors']['selected'] ) ) {
			$css .= '.smoobu-calendar .ui-state-active,
			.smoobu-calendar .ui-widget-content .ui-state-active,
			.smoobu-calendar .ui-widget-header .ui-state-active,
			a.smoobu-calendar .ui-button:active,
			.smoobu-calendar .ui-button:active,
			.smoobu-calendar .ui-button.ui-state-active:hover { color: ' . $styles['colors']['selected'] . '; }';
		}

		// remove new lines for optimization.
		$css = trim( preg_replace( '/\s+/', ' ', $css ) );

		$css = apply_filters( 'smoobu_custom_css', $css );

		return $css;
	}

	/**
	 * Get custom styling settings
	 *
	 * @return array
	 */
	public static function get_theme_styling() {
		// get custom styling.
		$atts = self::get_custom_theme_styling();

		// get default styling.
		$default = self::get_default_theme_styling();

		// replace default values with saved styling, if exists, recursively.
		$out = array_replace_recursive( $default, $atts );

		$out = apply_filters( 'smoobu_theme_styling', $out );

		return $out;
	}

	/**
	 * Get customized styling array
	 *
	 * @return array
	 */
	public static function get_custom_theme_styling() {
		// get saved styling.
		$atts = array(
			'border_shadow' => get_option( 'smoobu_custom_styling_border_shadow' ),
			'border_radius' => get_option( 'smoobu_custom_styling_border_radius' ),
			'colors'        => array(
				'general_bg'     => get_option( 'smoobu_custom_styling_color_general_bg' ),
				'header_bg'      => get_option( 'smoobu_custom_styling_color_header_bg' ),
				'header'         => get_option( 'smoobu_custom_styling_color_header' ),
				'days'           => get_option( 'smoobu_custom_styling_color_days' ),
				'regular_bg'     => get_option( 'smoobu_custom_styling_color_regular_bg' ),
				'regular'        => get_option( 'smoobu_custom_styling_color_regular' ),
				'disabled_bg'    => get_option( 'smoobu_custom_styling_color_disabled_bg' ),
				'disabled'       => get_option( 'smoobu_custom_styling_color_disabled' ),
				'highlighted_bg' => get_option( 'smoobu_custom_styling_color_highlighted_bg' ),
				'highlighted'    => get_option( 'smoobu_custom_styling_color_highlighted' ),
				'hover_bg'       => get_option( 'smoobu_custom_styling_color_hover_bg' ),
				'hover'          => get_option( 'smoobu_custom_styling_color_hover' ),
				'selected_bg'    => get_option( 'smoobu_custom_styling_color_selected_bg' ),
				'selected'       => get_option( 'smoobu_custom_styling_color_selected' ),
			),
		);

		// remove empty values (when user use default settings).
		$atts['colors'] = array_filter( $atts['colors'] );

		$atts = apply_filters( 'smoobu_custom_theme_styling', $atts );

		return $atts;
	}

	/**
	 * Get default color codes and other styling for the current theme
	 *
	 * @return array
	 */
	public static function get_default_theme_styling() {
		$current = self::get_current_theme();

		$styles = array(
			'default' => array(
				'border_shadow' => true,
				'border_radius' => '3',
				'colors'        => array(
					'general_bg'     => '#FFFFFF',
					'header_bg'      => '#FFFFFF',
					'header'         => '#333333',
					'days'           => '#333333',
					'regular_bg'     => '#F6F6F6',
					'regular'        => '#454545',
					'disabled_bg'    => '#FBFBFB',
					'disabled'       => '#CACACA',
					'highlighted_bg' => '#FFFA90',
					'highlighted'    => '#2B2B2B',
					'hover_bg'       => '#EDEDED',
					'hover'          => '#2B2B2B',
					'selected_bg'    => '#007FFF',
					'selected'       => '#FFFFFF',
				),
			),
			'dark'    => array(
				'border_shadow' => true,
				'border_radius' => '3',
				'colors'        => array(
					'general_bg'     => '#000000',
					'header_bg'      => '#333333',
					'header'         => '#FFFFFF',
					'days'           => '#DDDDDD',
					'regular_bg'     => '#555555',
					'regular'        => '#EEEEEE',
					'disabled_bg'    => '#222222',
					'disabled'       => '#666666',
					'highlighted_bg' => '#EEEEEE',
					'highlighted'    => '#2E7DB2',
					'hover_bg'       => '#777777',
					'hover'          => '#FFFFFF',
					'selected_bg'    => '#F58400',
					'selected'       => '#FFFFFF',
				),
			),
		);

		$styles = apply_filters( 'smoobu_default_theme_styling', $styles );

		if ( ! empty( $current ) && ! empty( $styles[ $current ] ) ) {
			return $styles[ $current ];
		} else {
			return array();
		}
	}

	/**
	 * Abbreviations translations used in loops
	 *
	 * @return array
	 */
	public static function get_abbr_translations() {
		$translations = array(
			'general_bg'     => __( 'General Background Color', 'smoobu-calendar' ),
			'header_bg'      => __( 'Header Background Color', 'smoobu-calendar' ),
			'header'         => __( 'Header Font Color', 'smoobu-calendar' ),
			'days'           => __( 'Days Abbr Font Color', 'smoobu-calendar' ),
			'regular_bg'     => __( 'Regular Day Field Background Color ', 'smoobu-calendar' ),
			'regular'        => __( 'Regular Day Field Font Color', 'smoobu-calendar' ),
			'disabled_bg'    => __( 'Disabled Day Field Background Color ', 'smoobu-calendar' ),
			'disabled'       => __( 'Disabled Day Field Font Color', 'smoobu-calendar' ),
			'highlighted_bg' => __( 'Highlighted Day Field Background Color', 'smoobu-calendar' ),
			'highlighted'    => __( 'Highlighted Day Field Font Color', 'smoobu-calendar' ),
			'hover_bg'       => __( 'Hovered Day Field Background Color', 'smoobu-calendar' ),
			'hover'          => __( 'Hovered Day Field Font Color', 'smoobu-calendar' ),
			'selected_bg'    => __( 'Selected Day Field Background Color', 'smoobu-calendar' ),
			'selected'       => __( 'Selected Day Field Font Color', 'smoobu-calendar' ),
		);

		$translations = apply_filters( 'smoobu_abbr_translations', $translations );

		return $translations;
	}

	/**
	 * Return array of available FAQ questions and answers
	 *
	 * @return array
	 */
	public static function get_faq_content() {
		$content = array(
			array(
				'question' => __( 'What is Smoobu Calendar plugin?', 'smoobu-calendar' ),
				'answer'   => __( 'Smoobu Calendar is a plugin to display a calendar with busy/available days highlighted for a chosen property.', 'smoobu-calendar' ),
			),
			array(
				'question' => __( 'How to get started?', 'smoobu-calendar' ),
				'answer'   => '<div>' .
				sprintf(
					// translators: various general settings link.
					__(
						'1. To get started, you need to go to Smoobu Calendar settings sub-page <a href="%s">General Settings</a>, fill in your <b>API key</b> and save it. In order to verify your API key is correct, please click <b>Check Connection</b> button - you should see a <b>Connection successful</b> message a few seconds later.',
						'smoobu-calendar'
					),
					esc_url( menu_page_url( 'smoobu-calendar-settings', false ) )
				) .
				'</div><div>' .
				__(
					'2. When you save your API key, your properties and their availability is updated automatically. To update it later, please see FAQ questions <b>How to update my properties list?</b> and <b>How to update my properties availability?</b>',
					'smoobu-calendar'
				) .
				'</div><div> ' .
				__(
					'3. To add a calendar, you can choose 1 out of 3 ways. You can add it to a sidebar by using <b>Smoobu Calendar Widget</b> widget. Alternatively, you can add it to a post or a page content by using a <b>Smoobu Calendar</b> Gutenberg block or by adding a shortcode.',
					'smoobu-calendar'
				) .
				' ' .
				sprintf(
					// translators: my properties settings link.
					__(
						'A list of all possible shortcodes can be found in <a href="%s">My Properties</a> page.</div>',
						'smoobu-calendar'
					),
					esc_url( menu_page_url( 'smoobu-calendar-settings-properties', false ) )
				),
			),
			array(
				'question' => __( 'Where can I find my API key?', 'smoobu-calendar' ),
				'answer'   => '<div>' .
				__(
					'You can find your API keys in Smoobu admin panel. Go to Settings and scroll down until you see a "For Developers" section.',
					'smoobu-calendar'
				) .
				'</div>
				<div><img src="' . esc_url( SMOOBU_URI . 'assets/images/api-key-instruction-1.png' ) . '"></div>
				<div> ' .
				__(
					'Simply copy the content of the <b>API key</b> input.',
					'smoobu-calendar'
				) .
				'</div>
				<div><img src="' . esc_url( SMOOBU_URI . 'assets/images/api-key-instruction-2.png' ) . '"></div>',
			),
			array(
				'question' => __( 'What is a webhook?', 'smoobu-calendar' ),
				'answer'   => '<div>' .
				__(
					'Webhook is a way to automatically push properties availability updates from Smoobu to your webpage. You will get automatic availability updates only if you have your webhook url set in the Smoobu admin panel.',
					'smoobu-calendar'
				) .
				'</div><div>' .
				sprintf(
					// translators: settings page link and screenshot.
					__(
						'To find your webhook url, go to <a href="%1$s">Webhook</a> page. Copy the url, paste it under <b>Developers</b> settings in Smoobu admin panel (also see <b>Where can I find my API key?</b>) and save.',
						'smoobu-calendar'
					),
					esc_url( menu_page_url( 'smoobu-calendar-settings-webhook', false ) )
				) .
				'</div>
				<div><img src="' . esc_url( SMOOBU_URI . 'assets/images/webhook-instruction-1.png' ) . '"></div>',
			),
			array(
				'question' => __( 'How to update my properties list?', 'smoobu-calendar' ),
				'answer'   => sprintf(
					// translators: data renewal settings page link.
					__(
						'You can update your properties list by going to <a href="%s">Data Renewal</a> page and clicking <b>Update Properties List</b> button.',
						'smoobu-calendar'
					),
					esc_url( menu_page_url( 'smoobu-calendar-settings-renewal', false ) )
				),
			),
			array(
				'question' => __( 'How to update my properties availability?', 'smoobu-calendar' ),
				'answer'   => sprintf(
					// translators: data renewal settings page link.
					__(
						'You can update your properties availability by going to <a href="%s">Data Renewal</a> page and clicking <b>Update Properties Availability</b> button. Alternatively, you can set up your webhook to get availability updates automatically (see <b>What is a webhook?</b>).',
						'smoobu-calendar'
					),
					esc_url( menu_page_url( 'smoobu-calendar-settings-renewal', false ) )
				),
			),
			array(
				'question' => __( 'What is calendar layout?', 'smoobu-calendar' ),
				'answer'   => __(
					'Calendar layout is a setting to choose how many rows and columns of months you want the calendar to have. The first number indicates number of rows and the second - number of columns. Currently, you can choose from 1 row x 3 columns to 3 rows x 1 colmun layout.',
					'smoobu-calendar'
				),
			),
		);

		return $content;
	}
}
