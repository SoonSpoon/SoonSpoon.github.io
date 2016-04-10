<?php
/**
 * Suits functions and definitions.
 *
 * @package Suits
 * @since Suits 1.0
 */

/**
 * Sets up the content width value based on the theme's design.
 * @see suits_content_width() for template-specific adjustments.
 */
if ( ! isset( $content_width ) )
	$content_width = 620;

/**
 * Sets up theme defaults and registers the various WordPress features that
 * Suits supports.
 *
 * @uses add_theme_support() To add support for automatic feed links,
 * post thumbnails and print styles
 * @uses register_nav_menu() To add support for a navigation menu.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Suits 1.0
 *
 * @return void
 */
function suits_setup() {
	load_theme_textdomain( 'suits', get_template_directory() . '/languages' );

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Switches default core markup for search form, comment form, and comments
	// to output valid HTML5.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Navigation Menu', 'suits' ) );

	/*
	 * This theme supports custom background color and image, and here
	 * we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff',
	) );

	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 620, 9999 );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );

	// Enable post format support
	add_theme_support( 'post-formats', array( 'audio', 'aside', 'chat', 'gallery', 'image', 'link', 'quote', 'video' ) );

}
add_action( 'after_setup_theme', 'suits_setup' );

/**
 * Returns the Google font stylesheet URL, if available.
 *
 * @since Suits 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 *
 * props to Twenty Fourteen
 */
function suits_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by Lato, translate this to 'off'. Do not translate into your
	 * own language.
	 */
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'suits' ) )
        $font_url = add_query_arg( 'family', urlencode( 'Lato:100,300,400' ), "//fonts.googleapis.com/css" );

    return $font_url;
}

/**
 * Enqueues scripts and styles for front end.
 *
 * @since Suits 1.0
 *
 * @return void
 */
function suits_scripts_styles() {

	// Adds JavaScript to pages with the comment form to support sites with
	// threaded comments (when in use).
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Loads JavaScript file with functionality specific to Suits.
	wp_enqueue_script( 'suits-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '2013-10-20', true );

	// Add Lato font, used in the main stylesheet.
	wp_enqueue_style( 'suits-font', suits_fonts_url(), array(), null );

	// Loads our main stylesheet.
	wp_enqueue_style( 'suits-style', get_stylesheet_uri(), array(), '2013-10-20' );

}
add_action( 'wp_enqueue_scripts', 'suits_scripts_styles' );

/**
 * Registers widget areas.
 *
 * @since Suits 1.0
 *
 * @return void
 */
function suits_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'suits' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Appears on posts and pages in the sidebar.', 'suits' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer One', 'suits' ),
		'id'            => 'footer-1',
		'description'   => __( 'Appears in the footer section of the site.', 'suits' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Two', 'suits' ),
		'id'            => 'footer-2',
		'description'   => __( 'Appears in the footer section of the site.', 'suits' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Three', 'suits' ),
		'id'            => 'footer-3',
		'description'   => __( 'Appears in the footer section of the site.', 'suits' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'suits_widgets_init' );

/**
* Customizer additions
*/
require get_template_directory() . '/inc/customizer.php';

/**
* Template tags
*/
require get_template_directory() . '/inc/template-tags.php';

/**
* Extras
*/
require get_template_directory() . '/inc/extras.php';

/**
* Template tags
*/
require get_template_directory() . '/inc/custom-header.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

// updater for WordPress.com themes
if ( is_admin() )
	include dirname( __FILE__ ) . '/inc/updater.php';
