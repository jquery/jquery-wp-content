<?php

require dirname( __FILE__ ) . '/sites.php';

if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'jquery-wp-content' );
if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/jquery-wp-content' );

// Enable all core updates
define( 'WP_AUTO_UPDATE_CORE', true );

// jQuery.com staging
if ( ! defined( 'JQUERY_STAGING' ) )
	define( 'JQUERY_STAGING', true );
if ( ! defined( 'JQUERY_STAGING_PREFIX' ) ) {
	if ( JQUERY_STAGING )
		define( 'JQUERY_STAGING_PREFIX', 'local.' );
	else
		define( 'JQUERY_STAGING_PREFIX', '' );
} elseif ( ! JQUERY_STAGING && JQUERY_STAGING_PREFIX ) {
	die( "If you are not staging, you should not have a JQUERY_STAGING_PREFIX." );
} // else ( JQUERY_STAGING && ! JQUERY_STAGING_PREFIX ) -- this case is okay.

// jQuery.com Multisite and domain staging configuration

global $blog_id;

$sites = jquery_sites();

if ( ! isset( $_SERVER['HTTP_HOST'] ) )
	$_SERVER['HTTP_HOST'] = JQUERY_STAGING_PREFIX . 'jquery.com';

$live_site = preg_replace( '/^www\.|:\d+$/', '', strtolower( $_SERVER['HTTP_HOST'] ) );
if ( JQUERY_STAGING )
	$live_site = str_replace( JQUERY_STAGING_PREFIX, '', $live_site );

if ( ! isset( $sites[ $live_site ] ) ) {
	if ( JQUERY_STAGING ) {
		header( "400 Invalid Request" );
		header( "Content-Type: text/plain" );
		die( 'Domain mapping issue. You have jquery-wp-content configured for ' . JQUERY_STAGING_PREFIX . 'jquery.com. You tried to get '.$_SERVER['HTTP_HOST'] );
	} else {
		// This shouldn't happen in production :-(
		// Record the event and treat this as a http://jquery.com hit.
		$vars = get_defined_vars();
		unset( $vars['GLOBALS'], $vars['sites'], $vars['_POST'], $vars['_GET'], $vars['_FILES'], $vars['_ENV'], $vars['_REQUEST'] );
		ob_start();
		var_dump( $vars );
		$debug = ob_get_clean();
		error_log( gmdate( '[d-M-Y H:i:s e] ' ) . $debug . "\n", 3, '/tmp/domain_mapping.log' );
		header( "Location: http://jquery.com/" );
		exit;
	}
}

if ( ! empty( $sites[ $live_site ]['subsites'] ) ) {
	list( $first_path_segment ) = explode( '/', trim( $_SERVER['REQUEST_URI'], '/' ), 2 );
	if ( $first_path_segment && isset( $sites[ $live_site . '/' . $first_path_segment ] ) )
		$live_site .= '/' . $first_path_segment;
}

define( 'JQUERY_LIVE_SITE', $live_site );

list( $live_domain ) = explode( '/', JQUERY_LIVE_SITE, 2 );
define( 'JQUERY_LIVE_DOMAIN', $live_domain );

$blog_id = $sites[ $live_site ]['blog_id'];
define( 'COOKIE_DOMAIN', $sites[ $live_site ]['cookie_domain'] );
unset( $sites, $live_site, $live_domain, $first_path_segment ); // Leave $blog_id.

if ( defined( 'MULTISITE' ) && ! MULTISITE )
	die( "Remove define( 'MULTISITE', false ); from wp-config.php. Maybe check out jquery-wp-content/wp-config-sample.php for the current sample." );

define( 'MULTISITE', true );
define( 'SUNRISE', true );

define( 'SUBDOMAIN_INSTALL', true );
$base = '/';
define( 'DOMAIN_CURRENT_SITE', JQUERY_STAGING_PREFIX . 'jquery.com' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );

define( 'ADMIN_COOKIE_PATH', '/' );

// Config settings that only apply to XML-RPC requests
if ( defined( 'XMLRPC_REQUEST' ) ) {

	// Never display WP_DEBUG notices during XML-RPC requests.
	define( 'WP_DEBUG_DISPLAY', false );

	// Increase memory limit
	define( 'WP_MEMORY_LIMIT', '256M' );
}

// jQuery staging URLs
if ( JQUERY_STAGING && ! defined( 'XMLRPC_REQUEST' ) )
	ob_start( 'jquery_com_staging_urls' );

function jquery_com_staging_urls( $content ) {
	foreach ( array_keys( jquery_sites() ) as $site )
		$content = str_replace( '//' . $site, '//' . JQUERY_STAGING_PREFIX . $site, $content );
	return $content;
}
