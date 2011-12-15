<?php

function jq_plugin_versions() {
	$post = get_post( get_the_ID() );
	$main_post = empty( $post->post_parent ) ? $post->ID : $post->post_parent;
	$versions = get_post_meta( $main_post, "versions", true );
	if ( !$versions ) {
		return;
	}

	$out = "";
	$versions = json_decode( $versions );
	foreach( $versions as $version ) {
		$out .= "$version<br>";
	}

	return $out;
}

add_shortcode( "jq_plugin_versions", "jq_plugin_versions" );

