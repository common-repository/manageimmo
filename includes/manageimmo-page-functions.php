<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get page ID.
 *
 * @since 1.1.0
 *
 * @param  string $page
 * @return void
 */
function manageimmo_get_page_id( $page ) {
	return absint( get_option( 'manageimmo_' . $page . '_page_id' ) );
}