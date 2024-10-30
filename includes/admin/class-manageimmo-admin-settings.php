<?php

/**
 * @package ManageImmo
 * @since  1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Admin_Settings {

    /**
     * Hook in methods
     *
     * @since 1.0.0
     *
     * @return array
     */
    public static function init() {
        add_action( 'admin_init', array( __CLASS__, 'catch_settings_request' ) );
        add_action( 'update_option_manageimmo_options_settings', array( __CLASS__, 'maybe_activate_license' ), 10, 2 );
        add_action( 'update_option_manageimmo_options_settings', array( __CLASS__, 'maybe_clear_oauth_tokens' ), 10, 2 );
    }

    /**
     * Catch when a certain button is clicked and we have to do something. E.g. authorizing or fetching.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function catch_settings_request()
    {
        if( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if( isset( $_POST['authorize_application'] ) || ( isset( $_GET['oauth_verifier'] ) && isset( $_GET['oauth_token'] ) ) ) {
            ManageImmo()->api->authorize( admin_url( 'admin.php?page=manageimmo-options-settings' ) );
        }

        if( isset( $_POST['update_properties'] ) ) {
            manageimmo_create_or_update_all_properties();
        }

        if( isset( $_POST['update_openimmo_properties'] ) ) {
            manageimmo_create_or_update_all_openimmo_properties();
        }
    }

    /**
     * Maybe activate the license if the license key has changed.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function maybe_activate_license( $old_value, $value )
    {
        if( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Activate the license if the license key has changed.
        if( $old_value[ 'licensing_license_key' ] !== $value[ 'licensing_license_key' ] ) {
            manageimmo_activate_license();
        }
    }

    /**
     * Clear the Oauth tokens if API credentials are changed.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function maybe_clear_oauth_tokens( $old_value, $value ) {
        if( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if(
            $old_value[ 'immoscout24_api_environment' ] !== $value[ 'immoscout24_api_environment' ] ||
            $old_value[ 'immoscout24_api_consumer_key' ] !== $value[ 'immoscout24_api_consumer_key' ] ||
            $old_value[ 'immoscout24_api_consumer_secret' ] !== $value[ 'immoscout24_api_consumer_secret' ]
        ) {
            update_option( 'immoscout24_oauth_token', '' );
            update_option( 'immoscout24_oauth_token_secret', '' );
        }
    }

}

ManageImmo_Admin_Settings::init();