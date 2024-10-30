<?php

/**
 * @package ManageImmo
 * @since  1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Creates attachment from the given URL. If it already exists, we return the attachment ID.
 *
 * @since 1.1.0
 *
 * @return string $file
 * @return int    $property_id
 * @return int
 */
function manageimmo_url_to_attachment( $url, $property_id ) {
    if ( ! function_exists( 'media_handle_upload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
	}

    $filename          = pathinfo( $url, PATHINFO_FILENAME );
    $attachment_exists = manageimmo_attachment_exists( $filename );

    if( $attachment_exists ) {
        return $attachment_exists;
    }

	$tmp = download_url( $url );

	$file_array = array(
        'name'     => basename( strtok( $url, '?' ) ), // Remove get params.
		'tmp_name' => $tmp
	);

	if ( is_wp_error( $tmp ) ) {
		@unlink( $file_array[ 'tmp_name' ] );
		return $tmp;
	}

	$attachment_id = media_handle_sideload( $file_array, $property_id );

	@unlink( $file_array['tmp_name'] );

    return $attachment_id;
}

/**
 * Creates attachment from the given file. If it already exists, we return the attachment ID.
 *
 * @since 1.1.0
 *
 * @return string $file
 * @return int    $property_id
 * @return int
 */
function manageimmo_file_to_attachment( $file, $property_id ) {
    $upload_dir = wp_upload_dir();
    $basename   = basename( $file );
    $filename   = pathinfo( $file, PATHINFO_FILENAME );

    $attachment_exists = manageimmo_attachment_exists( $filename );

    if( $attachment_exists ) {
        return $attachment_exists;
    }

    // If we can't rename the file, abort.
    if( ! rename( $file, $upload_dir['path'] . '/' . $basename ) ) {
        return;
    }

    $file     = $upload_dir['path'] . '/' . $basename;
    $filetype = wp_check_filetype( $file );

    $attachment = array(
        'guid'           => $upload_dir['url'] . '/' . $basename,
        'post_mime_type' => $filetype['type'],
        'post_title'     => $filename,
        'post_content'   => '',
        'post_status'    => 'inherit',
    );

    $attachment_id = wp_insert_attachment( $attachment, $file, $property_id );

    // Generate attachment metadata.
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $attach_data = wp_generate_attachment_metadata( $attachment_id, $file );
    wp_update_attachment_metadata( $attachment_id, $attach_data );

    return $attachment_id;
}

/**
 * Check if an attachment with the given title exists.
 *
 * @since 1.0.0
 *
 * @param  string $title
 * @return int
 */
function manageimmo_attachment_exists( $title ) {
    if ( ! function_exists( 'post_exists' ) ) {
        require_once ABSPATH . 'wp-admin/includes/post.php';
    }

    return post_exists( $title, '', '', 'attachment' );
}