<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Post_types {

	/**
	 * Hook in methods.
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_types' ) );
		add_action( 'init', array( __CLASS__, 'register_post_status' ) );
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ) );
        add_action( 'pre_get_posts',  array( __CLASS__, 'filter_properties' ) );
        add_filter( 'posts_orderby', array( __CLASS__, 'order_properties_by_status' ), 10, 2 );
	}

    /**
     * Register core post types
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function register_post_types() {
        $settings = get_option( 'manageimmo_options_settings' );

        register_post_type(
			'property',
            array(
                'labels'   => array(
                    'name'               => __( 'Properties', 'manageimmo' ),
                    'singular_name'      => __( 'Property', 'manageimmo' ),
                    'all_items'          => __( 'All Properties', 'manageimmo' ),
                    'search_items'       => __( 'Search Properties', 'manageimmo' ),
					'edit_item'          => __( 'Edit Property', 'manageimmo' ),
					'add_new_item'       => __( 'Add New Property', 'manageimmo' ),
                    'not_found'          => __( 'No properties found', 'manageimmo' ),
                    'not_found_in_trash' => __( 'No properties found in trash', 'manageimmo' ),
                ),
                'public'      => true,
                'has_archive' => true,
                'rewrite'     => array(
                    'slug' => sanitize_title( $settings['manageimmo_properties_archive_slug'] ?? __( 'property', 'manageimmo' ) ),
                ),
                'show_in_menu'    => 'manageimmo',
                'capability_type' => 'post',
                'capabilities'    => array(
                    'create_posts' => 'do_not_allow',
                ),
                'map_meta_cap' => true,
                'supports' => array( 'title', ),
            ),
		);
    }

    /**
	 * Register our custom post statuses.
     *
     * @since 1.1.0
     *
     * @return void
	 */
	public static function register_post_status() {
		$statuses = array(
            'manageimmo-sold'    => array(
                'label'                     => __( 'Sold', 'manageimmo' ),
                'public'                    => true,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Sold <span class="count">(%s)</span>', 'Sold <span class="count">(%s)</span>', 'manageimmo' ),
            ),
            'manageimmo-rented' => array(
                'label'                     => __( 'Rented', 'manageimmo' ),
                'public'                    => true,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Rented <span class="count">(%s)</span>', 'Rented <span class="count">(%s)</span>', 'manageimmo' ),
            ),
            'manageimmo-reserved'    => array(
                'label'                     => __( 'Reserved', 'manageimmo' ),
                'public'                    => true,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Reserved <span class="count">(%s)</span>', 'Reserved <span class="count">(%s)</span>', 'manageimmo' ),
            ),
            'manageimmo-reference'    => array(
                'label'                     => __( 'Reference', 'manageimmo' ),
                'public'                    => true,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Reference <span class="count">(%s)</span>', 'Reference <span class="count">(%s)</span>', 'manageimmo' ),
            ),
        );

		foreach ( $statuses as $status => $values ) {
			register_post_status( $status, $values );
		}
	}

    /**
     * Register core taxonomies
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function register_taxonomies() {
		register_taxonomy(
			'property_building_type',
			'property',
            array(
                'labels'                => array(
                    'name'                       => __( 'Building Types', 'manageimmo' ),
                    'search_items'               => __( 'Search Building Types', 'manageimmo' ),
					'edit_item'                  => __( 'Edit Building Type', 'manageimmo' ),
					'update_item'                => __( 'Update Building Type', 'manageimmo' ),
                    'add_new_item'               => __( 'Add New Building Type', 'manageimmo' ),
					'separate_items_with_commas' => __( 'Separate building types with commas', 'manageimmo' ),
					'choose_from_most_used'      => __( 'Choose from the most used building types', 'manageimmo' ),
                    'not_found'                  => __( 'No building types found', 'manageimmo' ),
                ),
                'publicly_queryable' => false,
                'hierarchical' => true,
            ),
		);

		register_taxonomy(
			'property_city',
			'property',
            array(
                'labels'                => array(
                    'name'                       => __( 'Cities', 'manageimmo' ),
                    'search_items'               => __( 'Search Cities', 'manageimmo' ),
					'edit_item'                  => __( 'Edit City', 'manageimmo' ),
					'update_item'                => __( 'Update City', 'manageimmo' ),
                    'add_new_item'               => __( 'Add New City', 'manageimmo' ),
					'separate_items_with_commas' => __( 'Separate cities with commas', 'manageimmo' ),
					'choose_from_most_used'      => __( 'Choose from the most used cities', 'manageimmo' ),
                    'not_found'                  => __( 'No cities found', 'manageimmo' ),
                ),
                'publicly_queryable' => false,
                'hierarchical'       => true,
            ),
		);

	    register_taxonomy(
		    'property_type',
		    'property',
		    array(
			    'labels' => array(
				    'name'                       => __( 'Types', 'manageimmo' ),
				    'search_items'               => __( 'Search Types', 'manageimmo' ),
				    'edit_item'                  => __( 'Edit Type', 'manageimmo' ),
				    'update_item'                => __( 'Update Type', 'manageimmo' ),
				    'add_new_item'               => __( 'Add New Type', 'manageimmo' ),
				    'separate_items_with_commas' => __( 'Separate types with commas', 'manageimmo' ),
				    'choose_from_most_used'      => __( 'Choose from the most used types', 'manageimmo' ),
				    'not_found'                  => __( 'No types found', 'manageimmo' ),
			    ),
                'rewrite'      => array( 'slug' => 'type' ),
			    'hierarchical' => true,
		    ),
	    );
    }

    /**
     * Filter properties based on the get params for the archive page.
     *
     * @since 1.0.0
     *
     * @param  WP_Query $query
     * @return void
     */
    public static function filter_properties( $query ) {
        if( is_admin() ) {
            return;
        }

        if ( $query->is_post_type_archive( 'property' ) || $query->is_tax( 'property_type' ) ) {
    		$settings = ManageImmo()->settings->get_settings();

            $meta_query = $query->get( 'meta_query' );
            $tax_query  = $query->get( 'tax_query' );

            if( ! $meta_query ) {
                $meta_query = array();
            }

            if( ! $tax_query ) {
                $tax_query = array();
            }

            if( ! empty( $_GET['property_type'] ) ) {
                $tax_query[] = array(
					'taxonomy' => 'property_type',
                    'field'    => 'id',
                    'terms'    => absint( $_GET['property_type'] ),
                );
            }

            if( ! empty( $_GET['building_type'] ) ) {
                $tax_query[] = array(
                    'taxonomy' => 'property_building_type',
                    'field'    => 'id',
                    'terms'    => absint( $_GET['building_type'] ),
                );
            }

            if( ! empty( $_GET['city'] ) ) {
                $tax_query[] = array(
                    'taxonomy' => 'property_city',
                    'field'    => 'id',
                    'terms'    => absint( $_GET['city'] ),
                );
            }

            if( ! empty( $_GET['proximity_search'] ) &&  ! empty( $_GET['proximity_distance'] ) ) {
                $lat_lng = manageimmo_address_to_lat_lng( $_GET['proximity_search'] );

                if( $lat_lng ) {
                    $query->set( 'geo_query', array(
                        'lat_field' => 'latitude',
                        'lng_field' => 'longitude',
                        'latitude'  => $lat_lng['lat'],
                        'longitude' => $lat_lng['lng'],
                        'distance'  => absint( $_GET['proximity_distance'] ),
                        'units'     => 'km'
                    ) );
                }
            }

            if( ! empty( $_GET['external_id'] ) ) {
                $meta_query[] = array(
                    'key'     => 'external_id',
                    'value'   => sanitize_text_field( $_GET['external_id'] ),
                    'compare' => 'LIKE',
                );
            }

            if( ! empty( floatval( $_GET['min_living_space'] ?? 0 ) ) ) {
                $meta_query[] = array(
                    'key'     => 'living_space',
                    'value'   => floatval( $_GET['min_living_space'] ),
                    'compare' => '>=',
                    'type'    => 'numeric',
                );
            }

            if( ! empty( $_GET['max_living_space'] ) ) {
                $meta_query[] = array(
                    'key'     => 'living_space',
                    'value'   => floatval( $_GET['max_living_space'] ),
                    'compare' => '<=',
                    'type'    => 'numeric',
                );
            }

            if( ! empty( floatval( $_GET['min_rooms'] ?? 0 ) ) ) {
                $meta_query[] = array(
                    'key'     => 'number_of_rooms',
                    'value'   => floatval( $_GET['min_rooms'] ),
                    'compare' => '>=',
                    'type'    => 'numeric',
                );
            }

            if( ! empty( $_GET['max_rooms'] ) ) {
                $meta_query[] = array(
                    'key'     => 'number_of_rooms',
                    'value'   => floatval( $_GET['max_rooms'] ),
                    'compare' => '<=',
                    'type'    => 'numeric',
                );
            }

            if( ! empty( floatval( $_GET['min_base_rent'] ?? 0 ) ) ) {
                $meta_query[] = array(
                    'key'     => 'base_rent',
                    'value'   => floatval( $_GET['min_base_rent'] ),
                    'compare' => '>=',
                    'type'    => 'numeric',
                );
            }

            if( ! empty( $_GET['max_base_rent'] ) ) {
                $meta_query[] = array(
                    'key'     => 'base_rent',
                    'value'   => floatval( $_GET['max_base_rent'] ),
                    'compare' => '<=',
                    'type'    => 'numeric',
                );
            }

            if( ! empty( floatval( $_GET['min_price'] ?? 0 ) ) ) {
                $meta_query[] = array(
                    'key'     => 'price_value',
                    'value'   => floatval( $_GET['min_price'] ),
                    'compare' => '>=',
                    'type'    => 'numeric',
                );
            }

            if( ! empty( $_GET['max_price'] ) ) {
                $meta_query[] = array(
                    'key'     => 'price_value',
                    'value'   => floatval( $_GET['max_price'] ),
                    'compare' => '<=',
                    'type'    => 'numeric',
                );
            }

            $query->set( 'meta_query', $meta_query );
            $query->set( 'tax_query', $tax_query );

            $query->set( 'meta_key', $settings['manageimmo_properties_properties_orderby_meta_key'] );
            $query->set( 'orderby', 'meta_value_num' );
            $query->set( 'order', $settings['manageimmo_properties_properties_order'] );
        }

    }

    /**
     * Order the properties by descending post_status which results is published posts coming first.
     *
     * @since 1.0.0
     *
     * @return string   $orderby_statement
     * @return WP_Query $wp_query
     * @return string
     */
    public static function order_properties_by_status( $orderby_statement, $wp_query ) {
        // Only for property post type.
        if ( ! is_admin() && $wp_query->get( 'post_type' ) === 'property' ) {
			global $wpdb;
            $orderby_statement = "{$wpdb->posts}.post_status DESC, " . $orderby_statement;
        }

        return $orderby_statement;
    }

}

ManageImmo_Post_types::init();
