<?php
/**
 * Set up all options for theme to use in customizer.
 *
 * @since Suits 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function suits_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'suits_customize_register' );

/**
 * Binds JavaScript handlers to make Customizer preview reload changes
 * asynchronously.
 *
 * @since Suits 1.0
 */
function suits_customize_preview_js() {
    wp_enqueue_script( 'suits-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20131020', true );
}
add_action( 'customize_preview_init', 'suits_customize_preview_js' );