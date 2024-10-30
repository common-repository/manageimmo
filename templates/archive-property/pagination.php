<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $wp_query;

?>

<div class="flex justify-between px-4 py-2 mt-4 text-sm border-y border-zinc-300 manageimmo-property-pagination">
    <span><?php printf( __( '%s properties being offered.', 'manageimmo' ), esc_html( $wp_query->found_posts ) ); ?></span>

    <?php the_posts_pagination( array(
        'prev_text' => sprintf( '<img src="%s" class="w-6">', esc_url( ManageImmo()->plugin_url() . '/assets/images/chevrons-left.svg' ) ),
        'next_text' => sprintf( '<img src="%s" class="w-6">', esc_url( ManageImmo()->plugin_url() . '/assets/images/chevrons-right.svg' ) ),
        'class'     => '',
    ) ); ?>
</div>