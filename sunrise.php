<?php

global $blog_id;

$wpdb->set_blog_id( 1 );
$wpdb->set_prefix( $table_prefix );

if ( ! is_blog_installed() && ! defined( 'WP_INSTALLING' ) ) {
	require( ABSPATH . WPINC . '/kses.php' );
	require( ABSPATH . WPINC . '/pluggable.php' );
	require( ABSPATH . WPINC . '/formatting.php' );
	wp_redirect( 'http://' . DOMAIN_CURRENT_SITE . '/wp-admin/install.php' );
	die();
}

if ( isset( $blog_id ) ) {
	$current_site = wpmu_current_site();
	if ( ! isset( $current_site->site_name ) )
		$current_site->site_name = 'jQuery';
	$current_blog = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->blogs WHERE blog_id = %d", $blog_id ) );
	if ( ! is_object( $current_blog ) ) {
		$current_blog = new stdClass;
		$current_blog->blog_id = $current_blog->site_id = $current_blog->public = 1;
		$current_blog->archived = $current_blog->deleted = $current_blog->spam = 0;
	}
	$current_blog->domain = $_SERVER['HTTP_HOST'];
	$current_blog->path   = '/';
}