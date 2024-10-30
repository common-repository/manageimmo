<?php

/**
 * Plugin Name:       ManageImmo
 * Description:       An Immoscout24 API integration for WordPress.
 * Version:           1.1.9
 * Requires at least: 5.3
 * Requires PHP:      7.4
 * Author:            Manageimmo
 * Author URI:        https://manageimmo.de/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       manageimmo
 * Domain Path:       /i18n/languages/
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'MANAGEIMMO_PLUGIN_FILE' ) ) {
	define( 'MANAGEIMMO_PLUGIN_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/woocommerce/action-scheduler/action-scheduler.php';

// Include the main ManageImmo class.
if ( ! class_exists( 'ManageImmo', false ) ) {
	include_once dirname( MANAGEIMMO_PLUGIN_FILE ) . '/includes/class-manageimmo.php';
}

ManageImmo::instance();

if ( ! function_exists( 'ManageImmo' ) ) {
    /**
     * Returns the main instance of ManageImmo.
     *
     * @since  1.0.0
     *
     * @return ManageImmo
     */
    function ManageImmo() {
        return ManageImmo::instance();
    }
}
