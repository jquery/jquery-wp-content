<?php

/**
 * Sets category listings to be ordered by slug ascending, with no paging.
 *
 */
add_action( 'pre_get_posts', function( $query ) {
	if ( ! $query->is_main_query() )
		return;

	$query->set( 'orderby', 'slug' );
	$query->set( 'order', 'ASC' );
	$query->set( 'posts_per_page', -1 );
});
