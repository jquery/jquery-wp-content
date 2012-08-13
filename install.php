<?php

global $blog_id;

if ( 1 != $blog_id && false !== strpos( $_SERVER['PHP_SELF'], 'wp-admin/install.php' ) ) {
	wp_redirect( 'http://' . DOMAIN_CURRENT_SITE . '/wp-admin/install.php' );
	exit;
}

if ( ! isset( $_POST['weblog_title'] ) )
	$_POST['weblog_title'] = 'jQuery';

function wp_install( $blog_title, $user_name, $user_email, $public, $deprecated = '', $user_password = '' ) {
	global $wpdb;

	$base = '/';
	$domain = JQUERY_STAGING_PREFIX . 'jquery.com';

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

	update_site_option( 'site_admins', array( $user->user_login ) );
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

	foreach ( jquery_domains() as $domain => $details )
		jquery_install_site( $domain, $user );

	wp_new_blog_notification( $blog_title, $guess_url, $user_id, $message = __( 'The password you chose during the install.' ) );
	wp_cache_flush();

	return array( 'url' => $guess_url, 'user_id' => $user_id, 'password' => $user_password, 'password_message' => $message );
}

function jquery_install_site( $domain, $user ) {
	$domains = jquery_domains();
	$details = $domains[ $domain ];

	$default_options = jquery_default_site_options();
	$default_options['admin_email'] = $user->user_email;

	if ( 1 !== $details['blog_id'] ) {
		$blog_id = insert_blog( JQUERY_STAGING_PREFIX . $domain, '/', 1 );
		if ( $blog_id != $details['blog_id'] )
			wp_die( "Something went very wrong when trying to install $domain as site $blog_id-{$details['blog_id']}. Find nacin." );

		switch_to_blog( $blog_id );

		install_blog( $blog_id, $details['options']['blogname'] );

		add_user_to_blog( $blog_id, $user->ID, 'administrator' );
	}

	$options = array_merge( $default_options, $details['options'] );
	foreach ( $options as $option => $value )
		update_option( $option, $value );

	// Work around a superficial bug in install_blog(), fixed in WP r21172.
	$home = get_option( 'home' );
	$siteurl = get_option( 'siteurl' );
	update_option( 'home', 'http://example.com' ); // Please just don't ask.
	update_option( 'siteurl', 'http://example.com' );
	update_option( 'home', $home );
	update_option( 'siteurl', $siteurl );

	flush_rewrite_rules();
	restore_current_blog();
}
