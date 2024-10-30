<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="manageimmo-panel">
    <h3 class="manageimmo-panel__header"><?php _e( 'Description', 'manageimmo' ); ?></h3>

    <p class="manageimmo-panel__body leading-normal"><?php echo wp_kses_post( get_post_meta( get_the_ID(), 'description_note', true ) ); ?></p>
</div>