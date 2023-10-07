<?php

global $blog_id;

if ( 1 != $blog_id && false !== strpos( $_SERVER['PHP_SELF'], 'wp-admin/install.php' ) ) {
	header( 'Location: http://' . DOMAIN_CURRENT_SITE . '/wp-admin/install.php', true );
	exit;
}

if ( ! isset( $_POST['weblog_title'] ) ) {
	$_POST['weblog_title'] = 'jQuery';
}

require_once __DIR__ . '/sites.php';

function wp_install( $blog_title, $user_name, $user_email, $public, $deprecated = '', $user_password = '' ) {
	global $wpdb;

	$base = '/';
	$domain = jquery_site_expand( 'jquery.com' );

	wp_check_mysql_version();
	wp_cache_flush();
	make_db_current_silent();
	populate_options();
	populate_roles();

	$user_id = wp_create_user( $user_name, trim( $user_password ), $user_email );
	$user = new WP_User( $user_id );
	$user->set_role( 'administrator' );

	$guess_url = wp_guess_url();

	foreach ( $wpdb->tables( 'ms_global' ) as $table => $prefixed_table )
		$wpdb->$table = $prefixed_table;

	install_network();
	populate_network( 1, $domain, $user_email, 'jQuery Network', $base, false );

	delete_site_option( 'site_admins' );
	add_site_option( 'site_admins', array( $user->user_login ) );

	update_site_option( 'allowedthemes', array() );

	$wpdb->insert( $wpdb->blogs, array( 'site_id' => 1, 'domain' => $domain, 'path' => $base, 'registered' => current_time( 'mysql' ) ) );
	$blog_id = $wpdb->insert_id;
	update_user_meta( $user_id, 'source_domain', $domain );
	update_user_meta( $user_id, 'primary_blog', $blog_id );
	if ( !$upload_path = get_option( 'upload_path' ) ) {
		$upload_path = substr( WP_CONTENT_DIR, strlen( ABSPATH ) ) . '/uploads';
		update_option( 'upload_path', $upload_path );
	}
	update_option( 'fileupload_url', get_option( 'siteurl' ) . '/' . $upload_path );

	foreach ( jquery_sites() as $site => $details )
		jquery_install_site( $site, $user );

	wp_new_blog_notification( $blog_title, $guess_url, $user_id, $message = __( 'The password you chose during the install.' ) );
	wp_cache_flush();

	return array( 'url' => $guess_url, 'user_id' => $user_id, 'password' => $user_password, 'password_message' => $message );
}

function jquery_install_site( $site, $user ) {
	$sites = jquery_sites();
	$details = $sites[ $site ];

	if ( strpos( $site, '/' ) ) {
		list( $domain, $path ) = explode( '/', $site, 2 );
		$path = '/' . trim( $path, '/' ) . '/';
	} else {
		$domain = $site;
		$path = '/';
	}

	$default_options = jquery_default_site_options();
	$default_options['admin_email'] = $user->user_email;

	if ( 1 !== $details['blog_id'] ) {
		// krinkle(2021-09-03): This used to use insert_blog which didn't take a blog_id.
		// Thus, this only worked reliably when setting up a fresh server, or when adding
		// sites in the exact order that they are defined, and without any gaps, as otherwise
		// the "next" inserted ID would not match what we declare in jquery_sites()
		//
		// $blog_id = insert_blog( jquery_site_expand( $domain ), $path, 1 );
		//
		// WordPress 5.1, deprecates insert_blog() in favour of a new wp_insert_site() function,
		// which does accept a custom 'blog_id' to be set up front.
		// But, we are still on WordPress 4.x, so, for now inline what insert_blog() did, but
		// augmented with a custom blog_id.
		//
		// Start insert_blog()
		global $wpdb;
		$path = trailingslashit($path);
		// WordPress 4 terms: The network is a site, and each domain is a blog.
		// WordPress 5+ terms: The network is a network, and each domain is a site.
		// Network id must be constant for all blogs, always 1.
		$site_id = 1;
		$result = $wpdb->insert( $wpdb->blogs, array('site_id' => (int)$site_id, 'blog_id' => (int)$details['blog_id'], 'domain' => $domain, 'path' => $path, 'registered' => current_time('mysql')) );
		$blog_id = $result ? $wpdb->insert_id : false;
		refresh_blog_details( $blog_id );
		wp_maybe_update_network_site_counts();
		// End insert_blog()

		if ( $blog_id != $details['blog_id'] )
			wp_die( "Something went very wrong when trying to install $domain as site $blog_id-{$details['blog_id']}. Find nacin." );

		switch_to_blog( $blog_id );

		install_blog( $blog_id, $details['options']['blogname'] );

		add_user_to_blog( $blog_id, $user->ID, 'administrator' );
	}

	$options = array_merge( $default_options, $details['options'] );
	foreach ( $options as $option => $value )
		update_option( $option, $value );

	delete_option( 'rewrite_rules' );
	restore_current_blog();
}
