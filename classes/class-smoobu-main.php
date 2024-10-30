<?php
/**
 * Main plugin class
 *
 * @package smoobu-calendar
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No skiddies please!' );
}

/**
 * Main class
 */
final class Smoobu_Main {
	/**
	 * Class instance
	 *
	 * @var Smoobu_Main
	 */
	protected static $instance = null;

	/**
	 * Main instance
	 *
	 * @return Smoobu_Main
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
		// localization.
		add_action( 'plugins_loaded', array( $this, 'text_domain' ) );

		// register styling and scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

		// register admin styling and scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		// admin settings.
		add_action( 'init', array( 'Smoobu_Settings', 'instance' ) );

		// shortcodes.
		add_action( 'init', array( 'Smoobu_Calendar_Shortcode', 'instance' ) );

		// widgets.
		add_action( 'widgets_init', array( $this, 'widgets' ) );

		// gutenberg blocks.
		add_action( 'plugins_loaded', array( 'Smoobu_Blocks', 'instance' ) );

		// webhooks.
		add_action( 'init', array( 'Smoobu_Webhook', 'instance' ) );

		// AJAX actions.
		add_action( 'init', array( 'Smoobu_Ajax', 'instance' ) );
	}

	/**
	 * Localization
	 *
	 * @return void
	 */
	public function text_domain() {
		load_plugin_textdomain( 'smoobu-calendar', false, SMOOBU_NAME . '/languages/' );
	}

	/**
	 * Rewrite plugin template with theme template if exists under folder /theme-name/smoobu-calendar/
	 *
	 * @param string $template template name.
	 * @param array  $args arguments to pass to template.
	 * @return void
	 */
	public static function load_template( $template, $args = array() ) {
		// transfer required arguments.
		foreach ( $args as $key => $arg ) {
			${$key} = $arg;
		}

		// load template below.
		if ( file_exists( get_template_directory() . SMOOBU_NAME . '/' . $template . '.php' ) ) {
			// if overriden in theme.
			$load = get_template_directory() . SMOOBU_NAME . '/' . $template . '.php';
		} elseif ( file_exists( SMOOBU_PATH . 'views/' . $template . '.php' ) ) {
			// if exists at all.
			$load = SMOOBU_PATH . 'views/' . $template . '.php';
		}

		if ( ! empty( $load ) ) {
			include $load;
		} else {
			esc_html_e( 'Template not found', 'smoobu-calendar' );
		}
	}

	/**
	 * Register native styling and scripts
	 *
	 * @return void
	 */
	public function scripts() {
		// styles.
		wp_register_style( 'smoobu-calendar-css-main', SMOOBU_URI . 'build/css/main.css', array(), SMOOBU_VERSION );

		// all calendar themes.
		wp_register_style( 'smoobu-calendar-css-theme-default', SMOOBU_URI . 'assets/css/default/theme.css', array(), SMOOBU_VERSION );
		wp_register_style( 'smoobu-calendar-css-theme-dark', SMOOBU_URI . 'assets/css/dark/theme.css', array(), SMOOBU_VERSION );

		// scripts.
		wp_register_script( 'smoobu-calendar-js', SMOOBU_URI . 'build/js/main-min.js', array( 'jquery-ui-datepicker' ), SMOOBU_VERSION, true );
	}

	/**
	 * Register admin styling and scripts
	 *
	 * @return void
	 */
	public function admin_scripts() {
		// styles.
		wp_enqueue_style( 'smoobu-calendar-admin-css', SMOOBU_URI . 'assets/css/admin/main.css', array(), SMOOBU_VERSION );

		// scripts.
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'smoobu-calendar-admin-js', SMOOBU_URI . 'assets/js/admin/main.js', array( 'wp-color-picker', 'jquery-ui-datepicker' ), SMOOBU_VERSION, true );

		wp_localize_script(
			'smoobu-calendar-admin-js',
			'smoobu_calendar_lists',
			array(
				'properties' => Smoobu_Utility::get_available_properties(),
				'layouts'    => Smoobu_Utility::get_available_layouts(),
			)
		);

		wp_localize_script(
			'smoobu-calendar-admin-js',
			'smoobu_calendar_ajax',
			array(
				'ajaxurl'                => admin_url( 'admin-ajax.php' ),
				'theme'                  => Smoobu_Utility::get_current_theme(),
				'connection_check_nonce' => wp_create_nonce( 'connection_check_nonce' ),
				'styling_nonce'          => wp_create_nonce( 'styling_nonce' ),
				'connection_success'     => __( 'Connection successful. Do not forget to save your settings.', 'smoobu-calendar' ),
				'connection_error'       => __( 'Connection failed. Error returned: ', 'smoobu-calendar' ),
			)
		);

		// front-end related scripts to show calendar preview in settings.
		// styles.
		wp_register_style( 'smoobu-calendar-css-main', SMOOBU_URI . 'build/css/main.css', array(), SMOOBU_VERSION );

		// all calendar themes.
		wp_register_style( 'smoobu-calendar-css-theme-default', SMOOBU_URI . 'assets/css/default/theme.css', array(), SMOOBU_VERSION );
		wp_register_style( 'smoobu-calendar-css-theme-dark', SMOOBU_URI . 'assets/css/dark/theme.css', array(), SMOOBU_VERSION );
	}


	/**
	 * Register widgets
	 *
	 * @return void
	 */
	public function widgets() {
		register_widget( 'Smoobu_Calendar_Widget' );
	}
}
