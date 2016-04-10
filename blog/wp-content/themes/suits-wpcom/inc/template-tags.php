<?php
/**
 * Merged navigation function
 *
 * @since Suits 1.0-wpcom
 *
 * @return void
 */
if ( ! function_exists( 'suits_nav' ) ) :
function suits_nav( $nav_id ) {
	global $wp_query, $post;

	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}

	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';

	?>

	<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'suits' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'suits' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'suits' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'suits' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'suits' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->

	<?php

}
endif;

if ( ! function_exists( 'suits_entry_meta' ) ) :
/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own suits_entry_meta() to override in a child theme.
 *
 * @since Suits 1.0
 */
function suits_entry_meta() {

	$format_text = '';

	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'suits' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'suits' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'suits' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);

	// Post format
	$format = get_post_format();
	if ( $format ) {
		$format_text = sprintf( '<span class="entry-format"><a href="%1$s">%2$s</a></span>',
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		);
	}

	// Translators: 1 is category, 2 is post format, 3 is tag, 4 is the date, 5 is the author's name.
	if ( $tag_list && ! empty( $format_text ) )  {
		$utility_text = __( 'Posted in %1$s, %2$s format and tagged %3$s<span class="on-date"> on %4$s</span><span class="by-author"> by %5$s</span>.', 'suits' );
	} elseif ( $tag_list ) {
		$utility_text = __( 'Posted in %1$s and tagged %3$s<span class="on-date"> on %4$s</span><span class="by-author"> by %5$s</span>.', 'suits' );
	} elseif ( $categories_list && ! empty( $format_text ) ) {
		$utility_text = __( 'Posted in %1$s, %2$s format <span class="on-date"> on %4$s</span><span class="by-author"> by %5$s</span>.', 'suits' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'Posted in %1$s <span class="on-date"> on %4$s</span><span class="by-author"> by %5$s</span>.', 'suits' );
	} else {
		$utility_text = __( '<span class="on-date">Posted on %4$s</span><span class="by-author"> by %5$s</span>.', 'suits' );
	}

	printf(
		$utility_text,
		$categories_list,
		$format_text,
		$tag_list,
		$date,
		$author
	);
}
endif;
