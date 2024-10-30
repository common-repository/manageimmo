<?php

/**
 * @package ManageImmo
 * @since  1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Settings {

    /**
     * Hook in methods
     *
     * @since 1.0.0
     *
     * @return array
     */
    public static function init() {
        add_filter( 'wpsf_register_settings_manageimmo_options', array( __CLASS__, 'define_options' ) );
    }

    /**
     * Define our options.
     *
     * @since 1.0.0
     *
     * @param  array $options
     * @return array $options
     */
    public static function define_options( $options ) {
        $pages = array( 0 => '' );

        foreach ( get_pages() as $page ) {
            $pages[ $page->ID ] = $page->post_title;
        }

	    // Tabs.
        $options['tabs'] = array(
            array(
                'id'    => 'manageimmo',
                'title' => __( 'ManageImmo', 'manageimmo' ),
            ),
            array(
                'id'    => 'immoscout24',
                'title' => __( 'ImmoScout24', 'manageimmo' ),
            ),
            array(
                'id'    => 'openimmo',
                'title' => __( 'OpenImmo', 'manageimmo' ),
            ),
            array(
                'id'    => 'google',
                'title' => __( 'Google', 'manageimmo' ),
            ),
            array(
                'id'    => 'licensing',
                'title' => __( 'Licensing', 'manageimmo' ),
            ),
        );

        // Options.
        $options['sections'] = array(
            array(
                'tab_id'        => 'manageimmo',
                'section_id'    => 'general',
                'section_title' => __( 'General', 'manageimmo' ),
                'fields'        => array(
                    array(
                        'id'      => 'show_credits',
                        'title'   =>  __( 'Show credits', 'manageimmo' ),
                        'type'    => 'checkbox',
                        'default' => true,
                    ),
	                array(
                        'id'      => 'hide_media_library_attachments',
                        'title'   =>  __( 'Hide media library attachments', 'manageimmo' ),
                        'type'    => 'checkbox',
                        'default' => true,
                    ),
                ),
            ),
            array(
                'tab_id'        => 'manageimmo',
                'section_id'    => 'properties',
                'section_title' => __( 'Properties', 'manageimmo' ),
                'fields'        => array(
                    array(
                        'id'      => 'archive_slug',
                        'title'   =>  __( 'Archive slug', 'manageimmo' ),
                        'type'    => 'text',
                        'default' => __( 'properties', 'manageimmo' ),
                        'desc'    => sprintf(
                            __( 'IMPORTANT: after changing the slug you should resave the permalinks by going to %s.', 'manageimmo' ),
                            sprintf(
                                '<a href="%s">%s</a>',
                                admin_url( 'options-permalink.php' ),
                                __( 'Settings > Reading > Save changes', 'manageimmo' )
                            ),
                        )
                    ),
                    array(
                        'id'      => 'properties_order',
                        'title'   =>  __( 'Order', 'manageimmo' ),
                        'type'    => 'select',
                        'default' => __( 'ASC', 'manageimmo' ),
                        'choices' => array(
                            'ASC' => __( 'Ascending', 'manageimmo' ),
                            'ESC' => __( 'Descending', 'manageimmo' ),
                        ),
                    ),
	                array(
                        'id'      => 'properties_orderby_meta_key',
                        'title'   =>  __( 'Order by', 'manageimmo' ),
                        'type'    => 'select',
                        'default' => __( 'orderby_price', 'manageimmo' ),
                        'choices' => array(
                            'orderby_price' => __( 'Price', 'manageimmo' ),
                        ),
                    ),
	                array(
                        'id'      => 'hide_archive_title',
                        'title'   =>  __( 'Hide archive title?', 'manageimmo' ),
                        'type'    => 'checkbox',
                    ),
                ),
            ),
            array(
                'tab_id'        => 'manageimmo',
                'section_id'    => 'contact_form',
                'section_title' => __( 'Contact Form', 'manageimmo' ),
                'fields'        => array(
                    array(
                        'id'      => 'cancellation_policy_page_id',
                        'title'   =>  __( 'Cancellation Policy Page', 'manageimmo' ),
                        'type'    => 'select',
                        'choices' => $pages,
                    ),
                    array(
                        'id'      => 'privacy_policy_page_id',
                        'title'   =>  __( 'Privacy Policy Page', 'manageimmo' ),
                        'type'    => 'select',
                        'choices' => $pages,
                    ),
                    array(
                        'id'    => 'fallback_shortcode',
                        'title' =>  __( 'Fallback shortcode', 'manageimmo' ),
                        'type'  => 'text',
                        'desc'  => __( 'This shortcode will be displayed if the property is not imported via ImmoScout24.', 'manageimmo' ),
                    ),
                ),
            ),
            array(
                'tab_id'        => 'manageimmo',
                'section_id'    => 'styling',
                'section_title' => __( 'Styling', 'manageimmo' ),
                'fields'        => array(
                    array(
                        'id'      => 'primary_btn_bg_color',
                        'title'   =>  __( 'Primary button background color', 'manageimmo' ),
                        'type'    => 'color',
                        'default' => '#3B82F6',
                    ),
                    array(
                        'id'      => 'primary_btn_border_color',
                        'title'   =>  __( 'Primary button border color', 'manageimmo' ),
                        'type'    => 'color',
                        'default' => '#3B82F6',
                    ),
                    array(
                        'id'      => 'primary_btn_text_color',
                        'title'   =>  __( 'Primary button text color', 'manageimmo' ),
                        'type'    => 'color',
                        'default' => '#FFFFFF',
                    ),

                    array(
                        'id'      => 'secondary_btn_bg_color',
                        'title'   =>  __( 'Secondary button background color', 'manageimmo' ),
                        'type'    => 'color',
                        'default' => '#FFFFFF',
                    ),
                    array(
                        'id'      => 'secondary_btn_border_color',
                        'title'   =>  __( 'Secondary button border color', 'manageimmo' ),
                        'type'    => 'color',
                        'default' => '#D4D4D8',
                    ),
                    array(
                        'id'      => 'secondary_btn_text_color',
                        'title'   =>  __( 'Secondary button text color', 'manageimmo' ),
                        'type'    => 'color',
                        'default' => '#000000',
                    ),
                    array(
                        'id'      => 'link_text_color',
                        'title'   =>  __( 'Link text color', 'manageimmo' ),
                        'type'    => 'color',
                        'default' => '#3B82F6',
                    ),
                ),
            ),
            array(
                'tab_id'        => 'immoscout24',
                'section_id'    => 'api',
                'section_title' => __( 'API', 'manageimmo' ),
                'fields'        => array(
                    array(
                        'id'      => 'environment',
                        'title'   =>  __( 'Environment', 'manageimmo' ),
                        'type'    => 'select',
                        'choices' => array(
                            'sandbox' => __( 'Sandbox', 'manageimmo' ),
                            'live'    => __( 'Live', 'manageimmo' ),
                        ),
                        'default' => 'sandbox',
                    ),
                    array(
                        'id'      => 'consumer_key',
                        'title'   =>  __( 'Consumer Key', 'manageimmo' ),
                        'type'    => 'text',
						'desc'    => sprintf( '<a href="https://manageimmo.de/immoscout24-mit-wordpress-plugin-manageimmo-verbinden/">%s</a>',  __( 'Instructions', 'manageimmo' ) ),
                    ),
                    array(
                        'id'      => 'consumer_secret',
                        'title'   =>  __( 'Consumer Secret', 'manageimmo' ),
                        'type'    => 'password',
						'desc'    => sprintf( '<a href="https://manageimmo.de/immoscout24-mit-wordpress-plugin-manageimmo-verbinden/">%s</a>',  __( 'Instructions', 'manageimmo' ) ),
                    ),
                    array(
                        'id'    => 'import_references',
                        'title' =>  __( 'Import References?', 'manageimmo' ),
                        'type'  => 'checkbox',
						'desc'  => sprintf( '<a href="https://manageimmo.de/immoscout24-referenzobjekte-importieren/">%s</a>',  __( 'Instructions', 'manageimmo' ) ),
                    ),
                    array(
                        'id'      => 'custom_field_authorize',
                        'title'   =>  __( 'Authorize', 'manageimmo' ),
                        'type'    => 'custom',
                        'output'  => function() {
                            $is_connected = get_option( 'immoscout24_oauth_token' ) && get_option( 'immoscout24_oauth_token_secret' );

                            // We have this button hidden to avoid clicking the authorize button on enter since it's the first button in the form. DO NOT REMOVE.
                            submit_button( __( 'Save changes', 'manageimmo' ), 'hidden', 'submit', false );

                            submit_button( __( 'Connect', 'manageimmo' ), 'secondary', 'authorize_application' );

                            printf(
                                '<p class="status-%1$s">%1$s</p>',
                                $is_connected ? 'connected' : 'unconnected',
                            );

                            printf( '<p>%s<p>', __( 'Please save your changes before trying to connect.', 'manageimmo' ) );
                        },
                    ),
                    array(
                        'id'      => 'custom_field_update_properties',
                        'title'   =>  __( 'Create/Update', 'manageimmo' ),
                        'type'    => 'custom',
                        'output'  => function() {
                            submit_button( __( 'Create/Update all ImmoScout24 properties', 'manageimmo' ), 'secondary', 'update_properties' );
                            printf(
                                '<p>%s</p>',
                                sprintf(
                                    __( 'This already happens automatically once a day. Next time: %s', 'manageimmo' ),
                                    wp_date( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ), wp_get_scheduled_event( 'manageimmo_update_immoscout24_properties' )->timestamp ),
                                )
                            );
                        },
                    ),
                ),
            ),
            array(
                'tab_id'        => 'openimmo',
                'section_id'    => 'general',
                'section_title' => __( 'General', 'manageimmo' ),
                'fields'        => array(
                    array(
                        'id'      => 'custom_field_update_openimmo_properties',
                        'title'   =>  __( 'Create/Update', 'manageimmo' ),
                        'type'    => 'custom',
                        'output'  => function() {
                            submit_button( __( 'Create/Update all OpenImmo properties', 'manageimmo' ), 'secondary', 'update_openimmo_properties' );
                            printf(
                                '<p>%s</p><p>%s</p>',
                                sprintf(
                                    __( 'This already happens automatically once an hour. Next time: %s', 'manageimmo' ),
                                    wp_date( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ), wp_get_scheduled_event( 'manageimmo_update_openimmo_properties' )->timestamp ),
                                ),
                                __( 'IMPORTANT! In order for the OpenImmo intergration to work files must be put in the /wp-content/uploads/openimmo directory.', 'manageimmo' ),
                            );
                        },
                    ),
                ),
            ),
            array(
                'tab_id'        => 'google',
                'section_id'    => 'api',
                'section_title' => __( 'API', 'manageimmo' ),
                'fields'        => array(
                    array(
                        'id'      => 'key',
                        'title'   => __( 'Key', 'manageimmo' ),
                        'type'    => 'password',
                        'default' => false,
                        'desc'    => sprintf(
                            __( 'This key is needed to search by location. The Geocoding API must be enabled. %s', 'manageimmo' ),
                            sprintf( '<a href="%s" target="_blank" rel="noreferrer">%s</a>', 'https://cloud.google.com/docs/authentication/api-keys', __( 'Learn how', 'manageimmo' ) )
                        )
                    ),
                ),
            ),
            array(
                'tab_id'        => 'licensing',
                'section_id'    => 'license',
                'section_title' => __( 'License', 'manageimmo' ),
                'fields'        => array(
                    array(
                        'id'      => 'key',
                        'title'   => __( 'Key', 'manageimmo' ),
                        'type'    => 'password',
                        'default' => false,
                    ),
                    array(
                        'id'      => 'custom_field_activate_license',
                        'title'   => __( 'Status', 'manageimmo' ),
                        'type'    => 'custom',
                        'output'  => function() {
                            $license = manageimmo_get_license();

                            printf(
                                '<p class="status-%s">%s</p>',
                                $license->status ?? '',
                                $license->message ?? '',
                            );
                        },
                    ),
                ),
            ),
        );

        return $options;
    }

}

ManageImmo_Settings::init();