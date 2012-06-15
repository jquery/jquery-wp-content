<?php

if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', dirname( ABSPATH ) . '/web-base-template' );
if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/web-base-template' );

// jQuery.com staging
if ( ! defined( 'JQUERY_STAGING' ) )
	define( 'JQUERY_STAGING', true );
if ( ! defined( 'JQUERY_STAGING_PREFIX' ) )
	define( 'JQUERY_STAGING_PREFIX', 'dev.' );

// jQuery.com Multisite and domain staging configuration

global $blog_id;

function jquery_domains() {
	return array( /* blog_id, cookie domain */
		'jquery.com' => array( 1, '.jquery.com' ),
		'blog.jquery.com' => array( 2, '.jquery.com' ),
		'api.jquery.com' => array( 3, '.jquery.com' ),
		'plugins.jquery.com' => array( 4, '.jquery.com' ),
		'learn.jquery.com' => array( 5, '.jquery.com' ),

		'jqueryui.com' => array( 6, '.jqueryui.com' ),
		'api.jqueryui.com' => array( 7, '.jqueryui.com' ),

		'jquery.org' => array( 8, '.jquery.org' ),
		'qunitjs.com' => array( 9, '.qunitjs.com' ),
		'sizzlejs.com' => array( 10, '.sizzlejs.com' ),
		'jquerymobile.com' => array( 11, '.jquerymobile.com' ),
	);
}

$domains = jquery_domains();

if ( ! isset( $_SERVER['HTTP_HOST'] ) )
	$_SERVER['HTTP_HOST'] = (JQUERY_STAGING ? JQUERY_STAGING_PREFIX : '') . 'jquery.com';

$live_domain = $_SERVER['HTTP_HOST'];
if ( JQUERY_STAGING )
	$live_domain = str_replace( JQUERY_STAGING_PREFIX, '', $live_domain );

if ( ! isset( $domains[ $live_domain ] ) )
	die( '<!-- Domain mapping issue. -->' );

define( 'JQUERY_LIVE_SITE', $live_domain );

list( $blog_id, $cookie_domain ) = $domains[ $live_domain ];

define( 'COOKIE_DOMAIN', $cookie_domain );
unset( $domains, $cookie_domain, $live_domain ); // Leave $blog_id!

if ( ! defined( 'MULTISITE' ) )
	define( 'MULTISITE', true );
if ( MULTISITE )
	define( 'SUNRISE', true );

define( 'SUBDOMAIN_INSTALL', true );
$base = '/';
define( 'DOMAIN_CURRENT_SITE', ( JQUERY_STAGING ? JQUERY_STAGING_PREFIX : '' ) . 'jquery.com' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );

// jQuery staging URLs
if ( JQUERY_STAGING && ! defined( 'XMLRPC_REQUEST' ) )
	ob_start( 'jquery_com_staging_urls' );

function jquery_com_staging_urls( $content ) {
	foreach ( array_keys( jquery_domains() ) as $domain )
		$content = str_replace( 'http://' . $domain, 'http://' . JQUERY_STAGING_PREFIX . $domain, $content );
	return $content;
}
