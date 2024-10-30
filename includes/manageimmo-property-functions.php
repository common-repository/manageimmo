<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get the formatted full name of the contact.
 *
 * @since 1.0.0
 *
 * @param  int $property_id
 * @return string
 */
function manageimmo_get_contact_formatted_full_name( $property_id ) {
    $first_name = get_post_meta( $property_id, 'contact_first_name', true );
    $last_name  = get_post_meta( $property_id, 'contact_last_name', true );

    return $first_name . ' ' . $last_name;
}

/**
 * Get the formatted address of the property.
 *
 * @since 1.0.0
 *
 * @param  int $property_id
 * @return string
 */
function manageimmo_get_formatted_address( $property_id ) {
    $show_address = get_post_meta( $property_id, 'show_address', true );

    $street       = get_post_meta( $property_id, 'street', true );
    $house_number = get_post_meta( $property_id, 'house_number', true );
    $postcode     = get_post_meta( $property_id, 'postcode', true );
    $city         = get_post_meta( $property_id, 'city', true );

    $address_components = array();

    if( $show_address && ( $street || $house_number ) ) {
        $address_components[] = $street . ' ' . $house_number;
    }

    $address_components[] = $postcode;
    $address_components[] = $city;

    return implode( ', ', $address_components );
}

/**
 * Get the formatted address the contact.
 *
 * @since 1.0.0
 *
 * @param  int $property_id
 * @return string
 */
function manageimmo_get_contact_formatted_address( $property_id ) {
    $street       = get_post_meta( $property_id, 'contact_street', true );
    $house_number = get_post_meta( $property_id, 'contact_house_number', true );
    $postcode     = get_post_meta( $property_id, 'contact_postcode', true );
    $city         = get_post_meta( $property_id, 'contact_city', true );

    $address = '';

    if( $street )       $address .= $street       . ' ';
    if( $house_number ) $address .= $house_number . ', ';
    if( $postcode )     $address .= $postcode     . ', ';
    if( $city )         $address .= $city;

    return $address;
}

/**
 * Converts values (from the ManageImmo API) to human readable ones. For example "MULTI_FAMILY_HOUSE" becomes Multi Family House.
 *
 * @since 1.0.0
 *
 * @param  string
 * @return string
 */
function manageimmo_value_to_label( $value ) {
    if( ! is_string( $value ) ) {
        return null;
    }

    return ucfirst( strtolower( str_replace( '_', ' ', $value ) ) );
}

/**
 * Converts a number to a price. E.g. 500 becomes 500 EUR.
 *
 * @since 1.0.0
 *
 * @param  int
 * @return string
 */
function manageimmo_format_price( $price ) {
    if( ! $price ) {
        return $price;
    }

    return $price . ' EUR';
}

/**
 * Converts a number to a an area. E.g. 500 becomes 500 m².
 *
 * @since 1.0.0
 *
 * @param  int
 * @return string
 */
function manageimmo_format_area( $area ) {
    if( ! $area ) {
        return $area;
    }

    return $area . ' m²';
}

/**
 * Converts a number to the energy certificate class.
 *
 * @since 1.0.0
 *
 * @param  int
 * @return string
 */
function manageimmo_kwh_m2_to_energy_certificate_class( $kwh_m2 ) {
    if( ! $kwh_m2 ) {
        return $kwh_m2;
    }

    if( $kwh_m2 < 30 ) {
        return 'A+';
    } elseif( $kwh_m2 < 50 ) {
        return 'A';
    } elseif( $kwh_m2 < 75 ) {
        return 'B';
    } elseif( $kwh_m2 < 100 ) {
        return 'C';
    } elseif( $kwh_m2 < 130 ) {
        return 'D';
    } elseif( $kwh_m2 < 160 ) {
        return 'E';
    } elseif( $kwh_m2 < 200 ) {
        return 'F';
    } elseif( $kwh_m2 < 250 ) {
        return 'G';
    } else {
        return 'H';
    }
}

/**
 * Converts a kwh/m2 number to a human readable label.
 *
 * @since 1.0.0
 *
 * @param  int
 * @return string
 */
function manageimmo_kwh_m2_to_label( $kwh_m2 ) {
    if( ! $kwh_m2 ) {
        return $kwh_m2;
    }

    return $kwh_m2 . ' kWh/(m²·a)';
}

/**
 * Get a users saved properties from the cookies.
 *
 * @since 1.0.0
 *
 * @return array
 */
function manageimmo_get_saved_properties() {
    $properties = json_decode( stripslashes( $_COOKIE['ic_saved_properties'] ?? '' ) );

    if( ! is_array( $properties ) ) {
        return array();
    }

    return $properties;
}

/**
 * Check if a given property is saved based on the users cookie.
 *
 * @since 1.0.0
 *
 * @param  int $property_id
 * @return bool
 */
function manageimmo_is_property_saved( $property_id ) {
    return in_array( $property_id, manageimmo_get_saved_properties() );
}

/**
 * Get a term by it's name. If a term with the given name doesn't exist, we create it.
 *
 * @since 1.0.0
 *
 * @param  string $term_name
 * @param  string $taxonomy
 * @return int
 */
function manageimmo_get_term_id_by_name( $term_name, $taxonomy ) {
    $term    = get_term_by( 'name', $term_name, $taxonomy );
    $term_id = 0;

    if( $term ) {
        $term_id = $term->term_id;
    } elseif( $term_name ) {
        $term = wp_insert_term( sanitize_text_field( $term_name ), $taxonomy );

        if( ! is_wp_error( $term ) ) {
            $term_id = $term['term_id'];
        }
    }

    return $term_id;
}

/**
 * Get all property statuses.
 *
 * @since 1.1.0

 * @return array
 */
function manageimmo_get_property_statuses() {
	$property_statuses = array(
        'manageimmo-sold'     => __( 'Sold', 'manageimmo' ),
        'manageimmo-rented'   => __( 'Rented', 'manageimmo' ),
        'manageimmo-reserved' => __( 'Reserved', 'manageimmo' ),
        'manageimmo-reference' => __( 'Reference', 'manageimmo' ),
	);

	return $property_statuses;
}


/**
 * Get properties count.
 *
 * @since 1.1.4

 * @return array
 */
function manageimmo_get_properties_count() {
    $properties = (array) wp_count_posts( 'property' );

    return array_sum( $properties );
}