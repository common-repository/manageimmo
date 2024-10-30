<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Admin_Menus {

    /**
     * Hook into actions and filters.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        // Add menus.
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_menu', array( $this, 'settings_menu' ) );
    }

    /**
     * Add admin menu page.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function admin_menu() {
        add_menu_page( __( 'ManageImmo', 'manageimmo' ), __( 'ManageImmo', 'manageimmo' ), 'manage_options', 'manageimmo', '', 'dashicons-admin-home', 58 );
        add_submenu_page( 'manageimmo', __( 'Property types', 'manageimmo' ), __( 'Types', 'manageimmo' ), 'manage_options', 'edit-tags.php?taxonomy=property_type');
    }

    /**
     * Add settings submenu page.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function settings_menu() {
        ManageImmo()->settings->add_settings_page( array(
			'parent_slug' => 'manageimmo',
			'page_title'  => __( 'ManageImmo Settings', 'manageimmo' ),
			'menu_title'  => __( 'Settings', 'manageimmo' ),
			'capability'  => 'manage_options',
		) );
    }

}

new ManageImmo_Admin_Menus();