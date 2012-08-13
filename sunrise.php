<?php

if ( isset( $blog_id ) ) {
	$current_site = wpmu_current_site();
	if ( ! isset( $current_site->site_name ) )
		$current_site->site_name = 'jQuery';
	$current_blog = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->blogs WHERE blog_id = %d", $blog_id ) );

	// Can't find the site in the DB:
	if ( ! is_object( $current_blog ) ) {
		$current_blog = new stdClass;
		$current_blog->blog_id = $current_blog->site_id = $current_blog->public = 1;
		$current_blog->archived = $current_blog->deleted = $current_blog->spam = 0;

		if ( ! defined( 'WP_INSTALLING' ) ) {
			// Okay, see if we can find the main site in the DB.
			// If not, time for a new network install.
			if ( 1 == $blog_id || ! $wpdb->get_var( "SELECT blog_id FROM $wpdb->blogs WHERE blog_id = 1" ) ) {
				require( ABSPATH . WPINC . '/kses.php' );
				require( ABSPATH . WPINC . '/pluggable.php' );
				require( ABSPATH . WPINC . '/formatting.php' );
				wp_redirect( 'http://' . DOMAIN_CURRENT_SITE . '/wp-admin/install.php' );
				die();
			}

			// Otherwise, we have a working network, but have a new site to install. Do that now.
			define( 'WP_INSTALLING', true );
			add_action( 'init', function() use ( $blog_id ) {
				global $wpdb;
				$wpdb->set_blog_id( $blog_id );
				if ( is_super_admin() ) {
					$super_admin = wp_get_current_user();
				} else {
					$super_admins = get_super_admins();
					$super_admin = get_user_by( 'login', reset( $super_admins ) );
				}
				require ABSPATH . 'wp-admin/includes/upgrade.php';
				jquery_install_site( str_replace( JQUERY_STAGING_PREFIX, '', $_SERVER['HTTP_HOST'] ), $super_admin );
				wp_safe_redirect( 'http://' . $_SERVER['HTTP_HOST'] );
				exit;
			} );
		}
	}
	$current_blog->domain = $_SERVER['HTTP_HOST'];
	$current_blog->path   = '/';
}