<?php

// These should be set by wp-config.php
if ( !defined( 'JQUERY_STAGING' ) ) {
	define( 'JQUERY_STAGING', true );
}
if ( !defined( 'JQUERY_STAGING_FORMAT' ) ) {
	define( 'JQUERY_STAGING_FORMAT', JQUERY_STAGING ? 'local.%s' : '' );
}
if ( !JQUERY_STAGING && JQUERY_STAGING_FORMAT ) {
	die( 'Error: JQUERY_STAGING_FORMAT must be empty on production domains' );
}

// Custom settings for WordPress
define( 'WP_AUTO_UPDATE_CORE', true );
if ( !defined( 'WP_CONTENT_DIR' ) ) {
	define( 'WP_CONTENT_DIR', ABSPATH . 'jquery-wp-content' );
}
if ( !defined( 'WP_CONTENT_URL' ) ) {
	define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/jquery-wp-content' );
}
if ( defined( 'XMLRPC_REQUEST' ) ) {
	// Never display WP_DEBUG notices during XML-RPC requests.
	define( 'WP_DEBUG_DISPLAY', false );
}

require_once __DIR__ . '/sites.php';
$sites = jquery_sites();

// Decide which site we should display
if ( !isset( $_SERVER['HTTP_HOST'] ) ) {
	die( 'Error: HTTP_HOST must be set' );
}
// Strip custom port number or staging prefix
// e.g. local.jquery.com:8080 -> jquery.com
$live_site =  jquery_site_extract( $_SERVER['HTTP_HOST'] );
if ( !isset( $sites[ $live_site ] ) ) {
	header( "400 Invalid Request" );
	header( "Content-Type: text/plain" );
	die( 'Domain not served here.' );
}
if ( isset( $sites[ $live_site ]['subsites'] ) ) {
	list( $first_path_segment ) = explode( '/', trim( $_SERVER['REQUEST_URI'], '/' ), 2 );
	if ( $first_path_segment && isset( $sites[ $live_site . '/' . $first_path_segment ] ) )
		$live_site .= '/' . $first_path_segment;
}
define( 'JQUERY_LIVE_SITE', $live_site );
unset( $live_site, $first_path_segment );

// jQuery.com Multisite and domain staging configuration
global $blog_id;
$blog_id = $sites[ JQUERY_LIVE_SITE ]['blog_id'];

if ( defined( 'MULTISITE' ) && !MULTISITE ) {
	die( "Remove define( 'MULTISITE', false ); from wp-config.php. Maybe check out jquery-wp-content/wp-config-sample.php for the current sample." );
}
define( 'COOKIE_DOMAIN', $sites[ JQUERY_LIVE_SITE ]['cookie_domain'] );
define( 'MULTISITE', true );
define( 'SUNRISE', true );
define( 'SUBDOMAIN_INSTALL', true );
define( 'DOMAIN_CURRENT_SITE', jquery_site_expand( 'jquery.com' ) );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );
define( 'ADMIN_COOKIE_PATH', '/' );
