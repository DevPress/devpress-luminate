<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Luminate
 */

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function luminate_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'luminate_setup_author' );

/**
 * Returns class to be used for footer
 *
 * @return string footer class
 */
function luminate_footer_class() {

	$count = luminate_count_widgets( 'footer' );

	// If there's two widgets or less
	if ( $count <= 2) {
		return 'columns-' . $count;
	}

	// Otherwise we'll have 3 columns
	return 'columns-3';

}

/**
 * Counts number of widgets in a sidebar
 *
 * @param string $sidebar_id
 * @return int $widget_count
 */
function luminate_count_widgets( $sidebar_id ) {

	// If loading from front page, consult $_wp_sidebars_widgets rather than options
	// to see if wp_convert_widget_settings() has made manipulations in memory.
	global $_wp_sidebars_widgets;
	if ( empty( $_wp_sidebars_widgets ) ) :
		$_wp_sidebars_widgets = get_option( 'sidebars_widgets', array() );
	endif;

	$sidebars_widgets_count = $_wp_sidebars_widgets;

	if ( isset( $sidebars_widgets_count[ $sidebar_id ] ) ) :
		$widget_count = count( $sidebars_widgets_count[ $sidebar_id ] );
		return $widget_count;
	endif;

}

/**
 * Returns the sidebar arguments
 *
 * @return array sidebar args
 */
function luminate_sidebar_args() {
	$args = array(
		'name'          => __( 'Sidebar', 'luminate' ),
		'id'            => 'primary',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget module %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	);
	return $args;
}

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function luminate_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => '#posts-wrap',
		'footer'    => false,
		'footer_widgets' => 'footer',
		'render' => 'luminate_infinite_scroll_render'
	) );
}
add_action( 'after_setup_theme', 'luminate_jetpack_setup' );

/**
 * Used by JetPack to render the correct template part
 */
function luminate_infinite_scroll_render() {
	while( have_posts() ) {
	    the_post();
	    get_template_part( 'content', luminate_template_part() );
	}
}
