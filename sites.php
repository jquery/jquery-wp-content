<?php

function jquery_sites() {
	static $sites;
	if ( isset( $sites ) )
		return $sites;

	$sites = array( /* blog_id, cookie domain */
		'jquery.com' => array(
			'blog_id' => 1,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'jQuery',
				'jquery_description' => 'jQuery: The Write Less, Do More, JavaScript Library',
				'stylesheet' => 'jquery.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
				'jquery_body_class' => 'jquery',
				'jquery_xfn_rel_me' => 'https://social.lfx.dev/@jquery',
				'jquery_typesense_key' => 'Zh8mMgohXECel9wjPwqT7lekLSG3OCgz',
				'jquery_typesense_collection' => 'jquery_com',
			),
		),
		'blog.jquery.com' => array(
			'blog_id' => 2,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'Official jQuery Blog',
				'blogdescription' => 'New Wave JavaScript',
				'jquery_author' => 'jQuery Team - jquery.com',
				'jquery_description' => 'jQuery: The Write Less, Do More, JavaScript Library',
				'stylesheet' => 'jquery.com',
				'jquery_body_class' => 'jquery',
				'permalink_structure' => '/%year%/%monthnum%/%day%/%postname%/',
				'jquery_is_blog' => true,
			),
		),
		'api.jquery.com' => array(
			'blog_id' => 3,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'jQuery API Documentation',
				'stylesheet' => 'api.jquery.com',
				'active_plugins' => array(
					'jquery-api-category-listing.php',
				),
				'jquery_body_class' => 'jquery',
				'jquery_logo_link'=> 'https://jquery.com/',
				'jquery_typesense_key' => 'Zh8mMgohXECel9wjPwqT7lekLSG3OCgz',
				'jquery_typesense_collection' => 'jquery_com',
			),
		),
		# Historical: Database blog_id 4 is reserved for plugins.jquery.com
		'learn.jquery.com' => array(
			'blog_id' => 5,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'jQuery Learning Center',
				'stylesheet' => 'learn.jquery.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
				'jquery_body_class' => 'jquery-learn',
			),
		),
		'jqueryui.com' => array(
			'blog_id' => 6,
			'cookie_domain' => '.jqueryui.com',
			'options' => array(
				'blogname' => 'jQuery UI',
				'jquery_description' => 'jQuery UI is a curated set of user interface interactions, effects, widgets, and themes built on top of the jQuery JavaScript Library. Whether you\'re building highly interactive web applications or you just need to add a date picker to a form control, jQuery UI is the perfect choice.',
				'stylesheet' => 'jqueryui.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
				'jquery_body_class' => 'jquery-ui',
				'jquery_typesense_key' => 'Zh8mMgohXECel9wjPwqT7lekLSG3OCgz',
				'jquery_typesense_collection' => 'jqueryui_com',
				'jquery_twitter_link' => 'https://twitter.com/jqueryui',
			),
		),
		'blog.jqueryui.com' => array(
			'blog_id' => 7,
			'cookie_domain' => '.jqueryui.com',
			'options' => array(
				'blogname' => 'jQuery UI Blog',
				'stylesheet' => 'blog.jqueryui.com',
				'jquery_body_class' => 'jquery-ui',
				'jquery_twitter_link' => 'https://twitter.com/jqueryui',
			),
		),
		'api.jqueryui.com' => array(
			'subsites' => true, // Has one level of sub-sites (api.jqueryui.com/([^/]+))
			'blog_id' => 8,
			'cookie_domain' => '.jqueryui.com',
			'options' => array(
				'blogname' => 'jQuery UI API Documentation',
				'stylesheet' => 'api.jqueryui.com',
				'active_plugins' => array(
					'jquery-api-category-listing.php',
				),
				'jquery_body_class' => 'jquery-ui',
				'jquery_logo_link'=> 'https://jqueryui.com/',
				'jquery_typesense_key' => 'Zh8mMgohXECel9wjPwqT7lekLSG3OCgz',
				'jquery_typesense_collection' => 'jqueryui_com',
				'jquery_twitter_link' => 'https://twitter.com/jqueryui',
			),
		),
		'api.jqueryui.com/1.8' => array(
			'blog_id' => 17,
			'cookie_domain' => '.jqueryui.com',
			'options' => array(
				'blogname' => 'jQuery UI 1.8 Documentation',
				'stylesheet' => 'api.jqueryui.com',
				'active_plugins' => array(
					'jquery-api-category-listing.php',
					'jquery-api-versioned-links.php',
				),
				'jquery_body_class' => 'jquery-ui',
				'jquery_logo_link'=> 'https://jqueryui.com/',
				'jquery_typesense_key' => 'Zh8mMgohXECel9wjPwqT7lekLSG3OCgz',
				'jquery_typesense_collection' => 'jqueryui_com',
				'jquery_twitter_link' => 'https://twitter.com/jqueryui',
			),
		),
		'api.jqueryui.com/1.9' => array(
			'blog_id' => 21,
			'cookie_domain' => '.jqueryui.com',
			'options' => array(
				'blogname' => 'jQuery UI 1.9 Documentation',
				'stylesheet' => 'api.jqueryui.com',
				'active_plugins' => array(
					'jquery-api-category-listing.php',
					'jquery-api-versioned-links.php',
				),
				'jquery_body_class' => 'jquery-ui',
				'jquery_logo_link'=> 'https://jqueryui.com/',
				'jquery_typesense_key' => 'Zh8mMgohXECel9wjPwqT7lekLSG3OCgz',
				'jquery_typesense_collection' => 'jqueryui_com',
				'jquery_twitter_link' => 'https://twitter.com/jqueryui',
			),
		),
		'jquery.org' => array(
			'blog_id' => 9,
			'cookie_domain' => '.jquery.org',
			'options' => array(
				'blogname' => 'jQuery Foundation',
				'stylesheet' => 'jquery.org',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
				'jquery_body_class' => 'jquery-foundation',
			),
		),
		# Historical: Database blog_id 10 is reserved for qunitjs.com.
		# Historical: Database blog_id 11 is reserved for sizzlejs.com.
		'jquerymobile.com' => array(
			'blog_id' => 12,
			'cookie_domain' => '.jquerymobile.com',
			'options' => array(
				'blogname' => 'jQuery Mobile',
				'stylesheet' => 'jquerymobile.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
				'jquery_body_class' => 'jquery-mobile',
				'jquery_typesense_key' => 'Zh8mMgohXECel9wjPwqT7lekLSG3OCgz',
				'jquery_typesense_collection' => 'jquerymobile_com',
				'jquery_twitter_link' => 'https://twitter.com/jquerymobile',
			),
		),
		'api.jquerymobile.com' => array(
			'subsites' => true, // Has one level of sub-sites (api.jquerymobile.com/([^/]+))
			'blog_id' => 13,
			'cookie_domain' => '.jquerymobile.com',
			'options' => array(
				'blogname' => 'jQuery Mobile API Documentation',
				'stylesheet' => 'api.jquerymobile.com',
				'active_plugins' => array(
					'jquery-api-category-listing.php',
				),
				'jquery_body_class' => 'jquery-mobile',
				'jquery_logo_link'=> 'https://jquerymobile.com/',
				'jquery_typesense_key' => 'Zh8mMgohXECel9wjPwqT7lekLSG3OCgz',
				'jquery_typesense_collection' => 'jquerymobile_com',
				'jquery_twitter_link' => 'https://twitter.com/jquerymobile',
			),
		),
		# Historical: Database blog_id 14 is reserved for api.qunitjs.com.
		# Historical: Database blog_id 15 is reserved for books.jquery.com
		# Historical: Database blog_id 16 is reserved for events.jquery.org
		'brand.jquery.org' => array(
			'blog_id' => 18,
			'cookie_domain' => '.jquery.org',
			'options' => array(
				'blogname' => 'jQuery Brand Guidelines',
				'stylesheet' => 'brand.jquery.org',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
				'jquery_body_class' => 'jquery-foundation',
			),
		),
		'contribute.jquery.org' => array(
			'blog_id' => 19,
			'cookie_domain' => '.jquery.org',
			'options' => array(
				'blogname' => 'Contribute to jQuery',
				'stylesheet' => 'contribute.jquery.org',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
				'jquery_body_class' => 'jquery-foundation',
			),
		),
		# Historical: Database blog_id 20 is reserved for irc.jquery.org.
		'meetings.jquery.org' => array(
			'blog_id' => 22,
			'cookie_domain' => '.jquery.org',
			'options' => array(
				'blogname' => 'jQuery Meetings',
				'stylesheet' => 'meetings.jquery.org',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
				'jquery_body_class' => 'jquery-foundation',
			),
		),
		# Historical: Database blog_id 23 is reserved for codeorigin.jquery.com
		'api.jquerymobile.com/1.3' => array(
			'blog_id' => 24,
			'cookie_domain' => '.jquerymobile.com',
			'options' => array(
				'blogname' => 'jQuery Mobile 1.3 Documentation',
				'stylesheet' => 'api.jquerymobile.com',
				'active_plugins' => array(
					'jquery-api-category-listing.php',
					'jquery-api-versioned-links.php',
				),
				'jquery_body_class' => 'jquery-mobile',
				'jquery_logo_link'=> 'https://jquerymobile.com/',
				'jquery_typesense_key' => 'Zh8mMgohXECel9wjPwqT7lekLSG3OCgz',
				'jquery_typesense_collection' => 'jquerymobile_com',
				'jquery_twitter_link' => 'https://twitter.com/jquerymobile',
			),
		),
		'api.jqueryui.com/1.10' => array(
			'blog_id' => 25,
			'cookie_domain' => '.jqueryui.com',
			'options' => array(
				'blogname' => 'jQuery UI 1.10 Documentation',
				'stylesheet' => 'api.jqueryui.com',
				'active_plugins' => array(
					'jquery-api-category-listing.php',
					'jquery-api-versioned-links.php',
				),
				'jquery_body_class' => 'jquery-ui',
				'jquery_logo_link'=> 'https://jqueryui.com/',
				'jquery_typesense_key' => 'Zh8mMgohXECel9wjPwqT7lekLSG3OCgz',
				'jquery_typesense_collection' => 'jqueryui_com',
				'jquery_twitter_link' => 'https://twitter.com/jqueryui',
			),
		),
		'api.jqueryui.com/1.12' => array(
			'blog_id' => 26,
			'cookie_domain' => '.jqueryui.com',
			'options' => array(
				'blogname' => 'jQuery UI 1.12 Documentation',
				'stylesheet' => 'api.jqueryui.com',
				'active_plugins' => array(
					'jquery-api-category-listing.php',
					'jquery-api-versioned-links.php',
				),
				'jquery_body_class' => 'jquery-ui',
				'jquery_logo_link'=> 'https://jqueryui.com/',
				'jquery_typesense_key' => 'Zh8mMgohXECel9wjPwqT7lekLSG3OCgz',
				'jquery_typesense_collection' => 'jqueryui_com',
				'jquery_twitter_link' => 'https://twitter.com/jqueryui',
			),
		),
		'api.jqueryui.com/1.11' => array(
			'blog_id' => 27,
			'cookie_domain' => '.jqueryui.com',
			'options' => array(
				'blogname' => 'jQuery UI 1.11 Documentation',
				'stylesheet' => 'api.jqueryui.com',
				'active_plugins' => array(
					'jquery-api-category-listing.php',
					'jquery-api-versioned-links.php',
				),
				'jquery_body_class' => 'jquery-ui',
				'jquery_logo_link'=> 'https://jqueryui.com/',
				'jquery_typesense_key' => 'Zh8mMgohXECel9wjPwqT7lekLSG3OCgz',
				'jquery_typesense_collection' => 'jqueryui_com',
				'jquery_twitter_link' => 'https://twitter.com/jqueryui',
			),
		),
		'api.jquerymobile.com/1.4' => array(
			'blog_id' => 28,
			'cookie_domain' => '.jquerymobile.com',
			'options' => array(
				'blogname' => 'jQuery Mobile 1.4 Documentation',
				'stylesheet' => 'api.jquerymobile.com',
				'active_plugins' => array(
					'jquery-api-category-listing.php',
					'jquery-api-versioned-links.php',
				),
				'jquery_body_class' => 'jquery-mobile',
				'jquery_logo_link'=> 'https://jquerymobile.com/',
				'jquery_typesense_key' => 'Zh8mMgohXECel9wjPwqT7lekLSG3OCgz',
				'jquery_typesense_collection' => 'jquerymobile_com',
				'jquery_twitter_link' => 'https://twitter.com/jquerymobile',
			),
		),
		'releases.jquery.com' => array(
			'blog_id' => 29,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'jQuery CDN',
				'jquery_description' => 'Worldwide distribution of jQuery releases.',
				'stylesheet' => 'releases.jquery.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
				'jquery_body_class' => 'jquery',
			),
		),
		'api.jqueryui.com/1.13' => array(
			'blog_id' => 30,
			'cookie_domain' => '.jqueryui.com',
			'options' => array(
				'blogname' => 'jQuery UI 1.13 Documentation',
				'stylesheet' => 'api.jqueryui.com',
				'active_plugins' => array(
					'jquery-api-category-listing.php',
					'jquery-api-versioned-links.php',
				),
				'jquery_body_class' => 'jquery-ui',
				'jquery_logo_link'=> 'https://jqueryui.com/',
				'jquery_typesense_key' => 'Zh8mMgohXECel9wjPwqT7lekLSG3OCgz',
				'jquery_typesense_collection' => 'jqueryui_com',
				'jquery_twitter_link' => 'https://twitter.com/jqueryui',
			),
		),
	);

	uasort( $sites, function( $a, $b ) {
		if ( $a['blog_id'] == $b['blog_id'] )
			die( 'Two sites have the same blog_id.' );
		if ( $a['blog_id'] > $b['blog_id'] )
			return 1;
		return -1;
	} );
	return $sites;
}

/**
 * Resolve a canonical site (e.g. JQUERY_LIVE_SITE) into one for
 * the current environment. This exists to automatically change
 * the site hostname if JQUERY_STAGING is true.
 *
 * This is cheap and can be applied at the last minute as-needed.
 *
 * @param string $site
 * @return string
 */
function jquery_site_expand( $site ) {
	if ( JQUERY_STAGING ) {
		return strtr( JQUERY_STAGING_FORMAT, [ '%s' => $site ] );
	}
	return $site;
}

/**
 * @param string $site E.g. `$_SERVER['HTTP_HOST']`
 * @return string
 */
function jquery_site_extract( $hostname ) {
	$live_site = preg_replace( '/:\d+$/', '', strtolower( $hostname ) );
	if ( JQUERY_STAGING ) {
		// Convert the format into a regex that matches the placeholder
		// Strip port from both because the webserver may internally have
		// a different port from the public one
		$rPortless = preg_quote( preg_replace( '/:\d+$/', '', JQUERY_STAGING_FORMAT ), '/' );
		$rPortless = strtr( $rPortless, [ '%s' => '(.+)' ] );
		$rPortless = "/^{$rPortless}$/";
		if ( preg_match( $rPortless, $live_site, $m ) ) {
			$live_site = $m[1];
		}
	}
	return $live_site;
}

function jquery_default_site_options() {
	$defaults = array(
		'enable_xmlrpc' => 1,
		'template' => 'jquery',
		'blogdescription' => '',
		'permalink_structure' => '/%postname%/',
		'use_smilies' => 0,

		// The one site where comments are sometimes enabled (blog.jquery.com)
		// has always had thread_comments turned off.
		//
		// Other sites like api.jquery.com implement their pages as posts,
		// but naturally don't support comments at all. Turn this off to
		// remove the WordPress comment-reply script from pages by default.
		'thread_comments' => 0,
	);

	// Production databases set the home values in corresponding site options tables.
	// However, sites that use jquery-static-index.php cause index pages
	// to redirect to live sites in local development. This filter does not
	// prevent the redirect, but changes the redirect to the local site.
	//
	// WordPress/wp-login.php requires 'home' to use a full URL.
	// If it uses a protocol-relative URL, it uses the entire URL as the "path="
	// and thus cause cookies to never be sent by the browser. This doesn't matter
	// much for the public stage sites where we don't login, but it matters for
	// jquery-wp-docker.
	//
	// To ensure canonical URLs work correctly on public stage sites and avoid
	// HTTP-403 errors, apply set_url_scheme() which will change it to stay on
	// HTTPS if you're already on HTTPS.
	if ( JQUERY_STAGING ) {
		$defaults['home'] = set_url_scheme( 'http://' . jquery_site_expand( JQUERY_LIVE_SITE ) );
		$defaults['siteurl'] = set_url_scheme( 'http://' . jquery_site_expand( JQUERY_LIVE_SITE ) );
	}
	return $defaults;

}
