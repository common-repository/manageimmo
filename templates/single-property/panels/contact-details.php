<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$contact_attachment_id = get_post_meta( get_the_ID(), 'contact_attachment_id', true );

$table_rows = array(
    __( 'Name', 'manageimmo' )         => manageimmo_get_contact_formatted_full_name( get_the_ID() ),
    __( 'Company', 'manageimmo' )      => get_post_meta( get_the_ID(), 'contact_company', true ),
    __( 'Address', 'manageimmo' )      => manageimmo_get_contact_formatted_address( get_the_ID() ),
    __( 'Email', 'manageimmo' )        => get_post_meta( get_the_ID(), 'contact_email', true ),
    __( 'Phone number', 'manageimmo' ) => get_post_meta( get_the_ID(), 'contact_phone_number', true ),
);

?>

<div class="manageimmo-panel">
    <h3 class="manageimmo-panel__header"><?php _e( 'Contact details', 'manageimmo' ); ?></h3>

    <div class="grid grid-cols-3 text-sm manageimmo-panel__body">
        <?php if( $contact_attachment_id ): ?>
            <div class="col-span-3 text-center md:col-span-1 md:order-1 md:text-right">
                <?php echo wp_get_attachment_image( $contact_attachment_id, 'medium', false, array( 'class' => 'object-cover w-40 h-40 rounded-full' ) ); ?>
            </div>
        <?php endif; ?>
        <div class="col-span-3 md:col-span-2">
            <table class="mb-0 text-left border-none">
                <?php foreach ( $table_rows as $label => $value ): if( ! $value ) continue; ?>
                    <tr>
                        <th class="pb-2 pr-2 font-bold border-none"><?php echo esc_html( $label ); ?>:</th>
                        <td class="pb-2 border-none"><?php echo esc_html( $value ); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <a href="#manageimmo-contact-form" class="manageimmo-button manageimmo-button--primary manageimmo-button--full-width manageimmo-button--large"><?php _e( 'To contact form', 'manageimmo' ); ?></a>
        </div>
    </div>
</div>