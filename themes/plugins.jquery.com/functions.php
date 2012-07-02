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
// The functions below (with the jq_release_ prefix) relate to a specific
// version number release of a plugin
//

function jq_release_manifest() {
	return json_decode( get_post_meta( get_the_ID(), "manifest", true ) );
}

function person( $person ) {
	$ret = htmlspecialchars( $person->name );
	if ( !empty( $person->url ) ) {
		$url = htmlspecialchars( $person->url );
		$ret = "<a href='$url'>$ret</a>";
	}
	return $ret;
}

function jq_release_download_url() {
	return get_post_meta( get_the_ID(), "download_url", true );
}

function jq_release_homepage() {
	$pkg = jq_release_manifest();
	return $pkg->homepage;
}

function jq_release_demo() {
	$pkg = jq_release_manifest();
	return empty( $pkg->demo ) ? "" : $pkg->demo;
}

function jq_release_docs() {
	$pkg = jq_release_manifest();
	return empty( $pkg->docs ) ? "" : $pkg->docs;
}

function jq_release_date() {
	return get_the_date();
}

function jq_release_version() {
	$pkg = jq_release_manifest();
	return $pkg->version;
}

function jq_release_licenses() {
	$pkg = jq_release_manifest();
	$ret = "";
	foreach( $pkg->licenses as $license ) {
		$url = htmlspecialchars( $license->url );
		$type = empty( $license->type ) ? $url : htmlspecialchars( $license->type );
		$ret .= "<li><a href='$url'>$type</a></li>";
	}
	return $ret;
}

function jq_release_maintainers() {
	$pkg = jq_release_manifest();
	$ret = "";

	if ( empty( $pkg->maintainers ) ) {
		return $ret;
	}

	foreach( $pkg->maintainers as $maintainer ) {
		$ret .= person( $maintainer ) . ", ";
	}
	return substr( $ret, 0, -2 );
}

function jq_release_author() {
	$pkg = jq_release_manifest();
	return person( $pkg->author );
}

function jq_release_dependencies() {
	$pkg = jq_release_manifest();
	$ret = "";
	foreach( $pkg->dependencies as $plugin => $version ) {
		if ( get_page_by_path( $plugin ) ) {
			$ret .= "<li><a href='/$plugin'>$plugin</a> $version</li>";
		} else {
			$ret .= "<li>$plugin $version</li>";
		}
	}
	return $ret;
}

function jq_release_keywords() {
	return get_the_tag_list( "<ul><li>", "</li><li>", "</li></ul>" );
}

