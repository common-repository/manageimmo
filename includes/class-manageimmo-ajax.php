<?php

/**
 * @since   1.0.0
 * @package ManageImmo
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_AJAX {

	/**
	 * Hook in methods.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		self::add_ajax_events();

		add_filter( 'ajax_query_attachments_args', array( __CLASS__, 'hide_property_attachments' ) );
	}

	/**
	 * Setup all ajax actions.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function add_ajax_events() {
		$ajax_events_nopriv = array(
			'contact_estate_agent',
		);

		foreach ( $ajax_events_nopriv as $ajax_event ) {
			add_action( 'wp_ajax_manageimmo_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			add_action( 'wp_ajax_nopriv_manageimmo_' . $ajax_event, array( __CLASS__, $ajax_event ) );
		}

		$ajax_events = array();

		foreach ( $ajax_events as $ajax_event ) {
			add_action( 'wp_ajax_manageimmo_' . $ajax_event, array( __CLASS__, $ajax_event ) );
		}
	}

	/**
	 * Contact an estate agent via the ManageImmo API.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function contact_estate_agent() {
		if (
			empty( $_POST['immoscout24_id'] ) ||
			empty( $_POST['first_name'] ) ||
			empty( $_POST['last_name'] ) ||
			empty( $_POST['phone'] ) ||
			empty( $_POST['email'] ) ||
			empty( $_POST['street'] ) ||
			empty( $_POST['house_number'] ) ||
			empty( $_POST['postcode'] ) ||
			empty( $_POST['city'] ) ||
			empty( $_POST['salutation'] )
		) {
			wp_send_json( array(), 400 );
		}

		$body = array(
			'expose.contactForm' => array(
				'@xmlns'               => array(
					'common' => 'http://rest.immobilienscout24.de/schema/common/1.0',
				),
				'firstname'            => sanitize_text_field( $_POST['first_name'] ),
				'lastname'             => sanitize_text_field( $_POST['last_name'] ),
				'phoneNumber'          => sanitize_text_field( $_POST['phone'] ),
				'emailAddress'         => sanitize_email( $_POST['email'] ),
				'appointmentRequested' => 'YES',
				'message'              => sanitize_text_field( $_POST['message'] ?? '' ),
				'address'              => array(
					'@xsi.type'   => 'common:Address',
					'street'      => sanitize_text_field( $_POST['street'] ),
					'houseNumber' => absint( $_POST['house_number'] ),
					'postcode'    => absint( $_POST['postcode'] ),
					'city'        => sanitize_text_field( $_POST['city'] ),
				),
				'salutation'           => sanitize_text_field( $_POST['salutation'] ),
			),
		);

		$endpoint = sprintf( '/search/v1.0/expose/%s/contact', absint( $_POST['immoscout24_id'] ) );

		$response      = ManageImmo()->api->post( $endpoint, json_encode( $body ) );
		$response_code = wp_remote_retrieve_response_code( $response );

		wp_send_json( array(), $response_code );
	}

	/**
	 * Hide property attachments.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $query
	 * @return array $query
	 */
	public static function hide_property_attachments( $query ) {
		$hide_media_library_attachments = ManageImmo()->settings->get_settings()['manageimmo_general_hide_media_library_attachments'];

		if ( $hide_media_library_attachments ) {
			$ids = get_posts( array(
				'fields'      => 'ids',
				'numberposts' => -1,
				'post_status' => 'any',
				'post_type'   => 'property',
			) );

			$query['post_parent__not_in'] = $ids;
		}

		return $query;
	}

}

ManageImmo_AJAX::init();
