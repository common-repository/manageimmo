<?php

/**
 * @package ManageImmo
 * @since  1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Admin_Assets {

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
    }

    /**
     * Enqueue styles
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function admin_styles() {

        $screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

        wp_register_style( 'immoscout_admin_styles', ManageImmo()->plugin_url() . '/assets/css/admin.min.css', array(), MANAGEIMMO_VERSION );

        if( 'manageimmo_page_manageimmo-options-settings' === $screen_id ) {
            wp_enqueue_style( 'immoscout_admin_styles' );
        }

    }

    /**
     * Enqueue styles
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function admin_scripts() {

        $screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

    }

}

new ManageImmo_Admin_Assets();