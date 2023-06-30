<?php
/**
 * Plugin Name: API category listing
 * Description: Tweak category pages for API documentation sites.
 */

/**
 * Sets category listings to be ordered by slug ascending, with no paging.
 *
 * For category/all, show all listings.
 */
add_action( 'pre_get_posts', function( $query ) {
	if ( ! $query->is_main_query() )
		return;

	$query->set( 'orderby', 'name' );
	$query->set( 'order', 'ASC' );
	$query->set( 'posts_per_page', -1 );

	if ( $query->is_category( 'all' ) )
		$query->set( 'category_name', '' );
});
