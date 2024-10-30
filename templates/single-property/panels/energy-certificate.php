<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$energy_certificate_creation_date = get_post_meta( get_the_ID(), 'energy_certificate_creation_date', true );
$thermal_characteristic            = get_post_meta( get_the_ID(), 'thermal_characteristic', true );
$building_energy_rating_type       = get_post_meta( get_the_ID(), 'building_energy_rating_type', true );

// Format [label, value]
$table_rows = array(
    __( 'Building energy rating type', 'manageimmo' )                          => $building_energy_rating_type,
    __( 'Creation date', 'manageimmo' )                                        => manageimmo_value_to_label( $energy_certificate_creation_date ),
    __( 'Legal construction year', 'manageimmo' )                              => get_post_meta( get_the_ID(), 'energy_certificate_legal_construction_year', true ),
    __( 'Energy source', 'manageimmo' )                                        => get_post_meta( get_the_ID(), 'energy_source_enev_2014', true ),
    _x( 'Energy consumption', 'certificate energy consumption', 'manageimmo' ) => 'Energy consumption' === $building_energy_rating_type ? manageimmo_kwh_m2_to_label( $thermal_characteristic ) : false,
    __( 'Thermal characteristic', 'manageimmo' )                               => 'Energy consumption' !== $building_energy_rating_type ? manageimmo_kwh_m2_to_label( $thermal_characteristic ) : false,
    __( 'Consumption contains warm water', 'manageimmo' )                      => get_post_meta( get_the_ID(), 'energy_consumption_contains_warm_water', true ) ? __( 'Yes' ) : '',
    __( 'Certificate class', 'manageimmo' )                                    => get_post_meta( get_the_ID(), 'energy_certificate_class', true ),
);

?>

<div class="manageimmo-panel">
    <h3 class="manageimmo-panel__header"><?php _e( 'Energy certificate', 'manageimmo' ); ?></h3>

    <div class="text-sm manageimmo-panel__body">
        <table class="mb-0 text-left border-none">
            <?php foreach ( $table_rows as $label => $value ): if( ! $value ) continue; ?>
                <tr>
                    <th class="pb-2 pr-2 font-bold border-none"><?php echo esc_html( $label ); ?>:</th>
                    <td class="pb-2 border-none"><?php _e( $value, 'manageimmo' ); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="mt-4">
            <?php if(  $thermal_characteristic && 'BEFORE_01_MAY_2014' === $energy_certificate_creation_date ): ?>
                <?php include_once ManageImmo()->plugin_path() . '/templates/energy-certificates/before-2014.php'; ?>
            <?php elseif(  $thermal_characteristic && 'FROM_01_MAY_2014' === $energy_certificate_creation_date ): ?>
                <?php include_once ManageImmo()->plugin_path() . '/templates/energy-certificates/from-2014.php'; ?>
            <?php endif; ?>
        </div>
    </div>
</div>