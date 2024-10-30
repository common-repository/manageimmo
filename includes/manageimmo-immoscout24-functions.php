<?php

/**
 * @package ManageImmo
 * @since   1.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * This function updates all properties or creates them if they don't exists.
 * We also utilize an action scheduler for background processing.
 *
 * @since 1.0.0
 *
 * @return void
 */
function manageimmo_create_or_update_all_properties() {
    $immoscout24_ids = array();
    $page            = 1;

    do {
        $response = ManageImmo()->api->get( "/offer/v1.0/user/me/realestate?pagesize=100&pagenumber=$page" );

        if( ! $response ) {
            break;
        }

        $response   = $response->{'realestates.realEstates'};
        $properties = $response->realEstateList->realEstateElement;

        foreach ( $properties as $property ) {
            $immoscout24_ids[] = $property->{'@id'};
            as_enqueue_async_action( 'manageimmo_create_or_update_property', array( $property->{'@id'} ) );
        }

        $page = isset( $response->Paging->next ) ? $page + 1 : 0;
    } while ( $page );

    // Get all properties that don't exist anymore.
    $properties = get_posts( array(
        'post_type'   => 'property',
        'post_status' => 'publish',
        'numberposts' => -1,
        'meta_query'  => array(
            'relation' => 'OR',
            array(
                'key'     => 'immoscout24_id',
                'value'   => $immoscout24_ids,
                'compare' => 'NOT IN',
            ),
        ),
    ) );

    // Delete all properties that don't exist anymore.
    foreach ( $properties as $property ) {
        wp_delete_post( $property->ID, true );
    }
}

add_action( 'manageimmo_update_immoscout24_properties', 'manageimmo_create_or_update_all_properties' );

/**
 * Create a property if it doesn't exist, update if it does.
 *
 * @since 1.0.0
 *
 * @param  int $immoscout24_id
 * @return void
 */
function manageimmo_create_or_update_property( $immoscout24_id ) {
	if( ! $immoscout24_id ) {
		return;
	}

    $current_property = manageimmo_get_property_by_immoscout24_id( $immoscout24_id );

    // Don't continue if the limit is reached and the property doesn't exist yet.
    if( ! manageimmo_has_active_license() && manageimmo_get_properties_count() >= manageimmo_property_limit() && ! $current_property ) {
        return;
    }

    $settings          = ManageImmo()->settings->get_settings();
    $import_references = $settings['immoscout24_api_import_references'];

    $property = ManageImmo()->api->get( "/offer/v1.0/user/me/realestate/$immoscout24_id" );
	$key      = array_keys( (array) $property )[0];

	$property = $property->{$key};

	$is_reference_object = isset( $property->apiSearchData ) && 'referenzobjekt' === $property->apiSearchData->searchField1;

	if ( 'ACTIVE' !== $property->realEstateState && ( ! $import_references || ! $is_reference_object ) ) {
        wp_delete_post( $current_property ? $current_property->ID : 0 );
		return;
	}

    $property_attachments = ManageImmo()->api->get( "/offer/v1.0/user/me/realestate/$immoscout24_id/attachment" )->{'common.attachments'}[0]->attachment;

    $building_type_label = manageimmo_value_to_label( $property->buildingType ?? '' );
    $types = require ManageImmo()->plugin_path() . '/i18n/type.php';

    $property_city_term_id          = manageimmo_get_term_id_by_name( $property->address->city, 'property_city' );
    $property_building_type_term_id = manageimmo_get_term_id_by_name( $building_type_label, 'property_building_type' );
    $property_type_term_id          = manageimmo_get_term_id_by_name( $types[ explode( '.', $key )[1] ], 'property_type' );

    // If it's marked as referenzobjekt in ImmoScout24, we add our reference status to it.
    $property_status = $is_reference_object ? 'manageimmo-reference' : 'publish';

    $property_args = array(
        'ID'           => $current_property ? $current_property->ID : 0,
        'post_author'  => 0,
        'post_content' => '',
        'post_title'   => sanitize_text_field( $property->title ?? '' ),
        'post_status'  => $property_status,
        'post_type'    => 'property',
        'tax_input'    => array(
            'property_city'          => array( $property_city_term_id ),
            'property_building_type' => array( $property_building_type_term_id ),
            'property_type'          => array( $property_type_term_id ),
        ),
        'meta_input'   => array(
            'immoscout24_id' => $immoscout24_id,

            'external_id'                                => sanitize_text_field( $property->externalId                                                                   ?? '' ),
            'street'                                     => sanitize_text_field( $property->address->street                                                              ?? '' ),
            'house_number'                               => sanitize_text_field( $property->address->houseNumber                                                         ?? '' ),
            'postcode'                                   => sanitize_text_field( $property->address->postcode                                                            ?? '' ),
            'city'                                       => sanitize_text_field( $property->address->city                                                                ?? '' ),
            'latitude'                                   => floatval( $property->address->wgs84Coordinate->latitude                                                      ?? '' ),
            'longitude'                                  => floatval( $property->address->wgs84Coordinate->longitude                                                     ?? '' ),
            'show_address'                               => rest_sanitize_boolean($property->showAddress                                                                 ?? ''),

            'energy_certificate_creation_date'           => sanitize_text_field( $property->energyCertificate->energyCertificateCreationDate                             ?? '' ),
            'energy_certificate_class'                   => sanitize_text_field( manageimmo_kwh_m2_to_energy_certificate_class( $property->thermalCharacteristic        ?? 0 ) ),
            'energy_source_enev_2014'                    => sanitize_text_field( manageimmo_value_to_label( $property->energySourcesEnev2014->energySourceEnev2014      ?? '' ) ),
            'energy_certificate_legal_construction_year' => absint( $property->energyCertificate->legalConstructionYear                                                  ?? 0 ),
            'energy_consumption_contains_warm_water'     => $property->energyConsumptionContainsWarmWater === 'YES' ? true : false,
            'lodger_flat'                                => $property->lodgerFlat                         === 'YES' ? true : false,
            'cellar'                                     => $property->cellar                             === 'YES' ? true : false,
            'handicapped_accessible'                     => $property->handicappedAccessible              === 'YES' ? true : false,
            'guest_toilet'                               => $property->guestToilet                        === 'YES' ? true : false,
            'summer_residence_practical'                 => $property->summerResidencePractical           === 'YES' ? true : false,
            'marketing_type'                             => isset( $property->baseRent ) ? 'rent' : 'buy',
            'condition'                                  => sanitize_text_field( manageimmo_value_to_label( $property->condition                                        ?? '' ) ),
            'number_of_parking_spaces'                   => absint( $property->numberOfParkingSpaces                                                                     ?? 0 ),
            'construction_year'                          => sanitize_text_field( $property->constructionYear                                                             ?? '' ),
            'building_energy_rating_type'                => sanitize_text_field( manageimmo_value_to_label( $property->buildingEnergyRatingType                         ?? '' ) ),
            'thermal_characteristic'                     => absint( $property->thermalCharacteristic                                                                     ?? 0 ),
            'description_note'                           => wp_filter_post_kses( nl2br( $property->descriptionNote                                                       ?? '' ) ),
            'furnishing_note'                            => wp_filter_post_kses( nl2br( $property->furnishingNote                                                        ?? '' ) ),
            'location_note'                              => wp_filter_post_kses( nl2br( $property->locationNote                                                          ?? '' ) ),
            'other_note'                                 => wp_filter_post_kses( nl2br( $property->otherNote                                                             ?? '' ) ),

            'number_of_floors'      => absint( $property->numberOfFloors                                            ?? 0 ),
            'usable_floor_space'    => sanitize_text_field( manageimmo_format_area( $property->usableFloorSpace     ?? 0 ) ),
            'number_of_bed_rooms'   => absint( $property->numberOfBedRooms                                           ?? 0 ),
            'number_of_bath_rooms'  => absint( $property->numberOfBathRooms                                          ?? 0 ),
            'rental_income'         => absint( $property->rentalIncome                                               ?? 0 ),
            'base_rent'             => absint( $property->baseRent                                                   ?? 0 ),
            'total_rent'            => sanitize_text_field( manageimmo_format_price( $property->totalRent           ?? 0 ) ),
            'service_charge'        => sanitize_text_field( manageimmo_format_price( $property->serviceCharge       ?? 0 ) ),
            'heatingCosts'          => sanitize_text_field( manageimmo_format_price( $property->heatingCosts        ?? 0 ) ),
            'calculated_total_rent' => sanitize_text_field( manageimmo_format_price( $property->calculatedTotalRent ?? 0 ) ),
            'price_value'           => absint( $property->price->value                                               ?? 0 ),
            'living_space'          => sanitize_text_field( $property->livingSpace                                   ?? 0 ),
            'plot_area'             => sanitize_text_field( manageimmo_format_area( $property->plotArea             ?? 0 ) ),
            'number_of_rooms'       => absint( $property->numberOfRooms                                              ?? 0 ),
        ),
    );

    $meta = $property_args['meta_input'];
    $property_args['meta_input']['orderby_price'] = ! empty( $meta['price_value'] ) ? $meta['price_value'] : $meta['base_rent']; // The price that will be used for sorting.

    if( ! $import_references && 'manageimmo-reference' === $property_args['post_status'] ) {
        wp_delete_post( $property_args['ID'] );
        return;
    }

    $expose = ManageImmo()->api->get( "/search/v1.0/expose/$immoscout24_id" );

    // If there is an expost we try to retrieve the contact details.
    if( $expose ) {
        $expose  = $expose->{'expose.expose'};
        $contact = $expose->contactDetails;

        $property_args['meta_input']['contact_id']           = absint( $contact->{'@id'}                           ?? '' );
        $property_args['meta_input']['contact_email']        = sanitize_email( $contact->email                     ?? '' );
        $property_args['meta_input']['contact_first_name']   = sanitize_text_field( $contact->firstname            ?? '' );
        $property_args['meta_input']['contact_last_name']    = sanitize_text_field( $contact->lastname             ?? '' );
        $property_args['meta_input']['contact_phone_number'] = sanitize_text_field( $contact->phoneNumber          ?? '' );
        $property_args['meta_input']['contact_street']       = sanitize_text_field( $contact->address->street      ?? '' );
        $property_args['meta_input']['contact_house_number'] = sanitize_text_field( $contact->address->houseNumber ?? '' );
        $property_args['meta_input']['contact_postcode']     = sanitize_text_field( $contact->address->postcode    ?? '' );
        $property_args['meta_input']['contact_city']         = sanitize_text_field( $contact->address->city        ?? '' );
        $property_args['meta_input']['contact_title']        = sanitize_text_field( $contact->title                ?? '' );
        $property_args['meta_input']['contact_company']      = sanitize_text_field( $contact->company              ?? '' );

        $contact_attachment_id = null;

        if( isset( $contact->portraitUrl ) ) {
            $contact_attachment_id = manageimmo_url_to_attachment( manageimmo_clean_immoscout24_img_url( $contact->portraitUrl ), $property_args['ID'] );
        }

        update_post_meta( $property_args['ID'], 'contact_attachment_id', $contact_attachment_id );
    }

    // If there is an ID, update the property. If there isn't, create a new property.
    if( $property_args['ID'] ) {
        $property_args['post_status'] = get_post_status( $property_args['ID'] ); // Same status
        wp_update_post( $property_args );
    } else {
        $property_args['ID'] = wp_insert_post( $property_args );

        // Because we are not allowed not use tax_input with wp_insert_post, we use wp_set_object_terms instead.
        wp_set_object_terms( $property_args['ID'], $property_city_term_id, 'property_city' );
        wp_set_object_terms( $property_args['ID'], $property_building_type_term_id, 'property_building_type' );
        wp_set_object_terms( $property_args['ID'], $property_type_term_id, 'property_type' );
    }

    $attachment_ids        = array();

    if( ! is_array( $property_attachments ) ) {
        $property_attachments = array( $property_attachments );
    }

    foreach ( $property_attachments as $attachment ) {
        $type = $attachment->{'@xsi.type'};

        switch( $type ) {
            case 'common:Picture' :
                $url = rawurldecode( $attachment->urls[0]->url[0]->{'@href'} );
                $url = manageimmo_clean_immoscout24_img_url( $url );
                break;
            case 'common:StreamingVideo' :
                $url = rawurldecode( $attachment->videoInfo->videoUrlList->url );
                break;
        }

        $attachment_ids[] = manageimmo_url_to_attachment( $url, $property_args['ID'] );
    }

    update_post_meta( $property_args['ID'], 'gallery_attachment_ids', $attachment_ids );
}

add_action( 'manageimmo_create_or_update_property', 'manageimmo_create_or_update_property' );

/**
 * Get a property by it's ImmoScout24 ID.
 *
 * @since 1.0.0
 *
 * @return WP_Post|null
 */
function manageimmo_get_property_by_immoscout24_id( $immoscout24_id ) {
    $property = get_posts( array(
        'post_type'    => 'property',
        'post_status'  => 'any',
        'numberposts'  => 1,
        'meta_query'   => array(
            array(
                'key'   => 'immoscout24_id',
                'value' => absint( $immoscout24_id ),
            ),
        )
    ) );

    return $property ? $property[0] : null;
}

/**
 * Clean the given ImmoScout24 image url by removing the gibberish after and including '/ORIG'.
 *
 * @since 1.0.0
 *
 * @param  string $img_url
 * @return string
 */
function manageimmo_clean_immoscout24_img_url( $img_url ) {
    $offset = strpos( $img_url, '/ORIG' );

    if( false === $offset ) {
        return '';
    }

    return substr( $img_url, 0, $offset );
}
