<?php
/**
 * Sort unpaginated search results more intutiviely.
 *
 * Results are sorted based on title, then excerpt, then content. Exact title matches come first.
 */

add_filter( 'the_posts', function( $posts, $query ) {
	if ( ! $query->is_main_query() || ! $query->is_search() ||
			is_null( $query->query_vars[ 'search_term' ] ) ) {
		return $posts;
	}

	$priorities = array_fill( 0, 4, array() );

	$search_terms = $query->query_vars['search_terms'];
	$search_terms = array_map( 'strtolower', $search_terms );
	foreach ( $posts as $post ) {
		if ( strtolower( $post->post_title ) === implode( ' ', $search_terms ) ) {
			// Perfect title match: Priority 0.
			$priorities[0][] = $post;
			continue;
		}

		foreach ( array( 1 => 'post_title', 2 => 'post_excerpt' ) as $priority => $field ) {
			foreach ( $search_terms as $search_term ) {
				// A search term is not present in the field, so move to the next field.
				if ( false === stripos( $post->$field, $search_term ) )
					continue 2;
			}

			// Found all matching terms in this field, so add it to the respective priority.
			$priorities[ $priority ][] = $post;
			continue 2;
		}

		// No matches in a priority field, so assume the post got here via the content.
		$priorities[3][] = $post;
	}

	return array_merge( $priorities[0], $priorities[1], $priorities[2], $priorities[3] );
}, 10, 2 );