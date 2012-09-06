<?php
/**
 * Plugin Name: Post Type - jQuery Plugin
 */

// Create custom post type: jquery_plugin
function post_type_jquery_plugin_init() {
	register_post_type( 'jquery_plugin', array(
		'labels' => array(
			'name' => __( 'jQuery Plugins' ),
			'singular_name' => __( 'jQuery Plugin' )
		),
		'public' => true,
		'map_meta_cap' => true,
		'hierarchical' => true,
		'taxonomies' => array( 'post_tag' ),
		'rewrite' => false,
		'query_var' => 'plugin',
		'delete_with_user' => false,
	) );
}

// Rewrite jquery_plugin posts to be at the root
add_filter( 'post_type_link', function( $post_link, $post ) {
	if ( 'jquery_plugin' === $post->post_type ) {
		return user_trailingslashit( home_url( get_page_uri( $post ) ) );
	}
	return $post_link;
}, 10, 2 );

// Copy the page rewrite rules and have them catch plugins (after pages are checked).
add_filter( 'page_rewrite_rules', function( $rules ) {
	foreach ( $rules as $rule => $match ) {
		if ( false === strpos( $rule, 'attachment/' ) )
			$rules[ str_replace( '.?.+?', '.*?.+?', $rule ) ] = str_replace( 'pagename=', 'plugin=', $match );
	}
	return $rules;
} );

// Only search against parent jquery_plugin posts
function jquery_plugin_posts_only_for_searches( $query ) {
	if ( $query->is_main_query() && ($query->is_search() || $query->is_tag()) ) {
		$query->set( 'post_type', 'jquery_plugin' );
		$query->set( 'post_parent', 0 );
	}
}

add_action( 'init', 'post_type_jquery_plugin_init' );
add_action( 'pre_get_posts', 'jquery_plugin_posts_only_for_searches' );
add_filter( 'pre_option_permalink_structure', function() {
	return '/%postname%';
} );
