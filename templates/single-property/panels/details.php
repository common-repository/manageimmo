<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$terms = get_the_terms( get_the_ID(), 'property_building_type' );

if( $terms ) {
    $terms = implode( ', ', array_map( function( $name ) { return __( $name, 'manageimmo' ); }, wp_list_pluck( $terms, 'name' ) ) );
}

// Format [label, value]
$table_rows = array(
    __( 'External ID', 'manageimmo' )              => get_post_meta( get_the_ID(), 'external_id', true ),
    __( 'Building type', 'manageimmo' )            => $terms,
    __( 'Rooms', 'manageimmo' )                    => get_post_meta( get_the_ID(), 'number_of_rooms', true ),
    __( 'Living space', 'manageimmo' )             => manageimmo_format_area( get_post_meta( get_the_ID(), 'living_space', true ) ),
    __( 'Plot area', 'manageimmo' )                => get_post_meta( get_the_ID(), 'plot_area', true ),
    __( 'Usable floor space', 'manageimmo' )       => get_post_meta( get_the_ID(), 'usable_floor_space', true ),
    __( 'Address', 'manageimmo' )                  => manageimmo_get_formatted_address( get_the_ID() ),
    __( 'Number of floors', 'manageimmo' )         => get_post_meta( get_the_ID(), 'number_of_floors', true ),
    __( 'Construction year', 'manageimmo' )        => get_post_meta( get_the_ID(), 'construction_year', true ),
    __( 'Number of parking spaces', 'manageimmo' ) => get_post_meta( get_the_ID(), 'number_of_parking_spaces', true ),
    __( 'Base rent', 'manageimmo' )                => manageimmo_format_price( get_post_meta( get_the_ID(), 'base_rent', true ) ),
    __( 'Total rent', 'manageimmo' )               => get_post_meta( get_the_ID(), 'total_rent', true ),
    __( 'Service charge', 'manageimmo' )           => get_post_meta( get_the_ID(), 'service_charge', true ),
    __( 'Heating costs', 'manageimmo' )            => get_post_meta( get_the_ID(), 'heating_costs', true ),
    __( 'Condition', 'manageimmo' )                => get_post_meta( get_the_ID(), 'condition', true ),
    __( 'Calculated total rent', 'manageimmo' )    => get_post_meta( get_the_ID(), 'calculated_total_rent', true ),
    __( 'Price', 'manageimmo' )                    => manageimmo_format_price( get_post_meta( get_the_ID(), 'price_value', true ) ),
);

?>

<div class="manageimmo-panel">
    <h3 class="manageimmo-panel__header"><?php _e( 'Property details', 'manageimmo' ); ?></h3>

    <div class="text-sm manageimmo-panel__body">
        <table class="mb-0 text-left border-none">
            <?php foreach ( $table_rows as $label => $value ): if( ! $value ) continue; ?>
                <tr>
                    <th class="pb-2 pr-2 font-bold border-none"><?php echo esc_html( $label ); ?>:</th>
                    <td class="pb-2 border-none"><?php esc_html_e( $value, 'manageimmo' ); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>