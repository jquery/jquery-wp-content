<?php
/** =Common Functions for jQuery Sites
************************************************************
************************************************************/

/*
	Returns a formatted list (set of <li> elements) of subcategories
	If the current category, represented by $cat_id, has a parent,
	the list includes the current category along with its siblings.
	If the current category is top-level, the list includes its children.
*/
function jq_get_subcategories( $cat_id=0, $cat_args = array() ) {
	if ( !is_category() ) {
		return '';
	}

	$cat_default_args = array(
		'depth' => '1',
		'title_li' => '',
		'show_option_none' => '',
		'echo' => 0
	);

	$cat_args = array_merge( $cat_default_args, $cat_args );
	$cat_data = get_term_by( 'id', (int)$cat_id, 'category' );
	$cat_parent = $cat_data->parent;

	if ($cat_parent == 0) {
		$cat_parent = $cat_id;
	}

	$cat_args['parent'] = $cat_parent;
	return wp_list_categories( $cat_args );
}

/*
	Returns a post's categories, along with their parent categories
*/
function jq_categories_and_parents() {
	$ret = '';
	$all_cats = get_categories();
	$cat_list = array();
	$current_cat = is_category() ? single_cat_title( '', false ) : 'ZZZ';
	// $ccat = get_category_by_name($current_cat);
	foreach ($all_cats as $cat => $catinfo) {
		$catid = intval( $catinfo->term_id );
		if ($catinfo->name !== $current_cat && in_category( $catid ) && strpos($catinfo->cat_name, "Version") === false) {
			$cat_and_parents = substr(get_category_parents($catid, true, ' &gt; '), 0, -6);
			$cat_list[] = '<span class="category">' . $cat_and_parents . '</span>';
		}
	}

	$ret = implode('<span class="category-divider"> | </span>', $cat_list);
	if ( !empty($cat_list) ) {
		if ( is_category() ) {
			$ret = 'Also in: ' . $ret;
		} elseif ( is_single() ) {
			$ret = 'Categories: ' . $ret;
		}
	}
	return $ret;
}


/**
 * Get the category id for a post to allow the sidebar to reflect what category the currently displayed page is on
 * @returns int The category id
 */
function jq_post_category () {
	$current_category = 0;
	if ( is_category() ) {
		$current_category = get_queried_object_id();
	} elseif ( is_singular( 'post' ) ) {
		$categories = get_the_category();
		foreach ( $categories as $category ) {
			if ( false === strpos( $category->name, 'Version' ) ) {
				$current_category = $category->term_id;
				break;
			}
		}
	}

	return $current_category;
}



// For category lists on category archives: Returns other categories except the current one (redundant)
function jq_other_cats($glue = ', ') {
	$current_cat = single_cat_title( '', false );
	$separator = "\n";
	$cats = explode( $separator, get_the_category_list($separator) );
	foreach ( $cats as $i => $str ) {
		if ( strstr( $str, ">$current_cat<" ) || strpos( $str, "Version" ) !== false ) {
			unset($cats[$i]);
		}
	}
	if ( empty($cats) )
		return false;

	return trim(join( $glue, $cats ));
}

function jq_pages_for_category( $category ) {
	return get_posts( array(
		'post_type' => 'page',
		'category_name' => $category,
		'posts_per_page' => -1,
		'orderby' => 'title',
		'order' => 'ASC'
	) );
}

function jq_page_links_for_category( $category ) {
	$ret = '';
	foreach ( jq_pages_for_category( $category ) as $post ) {
		$ret .= '<li><a href="' . get_permalink( $post->ID ) . '">' .
			$post->post_title . '</a></li>';
	}
	return $ret;
}

function jq_get_github_url() {
	global $post;
	$source_path = get_post_meta( $post->ID, "source_path" );
	$github_prefix = 'https://github.com/jquery/' . get_stylesheet() . '/tree/master/' . $source_path[0];
	return $github_prefix;
}

function jq_content_nav() {
	global $wp_query;

	if ( $wp_query->max_num_pages == 1 ) {
		return '';
	}

	$big = 999999999;
	return '<div class="pagination">' . paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages
	)) . '</div>';
}

function jq_banner() {
	echo '<div id="broadcast"></div>';
}

function jq_post_heirarchy() {
	global $post;
	$current = $post;
	$parents = array();
	while ( $current->post_parent ) {
		$current = get_post( $current->post_parent );
		$parents[] = '<a href="' . get_permalink( $current->ID ) . '">' .
			$current->post_title . '</a>';
	}

	if ( empty( $parents ) ) {
		return '';
	}

	return '' .
		'<div class="post-heirarchy">' .
			'Posted in: ' . implode( ' > ', array_reverse( $parents ) ) .
		'</div>';
}

function jq_logo_link() {
	// TODO: remove when blog.jquery.com-theme is gone
	if ( !function_exists( 'jquery_sites' ) ) {
		return '/';
	}

	$sites = jquery_sites();
	return empty( $sites[ JQUERY_LIVE_SITE ][ 'logo_link' ] ) ? '/' :
		$sites[ JQUERY_LIVE_SITE ][ 'logo_link' ];
}
