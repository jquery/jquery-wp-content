<?php
/**
 * Plugin Name: Post Type - jQuery Plugin
 */

// Prevent default redirects
// https://github.com/jquery/plugins.jquery.com/issues/108
add_filter( 'redirect_canonical', function ( $redirect_url ) {
	return is_404() ? false : $redirect_url;
});

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
		$query->set( 'meta_key', 'watchers' );
		$query->set( 'orderby', 'meta_value_num' );
		$query->set( 'order', 'DESC' );
	}
}

add_action( 'init', 'post_type_jquery_plugin_init' );
add_action( 'pre_get_posts', 'jquery_plugin_posts_only_for_searches' );
add_filter( 'pre_option_permalink_structure', function() {
	return '/%postname%';
} );

/*
 * Ensure that the post_tag taxonomy uses our own special counting logic.
 *
 * Normally, this would simply be specified by register_taxonomy() with
 * the update_count_callback flag. Since we use the core tag taxonomy,
 * let's just continue to hijack it.
 */
add_action( 'init', function() {
	$GLOBALS['wp_taxonomies']['post_tag']->update_count_callback = 'jquery_update_plugin_tag_count';
} );

/**
 * Mostly just _update_post_term_count(), tweaked for
 * post_type = 'jquery_plugin' and post_parent = 0.
 */
function jquery_update_plugin_tag_count( $terms, $taxonomy ) {
	global $wpdb;
	foreach ( (array) $terms as $term ) {
		$count = (int) $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM $wpdb->term_relationships, $wpdb->posts " .
			"WHERE $wpdb->posts.ID = $wpdb->term_relationships.object_id " .
				"AND post_status = 'publish' AND post_type = 'jquery_plugin' " .
				"AND post_parent = 0 AND term_taxonomy_id = %d",
			$term ) );
		do_action( 'edit_term_taxonomy', $term, $taxonomy );
		$wpdb->update( $wpdb->term_taxonomy, compact( 'count' ), array( 'term_taxonomy_id' => $term ) );
		do_action( 'edited_term_taxonomy', $term, $taxonomy );
	}
}
