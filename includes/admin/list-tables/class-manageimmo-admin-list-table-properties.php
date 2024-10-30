<?php

/**
 * @package ManageImmo
 * @since   1.1.0
 */

defined( 'ABSPATH' ) || exit;

class Manageimmo_Admin_List_Table_Properties {

    /**
     * Constructor
     *
     * @since 1.1.0
     *
     * @return void
     */
    public function __construct() {
        add_filter( 'bulk_actions-edit-property', array( $this, 'change_properties_bulk_actions' ) );
        add_filter( 'post_row_actions', array( $this, 'remove_quick_edit' ), 10, 2 );
    }

    /**
     * Change properties bulk actions.
     *
     * @since 1.0.0
     *
     * @param  array $actions
     * @return array
     */
    public function change_properties_bulk_actions( $actions ) {
        unset( $actions['edit'] );
        return $actions;
    }

    /**
     * Remove quick edit for properties.
     *
     * @since 1.0.0
     *
     * @param  array   $actions
     * @param  WP_Post $post
     * @return array
     */
    public function remove_quick_edit( $actions, $post ) {
        if( 'property' === $post->post_type ) {
            unset( $actions['inline hide-if-no-js'] );
        }

        return $actions;
    }

}

new Manageimmo_Admin_List_Table_Properties();