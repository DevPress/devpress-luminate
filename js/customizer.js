/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	wp.customize( 'highlight-color', function( value ) {

		value.bind( function( to ) {
			$('.site-content a, .footer-widgets a').css( 'color', to );
			$('.page-header, #content blockquote').css( 'border-left-color', to );
			$("button, .button, input[type='button'], input[type='reset'], input[type='submit']").css( 'background', to );
		} );
	} );

	wp.customize( 'highlight-hover', function( value ) {
		value.bind( function( to ) {
			$('.site-content a:hover, .footer-widgets a:hover').css( 'color', to );
			$("button:hover, .button:hover, input[type='button']:hover, input[type='reset']:hover, input[type='submit']:hover").css( 'background', to );
		} );
	} );

} )( jQuery );
