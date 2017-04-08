<?php
/**
 * Plugin Name: jquery-wp-content Redirection extensions
 * Description: Adds custom XML-RPC methods to control redirection.
 */

$jquery_redirects = 'jquery_redirects';

add_filter( 'template_redirect', function() {
	global $jquery_redirects;

	// Only handle 404 Not Found
	if ( !is_404() ) {
		return;
	}

	$url = trailingslashit( $_SERVER[ 'REQUEST_URI' ] );

	// Check for redirects stored in the transients
	$transient = get_option( $jquery_redirects );

	if ( $transient && !empty( $transient[ $url ] ) ) {
		wp_redirect( $transient[ $url ], 301 );
	}
} );

add_filter( 'xmlrpc_methods', function( $methods ) {
	$methods[ 'jq.setRedirects' ] = 'jq_set_redirects';
	return $methods;
} );

function jq_set_redirects( $args ) {
	global $wp_xmlrpc_server;
	global $jquery_redirects;

	// Authenticate
	$username = $args[ 1 ];
	$password = $args[ 2 ];

	if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {
		return $wp_xmlrpc_server->error;
	}

	// Store redirects
	return update_option( $jquery_redirects, json_decode( $args[ 3 ], true ) );
}
