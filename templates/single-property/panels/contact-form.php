<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$error_msg = sprintf( 'data-pristine-required-message="%s"', __( 'This field is required', 'manageimmo' ) );

$cancellation_policy_page_id = absint( ManageImmo()->settings->get_settings()['manageimmo_contact_form_cancellation_policy_page_id'] );
$privacy_policy_page_id      = absint( ManageImmo()->settings->get_settings()['manageimmo_contact_form_privacy_policy_page_id'] );
$fallback_shortcode          = ManageImmo()->settings->get_settings()['manageimmo_contact_form_fallback_shortcode'];

$immoscout24_id = get_post_meta( get_the_ID(), 'immoscout24_id', true );

?>

<div class="relative manageimmo-panel" id="manageimmo-contact-form">
    <h3 class="manageimmo-panel__header"><?php _e( 'Contact form', 'manageimmo' ); ?></h3>

    <div class="p-4">
        <?php if( $immoscout24_id ): ?>
            <form action="" class="grid grid-cols-12 gap-4" method="POST">

                <input type="hidden" name="immoscout24_id" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'immoscout24_id', true ) ); ?>">

                <div class="grid grid-cols-12 col-span-12 gap-x-4">
                    <label class="col-span-12" for="salutation"><?php _e( 'Your name', 'manageimmo' ); ?></label>

                    <div class="col-span-3 input-wrapper">
                        <select name="salutation" id="salutation" required <?php echo $error_msg; ?> class="block w-full mt-2">
                            <option value="MALE"><?php _e( 'Mr.', 'manageimmo' ); ?></option>
                            <option value="FEMALE"><?php _e( 'Mrs.', 'manageimmo' ); ?></option>
                        </select>
                    </div>

                    <div class="col-span-4 input-wrapper">
                        <input type="text" name="first_name" required <?php echo $error_msg; ?> class="block w-full col-span-4 mt-2" placeholder="<?php _e( 'First name', 'manageimmo' ); ?>">
                    </div>

                    <div class="col-span-5 input-wrapper">
                        <input type="text" name="last_name" required <?php echo $error_msg; ?> class="block w-full mt-2" placeholder="<?php _e( 'Last name', 'manageimmo' ); ?>">
                    </div>
                </div>

                <label class="col-span-12 input-wrapper">
                    <?php _e( 'Your email', 'manageimmo' ); ?>
                    <input type="email" name="email" required <?php echo $error_msg; ?> class="block w-full mt-2">
                </label>

                <div class="grid grid-cols-12 col-span-12 gap-x-4">
                    <label class="col-span-12"><?php _e( 'Your address', 'manageimmo' ); ?></label>

                    <div class="col-span-9 mt-2 input-wrapper">
                        <input type="text" name="street" required <?php echo $error_msg; ?> class="block w-full" placeholder="<?php _e( 'Street', 'manageimmo' ); ?>">
                    </div>

                    <div class="col-span-3 mt-2 input-wrapper">
                        <input type="number" name="house_number" required <?php echo $error_msg; ?> class="block w-full" placeholder="<?php _e( 'Nr.', 'manageimmo' ); ?>">
                    </div>

                    <div class="col-span-4 input-wrapper">
                        <input type="number" name="postcode" required <?php echo $error_msg; ?> class="block w-full mt-4" placeholder="<?php _e( 'Postcode', 'manageimmo' ); ?>">
                    </div>

                    <div class="col-span-8 input-wrapper">
                        <input type="text" name="city" required <?php echo $error_msg; ?> class="block w-full mt-4" placeholder="<?php _e( 'City', 'manageimmo' ); ?>">
                    </div>
                </div>

                <label class="col-span-12 input-wrapper">
                    <?php _e( 'Your phone number', 'manageimmo' ); ?>
                    <input type="text" name="phone" required <?php echo $error_msg; ?> class="block w-full mt-2">
                </label>

                <label class="col-span-12">
                    <?php _e( 'Your message', 'manageimmo' ); ?>
                    <textarea name="message" rows="8" class="block w-full mt-2" placeholder="<?php _e( 'If you are interested please contact us.', 'manageimmo' ); ?>"></textarea>
                </label>

                <?php if( $cancellation_policy_page_id ): ?>
                    <label class="col-span-12 text-base input-wrapper">
                        <input type="checkbox" required name="cancellation_policy" <?php echo $error_msg; ?>>
                        <?php printf(
                                __( 'I have read and accepted the %s', 'manageimmo' ),
                                sprintf(
                                    '<a href="%s" class="text-blue-500 no-underline">%s</a>',
                                    get_the_permalink( $cancellation_policy_page_id ),
                                    __( 'cancellation policy', 'manageimmo' )
                                ),
                            );
                        ?>
                    </label>
                <?php endif; ?>

                <label class="col-span-12 text-base input-wrapper">
                    <input type="checkbox" required name="privacy_policy" <?php echo $error_msg; ?>>
                    <?php printf(
                        __( 'I consent to the processing of my data for the purpose of handling my request and I have read the %s', 'manageimmo' ),
                        sprintf( '<a href="%s" class="text-blue-500 no-underline">%s</a>.', get_the_permalink( $privacy_policy_page_id ), __( 'privacy policy', 'manageimmo' ) )
                    ); ?>

                </label>

                <button class="col-span-12 manageimmo-button manageimmo-button--primary manageimmo-button--full-width manageimmo-button--large"><?php _e( 'Submit', 'manageimmo' ); ?></button>

            </form>
        <?php elseif( $fallback_shortcode ): ?>
            <?php echo do_shortcode( $fallback_shortcode ); ?>
        <?php endif; ?>
    </div>

</div>