<?php
/*
Template Name: Post-Receive Hook
*/
$handle = popen( "node " . $post->post_content_filtered, "w" );
fwrite( $handle, file_get_contents( "php://input" ) );
pclose( $handle );
