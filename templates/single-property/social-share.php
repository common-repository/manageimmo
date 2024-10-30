<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="flex mt-6">
    <div class="mr-2 text-sm"><?php _e( 'Share' ); ?>:</div>
    <ul class="list-none m-0 p-0 flex space-x-2">
        <li>
            <a href="<?php echo esc_url( sprintf( 'mailto:?subject=%s&body=%s', esc_attr( get_the_title() ), esc_attr( get_the_permalink() ) ) ); ?>">
                <img src="<?php echo esc_url( ManageImmo()->plugin_url() . '/assets/images/mail.svg' ); ?>" class="block w-6">
            </a>
        </li>
        <li>
            <a href="<?php echo esc_url( sprintf( 'https://api.whatsapp.com/send?text=%s %s', esc_attr( get_the_title() ), esc_attr( get_the_permalink() ) ) ); ?>">
                <img src="<?php echo esc_url( ManageImmo()->plugin_url() . '/assets/images/brand-whatsapp.svg' ); ?>" class="block w-6">
            </a>
        </li>
        <li>
            <a href="<?php echo esc_url( sprintf( 'https://www.facebook.com/sharer.php?u=%s', esc_attr( get_the_permalink() ) ) ); ?>">
                <img src="<?php echo esc_url( ManageImmo()->plugin_url() . '/assets/images/brand-facebook.svg' ); ?>" class="block w-6">
            </a>
        </li>
    </ul>
</div>