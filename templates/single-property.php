<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) : the_post();  ?>

    <div class="manageimmo">

        <h2 class="text-4xl"><?php the_title(); ?></h2>

        <div class="flex mt-6 font-semibold">
            <img src="<?php echo esc_url( ManageImmo()->plugin_url() . '/assets/images/map-pin.svg' ); ?>" class="block w-6 mr-1">
            <?php echo esc_html( manageimmo_get_formatted_address( get_the_ID() ) ); ?>
        </div>

        <?php include_once __DIR__ . '/single-property/social-share.php'; ?>

        <span class="inline-flex mt-2 text-sm shadow-sm">
            <a class="flex items-center gap-1 px-2 py-1 text-black no-underline bg-white border rounded-l border-zinc-300 hover:bg-neutral-200" href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>">
                <img src="<?php echo esc_url( ManageImmo()->plugin_url() . '/assets/images/layout-grid-black.svg' ); ?>" class="w-4">
                <?php _e( 'To overview', 'manageimmo' ); ?>
            </a>
            <label class="flex items-center gap-1 px-2 py-1 text-black bg-white border-y border-zinc-300 hover:bg-neutral-200">
                <input type="checkbox" value="<?php echo esc_attr( get_the_ID() ) ?>" class="save-property" <?php checked( manageimmo_is_property_saved( get_the_ID() ) ) ?>>
                <?php _e( 'Save', 'manageimmo' ); ?>
            </label>
            <a class="flex items-center gap-1 px-2 py-1 no-underline border rounded-r manageimmo-button--primary" href="#manageimmo-contact-form">
                <img src="<?php echo esc_url( ManageImmo()->plugin_url() . '/assets/images/send.svg' ); ?>" class="w-4">
                <?php _e( 'Contact', 'manageimmo' ); ?>
            </a>
        </span>

        <div class="grid grid-cols-10 gap-6 mt-6">
            <div class="col-span-10 space-y-6 md:col-span-4">
                <?php include_once __DIR__ . '/single-property/panels/details.php'; ?>
                <?php include_once __DIR__ . '/single-property/panels/characteristics.php'; ?>

                <?php if( get_post_status() !== 'manageimmo-reference' ) : ?>
                    <?php include_once __DIR__ . '/single-property/panels/energy-certificate.php'; ?>
                <?php endif; ?>
            </div>
            <div class="col-span-10 space-y-6 md:col-span-6 h-fit">
                <?php include_once __DIR__ . '/single-property/panels/gallery.php'; ?>

                <?php if( get_post_meta( get_the_ID(), 'contact_attachment_id', true ) ): ?>
                    <?php include_once __DIR__ . '/single-property/panels/contact-details.php'; ?>
                <?php endif; ?>
            </div>
            <div class="col-span-10 md:col-span-10"><?php include_once __DIR__ . '/single-property/panels/description-note.php'; ?></div>
            <div class="col-span-10 md:col-span-5"><?php include_once __DIR__ . '/single-property/panels/location-note.php'; ?></div>
            <div class="col-span-10 md:col-span-5"><?php include_once __DIR__ . '/single-property/panels/contact-form.php'; ?></div>
        </div>

        <?php if( ManageImmo()->settings->get_settings()['manageimmo_general_show_credits'] ): ?>
            <p class="mt-2"><a href="https://manageimmo.de" class="text-sm text-blue-500 no-underline"><?php _e( 'Real estate data imported by: Manageimmo', 'manageimmo' ); ?></a></p>
        <?php endif; ?>

    </div>

<?php endwhile;

get_footer();