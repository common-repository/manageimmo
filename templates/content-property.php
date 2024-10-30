<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$attachment_ids = get_post_meta( get_the_ID(), 'gallery_attachment_ids', true );
$address        = manageimmo_get_formatted_address( get_the_ID() );

// Format [label, value]
$table_data = array(
    __( 'External ID', 'manageimmo' )           => get_post_meta( get_the_ID(), 'external_id', true ),
    __( 'Rooms', 'manageimmo' )                 => get_post_meta( get_the_ID(), 'number_of_rooms', true ),
    __( 'Living space', 'manageimmo' )          => manageimmo_format_area( get_post_meta( get_the_ID(), 'living_space', true ) ),
    __( 'Plot area', 'manageimmo' )             => get_post_meta( get_the_ID(), 'plot_area', true ),
    __( 'Usable floor space', 'manageimmo' )    => get_post_meta( get_the_ID(), 'usable_floor_space', true ),
    __( 'Base rent', 'manageimmo' )             => manageimmo_format_price( get_post_meta( get_the_ID(), 'base_rent', true ) ),
    __( 'Calculated total rent', 'manageimmo' ) => get_post_meta( get_the_ID(), 'calculated_total_rent', true ),
    __( 'Price', 'manageimmo' )                 => manageimmo_format_price( get_post_meta( get_the_ID(), 'price_value', true ) ),
);

?>

<div class="overflow-hidden bg-white border rounded shadow-sm border-zinc-300">
    <a href="<?php the_permalink(); ?>" class="relative">
        <?php if( $attachment_ids && is_array( $attachment_ids ) ): ?>
            <?php echo wp_get_attachment_image( $attachment_ids[0], 'large', false, array( 'class' => 'block object-cover w-full h-60' ) ); ?>
        <?php endif; ?>

        <?php if( get_post_status() !== 'publish' ): ?>
            <span class="absolute px-4 text-sm bg-white border rounded border-zinc-300 right-2 top-2"><?php echo get_post_status_object( get_post_status() )->label; ?></span>
        <?php endif; ?>
    </a>

    <div class="p-4">
        <h3 class="text-base font-medium"><a href="<?php the_permalink(); ?>" class="no-underline manageimmo-link hover:underline hover:decoration-solid"><?php echo esc_html( the_title() ); ?></a></h3>
        <div class="text-sm font-semibold"><?php echo esc_html( $address ); ?></div>

        <table class="w-full mt-4 text-sm text-left table-fixed">
            <?php foreach ( $table_data as $label => $value ): if( ! $value ) continue; ?>
                <tr>
                    <td><?php echo esc_html( $label ); ?>:</td>
                    <td><?php echo esc_html( $value ); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <span class="inline-flex mt-6 text-sm border rounded shadow-sm border-zinc-300">
            <a class="flex items-center gap-1 px-2 py-1 text-black no-underline border-r border-zinc-300 hover:bg-neutral-200" href="<?php the_permalink(); ?>">
                <img src="<?php echo esc_url( ManageImmo()->plugin_url() . '/assets/images/file.svg' ); ?>" class="w-4">
                <?php _e( 'Details', 'manageimmo' ); ?>
            </a>
            <label class="flex items-center gap-1 px-2 py-1 text-black hover:bg-neutral-200">
                <input type="checkbox" value="<?php echo esc_attr( get_the_ID() ) ?>" class="save-property" <?php checked( manageimmo_is_property_saved( get_the_ID() ) ) ?>>
                <?php _e( 'Save', 'manageimmo' ); ?>
            </label>
        </span>
    </div>
</div>