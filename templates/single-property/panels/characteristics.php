<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$table_rows = array(
    __( 'Lodger flat', 'manageimmo' )                => get_post_meta( get_the_ID(), 'lodger_flat', true ),
    __( 'Cellar', 'manageimmo' )                     => get_post_meta( get_the_ID(), 'cellar', true ),
    __( 'Handicapped accessible', 'manageimmo' )     => get_post_meta( get_the_ID(), 'handicapped_accessible', true ),
    __( 'Guest toilet', 'manageimmo' )               => get_post_meta( get_the_ID(), 'guest_toilet', true ),
    __( 'Summer residence practical', 'manageimmo' ) => get_post_meta( get_the_ID(), 'summer_residence_practical', true ),
);

// Only show if at least one row has a value.
if( array_filter( $table_rows ) ): ?>
    <div class="manageimmo-panel">
        <h3 class="manageimmo-panel__header"><?php _e( 'Characteristics', 'manageimmo' ); ?></h3>

        <ul class="grid grid-cols-2 gap-4 m-0 text-sm list-none manageimmo-panel__body">
            <?php foreach ( $table_rows as $label => $value ): if( ! $value ) continue; ?>
                <li class="flex font-semibold">
                    <img src="<?php echo esc_url( ManageImmo()->plugin_url() . '/assets/images/check.svg' ); ?>" class="inline-block w-5 mr-1">
                    <?php echo esc_html( $label ); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>