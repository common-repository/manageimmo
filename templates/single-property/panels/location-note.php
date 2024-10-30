<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="manageimmo-panel">
    <h3 class="manageimmo-panel__header"><?php _e( 'Location', 'manageimmo' ); ?></h3>

    <div class="manageimmo-panel__body">
        <p class="leading-normal"><?php echo wp_kses_post( get_post_meta( get_the_ID(), 'location_note', true ) ); ?></p>
		<?php if( ! function_exists( 'BorlabsCookieHelper' ) ): ?>
			<iframe
				class="w-full h-96 mt-4"
				src="<?php echo esc_url( manageimmo_get_maps_url( get_the_ID() ) ); ?>"
			>
			</iframe>
		<?php else:
			echo BorlabsCookieHelper()->blockContent(
				sprintf( '<iframe class="w-full h-96 mt-4" src="%s"></iframe>', esc_url( manageimmo_get_maps_url( get_the_ID() ) ) ),
				'googlemaps'
			);
		endif; ?>
    </div>
</div>