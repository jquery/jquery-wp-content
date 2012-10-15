<?php
/*
 * Plugin Name: WordPress Shims
 * Author: Nacin
 */

// Add menu_order query variable for pre-3.5.
if ( version_compare( $wp_version, '3.5-alpha', '<' ) ) :
	add_filter( 'posts_where_paged', function( $where, $query ) {
		global $wpdb;
		if ( $menu_order = absint( $query->get( 'menu_order' ) ) )
			$where .= " AND $wpdb->posts.menu_order = " . $menu_order;
		return $where;
	}, 10, 2 );
endif;