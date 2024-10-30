<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Install {

    /**
	 * Hook into actions and filters.
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
        add_action( 'init', array( __CLASS__, 'maybe_flush_rewrite_rules' ), 20 );
    }

    /**
	 * Check ManageImmo version and run the updater if required.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function check_version() {
        $manageimmo_version = get_option( 'manageimmo_version' );
		$requires_update    = version_compare( $manageimmo_version, MANAGEIMMO_VERSION, '<' );

		if( $requires_update ) {
			self::install();
		}
	}

	/**
	 * Install ManageImmo
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public static function install() {
        // Should be removed in the future.
        self::update_renamed_settings();

        self::create_files();
        self::create_cron_jobs();
        self::update_manageimmo_version();

        update_option( 'manageimmo_flush_rewrite_rules', true );
	}

    /**
     * Update renamed settings.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function update_renamed_settings() {
        // ImmoScout24 settings.
        $settings = get_option( 'manageimmo_options_settings' );
        $options  = array( 'api_environment', 'api_consumer_key', 'api_consumer_secret' );

        foreach( $options as $option ) {
            if( ! empty( $settings[ 'manageimmo_' . $option ] ) ) {
                $settings[ 'immoscout24_' . $option ] = $settings[ 'manageimmo_' . $option ];
            }
        }

        // ImmoScout24 oauth tokens.
        $old_token        = get_option( 'manageimmo_oauth_token' );
        $old_token_secret = get_option( 'manageimmo_oauth_token_secret' );

        update_option( 'manageimmo_options_settings', $settings );

        // ImmoScout24 oauth tokens.
        $new_token        = get_option( 'immoscout24_oauth_token' );
        $new_token_secret = get_option( 'immoscout24_oauth_token_secret' );

        if( $old_token && ! $new_token ) {
            add_option( 'immoscout24_oauth_token', $old_token );
        }

        if( $old_token_secret && ! $new_token_secret ) {
            add_option( 'immoscout24_oauth_token_secret', $old_token_secret );
        }
    }

    /**
	 * Create cron jobs.
     *
     * @since 1.0.0
     *
     * @return void
	 */
	private static function create_cron_jobs() {
		wp_clear_scheduled_hook( 'manageimmo_update_immoscout24_properties' );
		wp_clear_scheduled_hook( 'manageimmo_update_openimmo_properties' );

		wp_schedule_event( time() + DAY_IN_SECONDS, 'daily', 'manageimmo_update_immoscout24_properties' );
		wp_schedule_event( time() + HOUR_IN_SECONDS, 'hourly', 'manageimmo_update_openimmo_properties' );
	}

    /**
	 * Create files/directories.
     *
     * @since 1.0.0
     *
     * @return void
	 */
	private static function create_files() {
		$upload_dir = wp_get_upload_dir();

		$files = array(
			array(
				'base'    => $upload_dir['basedir'] . '/openimmo',
				'file'    => 'index.html',
				'content' => '',
			),
			array(
				'base'    => $upload_dir['basedir'] . '/openimmo',
				'file'    => '.htaccess',
				'content' => 'deny from all',
			),
		);

		foreach ( $files as $file ) {
			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				$file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'wb' );
				if ( $file_handle ) {
					fwrite( $file_handle, $file['content'] );
					fclose( $file_handle );
				}
			}
		}
	}

    /**
	 * Maybe flush rewrite rules
     *
     * @since 1.0.0
     *
     * @return void
	 */
    public static function maybe_flush_rewrite_rules() {
        if( get_option( 'manageimmo_flush_rewrite_rules' ) ) {
            flush_rewrite_rules();
            delete_option( 'manageimmo_flush_rewrite_rules' );
        }
    }

    /**
	 * Update ManageImmo version to current.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function update_manageimmo_version() {
		update_option( 'manageimmo_version', MANAGEIMMO_VERSION );
	}

}

ManageImmo_Install::init();