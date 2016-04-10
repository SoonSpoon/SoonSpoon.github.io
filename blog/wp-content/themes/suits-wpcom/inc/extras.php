<?php

/**
 * Gets the number of sidebars.
 *
 * @since Suits 1.0
 *
 * @return the number of sidebars
 *
 */
function suits_get_sidebars(){
	$num_sidebars = 0;

	if ( is_active_sidebar( 'footer-1' ) )
		$num_sidebars++;

	if ( is_active_sidebar( 'footer-2' ) )
		$num_sidebars++;

	if ( is_active_sidebar( 'footer-3' ) )
		$num_sidebars++;

	return $num_sidebars;
}

/**
 * Extends the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. When avatars are disabled in discussion settings.
 * 3. Using a full-width layout.
 * 4. Active widgets in the sidebar to change the layout and spacing.
 *
 * @since Suits 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 *
 * props to Twenty Fourteen
 */
function suits_body_class( $classes ) {
	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	if ( ! get_option( 'show_avatars' ) )
		$classes[] = 'no-avatars';

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'template-full-width.php' ) )
		$classes[] = 'full-width';

	$sidebar_num = suits_get_sidebars();

	switch( $sidebar_num ) :

		case 1:
			$classes[] = 'one-footer-sidebar';
			break;

		case 2:
			$classes[] = 'two-footer-sidebars';
			break;

		case 3:
			$classes[] = 'three-footer-sidebars';
			break;

		default:
			$classes[] = 'no-footer-sidebar';

	endswitch;

	return $classes;
}
add_filter( 'body_class', 'suits_body_class' );


/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Suits 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 *
 *  * props to _s
 */
function suits_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'suits' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'suits_wp_title', 10, 2 );


/**
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Suits 1.0
 *
 * @return void
 *
 * props to Twenty Fourteen
 */
function suits_content_width() {
	if ( is_page_template( 'template-full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 940;
	}
}
add_action( 'template_redirect', 'suits_content_width' );

if ( ! function_exists( 'suits_the_attached_image' ) ) :
/**
 * Prints the attached image with a link to the next attached image.
 *
 * @since Suits 1.0
 *
 * @return void
 *
 * props to _s
 */
function suits_the_attached_image() {
	$post                = get_post();
	$attachment_size     = apply_filters( 'suits_attachment_size', array( 940, 940 ) );
	$next_attachment_url = wp_get_attachment_url();

	/**
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID'
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id ) {
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		} else {
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
		}
	}

	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),
		the_title_attribute( array( 'echo' => false ) ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;