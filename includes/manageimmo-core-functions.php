<?php

/**
 * @package ManageImmo
 * @since  1.0.0
 */

defined( 'ABSPATH' ) || exit;

require MANAGEIMMO_ABSPATH . 'includes/manageimmo-attachment-functions.php';
require MANAGEIMMO_ABSPATH . 'includes/manageimmo-property-functions.php';
require MANAGEIMMO_ABSPATH . 'includes/manageimmo-immoscout24-functions.php';
require MANAGEIMMO_ABSPATH . 'includes/manageimmo-openimmo-functions.php';
require MANAGEIMMO_ABSPATH . 'includes/manageimmo-google-api-functions.php';
require MANAGEIMMO_ABSPATH . 'includes/manageimmo-license-functions.php';

/**
 * Load the settings functionality.
 *
 * @since 1.0.0
 *
 * @return void
 */
function manageimmo_load_settings() {
    ManageImmo()->initialize_settings();
}

/**
 * Load the API functionality.
 *
 * @since 1.0.0
 *
 * @return void
 */
function manageimmo_load_api() {
    ManageImmo()->initialize_api();
}

/**
 * Get other templates.
 *
 * @since 1.0.0
 *
 * @return string $template_name
 * @return void
 */
function manageimmo_get_template( $template_name, $args = array() ) {
    $template = manageimmo_locale_template( $template_name );

    $action_args = array(
		'template_name' => $template_name,
		'located'       => $template,
		'args'          => $args,
	);

	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

    include $action_args['located'];
}

/**
 * Locate a template.
 *
 * @since 1.0.0
 *
 * @param  string $template_name
 * @return string
 */
function manageimmo_locale_template( $template_name ) {
    $default_path = manageimmo()->plugin_path() . '/templates/';

    return $default_path . $template_name;
}