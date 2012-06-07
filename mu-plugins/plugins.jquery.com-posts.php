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
		'hierarchichal' => true,
		'taxonomies' => array( 'post_tag' ),
		'rewrite' => false,
		'query_var' => false,
		'delete_with_user' => false,
	) );
}

// Only search against parent jquery_plugin posts
function jquery_plugin_posts_only_for_searches( $query ) {
	if ( $query->is_main_query() && $query->is_search() ) {
		$query->set( 'post_type', 'jquery_plugin' );
		$query->set( 'post_parent', 0 );
	}
}

add_action( 'init', 'post_type_jquery_plugin_init' );
add_action( 'pre_get_posts', 'jquery_plugin_posts_only_for_searches' );
