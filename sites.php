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
				'jquery_docsearch_api_key' => '3cfde9aca378c8aab554d5bf1b23489b',
				'jquery_docsearch_index_name' => 'jquery',
			),
		),
		'blog.jquery.com' => array(
			'blog_id' => 2,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'jQuery Blog',
				'stylesheet' => 'blog-jquery-com',
				// 'permalink_structure' => '/%category%/%postname%/',
				'jquery_body_class' => 'jquery',
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
				'jquery_docsearch_api_key' => '3cfde9aca378c8aab554d5bf1b23489b',
				'jquery_docsearch_index_name' => 'jquery',
			),
		),
		'plugins.jquery.com' => array(
			'blog_id' => 4,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'jQuery Plugin Registry',
				'stylesheet' => 'plugins.jquery.com',
				'jquery_body_class' => 'jquery',
			),
		),
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
				'jquery_docsearch_api_key' => '2fce35e56784bbb48c78d105739190c2',
				'jquery_docsearch_index_name' => 'jqueryui',
			),
		),
		'blog.jqueryui.com' => array(
			'blog_id' => 7,
			'cookie_domain' => '.jqueryui.com',
			'options' => array(
				'blogname' => 'jQuery UI Blog',
				'stylesheet' => 'blog.jqueryui.com',
				'jquery_body_class' => 'jquery-ui',
			),
		),
		'api.jqueryui.com' => array(
			'subsites' => 1, // Has one level of sub-sites (api.jqueryui.com/([^/]+))
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
				'jquery_docsearch_api_key' => '2fce35e56784bbb48c78d105739190c2',
				'jquery_docsearch_index_name' => 'jqueryui',
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
				'jquery_docsearch_api_key' => '2fce35e56784bbb48c78d105739190c2',
				'jquery_docsearch_index_name' => 'jqueryui',
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
				'jquery_docsearch_api_key' => '2fce35e56784bbb48c78d105739190c2',
				'jquery_docsearch_index_name' => 'jqueryui',
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
				'jquery_docsearch_api_key' => '207328b0f1c18555c9021d05157dd651',
				'jquery_docsearch_index_name' => 'jquerymobile',
			),
		),
		'api.jquerymobile.com' => array(
			'subsites' => 1, // Has one level of sub-sites (api.jquerymobile.com/([^/]+))
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
				'jquery_docsearch_api_key' => '207328b0f1c18555c9021d05157dd651',
				'jquery_docsearch_index_name' => 'jquerymobile',
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
				'jquery_docsearch_api_key' => '207328b0f1c18555c9021d05157dd651',
				'jquery_docsearch_index_name' => 'jquerymobile',
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
				'jquery_docsearch_api_key' => '2fce35e56784bbb48c78d105739190c2',
				'jquery_docsearch_index_name' => 'jqueryui',
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
				'jquery_docsearch_api_key' => '2fce35e56784bbb48c78d105739190c2',
				'jquery_docsearch_index_name' => 'jqueryui',
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
				'jquery_docsearch_api_key' => '2fce35e56784bbb48c78d105739190c2',
				'jquery_docsearch_index_name' => 'jqueryui',
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
				'jquery_docsearch_api_key' => '207328b0f1c18555c9021d05157dd651',
				'jquery_docsearch_index_name' => 'jquerymobile',
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
				'jquery_docsearch_api_key' => '2fce35e56784bbb48c78d105739190c2',
				'jquery_docsearch_index_name' => 'jqueryui',
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
