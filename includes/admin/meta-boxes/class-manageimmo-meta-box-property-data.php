<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Meta_Box_Property_Data {

	/**
	 * Output the meta box
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $property
	 * @return void
	 */
	public static function output( $property ) {
		wp_nonce_field( 'manageimmo_save_data', 'manageimmo_meta_nonce' );

        include __DIR__ . '/views/html-property-data.php';
	}

	/**
	 * Save the meta box data
	 *
	 * @since 1.0.0
	 *
	 * @param  int     $property_id
	 * @param  WP_Post $property
	 * @return void
	 */
    public static function save( $property_id, $property ) {
	}

}