<?php
/**
 * Luminate Theme Customizer
 *
 * @package Luminate
 */

/**
 * Adds controls to the customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function luminate_customize_controls( $wp_customize ) {

	$wp_customize->add_setting( 'display_site_title',
		array(
			'default'    =>  1,
			'transport'  =>  'postMessage'
		)
	);

	$wp_customize->add_control( 'display_site_title',
		array(
			'label'     => __( 'Display Site Title', 'luminate' ),
			'section'   => 'title_tagline',
			'type'      => 'checkbox'
		)
	);

	$wp_customize->add_setting( 'display_site_description',
		array(
			'default'    =>  0,
			'transport'  =>  'postMessage'
		)
	);

	$wp_customize->add_control( 'display_site_description',
		array(
			'label'     => __( 'Display Site Description', 'luminate' ),
			'section'   => 'title_tagline',
			'type'      => 'checkbox'
		)
	);

	$wp_customize->add_setting( 'logo', array(
		'sanitize_callback' => 'esc_url_raw',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo',
		array(
			'label'    => __( 'Logo', 'luminate' ),
			'section'  => 'title_tagline',
			'settings' => 'logo'
		)
	) );

}
add_action( 'customize_register', 'luminate_customize_controls' );

/**
 * Changes header text of the "Site Title" section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function luminate_customize_headers( $wp_customize ) {

	$wp_customize->get_section( 'title_tagline' )->title = __( 'Site Title, Tagline and Logo', 'luminate' );

}
add_action( 'customize_register', 'luminate_customize_headers' );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function luminate_customize_transports( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'display_site_title' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'display_site_description' )->transport  = 'postMessage';

}
add_action( 'customize_register', 'luminate_customize_transports' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function luminate_customize_preview_js() {
	wp_enqueue_script(
		'luminate_customizer',
		get_template_directory_uri() . '/js/customizer.js',
		array( 'customize-preview' ),
		LUMINATE_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'luminate_customize_preview_js' );
