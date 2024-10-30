<?php

/**
 * @package ManageImmo
 * @since  1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_Admin {

	/**
	 * Constructor
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_action('admin_notices', array( $this, 'property_limit_notice' ) );
	}

    /**
	 * Include any classes we need within admin
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public function includes() {
		include_once __DIR__ . '/class-manageimmo-admin-settings.php';
		include_once __DIR__ . '/class-manageimmo-admin-post-types.php';
		include_once __DIR__ . '/class-manageimmo-admin-menus.php';
		include_once __DIR__ . '/class-manageimmo-admin-assets.php';
	}

	/**
	 * If the website does not have an active license we tell the admin that 5 properties is the max without a license.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function property_limit_notice() {
		global $pagenow;

		if ( 'edit.php' === $pagenow && 'property' === $_GET['post_type'] && ! manageimmo_has_active_license() ) {
			?>
				<div class="notice notice-warning">
					<p><?php _e( 'To import more than 5 properties you must have an active license.', 'manageimmo' ); ?></p>
				</div>
			<?php
		}
	}

}

new ManageImmo_Admin();