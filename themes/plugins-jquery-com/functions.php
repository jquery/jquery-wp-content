<?php

function jq_plugin_meta( $attr ) {
	$post = get_post( get_the_ID() );
	$main_post = empty( $post->post_parent ) ? $post->ID : $post->post_parent;
	return get_post_meta( $main_post, $attr[ "key" ], true );
}

function jq_plugin_versions() {
	$versions = jq_plugin_meta( array( "key" => "versions" ) );
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
add_shortcode( "jq_plugin_meta", "jq_plugin_meta" );

