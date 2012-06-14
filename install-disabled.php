<?php

function wp_install( $blog_title, $user_name, $user_email, $public, $deprecated = '', $user_password = '' ) {
	global $wpdb;

	$base = '/beta/';
	$domain = 'localhost';

	wp_check_mysql_version();
	wp_cache_flush();
	make_db_current_silent();
	populate_options();
	populate_roles();

	update_option( 'blogname', 'jQuery' );
	update_option( 'admin_email', $user_email );
	update_option( 'stylesheet', 'jquery-com' );
	update_option( 'template', 'jquery' );
	update_option( 'allowedthemes', array( 'jquery-com' ) );

	$user_id = wp_create_user( $user_name, trim( $user_password ), $user_email );
	$user = new WP_User( $user_id );
	$user->set_role( 'administrator' );

	flush_rewrite_rules();

	$guess_url = wp_guess_url();

	foreach ( $wpdb->tables( 'ms_global' ) as $table => $prefixed_table )
		$wpdb->$table = $prefixed_table;

	install_network();
	populate_network( 1, $domain, $user_email, 'jQuery Network', $base, false );
	update_site_option( 'site_admins', array( $user->user_login ) );

	$wpdb->insert( $wpdb->blogs, array( 'site_id' => 1, 'domain' => $domain, 'path' => $base, 'registered' => current_time( 'mysql' ) ) );
	$blog_id = $wpdb->insert_id;
	update_user_meta( $user_id, 'source_domain', $domain );
	update_user_meta( $user_id, 'primary_blog', $blog_id );
	if ( !$upload_path = get_option( 'upload_path' ) ) {
		$upload_path = substr( WP_CONTENT_DIR, strlen( ABSPATH ) ) . '/uploads';
		update_option( 'upload_path', $upload_path );
	}
	update_option( 'fileupload_url', get_option( 'siteurl' ) . '/' . $upload_path );

	jquery_install_remaining_sites();

	wp_new_blog_notification( $blog_title, $guess_url, $user_id, __( 'The password you chose during the install.' ) );
	wp_cache_flush();

	echo '<h1>' . 'Success!' . '</h1>';
	echo '<p>' . 'The jQuery network has been installed. You must now go into <code>wp-config.php</code> and add these lines:' . '</p>';
	?>
	<textarea class="code" readonly="readonly" cols="100" rows="7">
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
$base = '<?php echo $base; ?>';
define('DOMAIN_CURRENT_SITE', '<?php echo $domain; ?>');
define('PATH_CURRENT_SITE', '<?php echo $base; ?>');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);</textarea>
	<p class="step"><a href="../wp-login.php" class="button"><?php _e( 'Log In' ); ?></a></p>
	</body>
	</html>
	<?php exit;
}

function jquery_install_remaining_sites() {

	// Must be in order for blog_id 2+

	$sites = array(
		'blog.jquery.com' => array(
			'blogname' => 'jQuery Blog',
			'stylesheet' => 'blog-jquery-com',
		),
		'api.jquery.com' => array(
			'blogname' => 'jQuery API Documentation',
			'stylesheet' => 'api-jquery-com',
		),
		'plugins.jquery.com' => array(
			'blogname' => 'jQuery Plugins',
			'stylesheet' => 'plugins-jquery-com',
		),
		'learn.jquery.com' => array(
			'blogname' => 'Learn jQuery',
			'stylesheet' => 'learn-jquery-com',
		),
		'jqueryui.com' => array(
			'blogname' => 'jQuery UI',
			'stylesheet' => 'jqueryui-com',
		),
		'blog.jqueryui.com' => array(
			'blogname' => 'jQuery UI Blog',
			'stylesheet' => 'blog-jqueryui-com',
		),
		'api.jqueryui.com' => array(
			'blogname' => 'jQuery UI API Documentation',
			'stylesheet' => 'api-jqueryui-com',
		),
		'jquery.org' => array(
			'blogname' => 'jQuery Foundation',
			'stylesheet' => 'jquery-org',
		),
		'qunitjs.com' => array(
			'blogname' => 'qUnit',
			'stylesheet' => 'qunitjs-com',
		),
		'sizzlejs.com' => array(
			'blogname' => 'Sizzle JS',
			'stylesheet' => 'sizzlejs-com',
		),
		'jquerymobile.com' => array(
			'blogname' => 'jQuery Mobile',
			'stylesheet' => 'jquerymobile-com',
		),
		'api.jquerymobile.com' => array(
			'blogname' => 'jQuery Mobile API Documentation',
			'stylesheet' => 'api-jquerymobile-com',
		),
	);

	$default_options = array(
		'enable_xmlrpc' => true,
		'template' => 'jquery',
	);

	foreach ( $sites as $domain => $site ) {
		$blog_id = insert_blog( $domain, '/', 1 );
		switch_to_blog( $blog_id );
		install_blog( $blog_id, $blogname );
		foreach ( $default_options as $option => $value ) {
			if ( ! isset( $site[ $option ] ) )
				update_option( $option, $value );
		}
		foreach ( $site as $option => $value ) {
			update_option( $option, $value );
		}		
		restore_current_blog();
	}
}