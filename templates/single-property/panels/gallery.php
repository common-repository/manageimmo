<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$gallery_attachment_ids = get_post_meta( get_the_ID(), 'gallery_attachment_ids', true );

?>

<div>
    <div
        class="w-full h-full rounded swiper max-h-96 property__images"
        style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
    >
        <div class="swiper-wrapper">
            <?php if( is_array( $gallery_attachment_ids ) ): ?>
                <?php foreach( $gallery_attachment_ids as $attachment_id ): ?>
                    <div class="swiper-slide">
                        <?php if ( wp_attachment_is( 'image', $attachment_id ) ) : ?>
                            <a href="<?php echo esc_url( wp_get_attachment_image_url( $attachment_id, 'full' ) ); ?>" class="glightbox">
                                <img class="object-cover w-full h-full rounded" src="<?php echo esc_url( wp_get_attachment_image_url( $attachment_id, 'large' ) ); ?>">
                            </a>
                        <?php elseif ( wp_attachment_is( 'video', $attachment_id ) ) : ?>
                            <video class="w-full" controls>
                                <source src="<?php echo esc_url( wp_get_attachment_url( $attachment_id ) ); ?>">
                            </video>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <div class="h-32 py-3 swiper property__thumbnails">
        <div class="swiper-wrapper">
            <?php if( is_array( $gallery_attachment_ids ) ): ?>
                <?php foreach( $gallery_attachment_ids as $attachment_id ): ?>
                    <div class="swiper-slide">
                        <img
                            class="w-full h-full rounded <?php echo wp_attachment_is( 'image', $attachment_id ) ? 'object-cover' : 'object-contain'; ?>"
                            src="<?php echo esc_url( wp_get_attachment_image_url( $attachment_id, 'thumbnail', true ) ); ?>"
                        >
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>