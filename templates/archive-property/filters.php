<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $wp;

$building_types = get_terms( array( 'taxonomy' => 'property_building_type' ) );
$cities         = get_terms( array( 'taxonomy' => 'property_city' ) );
$types          = get_terms( array( 'taxonomy' => 'property_type' ) );

$distances      = array( 5, 10, 25, 50, 100, 200, 500 );

?>

<form class="grid grid-cols-12 gap-4 mt-4" id="manageimmo-filter-form" method="GET" action="<?php echo esc_url( home_url( $wp->request ) ); ?>">

    <select name="property_type" class="col-span-6 md:col-span-4">
        <option value=""><?php _e( 'All', 'manageimmo' ); ?></option>
		<?php foreach ( $types as $type ) : ?>
			<option
				value="<?php echo esc_html( $type->term_id ); ?>"
				<?php selected( $_GET['property_type'] ?? '', 'rent' ) ?>
			>
				<?php echo esc_html( $type->name ); ?>
			</option>
		<?php endforeach; ?>
    </select>
    <select name="building_type" class="col-span-6 md:col-span-4">
        <option value=""><?php _e( 'All building types', 'manageimmo' ); ?></option>
        <?php foreach ( $building_types as $building_type ): ?>
            <option
                value="<?php echo esc_attr( $building_type->term_id ); ?>"
                <?php selected( $_GET['building_type'] ?? 0, $building_type->term_id ) ?>
            >
                <?php esc_html_e( $building_type->name, 'manageimmo' ); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <select name="city" class="col-span-12 md:col-span-4">
        <option value=""><?php _e( 'All cities', 'manageimmo' ); ?></option>
        <?php foreach ( $cities as $city ): ?>
            <option
                value="<?php echo esc_attr( $city->term_id ); ?>"
                <?php selected( $_GET['city'] ?? 0, $city->term_id ) ?>
            >
                <?php echo esc_html( $city->name ); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <?php if( manageimmo_get_google_api_key() ): ?>

        <input type="text" name="proximity_search" placeholder="<?php _e( 'Proximity search (E.g. city, postcode, street, etc.)', 'manageimmo' ); ?>" value="<?php echo esc_attr( $_GET['proximity_search'] ?? '' ); ?>" class="col-span-9">

        <select name="proximity_distance" class="col-span-3">
            <?php foreach ( $distances as $distance ): ?>
                <option
                    value="<?php echo esc_attr( $distance ); ?>"
                    <?php selected( $_GET['proximity_distance'] ?? 0, $distance ); ?>
                >
                    <?php echo esc_attr( $distance ); ?> km
                </option>
            <?php endforeach; ?>
        </select>

    <?php endif; ?>

    <div class="flex flex-wrap items-center col-span-12 gap-2 md:col-span-9">
        <button class="inline-flex items-center manageimmo-button manageimmo-button--primary">
            <img src="<?php echo esc_url( ManageImmo()->plugin_url() . '/assets/images/search.svg' ); ?>" class="inline-block w-6 mr-1">
            <?php _e( 'Search', 'manageimmo' ); ?>
        </button>
        <button type="button" class="inline-flex items-center manageimmo-button" id="manageimmo-open-more-filters">
            <img src="<?php echo esc_url( ManageImmo()->plugin_url() . '/assets/images/adjustments.svg' ); ?>" class="inline-block w-6 mr-1">
            <?php _e( 'More filters', 'manageimmo' ); ?>
        </button>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'property' ) . 'saved' ); ?>" class="inline-flex items-center leading-none manageimmo-button">
            <img src="<?php echo esc_url( ManageImmo()->plugin_url() . '/assets/images/device-flopy.svg' ); ?>" class="inline-block w-6 mr-1">
            <?php _e( 'Saved', 'manageimmo' ); ?>
        </a>
        <button class="manageimmo-button manageimmo-button--link" id="reset-filters"><?php _e( 'Reset', 'manageimmo' ); ?></button>
    </div>

    <input type="text" name="external_id" placeholder="<?php _e( 'Search by external ID', 'manageimmo' ); ?>" value="<?php echo esc_attr( $_GET['external_id'] ?? '' ); ?>" class="col-span-12 md:col-span-3">

    <div class="col-span-12" id="manageimmo-more-filters" style="display: none;">
        <div class="grid grid-cols-2 gap-16 p-4 pb-12 border-t border-zinc-300" id="manageimmo-more-filters">
            <div class="col-span-2 sm:col-span-1">
                <label><?php _e( 'Living space', 'manageimmo' ); ?></label>
                <div class="px-4 mt-2" id="living-space-slider"></div>
                <input type="hidden" name="min_living_space">
                <input type="hidden" name="max_living_space">
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label><?php _e( 'Rooms', 'manageimmo' ); ?></label>
                <div class="px-4 mt-2" id="rooms-slider"></div>
                <input type="hidden" name="min_rooms">
                <input type="hidden" name="max_rooms">
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label><?php _e( 'Base rent', 'manageimmo' ); ?></label>
                <div class="px-4 mt-2" id="base-rent-slider"></div>
                <input type="hidden" name="min_base_rent">
                <input type="hidden" name="max_base_rent">
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label><?php _e( 'Price', 'manageimmo' ); ?></label>
                <div class="px-4 mt-2" id="price-slider"></div>
                <input type="hidden" name="min_price">
                <input type="hidden" name="max_price">
            </div>
        </div>
    </div>
</form>