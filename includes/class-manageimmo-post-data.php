<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Post_Data {

	/**
	 * Hook in methods.
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public static function init() {
        add_action( 'before_delete_post', array( __CLASS__, 'delete_property_attachments' ) );
	}

    /**
     * Delete property attachments when the property is being deleted.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function delete_property_attachments( $post_id ) {
        if( get_post_type( $post_id ) === 'property' ) {
            $attachments = get_attached_media( '', $post_id );

            foreach ( $attachments as $attachment ) {
                wp_delete_attachment( $attachment->ID, true );
            }
        }
    }

}

ManageImmo_Post_Data::init();