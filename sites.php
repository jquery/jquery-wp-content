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
				'blogname' => 'jQuery Plugins',
				'stylesheet' => 'plugins.jquery.com',
			),
		),
		'learn.jquery.com' => array(
			'blog_id' => 5,
			'cookie_domain' => '.jquery.com',
			'body_class' => 'jquery-learn',
			'options' => array(
				'blogname' => 'Learn jQuery',
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
			'options' => array(
				'blogname' => 'jQuery UI API Documentation',
				'stylesheet' => 'api.jqueryui.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'api.jqueryui.com/1.8' => array(
			'blog_id' => 17,
			'cookie_domain' => '.jqueryui.com',
			'body_class' => 'jquery-ui',
			'options' => array(
				'blogname' => 'jQuery UI 1.8 Documentation',
				'stylesheet' => 'api.jqueryui.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
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
			'blog_id' => 13,
			'cookie_domain' => '.jquerymobile.com',
			'body_class' => 'jquery-mobile',
			'options' => array(
				'blogname' => 'jQuery Mobile API Documentation',
				'stylesheet' => 'api.jquerymobile.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'api.qunitjs.com' => array(
			'blog_id' => 14,
			'cookie_domain' => '.qunitjs.com',
			'body_class' => 'qunitjs',
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
			'body_class' => 'jquery-events',
			'options' => array(
				'blogname' => 'jQuery Events',
				'stylesheet' => 'events.jquery.org',
				'active_plugins' => array(
					'allow-numeric-stubs/allow-numeric-stubs.php',
				),
			),
		),
		'brand.jquery.org' => array(
			'blog_id' => 18,
			'cookie_domain' => '.jquery.org',
			'body_class' => 'jquery-foundation',
			'options' => array(
				'blogname' => 'jQuery Branding Guidelines',
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
		)
	);

	uasort( $sites, function( $a, $b ) {
		if ( $a['blog_id'] == $b['blog_id'] )
			die( 'Two sites have the same blog_id. Blame nacin?' );
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
