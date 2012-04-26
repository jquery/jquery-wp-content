<?php
/**
 * Plugin Name: Grunt WordPress XML-RPC extensions
 * Description: Adds custom XML-RPC methods for use with grunt-wordpress.
 */

function gw_get_post_paths( $args ) {
	$post_type = sanitize_key( $args[3] );

	$results = array();
	$query = new WP_Query( array(
		'post_type' => $post_type,
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
	) );
	foreach ( $query->posts as $post )
		$results[ $post->post_type . '/' . get_page_uri( $post->ID ) ] = $post->ID;

	return $results;
}

function gw_register_xmlrpc_methods( $methods ) {
	$methods['gw.getPostPaths'] = 'gw_get_post_paths';
	return $methods;
}
add_filter( 'xmlrpc_methods', 'gw_register_xmlrpc_methods' );
