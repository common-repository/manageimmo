<?php

/**
 * @package ManageImmo
 * @since  1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Admin_Post_Types {

	/**
	 * Constructor
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public function __construct() {
		include_once __DIR__ . '/class-manageimmo-admin-meta-boxes.php';
		include_once __DIR__ . '/list-tables/class-manageimmo-admin-list-table-properties.php';

		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
		add_filter( 'months_dropdown_results', array( $this, 'remvove_months_filter' ) );

		add_filter( 'display_post_states', array( $this, 'add_display_post_states' ), 10, 2 );
	}

	/**
	 * Change messages when a post type is updated
	 *
	 * @since 1.0.0
	 *
	 * @param  array $messages
	 * @return array
	 */
	public function post_updated_messages( $messages ) {
		$messages['property'][1] = __( 'Property updated.', 'manageimmo' );

		return $messages;
	}

	/**
	 * Remove months filter
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function remvove_months_filter( $months ) {
		global $typenow;

		if ( in_array( $typenow, array( 'property' ) ) ) {
			return array();
		}

		return $months;
	}

	/**
	 * Display post states for properties.
	 *
	 * @since 1.0.0
	 *
	 * @param  array   $post_states
	 * @param  WP_Post $post
	 * @return string
	 */
	public function add_display_post_states( $post_states, $post ) {
		if ( 'property' === $post->post_type && 'publish' !== $post->post_status ) {
			$post_states['manageimmo_status_for_property'] = get_post_status_object( $post->post_status )->label;
		}

		return $post_states;
	}

}

new ManageImmo_Admin_Post_Types();