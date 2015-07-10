<?php

function jquery_sites() {
	static $sites;
	if ( isset( $sites ) )
		return $sites;

	$sites = array( /* blog_id, cookie domain */
		'jquery.com' => array(
			'blog_id' => 1,
			'cookie_domain' => '.jquery.com',
			'body_class' => 'jquery',
			'options' => array(
				'blogname' => 'jQuery',
				'stylesheet' => 'jquery.com',
				'active_plugins' => array(
					'jquery-static-index.php',
					'vaultpress/vaultpress.php',
				),
			),
		),
		'blog.jquery.com' => array(
			'blog_id' => 2,
			'cookie_domain' => '.jquery.com',
			'body_class' => 'jquery',
			'options' => array(
				'blogname' => 'jQuery Blog',
				'stylesheet' => 'blog-jquery-com',
			//	'permalink_structure' => '/%category%/%postname%/',
			),
		),
		'api.jquery.com' => array(
			'blog_id' => 3,
			'cookie_domain' => '.jquery.com',
			'body_class' => 'jquery',
			'logo_link' => 'http://jquery.com/',
			'options' => array(
				'blogname' => 'jQuery API Documentation',
				'stylesheet' => 'api.jquery.com',
			),
		),
		'plugins.jquery.com' => array(
			'blog_id' => 4,
			'cookie_domain' => '.jquery.com',
			'body_class' => 'jquery',
			'options' => array(
				'blogname' => 'jQuery Plugin Registry',
				'stylesheet' => 'plugins.jquery.com',
			),
		),
		'learn.jquery.com' => array(
			'blog_id' => 5,
			'cookie_domain' => '.jquery.com',
			'body_class' => 'jquery-learn',
			'options' => array(
				'blogname' => 'jQuery Learning Center',
				'stylesheet' => 'learn.jquery.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'jqueryui.com' => array(
			'blog_id' => 6,
			'cookie_domain' => '.jqueryui.com',
			'body_class' => 'jquery-ui',
			'options' => array(
				'blogname' => 'jQuery UI',
				'stylesheet' => 'jqueryui.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'blog.jqueryui.com' => array(
			'blog_id' => 7,
			'cookie_domain' => '.jqueryui.com',
			'body_class' => 'jquery-ui',
			'options' => array(
				'blogname' => 'jQuery UI Blog',
				'stylesheet' => 'blog.jqueryui.com',
			),
		),
		'api.jqueryui.com' => array(
			'subsites' => 1, // Has one level of sub-sites (api.jqueryui.com/([^/]+))
			'blog_id' => 8,
			'cookie_domain' => '.jqueryui.com',
			'body_class' => 'jquery-ui',
			'logo_link' => 'http://jqueryui.com/',
			'options' => array(
				'blogname' => 'jQuery UI API Documentation',
				'stylesheet' => 'api.jqueryui.com',
				'active_plugins' => array(),
			),
		),
		'api.jqueryui.com/1.8' => array(
			'blog_id' => 17,
			'cookie_domain' => '.jqueryui.com',
			'body_class' => 'jquery-ui',
			'logo_link' => 'http://jqueryui.com/',
			'options' => array(
				'blogname' => 'jQuery UI 1.8 Documentation',
				'stylesheet' => 'api.jqueryui.com',
				'active_plugins' => array(),
			),
		),
		'api.jqueryui.com/1.9' => array(
			'blog_id' => 21,
			'cookie_domain' => '.jqueryui.com',
			'body_class' => 'jquery-ui',
			'logo_link' => 'http://jqueryui.com/',
			'options' => array(
				'blogname' => 'jQuery UI 1.9 Documentation',
				'stylesheet' => 'api.jqueryui.com',
				'active_plugins' => array(),
			),
		),
		'jquery.org' => array(
			'blog_id' => 9,
			'cookie_domain' => '.jquery.org',
			'body_class' => 'jquery-foundation',
			'options' => array(
				'blogname' => 'jQuery Foundation',
				'stylesheet' => 'jquery.org',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'qunitjs.com' => array(
			'blog_id' => 10,
			'cookie_domain' => '.qunitjs.com',
			'body_class' => 'qunitjs',
			'options' => array(
				'blogname' => 'QUnit',
				'stylesheet' => 'qunitjs.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'sizzlejs.com' => array(
			'blog_id' => 11,
			'cookie_domain' => '.sizzlejs.com',
			'body_class' => 'sizzlejs',
			'options' => array(
				'blogname' => 'Sizzle JS',
				'stylesheet' => 'sizzlejs.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'jquerymobile.com' => array(
			'blog_id' => 12,
			'cookie_domain' => '.jquerymobile.com',
			'body_class' => 'jquery-mobile',
			'options' => array(
				'blogname' => 'jQuery Mobile',
				'stylesheet' => 'jquerymobile.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'api.jquerymobile.com' => array(
			'subsites' => 1, // Has one level of sub-sites (api.jquerymobile.com/([^/]+))
			'blog_id' => 13,
			'cookie_domain' => '.jquerymobile.com',
			'body_class' => 'jquery-mobile',
			'logo_link' => 'http://jquerymobile.com/',
			'options' => array(
				'blogname' => 'jQuery Mobile API Documentation',
				'stylesheet' => 'api.jquerymobile.com',
				'active_plugins' => array(),
			),
		),
		'api.qunitjs.com' => array(
			'blog_id' => 14,
			'cookie_domain' => '.qunitjs.com',
			'body_class' => 'qunitjs',
			'logo_link' => 'http://qunitjs.com/',
			'options' => array(
				'blogname' => 'QUnit API Documentation',
				'stylesheet' => 'api.qunitjs.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'books.jquery.com' => array(
			'blog_id' => 15,
			'cookie_domain' => '.jquery.com',
			'body_class' => 'jquery',
			'options' => array(
				'blogname' => 'jQuery Books',
				'stylesheet' => 'books.jquery.com',
			),
		),
		'events.jquery.org' => array(
			'blog_id' => 16,
			'cookie_domain' => '.jquery.org',
			'body_class' => 'jquery-foundation jquery-events',
			'options' => array(
				'blogname' => 'jQuery Events',
				'stylesheet' => 'events.jquery.org',
				'active_plugins' => array(
					'allow-numeric-stubs/allow-numeric-stubs.php',
					'jquery-static-index.php',
				),
			),
		),
		'brand.jquery.org' => array(
			'blog_id' => 18,
			'cookie_domain' => '.jquery.org',
			'body_class' => 'jquery-foundation',
			'options' => array(
				'blogname' => 'jQuery Brand Guidelines',
				'stylesheet' => 'brand.jquery.org',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'contribute.jquery.org' => array(
			'blog_id' => 19,
			'cookie_domain' => '.jquery.org',
			'body_class' => 'jquery-foundation',
			'options' => array(
				'blogname' => 'Contribute to jQuery',
				'stylesheet' => 'contribute.jquery.org',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'irc.jquery.org' => array(
			'blog_id' => 20,
			'cookie_domain' => '.jquery.org',
			'body_class' => 'jquery-foundation',
			'options' => array(
				'blogname' => 'jQuery IRC Center',
				'stylesheet' => 'irc.jquery.org',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'meetings.jquery.org' => array(
			'blog_id' => 22,
			'cookie_domain' => '.jquery.org',
			'body_class' => 'jquery-foundation',
			'options' => array(
				'blogname' => 'jQuery Meetings',
				'stylesheet' => 'meetings.jquery.org',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'codeorigin.jquery.com' => array(
			'blog_id' => 23,
			'cookie_domain' => '.jquery.com',
			'body_class' => 'jquery',
			'options' => array(
				'blogname' => 'jQuery CDN',
				'stylesheet' => 'codeorigin.jquery.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'api.jquerymobile.com/1.3' => array(
			'blog_id' => 24,
			'cookie_domain' => '.jquerymobile.com',
			'body_class' => 'jquery-mobile',
			'logo_link' => 'http://jquerymobile.com/',
			'options' => array(
				'blogname' => 'jQuery Mobile 1.3 Documentation',
				'stylesheet' => 'api.jquerymobile.com',
				'active_plugins' => array(),
			),
		),
		'api.jqueryui.com/1.10' => array(
			'blog_id' => 25,
			'cookie_domain' => '.jqueryui.com',
			'body_class' => 'jquery-ui',
			'logo_link' => 'http://jqueryui.com/',
			'options' => array(
				'blogname' => 'jQuery UI 1.10 Documentation',
				'stylesheet' => 'api.jqueryui.com',
				'active_plugins' => array(),
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

function jquery_default_site_options() {
	return array(
		'enable_xmlrpc' => 1,
		'template' => 'jquery',
		'blogdescription' => '',
		'permalink_structure' => '/%postname%/',
		'use_smilies' => 0,
	);

}
