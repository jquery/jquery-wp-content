<?php
/**
 * Plugin Name: Gilded WordPress XML-RPC extensions
 * Description: Adds custom XML-RPC methods for use with Gilded WordPress.
 */

define( 'GW_VERSION', '1.0.6' );

if ( ! defined( 'GW_RESOURCE_DIR' ) )
	define( 'GW_RESOURCE_DIR', gw_resources_dir( home_url() ) );

function gw_resources_dir( $url ) {
	return dirname( WP_CONTENT_DIR ) . '/gw-resources/' . preg_replace( '/^\w+:\/\//', '', $url );
}

function gw_get_version( $args ) {
	global $wp_xmlrpc_server;
	$wp_xmlrpc_server->escape( $args );

	// Authenticate
	$blog_id = $args[0];
	$username = $args[1];
	$password = $args[2];

	// We require authentication so that we can ensure that the username
	// and password provided will work for other methods.
	if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {
		return $wp_xmlrpc_server->error;
	}

	return GW_VERSION;
}

function gw_get_post_paths( $post_type = "" ) {
	$results = array();
	$query = new WP_Query( array(
		'post_type' => $post_type,
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'update_post_term_cache' => false,
	) );
	foreach ( $query->posts as $post ) {
		$results[ $post->post_type . '/' . get_page_uri( $post->ID ) ] = array(
			'id' => $post->ID,
			'checksum' => get_post_meta( $post->ID, 'gwcs', true ),
		);
	}

	return $results;
}


function gw_get_resources() {
	$filename = GW_RESOURCE_DIR . "/__gw.json";
	if ( !file_exists( $filename ) ) {
		return array();
	}
	return json_decode( file_get_contents( $filename ), true );
}

function gw_set_resources( $resources ) {
	file_put_contents( GW_RESOURCE_DIR . "/__gw.json", json_encode( $resources ) );
}

function gw_add_resource( $args ) {
	global $wp_xmlrpc_server;
	$wp_xmlrpc_server->escape( $args );

	// Authenticate
	$blog_id = $args[0];
	$username = $args[1];
	$password = $args[2];

	if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {
		return $wp_xmlrpc_server->error;
	}

	// Verify path and decode file contents
	$path = $args[3];
	if ( false !== strpos( $path, ".." ) ) {
		return new IXR_ERROR( 500, "Invalid path." );
	}
	$bits = $args[4];
	$checksum = md5( $bits );
	$bits = base64_decode( $bits );

	// Create a temp file using built-in upload functionality
	$info = pathinfo( $path );
	$ext = !empty($info['extension']) ? '.' . $info['extension'] : '';
	$upload = wp_upload_bits( "gw-resource" . $ext, null, $bits );
	if ( !empty( $upload['error'] ) ) {
		return new IXR_Error( 500, $upload['error'] );
	}

	// Move the file to the gw-resources directory
	$new_file = GW_RESOURCE_DIR . "/$path";
	if ( ! wp_mkdir_p( dirname( $new_file ) ) ) {
		$message = sprintf( __( 'Unable to create directory %s. Is its parent directory writable by the server?' ), dirname( $new_file ) );
		return new IXR_Error( 500, $message );
	}
	rename( $upload['file'], $new_file );
	clearstatcache();

	// Update resource list
	$resources = gw_get_resources();
	$resources[ $path ] = $checksum;
	gw_set_resources( $resources );

	return $checksum;
}

function gw_delete_resource( $args ) {
	global $wp_xmlrpc_server;
	$wp_xmlrpc_server->escape( $args );

	// Authenticate
	$blog_id = $args[0];
	$username = $args[1];
	$password = $args[2];

	if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {
		return $wp_xmlrpc_server->error;
	}

	// Verify path
	$path = $args[3];
	if ( false !== strpos( $path, ".." ) ) {
		return new IXR_ERROR( 500, "Invalid path." );
	}

	// Delete resource
	$old_file = GW_RESOURCE_DIR . "/$path";
	if ( file_exists( $old_file ) ) {
		unlink( $old_file );
	}

	// Update resource list
	$resources = gw_get_resources();
	$checksum = empty( $resources[ $path ] ) ? null : $resources[ $path ];
	unset( $resources[ $path ] );
	gw_set_resources( $resources );

	return $checksum;
}

function gw_register_xmlrpc_methods( $methods ) {
	$methods['gw.getVersion'] = 'gw_get_version';
	$methods['gw.getPostPaths'] = 'gw_get_post_paths';
	$methods['gw.getResources'] = 'gw_get_resources';
	$methods['gw.addResource'] = 'gw_add_resource';
	$methods['gw.deleteResource'] = 'gw_delete_resource';
	return $methods;
}

add_filter( 'xmlrpc_methods', 'gw_register_xmlrpc_methods' );



function gw_sanitize_title_with_dashes( $title, $raw_title = '', $context = 'display' ) {
	// Special case during the install process.
	if ( 'Uncategorized' == $raw_title )
		return 'uncategorized';

	$title = strip_tags($title);
	// Preserve escaped octets.
	$title = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title );
	// Remove percent signs that are not part of an octet.
	$title = str_replace( '%', '', $title );
	// Restore octets.
	$title = preg_replace( '|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title );

	// CHANGE: Don't lowercase
	if ( seems_utf8( $title ) ) {
		$title = utf8_uri_encode( $title, 200 );
	}

	// CHANGE: Don't lowercase and don't remove dots
	$title = preg_replace( '/&.+?;/', '', $title ); // kill entities

	if ( 'save' == $context ) {
		// Convert nbsp, ndash and mdash to hyphens
		$title = str_replace( array( '%c2%a0', '%e2%80%93', '%e2%80%94' ), '-', $title );

		// Strip these characters entirely
		$title = str_replace( array(
			// iexcl and iquest
			'%c2%a1', '%c2%bf',
			// angle quotes
			'%c2%ab', '%c2%bb', '%e2%80%b9', '%e2%80%ba',
			// curly quotes
			'%e2%80%98', '%e2%80%99', '%e2%80%9c', '%e2%80%9d',
			'%e2%80%9a', '%e2%80%9b', '%e2%80%9e', '%e2%80%9f',
			// copy, reg, deg, hellip and trade
			'%c2%a9', '%c2%ae', '%c2%b0', '%e2%80%a6', '%e2%84%a2',
		), '', $title );
	}

	// CHANGE: Allow dots and case-insensitive
	$title = preg_replace( '/[^%a-z0-9 _.-]/i', '', $title );
	$title = preg_replace( '/\s+/', '-', $title );
	$title = preg_replace( '|-+|', '-', $title );
	$title = trim( $title, '-' );

	return $title;
}

remove_filter( 'sanitize_title', 'sanitize_title_with_dashes', 10, 3 );
add_filter( 'sanitize_title', 'gw_sanitize_title_with_dashes', 10, 3 );
