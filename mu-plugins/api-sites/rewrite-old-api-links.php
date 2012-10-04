<?php
/*
 * For subsites like /1.8/, this plugin rewrites root-relative links
 * like /Effects to /1.8/Effects.
 *
 * It handles both href and src attributes, and does not rewrite links
 * that already start with a version number-like string, in case
 * API sub-sites link to each other.
 */
if ( false === strpos( JQUERY_LIVE_SITE, '/' ) )
	return;

list( , $subsite ) = explode( '/', JQUERY_LIVE_SITE, 2 );

add_filter( 'the_content', function( $content ) use ( $subsite ) {
	$content = preg_replace( '~(href|src)=(["\'])/(?!\d+\.\d+/)~', '$1=$2/' . $subsite . '/', $content );
	return $content;
} );

unset( $subsite );