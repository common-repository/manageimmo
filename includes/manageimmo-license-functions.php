<?php

/**
 * @package ManageImmo
 * @since  1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get the license API endpoint.
 *
 * @since 1.0.0
 *
 * @return string
 */
function manageimmo_get_license_api_endpoint() {
    return 'https://api.manageimmo.de/api';
}

/**
 * Get the license key.
 *
 * @since 1.0.0
 *
 * @return string
 */
function manageimmo_get_license_key() {
    $settings = get_option( 'manageimmo_options_settings' );

    return $settings[ 'licensing_license_key' ] ?? '';
}

/**
 * Activate the license.
 *
 * @since 1.0.0
 *
 * @return object
 */
function manageimmo_activate_license() {
    $body = wp_json_encode( array(
        'domain' => parse_url( get_site_url(), PHP_URL_HOST ),
        'key'    => manageimmo_get_license_key(),
    ) );

    $response = wp_remote_post( manageimmo_get_license_api_endpoint() . '/ActivateUserLicense', array(
        'body'    => $body,
        'headers' => [
            'Content-Type' => 'application/json',
        ],
    ) );

    $body = json_decode( wp_remote_retrieve_body( $response ) );

    set_transient( 'manageimmo_license', $body, DAY_IN_SECONDS );

    return $body;
}

/**
 * Check the license.
 *
 * @since 1.0.0
 *
 * @return object
 */
function manageimmo_get_license() {
    $transient = get_transient( 'manageimmo_license' );

    if( $transient ) {
        return $transient;
    }

    $body = wp_json_encode( array(
        'domain' => parse_url( get_site_url(), PHP_URL_HOST ),
        'key'    => manageimmo_get_license_key(),
    ) );

    $response = wp_remote_post( manageimmo_get_license_api_endpoint() . '/CheckUserLicense', array(
        'body'    => $body,
        'headers' => [
            'Content-Type' => 'application/json',
        ],
    ) );

    $body = json_decode( wp_remote_retrieve_body( $response ) );

    set_transient( 'manageimmo_license', $body, DAY_IN_SECONDS );

    return $body;
}

/**
 * Check if the website has an active license.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function manageimmo_has_active_license() {
    return isset( manageimmo_get_license()->status ) && 'success' === manageimmo_get_license()->status;
}

/**
 * Return the property limit for free users.
 *
 * @since 1.0.0
 *
 * @return int
 */
function manageimmo_property_limit() {
    return 3;
}