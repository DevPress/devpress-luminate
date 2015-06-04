<?php
/**
 * Functions used to implement options
 *
 * @package Luminate
 */

/**
 * Get default footer text
 *
 * @return string $text
 */
function luminate_footer_text() {
	$text = sprintf(
		__( 'Powered by %s', 'luminate' ),
		'<a href="' . esc_url( __( 'http://wordpress.org/', 'luminate' ) ) . '">WordPress</a>'
	);
	$text .= '<span class="sep"> | </span>';
	$text .= sprintf(
		__( '%1$s by %2$s.', 'luminate' ),
			'Luminate Theme',
			'<a href="http://devpress.com/" rel="designer">DevPress</a>'
	);
	return $text;
}

/**
 * Outputs search icon in menu based on customizer option
 *
 * @since Luminate 0.1
 */
function luminate_search_in_menu( $items, $args ) {

	if (
		( get_theme_mod( 'primary-menu-search', false ) && 'primary' == $args->theme_location ) ||
		( get_theme_mod( 'secondary-menu-search', false ) && 'secondary' == $args->theme_location )
	) :

		$selector = '#' . $args->theme_location . '-navigation .toggle-search';
	    $items .= '<li class="menu-item menu-search">';
	    $items .= '<a class="toggle-search-link" href="#search" data-toggle="' . $selector . '">';
	    $items .= '<span class="screen-reader-text">' . __( 'Search', 'luminate' ) . '</span>';
	    $items .= '</a></li>';
	    $items .= '<div class="toggle-search">' . get_search_form( false ) . '</div>';

	endif;

    return $items;
}
add_filter( 'wp_nav_menu_items', 'luminate_search_in_menu', 10, 2 );

/**
 * Append class "social" to specific off-site links
 *
 * @since Luminate 0.1
 */
function luminate_social_nav_class( $classes, $item ) {

    if ( 0 == $item->parent && 'custom' == $item->type) {

    	$url = parse_url( $item->url );

    	if ( !isset( $url['host'] ) ) {
	    	return $classes;
    	}

    	$base = str_replace( "www.", "", $url['host'] );

    	// @TODO Make this filterable
    	$social = array(
    		'behance.com',
    		'dribbble.com',
    		'facebook.com',
    		'flickr.com',
    		'github.com',
    		'linkedin.com',
    		'pinterest.com',
    		'plus.google.com',
    		'instagr.am',
    		'instagram.com',
    		'skype.com',
    		'spotify.com',
    		'twitter.com',
    		'vimeo.com'
    	);

    	// Tumblr needs special attention
    	if ( strpos( $base, 'tumblr' ) ) {
			$classes[] = 'social';
		}

    	if ( in_array( $base, $social ) ) {
	    	$classes[] = 'social';
    	}

    }

    return $classes;

}
add_filter( 'nav_menu_css_class', 'luminate_social_nav_class', 10, 2 );

/**
 * Returns true if site title or tagline is visible
 *
 * @since Luminate 0.1
 */
function luminate_brand_text() {

	if ( get_theme_mod( 'display_site_title', 1 ) ) {
		return true;
	}

	if ( get_theme_mod( 'display_site_description', 0 ) && get_bloginfo( 'description' ) != '' ) {
		return true;
	}

	return false;
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Zelda 0.1
 */
function luminate_body_classes( $classes ) {

	global $post;

	// Simplify body class for showcase template
	if ( isset( $post ) && ( is_page_template( 'templates/post-showcase.php' ) || is_page_template( 'templates/page-showcase.php' )) ) {
		$classes[] = 'template-showcase';
	}

	return $classes;
}
add_filter( 'body_class', 'luminate_body_classes' );
