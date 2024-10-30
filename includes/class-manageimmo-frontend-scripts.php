<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Frontend_Scripts {

    /**
     * Hook into actions and filters.
     *
     * @since 1.0.0
     *
     * @return void
	 */
    public static function init()
    {
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_scripts' ), 20 );
    }

    /**
     * Register scripts
     *
     * @since 1.0.0
     *
     * @return void
	 */
	private static function register_scripts() {
		$register_scripts = array(
            'manageimmo' => array(
				'src'     => self::get_asset_url( 'assets/js/manageimmo.min.js' ),
				'deps'    => array( 'jquery' ),
			),
        );

		foreach ( $register_scripts as $name => $props ) {
			wp_register_script( $name, $props['src'], $props['deps'], MANAGEIMMO_VERSION, true );
		}
	}

    /**
     * Register styles
     *
     * @since 1.0.0
     *
     * @return void
	 */
	private static function register_styles() {
		$register_styles = array(
			'manageimmo' => array(
				'src'     => self::get_asset_url( 'assets/css/manageimmo.min.css' ),
				'deps'    => array(),
			),
		);

		foreach ( $register_styles as $name => $props ) {
			wp_register_style( $name, $props['src'], $props['deps'], MANAGEIMMO_VERSION );
		}
	}

    /**
     * Load scripts
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public static function load_scripts() {
		global $post;

		self::register_scripts();
		self::register_styles();

    if( is_singular( 'property' ) || is_post_type_archive( 'property' ) || is_tax( 'property_type' ) || ( get_query_var( 'name' ) === 'saved' && is_404() ) || ( $post && has_shortcode( $post->post_content, 'manageimmo_properties' ) ) ) {
            wp_localize_script( 'manageimmo', 'ManageImmo', array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),

                'yourMessageHasBeenReceived' => __( 'Your message has been received', 'manageimmo' ),
                'weWillContactYouASAP'       => __( 'We will contact you as soon as possible.', 'manageimmo' ),
                'somethingWentWrong'         => __( 'Something went wrong', 'manageimmo' ),
                'pleaseTryAgainLater'        => __( 'Please try again later.', 'manageimmo' ),
				'thisFieldIsRequired'        => __( 'This field is required', 'manageimmo' ),
            ) );

            wp_enqueue_style( 'manageimmo' );
            wp_enqueue_script( 'manageimmo' );

			$settings = ManageImmo()->settings->get_settings();

            wp_add_inline_style( 'manageimmo', "
                .manageimmo-button, .manageimmo-button:hover, .manageimmo-button:focus {
                    background-color: {$settings['manageimmo_styling_secondary_btn_bg_color']};
                    border-color: {$settings['manageimmo_styling_secondary_btn_border_color']};
                    color: {$settings['manageimmo_styling_secondary_btn_text_color']};
                }

                .manageimmo-button--primary, .manageimmo-button--primary:hover, .manageimmo-button--primary:focus {
                    background-color: {$settings['manageimmo_styling_primary_btn_bg_color']};
                    border-color: {$settings['manageimmo_styling_primary_btn_border_color']};
                    color: {$settings['manageimmo_styling_primary_btn_text_color']};
                }

                .manageimmo-link, .manageimmo-link:hover, .manageimmo-manageimmo-link:focus {
                    color: {$settings['manageimmo_styling_link_text_color']};
                }
            " );
        }

	}

    /**
     * Get the assets URL.
     *
     * @since 1.0.0
     *
     * @return void
	 */
	private static function get_asset_url( $path ) {
		return plugins_url( $path, MANAGEIMMO_PLUGIN_FILE );
	}

}

ManageImmo_Frontend_Scripts::init();