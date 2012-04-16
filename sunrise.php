<?php

global $blog_id;

if ( isset( $blog_id ) ) {
	$current_blog = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->blogs WHERE blog_id = %d", $blog_id ) );
	$current_blog->domain = $_SERVER['HTTP_HOST'];
	$current_blog->path   = '/';
}

