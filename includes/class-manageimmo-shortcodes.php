<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Shortcodes {

	/**
	 * Init shortcodes.
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public static function init() {
		$shortcodes = array(
			'manageimmo_properties' => __CLASS__ . '::properties',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( $shortcode, $function );
		}
	}

	/**
	 * Shortcode wrapper.
	 *
	 * @param  string[] $function
	 * @param  array    $atts
	 * @return string
	 */
	public static function shortcode_wrapper( $function, $atts = array() ) {
		ob_start();
		call_user_func( $function, $atts );
		return ob_get_clean();
	}

	/**
	 * Render the properties.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
    public static function properties( $atts ) {
		return self::shortcode_wrapper( array( 'ManageImmo_Shortcode_Properties', 'output' ), $atts );
    }

}