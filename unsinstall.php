<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

wp_clear_scheduled_hook( 'manageimmo_update_immoscout24_properties' );
wp_clear_scheduled_hook( 'manageimmo_update_openimmo_properties' );
delete_option( 'manageimmo_options_settings' );

// Delete all properties
$all_properties = get_posts( array(
    'post_type'=>'property',
    'numberposts'=> -1
) );

foreach ( $all_properties as $property ) {
    wp_delete_post( $property->ID, true );
}