<?php

/**
 * @package ManageImmo
 * @since  1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get the Google API key.
 *
 * @since 1.0.0
 *
 * @return string
 */
function manageimmo_get_google_api_key() {
    $settings = ManageImmo()->settings->get_settings();

    return $settings[ 'google_api_key' ];
}

/**
 * Convert the given address to a lat and lng via the Google Geocoding API. Caches results.
 *
 * @since 1.0.0
 *
 * @param  string $address
 * @return array|false [lat, lng] on success, false on failure.
 */
function manageimmo_address_to_lat_lng( $address ) {
    $address_slug = sanitize_title( $address );
    $transient    = get_transient( 'ic_lat_lng_' . $address_slug );

    if( $transient ) {
        return $transient;
    }

    $url = sprintf(
        'https://maps.googleapis.com/maps/api/geocode/json?address=%s&key=%s',
        urlencode( $address ),
        manageimmo_get_google_api_key()
    );

    $response = wp_remote_get( $url );

    $code = wp_remote_retrieve_response_code( $response );
    $body = json_decode( wp_remote_retrieve_body( $response ) );

    if( 200 === $code && ! isset( $body->error_message ) ) {

        $lat = $body->results[0]->geometry->location->lat ?? 0;
        $lng = $body->results[0]->geometry->location->lng ?? 0;

        $lat_lng = compact( 'lat', 'lng' );

        set_transient( 'ic_lat_lng_' . $address_slug, $lat_lng, MONTH_IN_SECONDS );

        return $lat_lng;
    }

    return false;
}

/**
 * Get the Google maps embed url.
 * @since 1.0.0
 *
 * @param  int $property_id
 * @return string
 */
function manageimmo_get_maps_url( $property_id ) {
    $url = add_query_arg( array(
        'q' => manageimmo_get_formatted_address( $property_id ),
        'hl' => 'de',
        'output' => 'embed',
    ), 'https://maps.google.com/maps' );

    return $url;
}