<?php

function jq_plugin_meta( $attr ) {
	$post = get_post( get_the_ID() );
	$main_post = empty( $post->post_parent ) ? $post->ID : $post->post_parent;
	return get_post_meta( $main_post, $attr[ "key" ], true );
}

function jq_plugin_package() {
	return json_decode( jq_plugin_meta( array( "key" => "package_json" ) ) );
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

	$out = "<ul>";
	$versions = array_reverse( json_decode( $versions ) );
	foreach( $versions as $version ) {
		if ( $version === $currentVersion ) {
			$out .= "<li>$version</li>";
		} else if ( $version === $latest ) {
			$out .= "<li><a href=\"/$parent->post_name/\">$version</a></li>";
		} else {
			$out .= "<li><a href=\"/$parent->post_name/$version/\">$version</a></li>";
		}
	}
	$out .= "</ul>";

	return $out;
}

//
// The functions below (with the jq_release_ prefix) relate to a specific
// version number release of a plugin
//

function jq_release_download_url() {
	return get_post_meta( get_the_ID(), "download_url", true );
}

function jq_release_package() {
	return json_decode( get_post_meta( get_the_ID(), "package_json", true ) );
}

function jq_release_homepage() {
	$pkg = jq_release_package();
	return $pkg->homepage;
}

function jq_release_date() {
	return get_the_date();
}

function jq_release_version() {
	$pkg = jq_release_package();
	return $pkg->version;
}

function jq_release_licenses() {
	$pkg = jq_release_package();
	$license = $pkg->licenses;
	// TODO
	return "<li>TODO</li>";
}

function jq_release_maintainers() {
	$pkg = jq_release_package();
	$maintainers = json_decode( $pkg->maintainers );
	// TODO
	return "<li>TODO</li>";
}

function jq_release_author() {
	$pkg = jq_release_package();
	$author = json_decode( $pkg->author );
	// TODO
	return "<a href=#>TODO</a>";
}

function jq_release_dependencies() {
	$pkg = jq_release_package();
	$dependencies = json_decode( $pkg->dependencies );
	// TODO
	return "<li>TODO</li>"; 
}

function jq_release_keywords() {
	return get_the_tag_list( "<ul><li>", "</li><li>", "</li></ul>" );
}
