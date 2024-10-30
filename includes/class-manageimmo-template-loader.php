<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Template_Loader
{

    /**
     * Init.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function init() {
        add_filter( 'template_include', array( __CLASS__, 'template_loader' ) );
    }

    /**
     * Load our custom templates.
     *
     * @since 1.0.0
     *
     * @param  string $template
     * @return string
     */
    public static function template_loader( $template ) {
        $default_file = self::get_template_loader_default_file();

        if ( $default_file ) {
            $template = ManageImmo()->plugin_path() . '/templates/' . $default_file;
        }

        return $template;
    }

    /**
     * Get the template loader default file
     *
     * @since 1.0.0
     *
     * @return string
     */
    private static function get_template_loader_default_file() {
        if ( is_singular( 'property' ) ) {
            $default_file = 'single-property.php';
        } elseif ( is_post_type_archive( 'property' ) || is_tax( 'property_type' ) ) {
            $default_file = 'archive-property.php';
        } elseif ( get_query_var( 'name' ) === 'saved' && is_404() ) {
            $default_file = 'archive-saved.php';
        } else {
            $default_file = '';
        }

        return $default_file;
    }

}

add_action( 'init', array( 'ManageImmo_Template_Loader', 'init' ) );
