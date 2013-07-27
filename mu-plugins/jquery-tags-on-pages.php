<?php
/* Plugin Name: jQuery Tags on Pages
 * Description: Adds the tags taxonomy to WordPress pages.
 * Author: Andrew Nacin
 *         <nacin@wordpress.org>
 * Author URI: http://nacin.com/
 * Version: 1.0
 */

add_action( 'init', function() {
	register_taxonomy_for_object_type( 'post_tag', 'page' );
	register_taxonomy_for_object_type( 'category', 'page' );
});

add_filter( 'request', function( $query ) {
	if ( !empty( $query[ 'tag' ] ) || !empty( $query[ 'category_name' ] ) ) {
		$query[ 'post_type' ] = array( 'post', 'page' );
	}
	return $query;
});
