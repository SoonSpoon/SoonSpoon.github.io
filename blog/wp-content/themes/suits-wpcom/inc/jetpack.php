<?php
/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 *
 * @since Suits 1.0
 *
 * @return void
 */
function suits_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' 	 => 'content',
		'footer_widgets' => array( 'footer-1', 'footer-2', 'footer-3' ),
		'footer'    	 => 'page',
	) );
}
add_action( 'after_setup_theme', 'suits_jetpack_setup' );

