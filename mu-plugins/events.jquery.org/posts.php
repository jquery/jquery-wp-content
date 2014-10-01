<?php

// Add support for pages with numeric slugs that match year queries
// For example, events.jquery.org/2014/
add_filter( 'request', function( $query_vars ) {
	if ( ! empty( $query_vars[ 'year' ] ) ) {
		$page = get_page_by_path( $query_vars[ 'year' ] );
		if ( $page ) {
			$query_vars[ 'pagename' ] = $page->post_name;
			unset( $query_vars[ 'year' ] );
		}
	}

	return $query_vars;
});
