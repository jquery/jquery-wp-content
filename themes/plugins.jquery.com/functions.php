<?php

function jq_plugin_meta( $attr ) {
	$post = get_post( get_the_ID() );
	$main_post = empty( $post->post_parent ) ? $post->ID : $post->post_parent;
	return get_post_meta( $main_post, $attr[ "key" ], true );
}

function jq_plugin_manifest() {
	return json_decode( jq_plugin_meta( array( "key" => "manifest" ) ) );
}

function jq_plugin_repo_url() {
	return jq_plugin_meta( array( "key" => "repo_url" ) );
}

function jq_plugin_bugs_url() {
	$manifest = jq_plugin_manifest();
	if ( empty( $manifest->bugs ) ) {
		return false;
	}
	return is_string( $manifest->bugs ) ? $manifest->bugs : $manifest->bugs->url;
}

function jq_plugin_watchers() {
	return jq_plugin_meta( array( "key" => "watchers" ) );
}

function jq_plugin_forks() {
	return jq_plugin_meta( array( "key" => "forks" ) );
}

function jq_plugin_versions() {
	$versions = jq_plugin_meta( array( "key" => "versions" ) );
	$latest = jq_plugin_meta( array( "key" => "latest" ) );
	if ( !$versions || !$latest ) {
		return;
	}

	$post = get_post( get_the_ID() );
	$main_post = empty( $post->post_parent ) ? $post->ID : $post->post_parent;
	$parent = get_post( $main_post );
	$currentVersion = $main_post === get_the_ID() ? $latest : $post->post_name;

	return array_map( function( $version ) use ( $currentVersion, $latest, $parent ) {
		$version_path = "$parent->post_name/$version";
		$post = get_page_by_path( $version_path, OBJECT, 'jquery_plugin' );
		$ret = array( 'date' => $post->post_date );
		if ( $version === $currentVersion ) {
			$ret[ 'link' ] = $version;
		} else if ( $version === $latest ) {
			$ret[ 'link' ] = "<a href=\"/$parent->post_name/\">$version</a>";
		} else {
			$ret[ 'link' ] = "<a href=\"/$version_path/\">$version</a>";
		}
		return $ret;
	}, array_reverse( json_decode( $versions ) ) );
}

//
// Display a list of the newest plugins (for the sidebar)
//

function jq_new_plugins($total = 10) {
	global $post;
	$new_plugins_args = array(
		'post_type' => 'jquery_plugin',
		'posts_per_page' => $total,
		'order' => 'DESC',
		'orderby' => 'date',
		'post_parent' => 0,
	);

	$new_plugins = new WP_Query( $new_plugins_args );
	if ( $new_plugins->have_posts() ):
		echo '<ul>';
		while ( $new_plugins->have_posts() ) :
			$new_plugins->the_post();
	?>
		<li>
			<a href="/<?php echo $post->post_name; ?>/"><?php echo $post->post_title; ?></a>
		</li>
	<?php
		endwhile;
		echo '</ul>';
	endif;
	wp_reset_postdata();
}

//
// Display a list of the most recently updated plugins (for the sidebar)
//
function jq_updated_plugins( $total = 10 ) {
	global $post;
	$updated_plugins_args = array(
		'post_type' => 'jquery_plugin',
		'order' => 'DESC',
		'orderby' => 'date',
	);

	$added = array();
	$updated_plugins = new WP_Query( $updated_plugins_args );
	if ( $updated_plugins->have_posts() ):
		echo '<ul class="recent-updates">';
		while ( $updated_plugins->have_posts() ) :
			$updated_plugins->the_post();
			$parent_id = $post->post_parent;

			// Only add children of the main plugin post
			// And only add if no other child of same parent has been added
			if ( $parent_id && !in_array($parent_id, $added)):
				$added[] = $parent_id;
				$parent = get_post( $parent_id );

	?>
		<li>
			<a href="/<?php echo $parent->post_name . '/' . $post->post_name ?>/"><?php echo $post->post_title; ?></a><br>
			(version <?php echo $post->post_name; ?>)
		</li>
	<?php
				if ( count($added) == $total ) {
					break;
				}
			endif;
		endwhile;
		echo '</ul>';
	endif;
	wp_reset_postdata();
}

function jq_popular_tags( $total = 10 ) {
	$tags = get_tags( array(
		'orderby' => 'count',
		'order' => 'DESC',
		'number' => $total
	));
	echo '<ul class="popular-tags">';
	foreach ( $tags as $tag ) {
		echo
			'<li>' .
				'<a href="' . get_tag_link( $tag->term_id ) . '">' .
					$tag->name .
				'</a>' .
				' <span class="count">(' . $tag->count . ')</span>' .
			'</li>';
	}
	echo '</ul>';
}

//
// The functions below (with the jq_release_ prefix) relate to a specific
// version number release of a plugin
//

function jq_release_manifest( $id = null ) {
	if ( !$id ) {
		$id = get_the_ID();
	}
	return json_decode( get_post_meta( $id, 'manifest', true ) );
}

//////////////////////////////////////////////////////////////////////////////
// Produces a block of markup describing the given person, optionally with
// a gravatar image at the given size.

function person( $person, $avatar, $size = 96 ) {

	$name = htmlspecialchars( $person->name );

	// If an avatar is requested, build a little gravatar tile; otherwise
	// just use the name by itself.

	if ( $avatar ) {
		if (!empty( $person->email )) {
			$content = get_avatar( $person->email, $size );
		} else {
			$content = get_avatar( null, $size );
		}
		$content = $content . $name;
	} else {
		$content = $name;
	}

	// Wrap the content in a link to the person's page or email address

	if (!empty( $person->url )) {
		$url = htmlspecialchars( $person->url );
		return "<a href='$url'>$content</a>";
	} elseif (!empty( $person->email )) {
		$email = htmlspecialchars( $person->email );
		return "<a href='mailto:$email'>$content</a>";
	} else {
		return $content;
	}
}

function jq_release_download_url() {
	return get_post_meta( get_the_ID(), 'download_url', true );
}

function jq_release_homepage() {
	$pkg = jq_release_manifest();
	return $pkg->homepage;
}

function jq_release_demo() {
	$pkg = jq_release_manifest();
	return empty( $pkg->demo ) ? '' : $pkg->demo;
}

function jq_release_docs() {
	$pkg = jq_release_manifest();
	return empty( $pkg->docs ) ? '' : $pkg->docs;
}

function jq_release_date() {
	$post = get_post( get_the_ID() );
	if ( empty( $post->post_parent ) ) {
		$latest = jq_plugin_meta( array( "key" => "latest" ) );
		$version_path = "$post->post_name/$latest";
		$post = get_page_by_path( $version_path, OBJECT, 'jquery_plugin' );
	}

	return date_format( new DateTime( $post->post_date ), 'F j, Y' );
}

function jq_release_version( $id = null ) {
	$pkg = jq_release_manifest( $id );
	return $pkg->version;
}

function jq_release_licenses() {
	$pkg = jq_release_manifest();
	$ret = '<ul>';
	foreach( $pkg->licenses as $license ) {
		$url = htmlspecialchars( $license->url );
		$type = empty( $license->type ) ? $url : htmlspecialchars( $license->type );
		$ret .= "<li><a href='$url'>$type</a></li>";
	}
	$ret .= '</ul>';
	return $ret;
}

function jq_release_maintainers( $options = array('avatar' => false, 'size' => 48) ) {
	$pkg = jq_release_manifest();

	if ( empty( $pkg->maintainers ) ) {
		return '';
	}

	$ret = '<ul>';
	foreach( $pkg->maintainers as $maintainer ) {
		$ret .= '<li>' . person( $maintainer, $options['avatar'], $options['size'] ) . '</li>';
	}
	$ret .= '</ul>';
	return $ret;
}

function jq_release_author( $options = array('avatar' => false, 'size' => 48) ) {
	$pkg = jq_release_manifest();
	return person( $pkg->author, $options['avatar'], $options['size'] );
}

function jq_release_dependencies() {
	$pkg = jq_release_manifest();
	$ret = '<ul>';
	foreach( $pkg->dependencies as $plugin => $version ) {
		if ( get_page_by_path( $plugin ) ) {
			$ret .= "<li><a href='/$plugin'>$plugin</a> $version</li>";
		} else {
			$ret .= "<li>$plugin $version</li>";
		}
	}
	$ret .= '</ul>';
	return $ret;
}

function jq_release_keywords() {
	$tags = get_the_tags();
	if ( !$tags || !count( $tags ) ) {
		return '';
	}

	$ret = '<ul>';
	foreach( $tags as $tag ) {
		$ret .= '<li><a class="tag icon-tag" href="' . get_tag_link( $tag->term_id ) . '">' .
			$tag->name . '</a></li>';
	}
	$ret .= '</ul>';
	return $ret;
}

function jq_release_is_stable() {
	return preg_match( '/^\d+\.\d+\.\d+$/', jq_release_version() );
}
