<?php
/**
 * Plugin Name: Grunt WordPress XML-RPC extensions
 * Description: Adds custom XML-RPC methods for use with grunt-wordpress.
 */

define( 'GW_RESOURCE_DIR', WP_CONTENT_DIR . '/gw-resources/' . $_SERVER['HTTP_HOST'] );

function gw_get_post_paths( $post_type = "" ) {
	$results = array();
	$query = new WP_Query( array(
		'post_type' => $post_type,
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
	) );
	foreach ( $query->posts as $post ) {
		$results[ $post->post_type . '/' . get_page_uri( $post->ID ) ] = $post->ID;
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
	$methods['gw.getPostPaths'] = 'gw_get_post_paths';
	$methods['gw.getResources'] = 'gw_get_resources';
	$methods['gw.addResource'] = 'gw_add_resource';
	$methods['gw.deleteResource'] = 'gw_delete_resource';
	return $methods;
}

add_filter( 'xmlrpc_methods', 'gw_register_xmlrpc_methods' );
