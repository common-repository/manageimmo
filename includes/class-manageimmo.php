<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

final class ManageImmo {

	/**
	 * The single instance of the class.
	 *
	 * @var ManageImmo
	 */
	protected static $_instance = null;

	/**
	 * Settings instance.
	 *
	 * @var WordPressSettingsFramework
	 */
	public $settings = null;

	/**
	 * API instance.
	 *
	 * @var ManageImmo_API
	 */
	public $api = null;


    /**
	 * @since 1.0.0
     *
	 * @return ManageImmo
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

    /**
	 * Constructor
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

    /**
	 * Define constants
     *
     * @since 1.0.0
     *
     * @return void
	 */
	private function define_constants() {

        $plugin_data = get_file_data( MANAGEIMMO_PLUGIN_FILE, array(
			'Version' => 'Version',
		) );

		$this->define( 'MANAGEIMMO_ABSPATH', dirname( MANAGEIMMO_PLUGIN_FILE ) . '/' );
		$this->define( 'MANAGEIMMO_VERSION', $plugin_data['Version'] );

	}

    /**
	 * Include required core files used in admin and on the frontend.
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public function includes() {

		/**
		 * Core classes
		 */
		include_once MANAGEIMMO_ABSPATH . 'includes/class-manageimmo-settings.php';
		include_once MANAGEIMMO_ABSPATH . 'includes/manageimmo-core-functions.php';
		include_once MANAGEIMMO_ABSPATH . 'includes/class-manageimmo-post-types.php';
		include_once MANAGEIMMO_ABSPATH . 'includes/class-manageimmo-install.php';
		include_once MANAGEIMMO_ABSPATH . 'includes/class-manageimmo-post-data.php';
		include_once MANAGEIMMO_ABSPATH . 'includes/class-manageimmo-ajax.php';
		include_once MANAGEIMMO_ABSPATH . 'includes/class-manageimmo-shortcodes.php';
		include_once MANAGEIMMO_ABSPATH . 'includes/class-manageimmo-geo-query.php';

		if ( is_admin() ) {
			include_once MANAGEIMMO_ABSPATH . 'includes/admin/class-manageimmo-admin.php';
		}

		if ( ! is_admin() ) {
			$this->frontend_includes();
		}

    }

	/**
	 * Include required frontend files.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function frontend_includes() {
		include_once MANAGEIMMO_ABSPATH . 'includes/class-manageimmo-frontend-scripts.php';
		include_once MANAGEIMMO_ABSPATH . 'includes/class-manageimmo-template-loader.php';
	}

    /**
     * Hook into actions and filters.
     *
     * @since 1.0.0
     *
     * @return void
	 */
	private function init_hooks() {
		register_activation_hook( MANAGEIMMO_PLUGIN_FILE, array( 'ManageImmo_Install', 'install' ) );

		add_action( 'init', array( $this, 'init' ), 5 );
		add_action( 'init', array( 'ManageImmo_Shortcodes', 'init' ) );
	}

    /**
     * Define constant if not already set.
     *
     * @since 1.0.0
     *
     * @param string $name
	 * @param mixed  $value
     * @return void
     */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Init ManageImmo when WordPress Initialises
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		// Set up localisation.
		$this->load_plugin_textdomain();

		manageimmo_load_settings();
		manageimmo_load_api();
	}

	/**
	 * Load Localisation files
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'manageimmo', false, plugin_basename( dirname( MANAGEIMMO_PLUGIN_FILE ) ) . '/i18n/languages' );
	}

	/**
	 * Get the plugin url
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', MANAGEIMMO_PLUGIN_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( MANAGEIMMO_PLUGIN_FILE ) );
	}

	/**
	 * Initialize the WordPressSettingsFramework class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function initialize_settings() {
		if ( is_null( $this->settings ) || ! $this->settings instanceof WordPressSettingsFramework ) {
			require_once MANAGEIMMO_ABSPATH . '/lib/wp-settings-framework/wp-settings-framework.php';

			$this->settings = new WordPressSettingsFramework( null, 'manageimmo_options' );
		}
	}

	/**
	 * Initialize the API class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function initialize_api() {
		if ( is_null( $this->api ) || ! $this->api instanceof ManageImmo_API ) {
			require_once __DIR__ . '/class-manageimmo-api.php';

			$settings = ManageImmo()->settings->get_settings();

			$key = $settings['immoscout24_api_consumer_key'];
			$secret = $settings['immoscout24_api_consumer_secret'];

			$this->api = new ManageImmo_API( $key, $secret );

			if( 'live' === $settings['immoscout24_api_environment'] ) {
				$this->api->disable_sandbox();
			}
		}
	}

}