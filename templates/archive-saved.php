<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$saved_properties =  manageimmo_get_saved_properties();

$query = new WP_Query( array(
    'post_type'   => 'property',
    'post__in'    => $saved_properties ? $saved_properties : array( 0 ), // We do this to avoid returning all results when the array is empty, see issue #28099.
    'numberposts' => -1,
) );

get_header(); ?>

<div class="manageimmo">

    <h2 class="text-3xl"><?php _e( 'Saved', 'manageimmo' ); ?></h2>

    <a href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>" class="inline-flex mt-4 manageimmo-button manageimmo-button--primary">
        <img src="<?php echo esc_url( ManageImmo()->plugin_url() . '/assets/images/layout-grid-white.svg' ); ?>" class="inline-block w-6 mr-1">
        <?php _e( 'To overview', 'manageimmo' ); ?>
    </a>

    <div class="px-4 py-2 mt-4 text-sm border-y border-zinc-300">
        <span><?php printf( __( '%s properties saved.', 'manageimmo' ), esc_html( $query->found_posts ) ); ?></span>
    </div>

    <div class="grid grid-cols-3 gap-8 mt-4">

        <?php if( $query->have_posts() ): ?>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <?php include __DIR__ . '/content-property.php'; ?>
            <?php endwhile; ?>
        <?php endif; ?>

    </div>

    <?php if( ManageImmo()->settings->get_settings()['manageimmo_general_show_credits'] ): ?>
        <p class="mt-2"><a href="https://manageimmo.de" class="text-sm text-blue-500 no-underline"><?php _e( 'Real estate data imported by: Manageimmo', 'manageimmo' ); ?></a></p>
    <?php endif; ?>

</div>

<?php get_footer();