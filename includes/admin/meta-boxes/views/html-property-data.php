<?php

/**
 * @package ManageImmo
 * @since  1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>

<table class="form-table">
    <tr>
        <td><?php _e( 'Status', 'manageimmo' ); ?></td>
        <td>
            <select name="post_status">
                <option value="publish" <?php selected( get_post_status(), 'publish' ); ?>><?php _e( 'Published', 'manageimmo' ); ?></option>
                <option value="manageimmo-sold" <?php selected( get_post_status(), 'manageimmo-sold' ); ?>><?php _e( 'Sold', 'manageimmo' ); ?></option>
                <option value="manageimmo-rented" <?php selected( get_post_status(), 'manageimmo-rented' ); ?>><?php _e( 'Rented', 'manageimmo' ); ?></option>
                <option value="manageimmo-reserved" <?php selected( get_post_status(), 'manageimmo-reserved' ); ?>><?php _e( 'Reserved', 'manageimmo' ); ?></option>
                <option value="manageimmo-reference" <?php selected( get_post_status(), 'manageimmo-reference' ); ?>><?php _e( 'reference', 'manageimmo' ); ?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td></td>
        <td><?php submit_button( null, 'primary', 'submit', false ); ?></td>
    </tr>
</table>

