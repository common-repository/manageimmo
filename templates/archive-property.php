<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $wp_query;

$settings = ManageImmo()->settings->get_settings();

get_header(); ?>

<div class="manageimmo">

    <?php if ( ! $settings['manageimmo_properties_hide_archive_title'] ) : ?>
        <h2 class="text-3xl"><?php is_tax() ? single_term_title() : _e( 'Properties', 'manageimmo' ); ?></h2>
    <?php endif; ?>

    <?php include_once __DIR__ . '/archive-property/filters.php'; ?>

    <?php include __DIR__ . '/archive-property/pagination.php'; ?>

    <div class="grid grid-cols-1 gap-8 mt-4 sm:grid-cols-2 md:grid-cols-3" id="manageimmo-property-list">

        <?php if( have_posts() ): ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php include __DIR__ . '/content-property.php'; ?>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="col-span-3 px-4"><?php _e( 'No properties found that match your criteria.', 'manageimmo' ); ?></p>
        <?php endif; ?>

    </div>

    <?php include __DIR__ . '/archive-property/pagination.php'; ?>

    <?php if( ManageImmo()->settings->get_settings()['manageimmo_general_show_credits'] ): ?>
        <p class="mt-2"><a href="https://manageimmo.de" class="text-sm text-blue-500 no-underline"><?php _e( 'Real estate data imported by: Manageimmo', 'manageimmo' ); ?></a></p>
    <?php endif; ?>

</div>

<?php get_footer();