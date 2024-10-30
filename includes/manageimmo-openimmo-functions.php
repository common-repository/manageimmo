<?php

/**
 * @package ManageImmo
 * @since   1.1.0
 */

defined( 'ABSPATH' ) || exit;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Update openimmo properties.
 *
 * @since 1.1.0
 *
 * @return void
 */
function manageimmo_create_or_update_all_openimmo_properties() {
    $directory = new DirectoryIterator( wp_get_upload_dir()['basedir'] . '/openimmo' );

    foreach( $directory as $file ) {
        if( 'zip' !== $file->getExtension() ) {
            continue;
        }

        as_enqueue_async_action( 'manageimmo_create_or_update_openimmo_property', array( $file->getPathname() ) );
    }
}

add_action( 'manageimmo_update_openimmo_properties', 'manageimmo_create_or_update_all_openimmo_properties' );

/**
 * Create or update an OpenImmo property.
 *
 * @since 1.1.0
 *
 * @return void
 */
function manageimmo_create_or_update_openimmo_property( $zip_path ) {
    $filesystem = new Filesystem();
    $zip        = new ZipArchive();

    if ( $zip->open( $zip_path ) === true ) {
        $pathinfo = pathinfo( $zip_path );

        $unzipped_folder_path = $pathinfo['dirname'] . '/' . $pathinfo['filename'];

        $zip->extractTo( $unzipped_folder_path );
        $zip->close();

        $dir_iterator       = new RecursiveDirectoryIterator( $unzipped_folder_path );
        $recursive_iterator = new RecursiveIteratorIterator( $dir_iterator );

        foreach( $recursive_iterator as $file ) {
            if( $file->getExtension() === 'xml' ) {
                $folder_path = $file->getPath();
                $xml         = simplexml_load_file( $file );

                foreach( $xml->anbieter->immobilie as $property ) {
                    $openimmo_id       = (int) $property->verwaltung_techn->objektnr_intern;
                    $existing_property = manageimmo_get_property_by_openimmo_id( $openimmo_id );

                    // Don't continue if the limit is reached and the property doesn't exist yet.
                    if( ! manageimmo_has_active_license() && manageimmo_get_properties_count() >= manageimmo_property_limit() && ! $existing_property ) {
                        // Delete folder.
                        $filesystem->remove( $unzipped_folder_path );

                        return;
                    }

                    $building_type_label            = manageimmo_value_to_label( (string) $property->objektkategorie->objektart->haus['haustyp'] );
                    $property_city_term_id          = manageimmo_get_term_id_by_name( (string) $property->geo->ort, 'property_city' );
                    $property_building_type_term_id = manageimmo_get_term_id_by_name( $building_type_label, 'property_building_type' );

                    $property_args = array(
                        'ID'           => $existing_property ? $existing_property->ID : 0,
                        'post_author'  => 0,
                        'post_content' => '',
                        'post_title'   => sanitize_text_field( (string) $property->freitexte->objekttitel ?? '' ),
                        'post_status'  => 'publish',
                        'post_type'    => 'property',
                        'tax_input'    => array(
                            'property_city'          => array( $property_city_term_id ),
                            'property_building_type' => array( $property_building_type_term_id ),
                        ),
                        'meta_input'   => array(
                            'openimmo_id' => $openimmo_id,

                            'external_id'  => sanitize_text_field( (string) $property->verwaltung_techn->objektnr_extern ),
                            'street'       => sanitize_text_field( (string) $property->geo->strasse ),
                            'house_number' => sanitize_text_field( (string) $property->geo->hausnummer ),
                            'postcode'     => sanitize_text_field( (string) $property->geo->plz ),
                            'city'         => sanitize_text_field( (string) $property->geo->ort ),
                            'latitude'     => (float) $property->geo->geokoordinaten['breitengrad'],
                            'longitude'    => (float) $property->geo->geokoordinaten['laengengrad'],

                            'energy_certificate_creation_date'           => (int) $property->zustand_angaben->energiepass->jahrgang >= 2014 ? 'FROM_01_MAY_2014' : 'BEFORE_01_MAY_2014',
                            'energy_certificate_class'                   => sanitize_text_field( (string) $property->zustand_angaben->energiepass->wertklasse ),
                            'energy_source_enev_2014'                    => manageimmo_value_to_label( sanitize_text_field( (string) $property->zustand_angaben->energiepass->primaerenergietraeger ) ),
                            'energy_certificate_legal_construction_year' => (int) $property->zustand_angaben->baujahr,
                            'energy_consumption_contains_warm_water'     => (bool) $property->zustand_angaben->mitwarmwasser,
                            'lodger_flat'                                => '',
                            'cellar'                                     => (bool) $property->flaechen->kellerflaeche,
                            'handicapped_accessible'                     => '',
                            'guest_toilet'                               => (bool) $property->ausstattung->gaestewc,
                            'summer_residence_practical'                 => '',
                            'marketing_type'                             => (bool) $property->objektkategorie->vermarktungsart['MIETE_PACHT'] ? 'rent' : 'buy',
                            'condition'                                  => manageimmo_value_to_label( sanitize_text_field( (string) $property->zustand_angaben->zustand['zustand_art'] ) ),
                            'number_of_parking_spaces'                   => (int) $property->flaechen->anzahl_stellplaetze,
                            'construction_year'                          => sanitize_text_field( (string) $property->zustand_angaben->baujahr ),
                            'building_energy_rating_type'                => '',
                            'thermal_characteristic'                     => 0,
                            'description_note'                           => wp_filter_post_kses( nl2br( (string) $property->freitexte->objektbeschreibung ) ),
                            'furnishing_note'                            => wp_filter_post_kses( nl2br( (string) $property->freitexte->ausstatt_beschr ) ),
                            'location_note'                              => wp_filter_post_kses( nl2br( (string) $property->freitexte->lage ) ),
                            'other_note'                                 => wp_filter_post_kses( nl2br( (string) $property->freitexte->sonstige_angaben ) ),

                            'contact_email'        => sanitize_email( (string) $property->kontaktperson->email_zentrale ),
                            'contact_first_name'   => sanitize_text_field( (string) $property->kontaktperson->vorname ),
                            'contact_last_name'    => sanitize_text_field( (string) $property->kontaktperson->name ),
                            'contact_phone_number' => sanitize_text_field( (string) $property->kontaktperson->tel_zentrale ),
                            'contact_street'       => sanitize_text_field( (string) $property->kontaktperson->strasse ),
                            'contact_house_number' => sanitize_text_field( (string) $property->kontaktperson->hausnummer ),
                            'contact_postcode'     => sanitize_text_field( (string) $property->kontaktperson->plz ),
                            'contact_city'         => sanitize_text_field( (string) $property->kontaktperson->ort ),
                            'contact_title'        => '',
                            'contact_company'      => sanitize_text_field( (string) $property->kontaktperson->firma ),

                            'number_of_floors'      => (int) $property->ausstattung->boden,
                            'usable_floor_space'    => (int) $property->flaechen->nutzflaeche,
                            'number_of_bed_rooms'   => (int) $property->flaechen->anzahl_schlafzimmer,
                            'number_of_bath_rooms'  => (int) $property->flaechen->anzahl_badezimmer,
                            'rental_income'         => 0,
                            'base_rent'             => (int) $property->preise->hauptmietzinsnetto,
                            'total_rent'            => manageimmo_format_price( (int) $property->preise->pauschalmiete ),
                            'service_charge'        => 0,
                            'heatingCosts'          => manageimmo_format_price( (int) $property->preise->heizkosten ),
                            'calculated_total_rent' => manageimmo_format_price( (int) $property->preise->nettokaltmiete ),
                            'price_value'           => (int) $property->preise->kaufpreis,
                            'living_space'          => (int) $property->flaechen->wohnflaeche,
                            'plot_area'             => manageimmo_format_area( (int) $property->flaechen->gesamtflaeche ),
                            'number_of_rooms'       => (int) $property->flaechen->anzahl_zimmer,
                        ),
                    );

                    $meta = $property_args['meta_input'];
                    $property_args['meta_input']['orderby_price'] = ! empty( $meta['price_value'] ) ? $meta['price_value'] : $meta['base_rent']; // The price that will be used for sorting.

                    // If there is an ID, update the property. If there isn't, create a new property.
                    if( $property_args['ID'] ) {
                        $property_args['post_status'] = get_post_status( $property_args['ID'] ); // Same status
                        wp_update_post( $property_args );
                    } else {
                        $property_args['ID'] = wp_insert_post( $property_args );

                        // Because we are not allowed not use tax_input with wp_insert_post, we use wp_set_object_terms instead.
                        wp_set_object_terms( $property_args['ID'], $property_city_term_id, 'property_city' );
                        wp_set_object_terms( $property_args['ID'], $property_building_type_term_id, 'property_building_type' );
                    }

                    $attachment_ids        = array();
                    $contact_attachment_id = null;

                    foreach( $property->anhaenge->anhang as $file ) {
                        $file             = $folder_path . '/' . $file->daten->pfad;
                        $attachment_ids[] = manageimmo_file_to_attachment( $file, $property_args['ID'] );
                    }

                    if( isset( $property->kontaktperson->foto->daten->pfad ) ) {
                        $file                  = $folder_path . '/' . $property->kontaktperson->foto->daten->pfad;
                        $contact_attachment_id = manageimmo_file_to_attachment( $file, $property_args['ID'] );
                    }

                    update_post_meta( $property_args['ID'], 'gallery_attachment_ids', $attachment_ids );
                    update_post_meta( $property_args['ID'], 'contact_attachment_id', $contact_attachment_id );

                }

                // Delete folder and zip.
                $filesystem->remove( array( $unzipped_folder_path, $zip_path ) );

                break;
            }
        }
    }
}

add_action( 'manageimmo_create_or_update_openimmo_property', 'manageimmo_create_or_update_openimmo_property' );

/**
 * Get a property by it's OpenImmo ID.
 *
 * @since 1.1.0
 *
 * @return WP_Post|null
 */
function manageimmo_get_property_by_openimmo_id( $openimmo_id ) {
    $property = get_posts( array(
        'post_type'    => 'property',
        'post_status'  => 'any',
        'numberposts'  => 1,
        'meta_query'   => array(
            array(
                'key'   => 'openimmo_id',
                'value' => absint( $openimmo_id ),
            ),
        )
    ) );

    return $property ? $property[0] : null;
}