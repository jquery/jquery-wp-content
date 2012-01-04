<?php

function jq_plugin_meta( $attr ) {
	$post = get_post( get_the_ID() );
	$main_post = empty( $post->post_parent ) ? $post->ID : $post->post_parent;
	return get_post_meta( $main_post, $attr[ "key" ], true );
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

function jq_plugin_keywords() {
	return get_the_tag_list( "<ul><li>", "</li><li>", "</li></ul>" );
}

function jq_plugin_date() {
	return get_the_date();
}

add_shortcode( "jq_plugin_versions", "jq_plugin_versions" );
add_shortcode( "jq_plugin_meta", "jq_plugin_meta" );
add_shortcode( "jq_plugin_keywords", "jq_plugin_keywords" );
add_shortcode( "jq_plugin_date", "jq_plugin_date" );
