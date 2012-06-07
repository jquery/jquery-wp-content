<?php
/**
 * Plugin Name: plugins.jquery.com XML-RPC extensions
 * Description: Adds custom XML-RPC methods for plugins.jquery.com.
 */

function jq_pjc_get_post_for_plugin( $args ) {
	global $wp_xmlrpc_server;
	$wp_xmlrpc_server->escape( $args );

	// Authenticate
	$blog_id = $args[0];
	$username = $args[1];
	$password = $args[2];

	if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {
		return $wp_xmlrpc_server->error;
	}

	// Find post
	$plugin_name = $args[3];
	$query = new WP_Query( array(
		'post_type' => 'jquery_plugin',
		'name' => $plugin_name,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
	));

	if ( empty( $query->posts ) ) {
		return null;
	}

	// Delegate to wp_getPost() for consistent return values
	return $wp_xmlrpc_server->wp_getPost(array(
		$blog_id,
		$username,
		$password,
		$query->posts[0]->ID
	));
}

function jq_pjc_register_xmlrpc_methods( $methods ) {
	$methods[ 'jq-pjc.getPostForPlugin' ] = 'jq_pjc_get_post_for_plugin';
	return $methods;
}

add_filter( 'xmlrpc_methods', 'jq_pjc_register_xmlrpc_methods' );
