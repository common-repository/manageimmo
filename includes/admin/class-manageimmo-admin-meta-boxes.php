<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Admin_Meta_Boxes {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 2 );

		add_action( 'manageimmo_process_property_meta', 'ManageImmo_Meta_Box_Property_Data::save', 10, 2 );
	}

	/**
	 * Remove meta boxes.
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public function remove_meta_boxes() {
		remove_meta_box( 'submitdiv', 'property', 'side' );
	}

	/**
	 * Add the meta boxes
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public function add_meta_boxes() {
		add_meta_box( 'manageimmo-property-data', __( 'Property data', 'manageimmo' ), 'ManageImmo_Meta_Box_Property_Data::output', 'property', 'normal', 'high' );
	}

	/**
	 * Check if saving is allowed, and if so trigger an action.
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public function save_meta_boxes( $post_id, $post ) {

		$post_id = absint( $post_id );

		// $post_id and $post are required
		if ( empty( $post_id ) || empty( $post ) ) {
			return;
		}

		// Dont save meta boxes for revisions or autosaves.
		if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		// Check the nonce.
		if ( empty( $_POST['manageimmo_meta_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['manageimmo_meta_nonce'] ), 'manageimmo_save_data' ) ) {
			return;
		}

		// Check user has permission to edit.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		do_action( 'manageimmo_process_' . $post->post_type . '_meta', $post_id, $post );
	}

}

new ManageImmo_Admin_Meta_Boxes();