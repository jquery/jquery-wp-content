<?php

require dirname( __FILE__ ) . '/domains.php';

if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'web-base-template' );
if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/web-base-template' );

// jQuery.com staging
if ( ! defined( 'JQUERY_STAGING' ) )
	define( 'JQUERY_STAGING', true );
if ( ! defined( 'JQUERY_STAGING_PREFIX' ) ) {
	if ( JQUERY_STAGING )
		define( 'JQUERY_STAGING_PREFIX', 'dev.' );
	else
		define( 'JQUERY_STAGING_PREFIX', '' );
} elseif ( ! JQUERY_STAGING && JQUERY_STAGING_PREFIX ) {
	die( "If you are not staging, you should not have a JQUERY_STAGING_PREFIX." );
} // else ( JQUERY_STAGING && ! JQUERY_STAGING_PREFIX ) -- this case is okay.

// jQuery.com Multisite and domain staging configuration

global $blog_id;

$domains = jquery_domains();

if ( ! isset( $_SERVER['HTTP_HOST'] ) )
	$_SERVER['HTTP_HOST'] = JQUERY_STAGING_PREFIX . 'jquery.com';

$live_domain = $_SERVER['HTTP_HOST'];
if ( JQUERY_STAGING )
	$live_domain = str_replace( JQUERY_STAGING_PREFIX, '', $live_domain );

if ( ! isset( $domains[ $live_domain ] ) )
	die( 'Domain mapping issue. You have web-base-template configured for ' . JQUERY_STAGING_PREFIX . 'jquery.com.' );

define( 'JQUERY_LIVE_SITE', $live_domain );

$blog_id = $domains[ $live_domain ]['blog_id'];
define( 'COOKIE_DOMAIN', $domains[ $live_domain ]['cookie_domain'] );
unset( $domains, $live_domain ); // Leave $blog_id.

if ( defined( 'MULTISITE' ) && ! MULTISITE )
	die( "Remove define( 'MULTISITE', false ); from wp-config.php. Maybe check out web-base-template/wp-config-sample.php for the current sample." );

define( 'MULTISITE', true );
define( 'SUNRISE', true );

define( 'SUBDOMAIN_INSTALL', true );
$base = '/';
define( 'DOMAIN_CURRENT_SITE', JQUERY_STAGING_PREFIX . 'jquery.com' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );

// Never display WP_DEBUG notices during XML-RPC requests.
if ( defined( 'XMLRPC_REQUEST' ) )
    define( 'WP_DEBUG_DISPLAY', false );

// jQuery staging URLs
if ( JQUERY_STAGING && ! defined( 'XMLRPC_REQUEST' ) )
	ob_start( 'jquery_com_staging_urls' );

function jquery_com_staging_urls( $content ) {
	foreach ( array_keys( jquery_domains() ) as $domain )
		$content = str_replace( 'http://' . $domain, 'http://' . JQUERY_STAGING_PREFIX . $domain, $content );
	return $content;
}
