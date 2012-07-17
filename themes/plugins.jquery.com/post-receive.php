<?php
/*
Template Name: Post-Receive Hook
*/
if ( $_SERVER["REQUEST_METHOD"] === "GET" ) {
	// TODO: redirect to instructions for adding a plugin
	exit;
}
$handle = popen( "node " . $post->post_content_filtered, "w" );
fwrite( $handle, file_get_contents( "php://input" ) );
pclose( $handle );
