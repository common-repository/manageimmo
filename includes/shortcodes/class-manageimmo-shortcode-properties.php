<?php

/**
 * @package ManageImmo
 * @since   1.1.7
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Shortcode_Properties {

	/**
	 * Output the shortcode.
     *
     * @since 1.1.7
	 *
	 * @param  array $atts
     * @return void
	 */
	public static function output( $atts ) {
        manageimmo_get_template( 'archive-property-shortcode.php' );
	}

}
